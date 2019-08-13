<?php

/**
 * 直播信号管理
 *
 * @created    2015-11-10
 */
class MediaSignalsController extends MediaBaseController {

    protected $urlName = 'media_signals';

    protected $type = 'live';
    
    const XHCHANNEL = 0;
    
    /**
     * 发送 推送地址给 云帆
     */
    public function sendYLAction() {
        header("Content-type: application/json;charset='utf-8'");
        $url = Request::get('url','string');
        $channel_id = Session::get('user')->channel_id;
        $signalId  = Request::get('id', 'int', -1);
        $playUrlData = SignalSource::findSignalSourceUrl($signalId, "rollback"); // 播放地址
        $res = "";
        if (isset($playUrlData) && !empty($playUrlData)) {
            $playUrl = $playUrlData[0]['url'];
            $res = F::liveUgcGuideProxy($url, $playUrl, $channel_id);
        }
        if($res=="200 OK") {
            $signalEpg = new SignalEpg();
            $resData = $signalEpg->findOneData($signalId);
            if(isset($resData) && !empty($resData)) {
                $epg_id = $resData->id;
                $signalPlayUrl = new SignalPlayurl();
                $res = $signalPlayUrl->updateAllDataStatic($epg_id);

                $signalPlayUrls = new SignalPlayurl();
                $result = $signalPlayUrls->updateIsPushing($epg_id, $url);

            }
            $this->echoExit(json_encode(['code' => '200', 'msg' => "success"]));
        }
        else {
            $this->echoExit(json_encode(['code' => '400', 'msg' => 'error']));
        }
    }

    /**
     * 获取播放地址
     */
    public function getGuidePlayUrlAction() {
        header("Content-type: application/json;charset='utf-8'");
        $signal_id = Request::get('id','int',-1);
        $type = Request::get('type','string');
        if ($type == "playurl") {
            $rs = SignalSource::findSignalSourceUrl($signal_id, "rollback");
            if(isset($rs) && !empty($rs)) {
                $this->echoExit(json_encode(['code' => '200', 'msg' => "success", 'data' => ($rs)]));
            }
            else {
                $this->echoExit(json_encode(['code' => '400', 'msg' => 'error', 'data' => ('')]));
            }
        }

    }

    /**
     * 获取导播的推流地址
     */
    public function getGuideUrlAction() {
    	header("Content-type: application/json;charset='utf-8'");
    	$id = Request::get('id','int',-1);
    	if($id > -1) {
    		$rs = SignalEpg::findEpgAllDatas($id);
    		$this->echoExit(json_encode(['code' => '200', 'msg' => "success", 'data' => ($rs)]));
    	}
    	else{
    		$this->echoExit(json_encode(['code' => '400', 'msg' => 'error', 'data' => ('')]));
    	}
    }
    
    /**
     * 修改状态
     */
    public function changeStatusAction() {
    	$dataStatus = Request::getPost('status','int');
    	$dataId = Request::getPost('id','int');
    	if($dataStatus < 0){
    		exit;
    	}
    	$res_status = "";
    	DB::begin();
    	$channelId = Auth::user()->channel_id;
    	$signals = new Signals();
    	$data = new Data();
    	switch ($dataStatus) {
    		case Signals::LIVE_DATA_STATUS_NO_REVIEWED:
    			$bRes = $data->modifyStatus($dataId, $dataStatus);
    			$bRes = $signals->updateLiveStatus($dataId, Signals::LIVE_STATUS_NOTSTART, $channelId);
    			$res_status = Signals::LIVE_DATA_STATUS_NO_REVIEWED_VALUES;
    			break;
    		case Signals::LIVE_DATA_STATUS_FORBIDDEN:
    			$bRes = $data->modifyStatus($dataId, $dataStatus);
    			$res_status = Signals::LIVE_DATA_STATUS_FORBIDDEN_VALUES;
    			$bRes = $signals->updateLiveStatus($dataId, Signals::LIVE_STATUS_FORBIDDEN, $channelId);
    			break;
    		case Signals::LIVE_DATA_STATUS_REVIEWED:
    			$dataV = Data::getByDataId($channelId, $dataId);
    			if($dataV->status != Signals::LIVE_DATA_STATUS_REVIEWED || $dataV->status == Signals::LIVE_DATA_STATUS_FORBIDDEN) {
    				$bRes = $data->modifyStatus($dataId, $dataStatus);
    				$signalsStatus = Signals::LIVE_STATUS_NOTSTART;
    				$res_status = Signals::LIVE_STATUS_NOTSTART_VALUES;
    				Signals::checkTimeLimit($dataId, $channelId, $signalsStatus, $res_status);
    				$bRes = $signals->updateLiveStatus($dataId, $signalsStatus, $channelId);
    				Signals::refreshCDN($dataId, $channelId);
    			}else {
    				$res_status = "";
    			}
    			break;
    		default:;
    	}
    	
    	if ($bRes) {
    		DB::commit();
    		echo json_encode($res_status);
    		exit;
    	} else {
    		DB::rollback();
    		exit;
    	}
    }
    
