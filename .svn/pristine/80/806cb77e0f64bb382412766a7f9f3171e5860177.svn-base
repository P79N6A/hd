<?php

use GenialCloud\Exceptions\DatabaseTransactionException;

/**
 * 文章管理
 *
 * @author     Xue Wei
 * @created    2015-9-18
 */
class MediaPostsController extends MediaBaseController {

    protected $urlName = 'media_posts';

    protected $type = 'news';

    const HUDONG_LIKES_COUNTS = "hudongLikesCounts";
    const BASELIKES = "baseLikesCounts:";
    const SETMEIZICOUNT = "setMeiZiCounts:";
    const BASECOMMENTCOUNTS = "baseCommentCounts:";
    const BASESHARECOUNTS = "baseShareCounts:";
    const BASEHITSCOUNTS = "baseHitsCounts:";


    public function addAction() {

        if($this->denySystemAdmin()) {
            return true;
        }

        $this->initFormView();
        $messages = [];
        $model = new News;
        $datas = [];

        // 默然地址，部门数据获取
        $region = $this->initRegionData();

        if(Request::isPost()) {

            $data = $this->preProcessData(Request::getPost());
            //兼容引用数据
            $quotes = ids(Request::getPost('quotes'));
            $datas = Data::queryByIds(Auth::user()->channel_id, $quotes);
            $vData = Data::makeValidator($data);
            $vNews = News::makeValidator($data);
            if(!$vData->fails() && !$vNews->fails() && $thumb = $this->validateAndUpload($messages)) {
                $model->thumb = $data['thumb'] = $thumb;
                $data['partition_by'] = date('Y');
                $data['created_at'] = $data['created_at']?strtotime($data['created_at']):time();
                $data['updated_at'] = time();
                $data['author_id'] = Auth::user()->id;
                $data['author_name'] = Auth::user()->name;
                $data['channel_id'] = (int) Auth::user()->channel_id;
                $data['redirect_url']= $data['isSubUrl'];
                if($data['referer_self']==0) {
                    $referer_url = "http://";
                    preg_match("/^(http[s]?:\/\/)?([^\/]+)/i", $data['referer_url'], $arr_domain);
                    $referer_url .= $arr_domain[2];
                    $referer_m = Referer::findByDomain(Auth::user()->channel_id, $referer_url);
                    if (!$referer_m->id) {
                        $data["referer_name"] =($data["referer_name"])?$data["referer_name"]:"未知网站-".time();
                        $data['referer_id'] = Referer::addDomian(Auth::user()->channel_id, $referer_url, $data["referer_name"]);
                    } else {
                        $data['referer_id'] = $referer_m->id;
                    }
                }
                //DB 事务
                DB::begin();
                try {
                    if(count($datas) != count($quotes)) {
                        $this->throwDbE('非法的引用对象', 1);
                    }
                    $data_data = json_encode($quotes);
                    //存新闻
                    if(!$id = $model->saveGetId($data)) {
                        $this->throwDbE('model');
                    }
                    $dModel = new Data();
                    //存data
                    if(!$data_id = $dModel->doSave($data, Data::getAllowed(), 'news', $id, $data_data)) {
                        $this->throwDbE('dModel');
                    }
                    //存地区
                    $regionData = new RegionData();
                    if(!$regionData->saveRegion($data,$data_id)){
                        // $this->throwDbE('regions');
                    }
                    
                    // 保存部门
                    $govData = new GovernmentDepartmentData();
                    if(!$govData->saveGovernmentDepartment($data, $data_id)){
                    	// $this->throwDbE('save government Department error');
                    }

                    //审核开关
                    $comment_type = intval(Request::getPost('comment_type'));
                    RedisIO::set(UserComments::REVIEW . $data_id,$comment_type);

                    //设置点赞基数
                    $baselikes = Request::getPost('baselikes','int');
                    RedisIO::set(self::BASELIKES . $data_id,$baselikes);

                    //设置点赞次数
                    $setMeiZiCount = Request::getPost('setMeiZiCount','int');
                    RedisIO::set(self::SETMEIZICOUNT . $data_id,$setMeiZiCount);
                    
                    //存私有分类
                    $msgs = PrivateCategoryData::publish($data_id, $data['partition_by']);
                    if(empty($msgs)) {
                        DB::commit();
                        $messages[] = Lang::_('add success');
                    } else {
                        DB::rollback();
                        $messages = array_merge($messages, $msgs);
                    }
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
                }
            } else {
                $msgBag = $vData->messages();
                $msgBag->merge($vNews->messages());
                foreach($msgBag->all() as $msg) {
                    $messages[] = $msg;
                }
            }
        }

        $media_type = PrivateCategory::MEDIA_TYPE_NEWS;
        $privateCategoryData = false;
        $data_id = 0;
        View::setVars(compact('model', 'messages', 'datas', 'media_type', 'privateCategoryData','region','data_id'));
    }

