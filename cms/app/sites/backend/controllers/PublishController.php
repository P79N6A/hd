<?php

class PublishController extends \MediaBaseController
{
    const XHCHANNEL = 6;
    public $ignore = [
        'getterminal'
    ];
    const BASELIKES = "baseLikesCounts:";
    const BASECOMMENTCOUNTS = "baseCommentCounts:";
    const BASESHARECOUNTS = "baseShareCounts:";
    const BASEHITSCOUNTS = "baseHitsCounts:";
    protected $type = 'news';

    public function indexAction() {
        $category_id = Request::getQuery('category_id', 'int', 0);
        $page = (Request::get('page'))?intval(Request::get('page')):1;
        $hot = CategoryData::findTopFromRedis();

        $list = CategoryData::findListFromRedis();
        list($all_number, $approve_number) = CategoryData::findCategoryPublishInfo();

        $cache_activated = ($page > CategoryData::PAGE_CACHE_NUMBER)?false:true;
        $category = Category::findById($category_id);

        $parents = $category->getParents();

        $statistic_info = array('count'=>array('published'=>0, 'unpublished'=>0));
        if($cache_activated) {
            //分页处理
            $keypage = "TempPagination::".$category_id.":".$page;
            if(!RedisIO::exists($keypage)) {
                $list2 = CategoryData::findList();
                $listpage = array('pagination' =>$list2->pagination->render(), 'count'=> $list2->count);
                RedisIO::set($keypage, json_encode($listpage), 600);
            }
            $listpage = json_decode(RedisIO::get($keypage), true);
        }
        else {
            $listpage = array('pagination' =>$list->pagination->render(), 'count'=> $list->count);
        }
        $listpage['count'] = $listpage['count']+count($hot);
        $data = array('hot'=>$hot, 'list'=>$list);
        View::setVars(compact('data', 'cache_activated', 'statistic_info', 'parents', 'category', 'listpage', 'all_number', 'approve_number'));
    }


    

    public function specialAction() {
        $category_id = Request::getQuery('category_id', 'int', 0);
        $sc_id = Request::getQuery('special_category_id', 'int', 0);
        $page = (Request::get('page'))?intval(Request::get('page')):1;
        $data_id = Request::get("data_id", "int");

        $hot = SpecialCategoryData::findTopFromRedis();

        $list = SpecialCategoryData::findListFromRedis();

        list($all_number, $approve_number) = SpecialCategoryData::findSpecialCategoryPublishInfo();var_dump($all_number);

        $cache_activated = ($page > SpecialCategoryData::PAGE_CACHE_NUMBER)?false:true;
        $special_category = SpecialCategory::findById($sc_id);

        $category = Category::findById($category_id);

        $channelId = Session::get('user')->channel_id;
        $special = $this->getQuoteMedia($data_id, $channelId, "Specials");
        $special_id = $special->id;
        $special = Specials::findOne($special_id);
        $special_title = $special->title;




        $parents = $category->getParents();
        $statistic_info = array('count'=>array('published'=>0, 'unpublished'=>0));
        if($cache_activated) {
            //分页处理
            $keypage = "TempPagination::special::".$category_id.":".$page;
            if(!RedisIO::exists($keypage)) {
                $list2 = SpecialCategoryData::findSpecialList();
                $listpage = array('pagination' =>$list2->pagination->render(), 'count'=> $list2->count);
                RedisIO::set($keypage, json_encode($listpage), 600);
            }
            $listpage = json_decode(RedisIO::get($keypage), true);
        }
        else {
            $listpage = array('pagination' =>$list->pagination->render(), 'count'=> $list->count);
        }
        $listpage['count'] = $listpage['count']+count($hot);
        $data = array('hot'=>$hot, 'list'=>$list);

        View::setVars(compact('data', 'special_title', 'category', 'category_id', 'data_id', 'cache_activated', 'statistic_info', 'parents', 'special_category', 'listpage', 'all_number', 'approve_number'));
    }

    //媒资新增
    public function addAction() {
        if ($this->denySystemAdmin()) {
            return true;
        }
        $inint = $this->initPublishPageData();
        $model = new Data;
        $this->initFormView();
        $channelId = Auth::user()->channel_id;
        $messages = Request::has("messages")?explode(",", Request::getQuery("messages")):[];
        if (Request::isPost()) {
            $messages = [];
            $data = $this->preProcessData(Request::getPost());
            $c_id = Request::getPost("c_id",'int',0);//未知数据
            $this->readyDataForNews($data, $messages);
            $quotes = ids(Request::getPost('quotes'));
            if(count($messages) == 0) {
//                $quo_album_id = $this->readyDataForAlbumQutoes($data,$messages);
//                if($quo_album_id) {
//                    array_push($quotes, $quo_album_id);
//                }
                $datas = Data::queryByIds($channelId, $quotes);
                $validData = Data::makeValidator($data);
                $validNews = News::makeValidator($data);
                $secretFlag = Data::checkSecretKey($channelId, $data["secret_key"]);//检查口令唯一性
                if (!$validData->fails() && !$validNews->fails() && $secretFlag) {
                    $data["timelimit_begin"] =Request::getPost("timelimit_begin");
                    $data["timelimit_end"]= Request::getPost("timelimit_end");
                    Data::compareTime($data);
                    //DB 事务
                    DB::begin();
                    try {
                        if (count($datas) != count($quotes)) {
                            $this->throwDbE('非法的引用对象', 1);
                        }
                        $data_values = array();
                        foreach($model->metaData()[0] as $key) {
                            if(isset($data[$key]))$data_values[$key] = $data[$key];
                        }
                        foreach(['content'] as $key) {
                            if(isset($data[$key]))$data_values[$key] = $data[$key];
                        }
                        $data_values["comment_type"] = $this->getCommenty(); //保存评论
                        if (!$new_data_id = Data::createData($data_values, 'news')) {//创建新闻类型的媒资
                            $this->throwDbE('News save failed');
                        }
                        // 传统tv直播
                        if($data['signals_static'] == 1) {
                            if (!$live_data_id = Data::createData($data, 'signals')) {//创建直播的媒资
                            	$this->throwDbE('Live save failed');
                            }
                            array_push($quotes, $live_data_id);
                            $data['quotelist_signal'] = $live_data_id;
                        }
                        array_push($quotes, $new_data_id);
                        $data['quotelist_news'] = $new_data_id;
                        $quo_album_id = $this->readyDataForAlbumQutoes($data,$messages);//直接添加的相册
                        if($quo_album_id) {
                            if($data['quotelist_album']) $data['quotelist_album'] .= ",";
                            $data['quotelist_album'] = $quo_album_id;
                            array_push($quotes, $quo_album_id);
                        }
                        $data_data_ext = array();
                        $this->setDataDataExt($data_data_ext, $data);
                        $data_data = json_encode($quotes);
                        $data['data_data'] = $data_data;
                        $data['data_data_ext'] =  json_encode($data_data_ext);
                        foreach(['data_data', 'data_data_ext'] as $key) {
                            if(isset($data[$key])) $data_values[$key] = $data[$key];
                        }
                        if(!$this->privilege['publish/approve']) $data_values['status'] = 0;

                        if (!$multimedia_data_id = Data::createData($data_values, 'multimedia')) {//创建复合类型媒资
                            $this->throwDbE('Multimedia save failed');
                        }
                        $data_id = $multimedia_data_id;
                        //口令
                        Data::setRedisSecretKey($channelId, $data_id, $data["secret_key"], $data['status']);
                        Data::setRedisSecretInputUrl($data_id, $data["secret_url"]);
                        //存地区
                        $regionData = new RegionData();
                        if (!$regionData->createRegionData($data, $data_id)) {
                            $this->throwDbE('Region data save fail');
                        }
                        // 保存部门
                        $government = new GovernmentDepartmentData();
                        if (!$government->createGovernmentDepartmentData($data, $data_id)) {
                            $this->throwDbE('Save government department error');
                        }
                        //发布
                        $media_publish = explode(',', $data['category_id']);

                        if (CategoryData::publish($data_id, $media_publish) === false){
                            $messages[] = '栏目发布异常';
                            $this->throwDbE('Category Publish Error');
                        }
                        if ($channelId == self::XHCHANNEL) {//同步智慧萧山
                            $this->postToXiaoshan($data_id, $media_publish);
                        }
                        $this->setRedisParam($data_id);
                        DB::commit();
                        $messages[] = "媒资创建成功";
                        if($data['signals_static'] == 1) {
                            $signalsJson = new Signals();
                            $signalsJson->setJsonByRedis($data_id);
                            if($data['limittime_choose'] == 0) {
                                if($data['timelimit_begin'] > time()) {
                                    Signals::setBeginTimeCache($data_id, Auth::user()->channel_id, $data['timelimit_begin']);
                                }
                                if( $data['timelimit_end'] != 0 &&$data['timelimit_begin'] < time() && $data['timelimit_end'] > time()) {
                                    Signals::setEndTimeCache($data_id, Auth::user()->channel_id, $data['timelimit_end']);
                                }
                            }
                            Signals::refreshCDN($data_id, $channelId);
                        }
                    } catch (DatabaseTransactionException $e) {
                        DB::rollback();
                        Data::deleteRedisSecretKey(Auth::user()->channel_id,$data_id,$data["secret_key"]);
                        Data::deleteRedisSecretInputUrl($data_id);
                        if ($e->getCode() === 0) {
                            $_m = $e->getMessage();
                            $msgs = $$_m->getMessages();
                            foreach ($msgs as $msg) {
                                $messages[] = $msg->getMessage();
                            }
                        }
                        else {
                            $messages[] = $e->getMessage();
                        }
                    }
                }
                else {
                    $msgBag = $validData->messages();
                    $msgBag->merge($validNews->messages());
                    foreach ($msgBag->all() as $msg) {
                        $messages[] = $msg;
                    }
                    if(!$secretFlag){
                        $messages[] = Lang::_("secret key repeat");
                    }
                }
            }
        }
        //默然地址，部门数据获取
        $channelData = Channel::findFirst($channelId);
        $channelAddress = $channelData->address;
        $inint = array_merge($inint, compact("model", "messages", "template","channelAddress"));
        View::setVars($inint);
    }