    /**
     * 直播列表排序
     */
    public function sortAction() {
    	$ids = Request::get("ids");
    	$sorts = Request::get("sorts");
    	if((empty($ids) || !is_array($ids)) 
    	|| (empty($sorts) || !is_array($sorts))) {
    		 $this->echoExit($this->_json([],40,Lang::_('only top can resort')));
    	}else {
    		$ret = Signals::sortBySorts($ids,$sorts);
    		if($ret) {
    			$this->echoExit(json_encode(['code' => '200', 'msg' => Lang::_('success')]));
    		}else {
    			$this->echoExit(json_encode(['code' => '400', 'msg' => Lang::_('error')]));
    		}
    	}
    }
    /**
     * 推荐位列表排序
     */
    public function featuresortAction() {
    	$ids = Request::get("ids");
    	$sorts = Request::get("sorts");
    	if((empty($ids) || !is_array($ids))
    	|| (empty($sorts) || !is_array($sorts))) {
    		$this->echoExit($this->_json([],40,Lang::_('only top can resort')));
    	}else {
    		$ret = FeaturedData::sortBySorts($ids,$sorts);
    		if($ret) {
    			$this->echoExit(json_encode(['code' => '200', 'msg' => Lang::_('success')]));
    		}else {
    			$this->echoExit(json_encode(['code' => '400', 'msg' => Lang::_('error')]));
    		}
    	}
    }
    
    /**
     * 置顶
     */
    public function topAction() {
    	$signals_sort = Request::get("signals_sort");
    	$signals_data_id = Request::get("signals_data_id");
    	if (!$signals_data_id || !is_numeric($signals_data_id)) {
    		$this->echoExit($this->_json([], 400, Lang::_('media_signals_id required')));
    	}
   		$msg = Signals::top($signals_sort,$signals_data_id);
    	$msg = $msg ? $this->_json([], 200) : $this->_json(400, Lang::_('error'));
    	$this->echoExit($msg);
    }
    
	/**
	 * 推荐位置顶
	 */
	public function featureTopAction() {
        $fd_id = Request::get("fd_id");
        $feature_id = Request::get("f_id");
        $sort = Request::get("sort");
        $ret = FeaturedData::top($fd_id, $feature_id, $sort);
        $this->echoExit(json_encode(['code' => '200', 'msg' => Lang::_('success')]));
    }
    
   
    
    public function indexAction(){
    	$channel_id = Auth::user()->channel_id;
    	
    	$featureData = FeaturedData::findAllByType($this->type, $channel_id);
    	$datas = Signals::findMediaData($channel_id, $this->type);
    	
    	View::setVars(compact('datas','featureData'));
    }
    
    /**
     * 绑定推荐位
     */
    public function bindingAction() {
    	$dataId = Request::getQuery('id','int');
    	$signalsId = Request::getQuery('sid','int');
    	$featureType = $this->type;
    	$channelId = Auth::user()->channel_id;
    	
    	$features = Features::findByType($featureType);
    	$data = Data::getByDataId($channelId, $dataId);
    	
    	$datas = array(
    		'feature_id' => $features->id,
    		'data_id' => $data->id,
    		'feature_title' => $data->title,
    		'created_at' => time(),
    		'updated_at' => time()
    	);
    	
    	DB::begin();
    	$featuredData = new FeaturedData();
    	$bRes = $featuredData->createData($datas);
    	if($bRes) {
    		$bRes = Signals::updateFeatureStatus($signalsId, Signals::IS_BINDING_FEATURES);
    	}
    	if ($bRes) {
    		DB::commit();
    		echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
    	} else {
    		DB::rollback();
    		echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
    	}
    	exit;
    }
    