    public function editAction() {

        if($this->denySystemAdmin()) {
            return true;
        }

        $this->initFormView();
        $messages = [];
        $model = News::channelQuery(Auth::user()->channel_id)
            ->andCondition('id', Request::get('id', 'int'))
            ->first();
        if(!$model) {
            abort(404);
        }
        $r = Data::getByMedia($model->id, 'news');
        $datas = [];
        if(!$r) {
            $messages[] = Lang::_('can not find carrier');
        } else {
            $quotes = json_decode($r->data_data, true);
            if($quotes) {
                $datas = Data::queryByIds(Auth::user()->channel_id, $quotes);
            }
        }
        
        // 默然地址，部门数据获取
        $region = $this->initRegionData();
       
        $government = $this->initDepartmentData($r->id);
      
        if(Request::isPost()) {
            //媒资ID
            $data_id = $r->id;

            //保存要更新的统计data_id
            RedisIO::zAdd(DataStatistics::QUEUEDATAID, 0, $data_id);

            //设置评论点赞数
            $value = Request::getPost(self::HUDONG_LIKES_COUNTS, "int");
            RedisIO::set(self::HUDONG_LIKES_COUNTS, $value);

            //设置点赞基数
            $baselikes = Request::getPost('baselikes','int');
            RedisIO::set(self::BASELIKES . $data_id,$baselikes);

            //设置点赞次数
            $setMeiZiCount = Request::getPost('setMeiZiCount','int');
            RedisIO::set(self::SETMEIZICOUNT . $data_id,$setMeiZiCount);

            //设置评论总次数
            $setCommentCount = Request::getPost('base_comment_count','int');
            RedisIO::set(self::BASECOMMENTCOUNTS . $data_id,$setCommentCount);

            //设置分享量基数
            $setShareCount = Request::getPost('base_share_count','int');
            RedisIO::set(self::BASESHARECOUNTS . $data_id,$setShareCount);

            //设置分享量基数
            $setHitsCount = Request::getPost('base_hits_count','int');
            RedisIO::set(self::BASEHITSCOUNTS . $data_id,$setHitsCount);

            //Jason 2016/8/22 微信关注配置
            if(Request::getPost('toas1')){
                RedisIO::set('authUserInfo:' . $data_id,1);
            } else {
                RedisIO::delete('authUserInfo:' . $data_id);

            }

            if(Request::getPost('toas2')){
                RedisIO::set('subscribe:' . $data_id,1);
            } else {
                RedisIO::delete('subscribe:' . $data_id);
            }

            $isSubUrl = Request::getPost('isSubUrl');
            $noSubUrl = Request::getPost('noSubUrl');
            //审核开关
            $comment_type = intval(Request::getPost('comment_type'));
            RedisIO::set(UserComments::REVIEW . $data_id,$comment_type);

            //H5地址
            RedisIO::set('isSubUrl:' . $data_id,$isSubUrl);
            //未关注地址
            if($noSubUrl){
                RedisIO::set('noSubUrl:' . $data_id,$noSubUrl);
            }


            $data = $this->preProcessData(Request::getPost());

            //兼容引用数据
            $quotes = ids(Request::getPost('quotes'));
            $datas = Data::queryByIds(Auth::user()->channel_id, $quotes);
            $vData = Data::makeValidator($data);
            $vNews = News::makeValidator($data);
            $oldSecretKey = $r->secret_key;
            $oldSecretUrl = $model->secret_url;
            $secretFlag = Data::checkSecretKey(Auth::user()->channel_id,$data["secret_key"],$oldSecretKey);
            if(!$vData->fails() && !$vNews->fails() && $thumb = $this->validateAndUpload($messages) && $secretFlag) {
                $model->comment_type = $comment_type;
                $model->thumb = $data['thumb'] = $thumb;
                $data['updated_at'] = time();
                $r->created_at = strtotime($data['created_at']);
                $model->created_at = strtotime($data['created_at']);

                $data['redirect_url']= $isSubUrl;
                if($data['referer_self']==0) {
                    $referer_url = "http://";
                    preg_match("/^(http[s]?:\/\/)?([^\/]+)/i", $data['referer_url'], $arr_domain);
                    $referer_url .= $arr_domain[2];
                    $referer_m = Referer::findByDomain(Auth::user()->channel_id, $referer_url);
                    if(!$referer_m->id) {
                        $data["referer_name"] =($data["referer_name"])?$data["referer_name"]:"未知网站-".time();
                        $data['referer_id'] = Referer::addDomian(Auth::user()->channel_id, $referer_url, $data["referer_name"]);
                    }
                    else {
                        $data['referer_id'] = $referer_m->id;
                    }
                }
                else {
                    $data['referer_id'] = 0;
                    $data['referer_url'] = "";
                }
                //DB 事务
                DB::begin();
                try {
                    if(count($datas) != count($quotes)) {
                        $this->throwDbE('非法的引用对象', 1);
                    }
                    //更新新闻
                    if(!$model->update($data, News::safeUpdateFields())) {
                        $this->throwDbE('model');
                    }

                    $data['data_data'] = json_encode($quotes);
                    //口令
                    Data::setRedisSecretKey(Auth::user()->channel_id,$data_id,$data['secret_key']);
                    Data::setRedisSecretInputUrl($data_id,$data['secret_url']);

                    if(!$r->update($data, Data::safeUpdateFields())) {
                        $this->throwDbE('r');
                    }
                    //删除地区
                    $region_arr = RegionData::findRegionData($r->id);
                    $index_arr = array();
                    $index=0;
                    foreach($data as $k=>$v){
                        if(preg_match('/^(region)+/',$k)){
                            $i=substr($k,-1);
                            $index_arr[$index]=$v;
                            $index++;
                        }
                    }
                    foreach($region_arr as $k =>$v){
                        if(!in_array($v['id'],$index_arr)){
                            RegionData::deleteRegionData($v['id']);
                            unset($region_arr[$k]);
                        }
                    }
                    //存地区
                    $regionData = new RegionData();
                    if(!$regionData->saveRegion($data,$r->id)){
                        //$this->throwDbE('regions');
                    }
                    
                    //保存部门
                    $gov = new GovernmentDepartmentData();
                    if(!$gov->updateGovernmentDepartment($data,$r->id)){
                    	//$this->throwDbE('save government Department error');
                    }

                    //私有分类发布
                    $msgs = PrivateCategoryData::publish($r->id, $r->partition_by);
                    if(empty($msgs)) {
                        DB::commit();
                        $messages[] = Lang::_('modify success');
                        $key = D::memKey('apiGetDataById', ['id' => $model->id]);
                        MemcacheIO::set($key, false, 86400*30);
                        $categoryData = CategoryData::getIdByData($model->id);
                        foreach ($categoryData as $v) {
                            $key = "columns_list_id:" . $v;
                            MemcacheIO::set($key, false, 86400 * 30);
                        }

                    } else {
                        DB::rollback();
                        Data::setRedisSecretKey(Auth::user()->channel_id,$data_id,$oldSecretKey);
                        Data::setRedisSecretInputUrl($data_id,$oldSecretUrl);
                        $messages = array_merge($messages, $msgs);
                    }
                } catch(DatabaseTransactionException $e) {
                    DB::rollback();
                    Data::setRedisSecretKey(Auth::user()->channel_id,$data_id,$oldSecretKey);
                    Data::setRedisSecretInputUrl($data_id,$oldSecretUrl);
                    if($e->getCode() === 0) {
                        $_m = $e->getMessage();
                        $msgs = $$_m->getMessages();
                        foreach($msgs as $msg) {
                            $messages[] = $msg->getMessage();
                        }
                    } else {
                        $messages[] = $e->getMessage();
                    }
                }
            } else {
                $msgBag = $vData->messages();
                $msgBag->merge($vNews->messages());
                foreach($msgBag->all() as $msg) {
                    $messages[] = $msg;
                }
                if(!$secretFlag){
                    $messages[] = Lang::_("secret key repeat");
                }
            }
        }
        $r->assignToMedia($model);
        $region_arr = RegionData::showRegion($r->id);

        $media_type = PrivateCategory::MEDIA_TYPE_NEWS;
        $privateCategoryData = privateCategoryData::getIdByData($r->id);
        $data_id = $r->id;
        $secret_key = $r->secret_key;
        $secret_url = Data::getRedisSecretInputUrl($data_id);

        View::setVars(compact('model', 'messages', 'datas','region_arr','data_id','privateCategoryData', 'media_type','region','government','secret_key','secret_url'));

    }