    //媒资编辑
    public function editAction() {
        if ($this->denySystemAdmin()) {
            return true;
        }
        $this->initFormView();
        $type = Request::getQuery("type", "string");
        $id = Request::getQuery("id", "int");
        $category_id = Request::getQuery("category_id", "int");
        $inint = $this->initPublishPageData($id);
        $channel_id = Auth::user()->channel_id;
        $channelId = $channel_id;
        $model = $this->getQuoteMedia($id, $channel_id, "Multimedia");
        //获取包含媒资
        $news = null;
        $signal = null;
        $special = null;
        if($model->type=="news") {
            $model = Data::findFirstOrFail($id);
            $news = News::channelQuery(Auth::user()->channel_id)->andCondition('id', $model->source_id)->first();
            $model->assignToMedia($news);
        }
        else if($model->data_data_ext) {
            foreach(json_decode($model->data_data_ext) as $type=>$items) {
                switch($type) {
                    case 'news':
                        foreach($items as $d) { $news = $this->getQuoteMedia($d->data_id, $channel_id, "News"); $news->data_id =$d->data_id; }
                        break;
                    case 'video':
                        foreach($items as $d) { $video = $this->getQuoteMedia($d->data_id, $channel_id, "Videos"); $video->data_id =$d->data_id; }
                        break;
                    case 'signal':
                        foreach($items as $d) { $signal = $this->getQuoteMedia($d->data_id, $channel_id, "Signals"); $signal->data_id =$d->data_id; }
                        $signalJson = Signals::getTVJsonByRedis($signal->id);
                        break;
                    case 'special':
                        foreach($items as $d) { $special = $this->getQuoteMedia($d->data_id, $channel_id, "Specials"); $special->data_id =$d->data_id; }
                        break;
                }
            }
        }
        $messages = Request::has("messages")?explode(",",Request::get("messages")):[];
        if(Request::isPost()) {
            $messages = [];
            $data = $this->preProcessData(Request::getPost());
            $this->readyDataForNews($data,$messages);
            $quotes = ids(Request::getPost('quotes'));
            if(count($messages)==0) {
                $datas = Data::queryByIds(Auth::user()->channel_id, $quotes);
                $vData = Data::makeValidator($data);
                $vNews = News::makeValidator($data);
                $oldSecretKey = $model->secret_key;
                $oldSecretUrl = $model->secret_url;
                $secretFlag = Data::checkSecretKey(Auth::user()->channel_id, $data["secret_key"], $model->secret_key, $oldSecretKey);
                if (!$vData->fails() && !$vNews->fails() && $secretFlag) {
                    $data['updated_at'] = time();

                    $data["timelimit_begin"] =Request::getPost("timelimit_begin");
                    $data["timelimit_end"]= Request::getPost("timelimit_end");
                    $data["comment_type"] = $this->getCommenty();
                    Data::compareTime($data);

                    //DB 事务
                    DB::begin();
                    try {
                        if (count($datas) != count($quotes)) {
                            $this->throwDbE('非法的引用对象', 1);
                        }
                        //更新新闻
                        if($news) {
                            if($model->type=='news') {
                                if(!$this->updateMedia($model->id, $channel_id, $data, "News", News::safeUpdateFields())) {
                                    $this->throwDbE('news model error');
                                }
                            }
                            else {
                                if(!$this->updateMedia($news->data_id, $channel_id, $data, "News", News::safeUpdateFields())) {
                                    $this->throwDbE('news model error');
                                }
                            }
                        }
                        
                        
                        if($data['signals_static'] == 1) {
                        	if($signal) {
                        		$signalId = $signal->id;
	                        	Signals::readyThumb($data['input_file2']);
	                        	Signals::readyThumb($data['input_file3']);
	                        	Signals::readyThumb($data['input_file4']);
	                        	Signals::readyThumb($data['input_file5']);
	                        	$status = Signals::LIVE_STATUS_NOTSTART;
	                        	$statusName = "";
	                        	Signals::checkInputTime($data["timelimit_begin"], $data["timelimit_end"], $status, $statusName);
	                        	$data["live_status"] = $status;
	                        	if(!SignalSource::deleteAllData($signalId)) {
	                        		$this->throwDbE('signal source delete failed');
	                        	}
	                        	if(!Signals::updateLiveData($signalId, $data)) {
	                        		$this->throwDbE('update signal failed');
	                        	}
	                        	// 保存直播流信息数据
	                        	$traditionTV = json_decode($data['json']);
	                        	$lives = new Signals();
	                        	if(!$lives->saveSignalsTV($traditionTV, $signalId))
	                        	{
	                        		$this->throwDbE('create new signal source failed ');
	                        		return false;
	                        	}
	                        	Signals::saveTVJsonToRedis($signalId);
                        	}
                        }
                        else {
                        	if($signal) {
                        		Signals::deleteTVJsonToRedis($signal->id);
                        	}                	
                        	$quotes2 = array();
                        	foreach($quotes as $quote_data_id) {
                        	    if($quote_data_id==intval($data['quotelist_signal'])) continue;                        		
                        		array_push($quotes2, $quote_data_id);                        		
                        	}
                        	$quotes = $quotes2;
                        	$data['quotelist_signal'] ="";
                        }
                        $data_data_ext = array();
                        $this->setDataDataExt($data_data_ext, $data);
                        $data_data = json_encode($quotes);
                        $data['data_data'] = $data_data;
                        $data['data_data_ext'] =  json_encode($data_data_ext);

                        //口令
                        Data::deleteRedisSecretKey(Auth::user()->channel_id, $oldSecretKey); //删除旧口令
                        Data::setRedisSecretKey(Auth::user()->channel_id, $model->id, $data['secret_key'],$model->status);
                        Data::setRedisSecretInputUrl($model->id, $data['secret_url']);



                        if (!$this->updateMedia($model->id, $channel_id, $data)) {
                            $this->throwDbE('data model error');
                        }

                        $data_id = $model->id;

                        SmartyData::delDataRedis($data_id);//清除缓存

                        //存地区
                        $dRegion = new RegionData();
                        if (!$dRegion->updateRegionData($data, $data_id)) {
                            $this->throwDbE('regions Data save fail');
                        }
                        // 保存部门
                        $dGovDep = new GovernmentDepartmentData();
                        if (!$dGovDep->updateGovernmentDepartmentData($data, $data_id)) {
                            $this->throwDbE('save government Department error');
                        }

                        //发布
                        $model = Data::getById($data_id, Auth::user()->channel_id);
                        $media_publish = ids($data['category_id']);

                        if($data['publish_status']==-1) unset($data['publish_status']);
                        if(isset($data['publish_status'])){
                            $data['publish_status'] = ($data['publish_status']==1)?1:0;
                            //修改媒资在当前栏目的发布状态
                            foreach($media_publish as $cid_inner) {
                                    CategoryData::approve($data_id, $cid_inner, $data['publish_status']);
                            }
                        }

                        if (Auth::user()->channel_id == self::XHCHANNEL)
                            $this->postToXiaoshan($data_id, $media_publish);
                        if (CategoryData::addPublish($data_id, $media_publish) === false) {
                            $messages[] = '栏目发布异常';
                            $this->throwDbE('Category Publish Error');
                        }
                        DB::commit();
                        $this->setRedisParam($data_id);
                        $messages[] = "更新成功";
                        if($data['signals_static'] == 1) {
                            Signals::delJsonByRedis($data_id);
                            $signalsJson = new Signals();
                            $signalsJson->setJsonByRedis($data_id);
                            if($data['limittime_choose'] == 0) {
                                if($data['timelimit_begin'] > time()) {
                                    Signals::setBeginTimeCache($data_id, Auth::user()->channel_id, $data['timelimit_begin']);
                                }
                                if( $data['timelimit_end'] != 0 &&$data['timelimit_begin'] < time() && $data['timelimit_end'] > time()) {
                                    Signals::setEndTimeCache($data_id, Auth::user()->channel_id, $data['timelimit_end']);
                                }
                            }
                            Signals::refreshCDN($data_id, $channelId);
                        }
                    } catch (DatabaseTransactionException $e) {
                        DB::rollback();
                        Data::deleteRedisSecretKey(Auth::user()->channel_id, $data_id, $data["secret_key"]);
                        Data::setRedisSecretKey(Auth::user()->channel_id, $data_id, $oldSecretKey,$model->status);
                        Data::setRedisSecretInputUrl($data_id, $oldSecretUrl);
                        if ($e->getCode() === 0) {
                            $_m = $e->getMessage();
                            $msgs = $$_m->getMessages();
                            foreach ($msgs as $msg) {
                                $messages[] = $msg->getMessage();
                            }
                        } else {
                            $messages[] = $e->getMessage();
                        }
                    }
                } else {
                    $msgBag = $vData->messages();
                    $msgBag->merge($vNews->messages());
                    foreach ($msgBag->all() as $msg) {
                        $messages[] = $msg;
                    }
                    if (!$secretFlag) {
                        $messages[] = Lang::_("secret key repeat");
                    }
                }
                $redirect = Url::get("publish/edit", ['id' => $id, 'messages' => join(',', $messages)]);
                redirect($redirect);
            }
        }
        $categoryData = CategoryData::findCategoryDataById($id, $category_id);
        $publish_status = ($categoryData)? $categoryData->publish_status:1;
        $secret_key = $model->secret_key;
        $secret_url = $model->secret_url;
        $template = $this->initTemplate();
        $channelData = Channel::findFirst($channelId);
        $channelAddress = $channelData->address;
        $editFlag = true;
        $inint = array_merge($inint, compact("model", "messages", "publish_status", "template", "news","secret_key","secret_url", "signalJson", "signal","channelAddress","editFlag"));
        View::setVars($inint);
    }