    /**
     * 推荐位取消绑定
     */
    public function bindingdelAction() {
    	$featuresId = Request::getQuery('id','int');
    	$signalsId = Request::getQuery('sid','int');
    	DB::begin();
    	$bRes = FeaturedData::deleteFeaturedData($featuresId);
    	if ($bRes) {
    		$bRes = Signals::updateFeatureStatus($signalsId, Signals::IS_NOT_BINDING_FEATURES);
    	}
    
    	if ($bRes) {
    		DB::commit();
    		echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
    	} else {
    		DB::rollback();
    		echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
    	}
    	exit;
    }
    
    /**
     * 删除数据
     */
    public function deleteAction() {
     	$dataId = Request::get('id', 'int');
     	$signalId = Request::get('sid', 'int');
     	$channelId = Auth::user()->channel_id;
     	$bSignalDel = false;
     	DB::begin();
     	if($signalId > 0) {
			if(SignalSource::deleteAllData($signalId)) {
     			$bSignalDel = Signals::deleteOneData($signalId, $channelId);
			}
     	}
     	
//     	$mediaSignalsData = Lives::deleteData($dataId, $channelId);
    	if ($bSignalDel) {
    		DB::commit();
    		echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
    	} else {
    		DB::rollback();
    		echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
    	}
    	exit;
    }
    
    public function searchAction() {
    
    	$title = Request::get('title');
    	$liveStatus = (int)Request::get('liveStatus');
    	$liveTypes = (int)Request::get('liveTypes');
    	$createdAtFrom = Request::get('created_at_from');
    	$createdAtTo = Request::get('created_at_to');
    	$mess = array(
    			'title' => $title,
    			'liveStatus' => $liveStatus,
    			'liveTypes' => $liveTypes,
    			'created_at_from' => $createdAtFrom == "" ? 0 : strtotime($createdAtFrom),
    			'created_at_to' => $createdAtTo == "" ? 0 : strtotime($createdAtTo)
    	);
    	
    	if($mess['title'] == "" && $mess['liveStatus'] == 0 && $mess['liveTypes'] == 0 &&  $mess['created_at_from'] == 0 &&  $mess['created_at_to'] == 0){
			$this->response->redirect('media_signals/index');	// 跳转到首页
		}
		$channel_id = Auth::user()->channel_id;
		$datas = Signals::searchMediaData($mess, $channel_id);
		$featureData = FeaturedData::searchFeatureData($mess, $channel_id);
	
		View::pick('media_signals/index');
		View::setVars(compact('mess','datas','featureData'));
    }
    
    
    