    public function listMediaAction() {
        $type = Request::get('type', 'string');
        if(!in_array($type, ['album', 'video', 'video_collection', 'signals'])) {
            abort(404);
        }
        if($data=Request::getPost()) {
            $data['type'] = $type;
            $parcel = Data::searchMedia($data,Session::get('user')->channel_id);
        }else {
            if($type=='album') {
            $album_data_id = Request::get('album_id', 'string');
            $parcel = Data::channelQuery(Auth::user()->channel_id, 'Data')
                ->columns(['Data.*', 'PrivateCategory.*'])
                ->andWhere("type='{$type}' and Data.id<>".$album_data_id)
                ->leftJoin("PrivateCategoryData", "Data.id = PrivateCategoryData.data_id")
                ->leftJoin("PrivateCategory", "PrivateCategory.id = PrivateCategoryData.category_id")
                ->orderBy('Data.id Desc')
                ->paginate(50, 'Pagination');
            }
            else {
            $parcel = Data::channelQuery(Auth::user()->channel_id, 'Data')
                ->columns(['Data.*', 'PrivateCategory.*'])
                ->andCondition('type', $type)
                ->leftJoin("PrivateCategoryData", "Data.id = PrivateCategoryData.data_id")
                ->leftJoin("PrivateCategory", "PrivateCategory.id = PrivateCategoryData.category_id")
                ->paginate(50, 'Pagination');
            }
        }
        View::setVars(compact('parcel', 'type','data'));
    }