    public function specialCategoryAction() {
        $data_id = Request::get("data_id", "int");
        $category_id = Request::get("category_id","int");
        $channelId = Session::get('user')->channel_id;
        $special = $this->getQuoteMedia($data_id, $channelId, "Specials");
        $special_id = $special->id;
        $data = SpecialCategory::findAllBySpecial($channelId, $special_id);
        $special = Specials::findOne($special_id);
        $special_title = $special->title;
        $category = Category::findById($category_id);
        $parents = $category->getParents();
        View::setVars(compact('data', 'special_title', 'data_id', 'special_id', 'parents', 'category_id', 'category'));
    }



    public function createSpecialAction() {
        $id = Request::get("data_id");
        $category_id  = Request::get("category_id");
        if(intval($id)==0) $this->_json([], 404, "not found");
        $model = Data::findFirstOrFail($id);
        $data = $model->toArray();
        $data['title'] =$data['title'];
        unset($data['id']);
        unset($data['source_id']);
        $data['type'] = "special";
        $special_data_id = Data::createData($data, 'special');
        $data_data_ext = json_decode($model->data_data_ext, true);
        $channelId = Auth::user()->channel_id;
        if(isset($data_data_ext['special'])) {
            $this->_json([], 400, "special is exist");
        }
        $data_data_ext['special'] = array();
        $data_data_ext['special'][] = array('data_id' => intval($special_data_id), 'template'=>'default');
        $model->data_data_ext = json_encode($data_data_ext);
        if($model->save()) {
//             $key = Data::data_detail_key .":". $model->id;
//             RedisIO::delete($key);
			$this->delQuoteMedia($model->id);
            $key = Data::data_list_key.":".$channelId .":". $model->id;
            RedisIO::delete($key);
            CategoryData::deleteCacheJson($category_id);
            $this->_json([], 200);
        }
        $this->_json([], 400, Lang::_('error'));
    }