    private function saveData($getData, $isAdd = true, $signalsId = 0, $oldSecretKey = null) {
    	if(!empty($getData)) {
			$temp = true;
    		$vData = Signals::makeValidator($getData);
    		if(!$vData->fails()) {
    			if(!Data::compareTime($getData)){
    				$messages[] = "请核对时效时间";
    				return  $messages;
    			}
    			$this->readyThumb($getData['thumb']);
    			$this->readyThumb($getData['thumb1']);
    			$this->readyThumb($getData['thumb2']);
    			$this->readyThumb($getData['thumb3']);
    			$this->readyThumb($getData['input_file2']);
    			$this->readyThumb($getData['input_file3']);
    			$this->readyThumb($getData['input_file4']);
    			$this->readyThumb($getData['input_file5']);
    	
    			$getData['created_at'] = $getData['created_at'] ? strtotime($getData['created_at']) : time();
    			$getData['updated_at'] = time();
    			$getData['status'] = Signals::LIVE_DATA_STATUS_REVIEWED;
    			
    			$getData['author_id'] = isset($getData['author_id']) ?: Auth::user()->id;
    			$getData['author_name'] = isset($getData['author_name']) ?: Auth::user()->name;
    			$getData['channel_id'] = isset($getData['channel_id']) ?: Auth::user()->channel_id;
    			$live_id = $signalsId;
    			//DB 事务
    			DB::begin();
    			try {
    				$getData['partition_by'] = date('Y');
    				
    				if($isAdd) {
    					// 新增操作
    					// 媒资关联表
    					$lives = new Signals();
	    				if(!$live_id = $lives->createLivesData($getData)) {
	    					$this->throwDbE('model');
	    				}
	    				
	    				// data表
	    				$dModel = new Data();
	    				if(!$data_id = $dModel->doSave($getData, Data::getAllowed(), 'live', $live_id)) {
	    					$this->throwDbE('model');
	    				}
	    				
    				} else {
    					// 修改操作
    					
    					if(!Signals::updateLiveData($live_id, $getData)) {
    						$this->throwDbE('model');
    					}
    					
    					if(!SignalSource::deleteAllData($live_id)) {
    						$this->throwDbE('model');
    					}
    					
    					$model = Data::getByMedia($live_id, 'live');
    					if(!$model->update($getData, Data::safeUpdateFields())) {
    						$this->throwDbE('model');
    					}
    					$data_id = $model->id;
    					$lives = new Signals();
    					//口令
    					Data::deleteRedisSecretKey(Auth::user()->channel_id, $oldSecretKey); //删除旧口令
    				}
    				$traditionTV = json_decode($getData['json']);
    				if(!$lives->saveSignalsTV($traditionTV, $live_id)) {
    					$this->throwDbE('model222');
    				}
    				
    				//口令
    				Data::setRedisSecretKey(Auth::user()->channel_id,$data_id,$getData["secret_key"],$dModel->status);
    				Data::setRedisSecretInputUrl($data_id,$getData["secret_url"]);
    				
    				//存地区
    				$dRegion = new RegionData();
    				if (!$dRegion->updateRegionData($getData, $data_id)) {
    					$this->throwDbE('regions Data save fail');
    				}
    				// 保存部门
    				$dGovDep = new GovernmentDepartmentData();
    				if (!$dGovDep->updateGovernmentDepartmentData($getData, $data_id)) {
    					$this->throwDbE('save government Department error');
    				}
    				
    				//发布
    				$model = Data::getById($data_id, Auth::user()->channel_id);
    				$media_publish = ids($getData['category_id']);
    				
    				if($getData['feature_cat_id']){
    					$media_publish = array_diff($media_publish,array(intval($getData['feature_cat_id'])));
    				}
    				if (Auth::user()->channel_id == self::XHCHANNEL)
    					$this->postToXiaoshan($data_id, $media_publish);
    				if (CategoryData::addPublish($data_id, $media_publish) === false) {
    					$messages[] = '栏目发布异常';
    					$this->throwDbE('Category Publish Error');
    				}
    				DB::commit();
    				$messages[] = $isAdd ? Lang::_('add success') : Lang::_('modify success') ;
    				$signalsJson = new Signals();
    				$signalsJson->setJsonByRedis($data_id);
    				if($getData['limittime_choose'] == 0) {
    					if($getData['timelimit_begin'] > time()) {
    						Signals::setBeginTimeCache($data_id, $getData['channel_id'], $getData['timelimit_begin']);
    					}
    					if( $getData['timelimit_end'] != 0 &&$getData['timelimit_begin'] < time() && $getData['timelimit_end'] > time()) {
    						Signals::setEndTimeCache($data_id, $getData['channel_id'], $getData['timelimit_end']);
    					}
    				}
    				Signals::refreshCDN($data_id, $getData['channel_id']);
    				Signals::saveTVJsonToRedis($live_id);
    			} catch(DatabaseTransactionException $e) {
    				DB::rollback();
    				Data::deleteRedisSecretKey(Auth::user()->channel_id,$data_id,$getData["secret_key"]);
    				Data::deleteRedisSecretInputUrl($data_id);
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
    			foreach($vData->messages()->all() as $msg) {
    				$messages[] = $msg;
    			}
    		}
    	}
    	else {
    		$messages[]='上传图片总量太大';
    	}
    	return $messages;
    }
    
    /**
     * 新增直播
     */
    public function addAction() {
        if($this->denySystemAdmin()) {
            return true;
        }
        $inint = $this->initPublishPageData();
        $this->initFormView();

        $data_id = 0;
        $messages = [];

        if(Request::isPost()) {
            $getData = $this->preProcessData(Request::getPost());
            $messages = $this->saveData($getData);
        } 
      
        $media_type = PrivateCategory::MEDIA_TYPE_LIVE;
        $privateCategoryData = false;
        $stationdata = false;
        
        
        $inint = array_merge($inint, compact('messages', 'media_type', 'stationdata', 'privateCategoryData', 'region'));
        View::setVars($inint);

    }

    

    /**
     * 编辑
     */
    public function editAction() {

        if($this->denySystemAdmin()) {
            return true;
        }
        
		$signalId = Request::get('id', 'int');
		
        $news = Signals::findDataById($signalId);
        if(!$news) {
            abort(404);
        }
       
        $model = Data::getByMedia($signalId, 'live');
        $inint = $this->initPublishPageData($model->id);
        if(!$news) {
            $this->alert(Lang::_('invalid request'));
        } else {
            $this->initFormView();
            $messages = [];
            if(Request::isPost()) { 
            	$getData = $this->preProcessData(Request::getPost());

                $vData = Data::makeValidator($getData, $model->id);
                $oldSecretKey = $model->secret_key;
                $oldSecretUrl = $model->secret_url;
                $secretFlag = Data::checkSecretKey(Auth::user()->channel_id, $getData["secret_key"], $model->secret_key, $oldSecretKey);
                if(!$vData->fails() && $secretFlag) {                   	
                  $messages = $this->saveData($getData, false, $signalId, $oldSecretKey);
                } else {
                    foreach($vData->messages()->all() as $msg) {
                        $messages[] = $msg;
                    }
                }
                $redirect = Url::get("media_signals/edit", ['id' => $signalId, 'messages' => join(',', $messages)]);
                redirect($redirect);
            }

//             $media_type = PrivateCategory::MEDIA_TYPE_LIVE;

//             $privateCategoryData = PrivateCategoryData::getIdByData($datas->id);

//             $stationdata = [$signals->id];
            $signalJson = Signals::getTVJsonByRedis($signalId);
            if($signalJson == "") {
            	$signalJson = Signals::editShowPackage($signalId);
            	Signals::saveTVJsonToRedis($signalId);
            }
           //var_dump(json_decode($signalJson));
//            if($news->notstarted_img == '')
			//var_dump($model);
// 			var_dump($news->paylist);
// 			$arr = explode(",", $news->paylist);
// 			var_dump($arr);
			//die();
            $inint = array_merge($inint, compact('messages','news', 'model', 'signalJson'));
            View::setVars($inint);
         
        }

    }
   

    /**
     * 初始化加载地区数据
     * @return unknown
     */
    protected function initRegionData()
    {
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
    
    private function postToXiaoshan($data_id)
    {
    	$rsync_arr = SupplyoutRsync::findAllByDataId(100, $data_id, Session::get('user')->channel_id);
    	$sign = md5($this->city_id . $this->sign . time());//签名
    	foreach ($rsync_arr as $key => $rsync) {
    		$input_post = array();
    		$input_post['cityid'] = $this->city_id;
    		$input_post['timestamp'] = time();
    		$input_post['sign'] = $sign;
    		$input_post['id'] = $rsync->origin_id;
    		$return_message = F::curlRequest("http://citynews.2500city.com/zxapi/news/del", 'post', $input_post);
    		$return_message = json_decode($return_message, true);
    		$rsync->delete();
    	}
    }
    
    /**
     * 处理图片路径
     * @param unknown $imgPath
     */
    private function readyThumb(&$imgPath){
    	$thumb_path = $this->uploadBase64StreamImg($imgPath);
    	if (empty($thumb_path) && strpos($imgPath,cdn_url("image","")) !== false)
    	{
    		$thumb_path = str_replace(cdn_url("image", ""), "", $imgPath);
    	}
    	$imgPath = $thumb_path;
    }
    
    private function uploadBase64StreamImg($thumb)
    {
    	$url ="";
    	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $thumb, $files))
    	{
    		$url = Auth::user()->channel_id.'/thumb/'.date('Y/m/d/').md5(uniqid(str_random())).".{$files[2]}";
    		Oss::uploadContent($url,base64_decode(str_replace($files[1], '', $thumb)));
    	}
    	return $url;
    }
    
    private function echoExit($msg){
    	echo $msg;
    	exit;
    }
    
}