    public function publishAction() {
        $this->initFormView();
        $messages = [];
        $id = Request::get('id', 'int');
        $model = Data::getById($id, Auth::user()->channel_id);
        if(!$model) {
            abort(404);
        }
        if(Request::isPost()) {
            $this->checkPublishAuth();
            $validator = Data::makePublishValidator(Request::getPost(), $model->type);
            /**
             * FIXME 校验分类所属
             */
            if(!$validator->fails()) {
                $media_publish = (array) Request::getPost('media_publish');
                $this->postToXiaoshan($id , $media_publish);
                if(CategoryData::addPublish($id, $media_publish) === false) {//这儿有问题
                    $messages[] = '栏目发布异常';
                }else {//发布成功进行智慧萧山的推送
                }
                if($model->type != 'special') {
                    $special_publish = (array) Request::getPost('special_publish');
                    if(SpecialCategoryData::publish($id, $special_publish) === false) {
                        $messages[] = '专题栏目发布异常';
                    }
                }
                if(empty($messages)) {
                    $messages[] = Lang::_('modify success');
                    $key = D::memKey('apiGetDataById', ['id' => $id]);
                    MemcacheIO::set($key, false, 86400*30);
                    foreach ($media_publish as $v) {
                        $key = "columns_list_id:" . $v;
                        MemcacheIO::set($key, false, 86400 * 30);
                    }
                }
            } else {
                foreach($validator->messages()->all() as $msg) {
                    $messages[] = $msg;
                }
            }
        }

        $categoryData = CategoryData::getIdByData($id);
        //专题不可以发布到专题类型下
        $specialCategoryData = [];
        if($model->type != 'special') {
            $specialCategoryData = SpecialCategoryData::getIdByData($id);
        }

        View::setVars(compact('model', 'messages', 'categoryData', 'specialCategoryData'));
    }


    public function tplAction() {
        $this->initFormView();
        $messages = [];
        $id = Request::get('id', 'int');
        $model = Data::getById($id, Auth::user()->channel_id);


        if (Request::isPost()) {
            $data = Request::getPost();
            $filedata = $this->validateAndUpload($messages);
            $filedatas = json_decode($filedata,true);
            foreach ($filedatas as $key => $value) {
                if($value['path'] != '') {
                    $data['channel_id'] = $channel_id;
                    $data['domain_id'] = $domain_id;
                    $data['author_id'] = Session::get('user')->id;
                    $data['created_at'] = $data['updated_at'] = time();
                    $data['status'] = 1;
                    $data['path'] = $value['path'];
                    $data['name'] = $value['name'];
                    $data['content'] = $value['content'];

                    $unique = Templates::checkUnique($domain_id,$value['path']);
                    if(!empty($unique)) {
                        $value['updated_at'] = time();
                        $unique->update($value);
                    }
                    else if($model->save($data)){
                        if($data['type'] != 'static') {
                            $key = 'smarty:'.$domain_id.':'.$data['path'];
                            $tpldata = array('content'=>$data['content'],'updated_at'=>$data['updated_at']);
                            MemcacheIO::set($key, $tpldata);
                        }
                        $model = new Templates();
                    }
                }
            }
            $messages[] = Lang::_('success');
        }


        View::setVars(compact('model'));

    }

    private $sign="f4fna96cdnf27i8W9Jd7bV6T1sadf9z5Zcasdy0W6ob88asdf126OOo659HUhoji";
    private $city_id="330109";

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

            if($arr[0]->type == 'news') {
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
    public function setWeixinUrlAction(){
        $data_id = Request::getPost("data_id",'int',0);
        $isSubUrl = Request::getPost("isSubUrl",'string',0);
        $noSubUrl = Request::getPost("noSubUrl",'string',0);
        //H5地址
        $isSubUrl = RedisIO::set('isSubUrl:' . $data_id,$isSubUrl);
        //未关注地址
        if($noSubUrl){
            $noSubUrl = RedisIO::set('noSubUrl:' . $data_id,$noSubUrl);
        }
        if($isSubUrl || $noSubUrl){
            $this->_json([],200,"修改成功");
        } else {
            $this->_json([],403,"修改失败");
        }
    }
    
    private function checkPublishAuth(){
        
    }

}