    public function specAction() {
        $category_id = Request::getQuery('category_id', 'int');
        $spec_publish = Request::getQuery('spec_publish', 'int', 0);
        $spec_category_id = Request::getQuery('spec_category_id', 'int', 0);
        if ($this->denySystemAdmin()) {
            return true;
        }
        $this->initFormView();
        $messages = [];
        $model = new News;
        $datas = [];
        if (Request::isPost()) {
            $data = $this->preProcessData(Request::getPost());
            //兼容引用数据
            $quotes = ids(Request::getPost('quotes'));
            $spec_publish = Request::getPost('spec_publish');
            $spec_category_id = Request::getPost('spec_category_id');
            $datas = Data::queryByIds(Auth::user()->channel_id, $quotes);
            $vData = Data::makeValidator($data);
            $vNews = News::makeValidator($data);
            if (!$vData->fails() && !$vNews->fails() && $thumb = $this->validateAndUpload($messages)) {
                $model->thumb = $data['thumb'] = $thumb;
                $data['partition_by'] = date('Y');
                $data['created_at'] = $data['created_at'] ? strtotime($data['created_at']) : time();
                $data['updated_at'] = time();
                $data['status'] = '1';
                $data['author_id'] = Auth::user()->id;
                $data['author_name'] = Auth::user()->name;
                $data['channel_id'] = (int)Auth::user()->channel_id;
                $data['comment_type'] = 2;
                $data['redirect_url'] = $data['isSubUrl'];
                if ($data['referer_self'] == 0) {
                    $referer_url = "http://";
                    preg_match("/^(http[s]?:\/\/)?([^\/]+)/i", $data['referer_url'], $arr_domain);
                    $referer_url .= $arr_domain[2];
                    $referer_m = Referer::findByDomain(Auth::user()->channel_id, $referer_url);
                    if (!$referer_m->id) {
                        $data["referer_name"] = ($data["referer_name"]) ? $data["referer_name"] : "未知网站-" . time();
                        $data['referer_id'] = Referer::addDomian(Auth::user()->channel_id, $referer_url, $data["referer_name"]);
                    } else {
                        $data['referer_id'] = $referer_m->id;
                    }
                }
                //DB 事务
                DB::begin();
                try {
                    if (count($datas) != count($quotes)) {
                        $this->throwDbE('非法的引用对象', 1);
                    }
                    $data_data = json_encode($quotes);

                    //存新闻
                    if (!$id = $model->saveGetId($data)) {
                        $this->throwDbE('model');
                    }

                    $dModel = new Data();
                    //存data
                    if (!$data_id = $dModel->doSave($data, Data::getAllowed(), 'news', $id, $data_data)) {
                        $this->throwDbE('dModel');
                    }

                    //存地区
                    if (!self::saveRegion($data, $data_id)) {
                        // $this->throwDbE('regions');
                    }
                    // 保存部门
                    if (!self::saveGovernmentDepartment($data, $data_id)) {
                        $this->throwDbE('save government Department error');
                    }

                    //存私有分类
                    $msgs = PrivateCategoryData::publish($data_id, $data['partition_by']);
                    if (empty($msgs)) {
                        DB::commit();
                        $messages[] = Lang::_('add success');
                    } else {
                        DB::rollback();
                        $messages = array_merge($messages, $msgs);
                    }

                    //发布
                    $model = Data::getById($data_id, Auth::user()->channel_id);
                    $media_publish = array($category_id);

                    if (Auth::user()->channel_id == self::XHCHANNEL)
                        $this->postToXiaoshan($data_id, $media_publish);

                    if ($spec_publish == "1" && SpecialCategoryData::publish($data_id, [$spec_category_id]) === false) {
                        $messages[] = '专题栏目发布异常';
                    } elseif ($spec_publish != "1") {
                        if (CategoryData::publish($data_id, $media_publish) === false) {
                            $messages[] = '栏目发布异常';
                        }
                    }
                    if (Request::getPost('toas1')) {
                        RedisIO::set('authUserInfo:' . $data_id, 1);
                    }
                    if (Request::getPost('toas2')) {
                        RedisIO::set('subscribe:' . $data_id, 1);
                    }
                    //审核开关
                    RedisIO::set(UserComments::REVIEW . $data_id, 2);
                    $isSubUrl = Request::getPost('isSubUrl');
                    $noSubUrl = Request::getPost('noSubUrl');
                    //H5地址
                    if (empty($isSubUrl)) {
                        RedisIO::set('isSubUrl:' . $data_id, $isSubUrl);
                    }
                    //未关注地址
                    if ($noSubUrl) {
                        RedisIO::set('noSubUrl:' . $data_id, $noSubUrl);
                    }

                    if ($spec_publish == "1")
                        $messages[] = "新闻、专题发布成功";
                    else {
                        $messages[] = "新闻发布成功";
                    }

                    foreach ($media_publish as $v) {
                        $key = "columns_list_id:" . $v;
                        MemcacheIO::set($key, false, 86400 * 30);
                    }

                } catch (DatabaseTransactionException $e) {
                    DB::rollback();
                    if ($e->getCode() === 0) {
                        $_m = $e->getMessage();
                        $msgs = $$_m->getMessages();
                        foreach ($msgs as $msg) {
                            $messages[] = $msg->getMessage();
                        }
                    } else {
                        $messages[] = $e->getMessage();
                    }
                }
            } else {
                $msgBag = $vData->messages();
                $msgBag->merge($vNews->messages());
                foreach ($msgBag->all() as $msg) {
                    $messages[] = $msg;
                }
            }
        }
        // 默然地址，部门数据获取
        $region = $this->initRegionData();

        $template = $this->initTemplate();
        $media_type = PrivateCategory::MEDIA_TYPE_NEWS;
        $privateCategoryData = false;
        View::setVars(compact('model', 'messages', 'datas', 'media_type', 'privateCategoryData', 'region', 'category_id', 'template', 'spec_category_id', 'spec_publish'));
    }

    protected function initSourceData($type) {
        return SupplySources::getSourceByType($type);
    }

    protected function initTemplate() {
        return array(array('id' => 1, 'name' => '模板一'), array('id' => 2, 'name' => '模板'), array('id' => 3, 'name' => '模板三'));
    }

    /**
     * 初始化加载地区数据
     * @return unknown
     */
    protected function initRegionData() {
        $channel_id = Session::get('user')->channel_id;
        // 初始化，根据频道获取地区id
        $fetch_id = RegionDefault::fetchByChannelId($channel_id);
        // 防止channel表中region_id与regions表id不符报错
        if ($fetch_id['region_id'] < intval(Regions::queIdByLevel())) {
            $fetch_id['region_id'] = intval(Regions::queIdByLevel());
        }
        $region = Regions::fetchById($fetch_id['region_id']);            // 获取id地区内容
        //生成上级地区
        $parents = $region->getParents();
        unset($parents[count($parents) - 1]);
        foreach ($parents as $p) {
            $level_id = ($p->level) . '_id';
            $region->$level_id = $p->id;
        }
        $regionData = $region;
        return $regionData;
    }

    /**
     * @return bool
     */
    protected function denySystemAdmin() {
        $r = false;
        $is_admin = Auth::user()->channel_id;
        if ($is_admin == "0") {
            $r = true;
            $this->alert('系统管理员, 请勿直接新增/编辑媒资数据.');
        }
        return $r;
    }

    /**
     * 拖动排序
     */
    public function sortAction() {
        $ids = Request::get("ids");
        $sorts = Request::get("sorts");
        $show_spec = Request::get("show_spec");
        if ((empty($ids) || !is_array($ids)) ||
            (empty($sorts) || !is_array($sorts)) ||
            (count($ids) != count($sorts))) {
            $this->echoExit($this->_json([], 40, Lang::_('only top can resort')));
        }
        if ($show_spec != "1") {
            $ret = CategoryData::sortBySorts($ids, $sorts);
        } else {
            $ret = SpecialCategoryData::sortBySorts($ids, $sorts);
        }
        if ($ret) {
            $this->echoExit(json_encode(['code' => '200', 'msg' => Lang::_('success')]));

        }
        else
            $this->echoExit(json_encode(['code' => '400', 'msg' => Lang::_('error')]));
    }


    /**
     * 专题拖动排序
     */
    public function specialSortAction() {
        $ids = Request::get("ids");
        $sorts = Request::get("sorts");
        if ((empty($ids) || !is_array($ids)) ||
            (empty($sorts) || !is_array($sorts)) ||
            (count($ids) != count($sorts))) {
            $this->echoExit($this->_json([], 40, Lang::_('only top can resort')));
        }
        $ret = SpecialCategoryData::sortBySorts($ids, $sorts);
        if ($ret) {
            $this->echoExit(json_encode(['code' => '200', 'msg' => Lang::_('success')]));
        }
        else
            $this->echoExit(json_encode(['code' => '400', 'msg' => Lang::_('error')]));
    }


    /**
     * 推荐
     */
    public function recommendAction() {
        $id = Request::get("data_id");
        $category_id = Request::get("category_id");
        $top = Request::get("top");
        if (!$category_id || !is_numeric($category_id)) {
            $this->_json([], 400, Lang::_('category_id required'));
        }
        if($top) {
            $result = CategoryData::recommend($category_id, $id);
        }
        else {
            $result = CategoryData::cancelRecommend($category_id, $id);
        }
        SmartyData::delCategoryDataRedis($category_id);
        $result ? $this->_json([], 200) : $this->_json([], 400, Lang::_('error'));
    }

    /**
     * 推荐
     */
    public function specialRecommendAction() {
        $id = Request::get("data_id");
        $sc_id = Request::get("special_category_id");
        $top = Request::get("top");
        if (!$sc_id || !is_numeric($sc_id)) {
            $this->_json([], 400, Lang::_('special_category_id required'));
        }
        if($top) {
            $result = SpecialCategoryData::recommend($sc_id, $id);
        }
        else {
            $result = SpecialCategoryData::cancelRecommend($sc_id, $id);
        }
        //删除前台专题缓存
        $result ? $this->_json([], 200) : $this->_json([], 400, Lang::_('error'));
    }

    /**
     * 发布操作
     */
    public function approveAction() {
        $id = Request::get("id");       // data_id
        $status = Request::get("status");
        $category_id = Request::get("category_id");


        $channelId = Auth::user()->channel_id;

        CategoryData::deleteCacheJson($category_id);
        F::_clearCache("media/latest:" . $category_id ,Session::get('user')->channel_id);
        SmartyData::delCategoryDataRedis($category_id);
       /* $key_approve = CategoryData::category_data_publish_num_key.":approve:".$channelId.":".$category_id;
        $approve_number = intval(RedisIO::get($key_approve));
        $listdetail = Data::getDataRedis($id, $channelId);

        if(intval($listdetail->status)==1&&$status!=1) {
            $approve_number = $approve_number-1;
        }
        else if(intval($listdetail->status)!=1&&$status==1){
            $approve_number = $approve_number+1;
        }
        RedisIO::set($key_approve, $approve_number);*/
        $result = CategoryData::approve($id, $category_id, $status);

        $key = D::memKey('apiGetDataById', ['id' => $id]);
        MemcacheIO::set($key, false, 86400 * 30);

        $specialCategoryData = SpecialCategoryData::getIdByData($id);
        foreach($specialCategoryData as $special_category_id){
            F::_clearCache("media/latest:" . $special_category_id ,Session::get('user')->channel_id);
            SmartyData::delSpecialCategoryDataRedis($special_category_id);
        }

        SmartyData::delDataRedis($id);
        $this->updateSecretRedis($id);
        $this->postToXiaoshan($id, array($category_id));

//         $key = Data::data_detail_key .":". $id;
//         RedisIO::delete($key);
		$this->delQuoteMedia($id);
        $key = Data::data_list_key.":".$channelId .":". $id;
        RedisIO::delete($key);

        $result ? $this->_json([], 200) : $this->_json([], 400, Lang::_('error'));
    }

    private $sign = "f4fna96cdnf27i8W9Jd7bV6T1sadf9z5Zcasdy0W6ob88asdf126OOo659HUhoji";
    private $city_id = "330109";

    private function postToXiaoshan($data_id , $media_publish) {
        global $config;

        $obj = $config->zhihuixiaoshanmap;//对应关系写在配置文件中
        /*
         * 有三点需要判断
         * 3.此新闻接收方是新建还是更新--查询SupplyoutRsync表
         * 1.推送的栏目是不是目标栏目--目前使用数组对应，换环境后可能需要修改对应数组
         * 2.查询此新闻的所有信息--Data::getMediaByData
         * 4.针对不同的data类型还要对不同地址进行推送
         */
        $category_arr = array();
        foreach($media_publish as $key => $category){
            if(isset($obj->$category)){
                $category_arr[] = $obj->$category;
            }
        }
        foreach($category_arr as $key => $category){//针对目标栏目推送
            $channel_id = Session::get('user')->channel_id;
            $supply_data = SupplyoutRsync::findOneByDataId(100,$data_id,$channel_id,$category);
            $origin_id = 0;
            if($supply_data){
                $origin_id = $supply_data->origin_id;
            }
            $arr = Data::getMediaByData($data_id);

            $sign = md5($this->city_id.$this->sign.time());//签名
            $referer = Referer::getById($channel_id, $arr[0]->referer_id);//来源

            if($arr[0]->type == 'news'||$arr[0]->type == 'multimedia') {
                $input_post = array();
                $input_post['cityid'] = $this->city_id;
                $input_post['timestamp'] = time();
                $input_post['sign'] = $sign;
                $input_post['id'] = $origin_id;
                $input_post['title'] = $arr[0]->title;
                $input_post['category_id'] = $category;
                $input_post['pics'] = (false===stripos($arr[0]->thumb, "image.xianghunet.com"))?cdn_url('image',$arr[0]->thumb):$arr[0]->thumb;
                $input_post['source'] = $referer->name ?: "";
                $input_post['author'] = $arr[0]->author_name;
                $input_post['digest'] = $arr[0]->intro;
                $input_post['can_reply'] = $arr[1]->comment_type == 1 ? 0 : 1;
                $input_post['pass'] = $arr[0]->status;
                $input_post['release_time'] = $arr[0]->created_at;
                $input_post['isshow'] = 1;
                $input_post['content'] = $arr[1]->content;
                $input_post['operator'] = $arr[0]->author_name;

                $return_message = F::curlRequest("http://citynews.2500city.com/zxapi/news/regular", 'post', $input_post);
                $return_message = json_decode($return_message, true);
            }
            if($arr[0]->type == 'album') {
                $image_arr = AlbumImage::findByAlbumId($arr[1]->id);
                $altas = array();
                $des = array();
                foreach($image_arr as $num => $value){
                    $altas[] = $value['path'];
                    $des[] = $value['intro'];
                }
                $input_post = array();
                $input_post['cityid'] = $this->city_id;
                $input_post['timestamp'] = time();
                $input_post['sign'] = $sign;
                $input_post['id'] = $origin_id;
                $input_post['title'] = $arr[0]->title;
                $input_post['category_id'] = $category;
                $input_post['pics'] = array(
                    0=>(false===stripos($arr[0]->thumb, "image.xianghunet.com"))?cdn_url('image',$arr[0]->thumb):$arr[0]->thumb
                );
                $input_post['source'] = $referer->name ?: "";
                $input_post['author'] = $arr[0]->author_name;
                $input_post['can_reply'] = $arr[1]->comment_type == 1 ? 0 : 1;
                $input_post['pass'] = $arr[0]->status;
                $input_post['release_time'] = $arr[0]->created_at;
                $input_post['isshow'] = 1;
                $input_post['altas'] = $altas;
                $input_post['des'] = $des;
                $input_post['digest'] = $arr[0]->intro;
                $input_post['operator'] = $arr[0]->author_name;

                $return_message = F::curlRequest("http://citynews.2500city.com/zxapi/news/atlas", 'post', $input_post);
                $return_message = json_decode($return_message, true);
            }
            if($arr[0]->type == 'video') {
                $video_file = VideoFiles::findByVideoId($arr[1]->id);
                $content = "";
                if($video_file) {
                    $content = $video_file->path;
                    $content = (false===stripos($content, "video.xianghunet.com"))?cdn_url('video', $content):$content;
                }
                $minute = floor($arr[1]->duration/60)<10?'0'.floor($arr[1]->duration/60):(floor($arr[1]->duration/60));
                $second = $arr[1]->duration%60<10?'0'.$arr[1]->duration%60:$arr[1]->duration%60;
                $video_time = $minute.":".$second;

                $input_post = array();
                $input_post['cityid'] = $this->city_id;
                $input_post['timestamp'] = time();
                $input_post['sign'] = $sign;
                $input_post['id'] = $origin_id;
                $input_post['title'] = $arr[0]->title;
                $input_post['category_id'] = $category;
                $input_post['pics'] = (false===stripos($arr[0]->thumb, "image.xianghunet.com"))?cdn_url('image',$arr[0]->thumb):$arr[0]->thumb;
                $input_post['source'] = $referer->name ?: "";
                $input_post['author'] = $arr[0]->author_name;
                $input_post['digest'] = $arr[0]->intro;
                $input_post['can_reply'] = $arr[1]->comment_type == 1 ? 0 : 1;
                $input_post['pass'] = $arr[0]->status;
                $input_post['release_time'] = $arr[0]->created_at;
                $input_post['isshow'] = 1;
                $input_post['content'] = $content;
                $input_post['video_time'] = $video_time;
                $input_post['tag'] = $arr[1]->keywords;
                $input_post['operator'] = $arr[0]->author_name;

                $return_message = F::curlRequest("http://citynews.2500city.com/zxapi/news/video", 'post', $input_post);
                $return_message = json_decode($return_message, true);
            }

            if($origin_id==0 && isset($return_message['data']['id'])){//有返回时增加对应关系
                SupplyoutRsync::createByDataId(array(
                    'channel_id' => $channel_id,
                    'origin_type' => 100,
                    'origin_id' =>$return_message['data']['id'],
                    'data_id' => $data_id,
                    'category_id' => $category
                ));
            }
        }



    }

    /**
     * Ajax获取媒资的发布途径
     */
    public function getterminalAction()
    {
        $data_id = Request::getPost('data_id', 'int');
        $return = CategoryData::getTerminal($data_id);
        $terminal = array();
        foreach ($return as $arr) {
            foreach ($arr as $k => $v) {
                $terminal[] = $v;
            }
        }
        $return = array_unique($terminal);
        echo json_encode($return);
        exit;
    }

    /**
     * 单条锁定
     */
    public function nailAction()
    {
        $id = Request::get("id");
        $msg = CategoryData::nail($id);
        $msg = $msg ? $this->_json([], 200) : $this->_json(400, Lang::_('error'));
        $this->echoExit($msg);
    }

    private function echoExit($msg){
        echo $msg;
        exit;
    }


    public function searchAction() {
        if ($mess = Request::getQuery()) {
            $channel_id = Session::get('user')->channel_id;
            $title = Category::getTitle($mess['c_id'], $channel_id);
            $data = CategoryData::search($mess);
            $showInfo = false;
            view::pick('publish/index');
            View::setVars(compact('data', 'mess', 'title', 'showInfo'));
        }
    }

    public function taskAction() {
        $data_id = Request::getQuery('id');
        $data = Data::getById($data_id, Auth::user()->channel_id);
        if ($data->status == 1) {
            Queues::addTask(Auth::user()->channel_id, Queues::TASK_PUSH, array('data_id' => $data_id));
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cant push without publish')]);
        }
        exit;
    }

    /**
     * 判断数据库中是否已经存在和提交的地址信息一致的数据
     * @param unknown $findData 数据表里 地址信息
     * @param unknown $data 提交的地址信息
     * @return boolean
     */
    private function checkSaveValue($findData, $data) {
        $bTemp = false;
        foreach ($findData as $key => $value) {
            if (array_key_exists('country_id', $data) && $value['country_id'] == $data['country_id']) {
                $bTemp = true;
            } else if (!array_key_exists('country_id', $data) && $value['country_id'] == '0') {
                $bTemp = true;
            } else {
                $bTemp = false;
            }

            if (array_key_exists('province_id', $data) && $value['province_id'] == $data['province_id']) {
                $bTemp = true;
            } else if (!array_key_exists('province_id', $data) && $value['province_id'] == '0') {
                $bTemp = true;
            } else {
                $bTemp = false;
            }

            if (array_key_exists('city_id', $data) && $value['city_id'] == $data['city_id']) {
                $bTemp = true;
            } else if (!array_key_exists('city_id', $data) && $value['city_id'] == '0') {
                $bTemp = true;
            } else {
                $bTemp = false;
            }

            if (array_key_exists('county_id', $data) && $value['county_id'] == $data['county_id']) {
                $bTemp = true;
            } else if (!array_key_exists('county_id', $data) && $value['county_id'] == '0') {
                $bTemp = true;
            } else {
                $bTemp = false;
            }

            if (array_key_exists('town_id', $data) && $value['town_id'] == $data['town_id']) {
                $bTemp = true;
            } else if (!array_key_exists('town_id', $data) && $value['town_id'] == '0') {
                $bTemp = true;
            } else {
                $bTemp = false;
            }

            if ($bTemp == true) {
                return $bTemp;
            }
        }
    }

    /**
     * 保存地区，可多个地区，第一个不带后缀的单独保存，其他的循环获取后保存
     * @param $data
     * @param $data_id
     * @return bool
     */
    private function saveRegion($data, $data_id) {
        //判断是否已经存在
        $regionVal = new RegionData();
        $findData = $regionVal->findRegionData($data_id);
        if ($this->checkSaveValue($findData, $data)) {
            return true;
        }
        //对多余的地区进行保存
        $arr = array();
        $index = 0;
        foreach ($data as $k => $v) {
            if (preg_match('/^(country_id)+/', $k)) {
                $i = substr($k, -1);
                $arr[$index] = array(
                    'country_id' => isset($data['country_id' . $i]) ? $data['country_id' . $i] : 0,
                    'province_id' => isset($data['province_id' . $i]) ? $data['province_id' . $i] : 0,
                    'city_id' => isset($data['city_id' . $i]) ? $data['city_id' . $i] : 0,
                    'county_id' => isset($data['county_id' . $i]) ? $data['county_id' . $i] : 0,
                    'town_id' => isset($data['town_id' . $i]) ? $data['town_id' . $i] : 0,
                    'village_id' => isset($data['village_id' . $i]) ? $data['village_id' . $i] : 0,
                    'description' => isset($data['description' . $i]) ? $data['description' . $i] : 0
                );
                if ($arr[$index]['country_id'] == 0) {
                    unset($arr[$index]);
                    $index--;
                }
                $index++;
            }
        }
        foreach ($arr as $k => $v) {
            $region = new RegionData();
            if (!$region->createRegionData($v, $data_id)) {
                return false;
            }
        }
        //对起始的地区进行保存
        if ($data['province_id'] != 0 && $data['country_id']) {
            $region = new RegionData();
            if (!$region->createRegionData($data, $data_id)) {
                return false;
            }
        }
        return true;
    }

    /**
     * 保存部门选择数据
     * @param unknown $data 界面输入数据，包含部门信息
     * @param unknown $data_id 媒资id
     * @return boolean
     */
    private function saveGovernmentDepartment($data, $data_id)
    {
        $governmentDepartment = new GovernmentDepartmentData();
        if (!$governmentDepartment->createGovernmentDepartmentData($data, $data_id)) {
            return false;
        }
        return true;
    }

    /**
     * 接口列表
     */
    public function interfaceAction() {
        $data_id = Request::getQuery('data_id', 'int');
        $channel_id = Session::get('user')->channel_id;
		$data = Data::getMediaByData($data_id);
        $data_data=$data[0]->data_data;
        $data_vote = Data::getDataDataIdByType($data_data,'vote');
        View::setVars(compact("data_id","channel_id",'data_vote'));
    }

    public function updateMedia($data_id, $channel_id, $data, $modelname='Multimedia',  $whitelist=null) {
        $r = Data::findFirstOrFail($data_id);
        if(!$r->update($data, Data::safeUpdateFields())) {
            return false;
        }
        if($modelname!='Multimedia') {
            $model = $modelname::channelQuery($channel_id)
                ->andCondition('id', $r->source_id)
                ->first();
            if(!$model) {
                abort(404);
            }
            if(!$model->update($data, $whitelist)) {
                return false;
            }
        }
//         $key = Data::data_detail_key .":". $data_id;
//         RedisIO::delete($key);
        $this->delQuoteMedia($data_id);
        
        $key = Data::data_list_key.":".$channel_id .":". $data_id;
        RedisIO::delete($key);

        return true;
    }

    /**
	  * 删除媒资
     */
    public function delQuoteMedia($data_id) {
    	$key = Data::data_detail_key .":". $data_id;
    	if(RedisIO::exists($key)) {
    		$result = RedisIO::del($key);
    	}
    }
    
    public function getQuoteMedia($data_id, $channel_id, $modelname='Multimedia') {
        $key = Data::data_detail_key .":". $data_id;
        if(!RedisIO::exists($key)) {
            $r = Data::findFirstOrFail($data_id);
            if($modelname!='Multimedia') {
                $model = $modelname::channelQuery($channel_id)
                    ->andCondition('id', $r->source_id)
                    ->first();
                if(!$model) {
                    abort(404);
                }
                if($r->type=="news") {
                    $r->assignToMedia($model);
                }
                $result = json_encode($model);
            }
            else {
                $result = json_encode($r);
            }
            RedisIO::set($key, $result, 86400);
        }
        else {
            $result = RedisIO::get($key);
        }
        $model = json_decode($result);
        return $model;
    }




	
    /*
     * @desc 上传文件
     *
     * */
    public function tmpAlbumImgUpAction()
    {
        if ($this->denySystemAdmin()) {
            return true;
        }
        $token = Request::get('token', 'string', '');
        $id = 0;
        if (!$token) {
            $this->jsonp(['error' => Lang::_('invalid token')]);
        }
        list($path, , $error) = $this->doUpload();
        if (!$error) {
            $intro = Request::getPost("intro",'string','');
            $model = new AlbumTmp;
            $model->path = $path;
            $model->intro = $intro;
            $model->code = $token;
            $model->sort = Request::get("order");
            $model->author_id = Auth::user()->id;
            $model->created_at = time();
            if (!$id = $model->saveGetId()) {
                $msgs = $model->getMessages();
                $messages = array();
                foreach ($msgs as $msg) {
                    $messages[] = $msg->getMessage();
                }
                $error = implode(', ', $messages);
            }
        }
        if ($error) {
            $this->jsonp(compact('error'));
        }
        $this->responseUpload($path, $intro, $id, $token);
    }

    protected function doUpload() {
        $path = '';
        $filename = '';
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if (!$error) {
                $ext = $file->getExtension();
                $filename = $file->getName();
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id . '/albums');
                } else {
                    $error = Lang::_('please upload valid poster image');
                }
            } elseif ($error == 4) {
                $error = Lang::_('please choose upload poster image');
            } else {
                $error = Lang::_('unknown error');
            }
        } else {
            $error = Lang::_('please choose upload poster image');
        }
        return [$path, $filename, $error];
    }

    protected function responseUpload($path, $intro, $id, $token = null, $album_id = null) {
        $url = Oss::url($path);
        echo json_encode([
            'path' => $url,
            'intro' => $intro,
            'id' => $id,
            'token' => $token,
            'album_id' => $album_id,
            'append' => true,
        ]);
        exit;
    }

    private function readyDataForNews(&$data, &$message) {

        $data['partition_by'] = date('Y');
        $data['created_at'] = $data['created_at'] ? strtotime($data['created_at']) : time();
        $data['timelimit_begin'] = $data['timelimit_begin'] ? strtotime($data['timelimit_begin']) : time();

        $data['updated_at'] = time();
		$data['status'] = '1';
        
        $data['author_id'] = isset($data['author_id']) ? $data['author_id'] : Auth::user()->id;
        $data['author_name'] = Auth::user()->name;
        $data['channel_id'] = (int)Auth::user()->channel_id;
        //comment_type 1:禁用频率, 2:允许评论; comment_type_form 3:先发后审, 4:先审后发
        $data['comment_type'] = $data['comment_type'] == '2' ? $data['comment_type_form'] : $data['comment_type'];
        $originThumb = $data['thumb'];

        if($data['thumb']) {
            $this->readyThumb($data, $data['thumb'], "thumb");
        }

        //如果3种比例的图不存在，用原图缩放
        for($thumbIdx=1; $thumbIdx<=3; ++$thumbIdx){
            $thumbKey = 'thumb'.$thumbIdx;
            if($data[$thumbKey]) {
                $this->readyThumb($data, $data[$thumbKey], $thumbKey);
            }
            else if($originThumb) {
                $this->readyThumb($data, $originThumb, $thumbKey);
            }
        }

        if($data['referer_self'] == 0) {
            if(!F::isUrl($data['referer_url'])) {
                $message[] = "发布失败，引用地址格式不正确";
                return;
            }
            $this->readyReferer($data);
        }
        else{
            $data['referer_id'] = 0;
            $data['referer_name'] = '';
            $data['referer_url'] = '';
        }

        if($data['redirect_url'] && !F::isUrl($data['redirect_url'])){
            $message[] = '发布失败，外链地址格式不正确';
        }
        if(!$data['title']){
            $message[] = '发布失败，标题不能为空';
        }
    }

    //$data post的数据，$thumbData 图片数据，$targetThumbKey实际存入数据库的索引
    //当某个比例图不存在时，会使用原图进行缩放，这时$thumbData是原图，$targetThumbKey是对应比例的图
    private function readyThumb(&$data, $thumbData, $targetThumbKey) {
        $thumb_path = $this->uploadBase64StreamImg($thumbData, $targetThumbKey);

        if (empty($thumb_path) && strpos($thumbData,cdn_url("image","")) !== false) {
            $thumb_path = str_replace(cdn_url("image", ""), "", $thumbData);
        }
        else if(empty($thumb_path) && strpos($thumbData,cdn_url("image","")) === false){
            $thumb_path =  str_replace("/assets/admin/pages/img/thumb.png","",$this->getRemoteFile($thumbData,"thumb"));
        }
        if($thumb_path != ""){
            $data[$targetThumbKey] = $thumb_path;
        }
    }

    private function remove_albums($quotes_id) {
        $quotes = [];
        foreach($quotes_id as $data_id)
        {
            $data = Data::findFirstOrFail($data_id);
            if($data->type != 'album')
                $quotes[] = $data_id;
        }
        return $quotes;
    }


    private function uploadBase64StreamImg($thumb, $thumbType="thumb"){//todo加参数
        $url ="";
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $thumb, $files)) {
            $url = Auth::user()->channel_id.'/thumb/'.date('Y/m/d/').md5(uniqid(str_random())).".{$files[2]}";
            $imgData = base64_decode(str_replace($files[1], '', $thumb));
            switch ($thumbType) {
                case "thumb1":
                    $imgData = Data::scaleImg($imgData,$files[2],320,200);
                    break;
                case "thumb2":
                    $imgData = Data::scaleImg($imgData,$files[2],300,300);
                    break;
                case "thumb3":
                    $imgData = Data::scaleImg($imgData,$files[2],200,320);
                    break;
            }
            if (empty($imgData)) {
                return "";
            }
            Oss::uploadContent($url,$imgData);
        }
        return $url;
    }


    private function readyReferer(&$data) {
        $referer_url = "http://";
        preg_match("/^(http[s]?:\/\/)?([^\/]+)/i", $data['referer_url'], $arr_domain);
        $referer_url .= $arr_domain[2];
        $referer_m = Referer::findByDomain(Auth::user()->channel_id, $referer_url);
        if (!$referer_m->id) {
            $data["referer_name"] = ($data["referer_name"]) ? $data["referer_name"] : "未知网站-" . time();
            $data['referer_id'] = Referer::addDomian(Auth::user()->channel_id, $referer_url, $data["referer_name"]);
        }
        else {
            $data['referer_id'] = $referer_m->id;
        }
    }


    protected function readyDataForAlbumQutoes($data, &$messages) {
        $token = $data['token'];
        //$album_data_id = 0;
        if (!$data['album_id']) {
            $album_data_id = $this->createAlbum($token, $data, $messages);
        }
        else {
            $album_ids = ids($data['album_id']);
            $album_data_ids = ids($data['album_data_id']);
            $album_data_id = $album_data_ids[0];
            if($album_ids && isset($album_ids[0])) {
                $album_id = $album_ids[0];
                $this->updateDataAlbum($token,$data,$album_id,$messages);
            }
        }
        return $album_data_id;
    }

    private function updateDataAlbum($token,$data,$album_id,$messages){
        $images = AlbumTmp::query()
            ->andCondition('code', $token)
            ->andCondition('author_id', Auth::user()->id)
            ->execute();
        $remoteImage = (isset($data['f_img'])&&$data['f_img'])?explode(",",$data['f_img']):[];
        $ref_album_imgs = isset($data['ref_album_img_ids'])?ids($data['ref_album_img_ids']):array();
        $album_image = AlbumImage::findByAlbumId($album_id);
        $image_ids = [];
        foreach($album_image as $image){
            $image_ids[] = $image['id'];
        }
        if(count($ref_album_imgs)>0){
            //插入未添加的数据
            $inserted = array_diff($ref_album_imgs,$image_ids);
            foreach($inserted as $img_id) {
                if($img_id) {
                    $ref_albumimg = AlbumImage::findFirstOrFail($img_id);
                    $m = new AlbumImage();
                    $r['path'] = $ref_albumimg->path;
                    $r['intro'] = $ref_albumimg->intro;
                    $r['sort'] = 0 ;
                    $m->saveOne($album_id, $r, $data['partition_by']);
                }
            }
            //删除多余的数据
            $deleted = array_diff($image_ids,$ref_album_imgs);
            foreach( $deleted as $img_id) {
                if($img_id) {
                    $albumimg = AlbumImage::findFirstOrFail($img_id);
                    $albumimg->delete();
                }
            }
        }
        //新上传的图片
        if(count($images)>0){
            foreach($images as $tmp) {
                $m = new AlbumImage();
                $r = $tmp->toArray();
                $m->saveOne($album_id, $r, $data['partition_by']);
                $tmp->delete();
            }
        }
        if(count($remoteImage)>0) {
            foreach($remoteImage as $imgurl) {
                $path = $this->getRemoteFile($imgurl);
                if($path) {
                    $m = new AlbumImage();
                    $r['path'] = $path;
                    $r['intro'] = '';
                    $r['sort'] = 0;
                    $m->saveOne($album_id, $r, $data['partition_by']);
                }
            }
        }
        return $album_id;
    }

    private function createAlbum($token, $data, &$messages) {
        //初始化相册数据
        $album_data = array();
        $album_data['title'] = $data['title'];
        $album_data['thumb'] = $data['thumb'];
        $album_data['intro'] = $data['intro'];
        $album_data['timelimit_begin'] = $data['timelimit_begin'] ? strtotime($data['timelimit_begin']) : time();
        $album_data['created_at'] = time();
        $album_data['updated_at'] = time();
        $album_data['author_id'] = Auth::user()->id;
        $album_data['author_name'] = Auth::user()->name;
        $album_data['channel_id'] = Auth::user()->channel_id;
        $album_data['referer_self'] = 1;
        $album_data['referer_url'] = "";
        //查找上传的临时数据
        $images = AlbumTmp::query()
            ->andCondition('code', $token)
            ->andCondition('author_id', Auth::user()->id)
            ->execute();
        $vData = Data::makeValidator($album_data);
        $remoteImage = (isset($data['f_img'])&&$data['f_img'])?explode(",",$data['f_img']):[];
        $ref_album_imgs = isset($data['ref_album_img_ids'])?ids($data['ref_album_img_ids']):array();
        if ((count($images) > 0 || count($remoteImage)>0 || count($ref_album_imgs) > 0) && !$vData->fails()) {
            $model = new Album();
            DB::begin();
            try {
                //存新闻
                if(!$id = $model->saveGetId($data)) {
                    $this->throwDbE('Album_model');
                }

                $data['partition_by'] = date('Y');
                $dModel = new Data();


                if(!$data_id = $dModel->doSave($data, Data::getAllowed(), 'album', $id)) {
                    $this->throwDbE('Album_dModel');
                }

                DB::commit();

                if($images)
                    foreach($images as $tmp) {
                        $m = new AlbumImage();
                        $r = $tmp->toArray();
                        $m->saveOne($id, $r, $data['partition_by']);
                        $tmp->delete();
                    }
                if($remoteImage)
                    foreach($remoteImage as $imgurl)
                    {
                        $path = $this->getRemoteFile($imgurl);
                        if($path)
                        {
                            $m = new AlbumImage();
                            $r['path'] = $path;
                            $r['intro'] = '';
                            $r['sort'] = 0;
                            $m->saveOne($id, $r, $data['partition_by']);
                        }
                    }
                if($ref_album_imgs)
                    foreach($ref_album_imgs as $album_img_id){
                        $ref_albumimg = AlbumImage::findFirstOrFail($album_img_id);
                        if($ref_albumimg)
                        {
                            $m = new AlbumImage();
                            $r['path'] = $ref_albumimg->path;
                            $r['intro'] = $ref_albumimg->intro;
                            $r['sort'] = 0 ;
                            $m->saveOne($id, $r, $data['partition_by']);
                        }
                    }
                return $data_id;
            } catch(DatabaseTransactionException $e) {
                DB::rollback();
                if($e->getCode() === 0) {
                    $_m = $e->getMessage();
                    $msgs = $$_m->getMessages();
                    foreach($msgs as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $messages[] = $e->getMessage();
                }
                return 0;
            }
        }
    }

    /*
  * @抓取远程文件
  *
  * */
    private function getRemoteFile($file, $directory_name='albums'){
        $prefix = $directory_name;
        $thumb = $file;
        $ext = substr(strrchr($thumb, '.'), 1);
        $legal_ext = "jpg";
        if(false!==stripos(strtolower($ext), 'png')) {
            $legal_ext = "png";
        }
        if(false!==stripos(strtolower($ext), 'gif')) {
            $legal_ext = "gif";
        }

        $filename = pathinfo($thumb)['filename'] . '.' . $legal_ext;
        $path = httpcopy($thumb, APP_PATH . '../tasks/tmp/' . $filename, 120, $proxy=true);
        $osspath = "";
        if ($path) {
            $osspath = Oss::uniqueUpload($legal_ext, $path, Auth::user()->channel_id.'/'.$prefix);
            unlink($path);
        }
        return $osspath;
    }


    private function setRedisParam($data_id) {
        //Jason 2016/8/22 微信关注配置
        if (Request::getPost('toas1') == '2') {
            RedisIO::set('authUserInfo:'. $data_id, 1);
        }
        else if(RedisIO::exists('authUserInfo:'. $data_id)) {
            RedisIO::del('authUserInfo:'. $data_id);
        }
        if (Request::getPost('toas2') == '2') {
            RedisIO::set('subscribe:'. $data_id, 1);
        }
        else if(RedisIO::exists('subscribe:'. $data_id)) {
            RedisIO::del('subscribe:'. $data_id);
        }
        //审核开关
        RedisIO::set(UserComments::REVIEW . $data_id, $this->getCommenty());
        //设置点赞基数
        $baselikes = Request::getPost('baselikes','int');
        RedisIO::set(self::BASELIKES.$data_id, $baselikes);
        //设置评论总次数
        $setCommentCount = Request::getPost('base_comment_count','int');
        RedisIO::set(self::BASECOMMENTCOUNTS . $data_id, $setCommentCount);
        //设置分享量基数
        $setShareCount = Request::getPost('base_share_count','int');
        RedisIO::set(self::BASESHARECOUNTS . $data_id, $setShareCount);
        //设置点击量基数
        $setHitsCount = Request::getPost('base_hits_count','int');
        RedisIO::set(self::BASEHITSCOUNTS . $data_id, $setHitsCount);
    }

    //审核、取消审核时更新口令
    private function updateSecretRedis($dataId) {
        $channelId = Session::get('user')->channel_id;
        $data = Data::getByDataId($channelId,$dataId);
        if($data->status == 1){ //审核通过
            Data::setRedisSecretKey($channelId,$dataId,$data->secret_key,$data->status);
            Data::setRedisSecretInputUrl($dataId,$data->secret_url);
        }else{ //取消审核
            Data::deleteRedisSecretInputUrl($dataId);
            if(!empty($data->secret_key)){
                Data::deleteRedisSecretKey($channelId,$data->secret_key);
            }
        }
    }

    private function getCommenty() {
        $comment_type       = Request::getPost('comment_type','int',0);
        $comment_type_form  = Request::getPost('comment_type_form','int',0);
        return $comment_type == 2 ? ($comment_type_form==4 ? 3: 2 ) :1;   //2先发后审   3 先审后发
    }

    public function reverse_addressAction() {
        $address = $_POST["address"];
        $res = Regions::reverseAddress($address);
        $this->_json(["address"=>$res]);
    }

    public function setDataDataExt(&$data_data_ext, $data) {
        $alltypes = array('news'=>'quotelist_news', 'album'=>'quotelist_album', 'special'=>'quotelist_special', 'video'=>'quotelist_video', 'signal'=>'quotelist_signal', 'vote'=>'quotelist_vote', 'lottery'=>'quotelist_lottery','attach'=>'quotelist_attach');
        foreach($alltypes as $type =>$label) {
            if(isset($data[$label])&&$data[$label]) {
                $data_data_ext[$type] = array();
                $ids = explode(',', "".$data[$label]);
                foreach($ids as $id) {
                    array_push($data_data_ext[$type], array('data_id' => intval($id), 'template'=>'default'));
                    $this->delQuoteMedia($id);
                }
            }
        }
    }

    //获取url
    public function videoUrlAction() {
        View::disable();
        $id = Request::getPost("id");
        $type = Request::getPost("type");
        $url=Data::getVideoUrl($id,$type);
        echo $url;
    }

    public function secretCheckAction() {
        View::disable();
        $oldSecret = "";
        $id = Request::getPost("id");
        $type = Request::getPost("type");
        $secret = Request::getPost("secret");
        if($type == "edit"){
            $model = Data::findFirstOrFail($id);
            $oldSecret = $model->secret_key;
        }
        $result = "200";
        if( !Data::checkSecretKey(Auth::user()->channel_id,$secret,$oldSecret)){
            $result = "400";
        }
        echo  $result;
        exit();
    }
}




