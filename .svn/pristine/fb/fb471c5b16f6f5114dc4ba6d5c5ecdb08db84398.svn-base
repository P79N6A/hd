<?php

/**
 * 
 * 个推，全推
 * cjh
 */
class PushMessage {
	const LANTV_PUSH_ALL = 1;				// 全推
	const LANTV_PUSH_LIST = 2;				// 列表推
	const LANTV_PUSH_SINGLE = 3;			// 单个推
	/**
	 * 市县推送
	 * @param unknown $dataId
	 * @param unknown $channelId
	 */
	public function doPush($data,array $clientArr) {
		if($data) {
			$push = Setting::getByChannel(Auth::user()->channel_id, 'getui.push');
			if($push) {
				$pushconfig = array(
						'AppKey'=>$push['AppKey'],
						'MasterSecret'=>$push['MasterSecret'],
						'AppID'=>$push['AppID'],
				);
				$pushtype = "news";                       		// 视频，新闻全为 news标示
				if($data->type=='album') $pushtype = "album";
				$pushdata = [
				'title' => $data->title,
				'data' => ['push_news_type'=>$pushtype, 'push_news_id'=>$data->id, 'title' => $data->title],
				'clientType' => $clientArr['push_terminal'],
				'offlineExpireTime' => 3600 * 2 * 1000,			// 离线时间单位为毫秒
				];
				$rep_return ="";
				if(array_key_exists("push_single_client",$clientArr)) {
					// 个推
					$clientArr['push_single_client'] = str_replace("，", ",", $clientArr['push_single_client']);
					$pushClient = explode(',', $clientArr['push_single_client']);
					if(count($pushClient) > 0) {
						$rep_return = F::getuiProxy($pushconfig, $pushdata, $pushClient[0]);
					}
				}else {
					// 全推
					$rep_return = F::getuiProxy($pushconfig, $pushdata);
				}
				return $res = GeTuiTask::valReturn($rep_return);
			} else {
				return "Invalid Getui setting.";
			}
		} else {
			return $data->id.' not found';
		}
	}
	
	
 	/**
     * 推送消息
     * @param unknown $data 推送数据
     */
    public function push2Vedio($data, $pushClient = "") {
    	if($data['push_single'] == 1) {
    		if($pushClient == "") {
    			$result = $this->push2Single($data, array());
    		}else {
    			$result = $this->push2Single($data, $pushClient);
    		}
    		return $result;
    	}else {
    		$result = $this->pushByMsg($data);
    		return $result;
    	}
    }
    
    /**
     * 推送消息(全推)
     * @param unknown $clientType 推送至客户端
     * @param unknown $geTui 个推对象
     */
    private function pushByMsg($data) {
    	$pushConfig = $this->setConfig();
    	if(isset($pushConfig) && !empty($pushConfig) && count($pushConfig) > 0) {
	    	$result = array();
	    	$clientType = $data['push_terminal'];
	    	$title = $data['push_content'];
	    	$pushType = self::LANTV_PUSH_ALL;
	    	// andriod
	    	if(($temp = $clientType & PushMsg::ANDRIOD)>0) {
	    		if(($temp >> 0)> 0) {
	    			$push_data = $this->setPushData($data);
	    			$geTui = new GeTuiTask($pushConfig, $title, $push_data);
	    			$geTui->push_all_android($pushType);
	    			$result[] = $geTui->getReturn();
	    		}
	    	}
	    	
	    	// ios
	    	if(($temp = $clientType & PushMsg::IOS)>0) {
	    		if(($temp >> 1)> 0) {
	    			$push_data = $this->setPushData($data);
	    			$geTui = new GeTuiTask($pushConfig, $title, $push_data);
	    			$geTui->push_all_ios($pushType);
	    			$result[] = $geTui->getReturn();
	    		}
	    	}
    	} else{
    		$result = array('warning' => "请在管理中心\系统配置下检查gt_android_key,gt_android_secrect,gt_android_appid,gt_ios_key,gt_ios_secrect,gt_ios_appid选项是否已配置");
    	}
    	return $result;
    }
    
    /**
     * 推送到个人(个推) 列表推送方式
     * @param unknown $data 推送数据
     */
    private function push2Single($data,array $clientData) {
    	$pushClient = array();
    	$result = "";
    	$pushConfig = $this->setConfig();
    	if(isset($pushConfig) && !empty($pushConfig) && count($pushConfig) > 0) {
    		$clientType = $data['push_terminal'];
    		$title = $data['push_content'];
    		if(array_key_exists("push_single_client",$data)) {
    			$data['push_single_client'] = str_replace("，", ",", $data['push_single_client']);
    			$pushClient = explode(',', $data['push_single_client']);
    		}
    		if(isset($clientData) && !empty($clientData)) {
    			$pushClient = $clientData;
    		}
    		$pushType = self::LANTV_PUSH_LIST;
    		// andriod
    		if(($temp = $clientType & PushMsg::ANDRIOD)>0) {
    			if(($temp >> 0)> 0) {
    				$push_data = $this->setPushData($data);
    				//$geTuiId = array('android_push_clients'=>array('c8bd4aee0beb9cd0ed46f0975738f0fe'));
    				$geTuiId = array('android_push_clients'=>$pushClient);
    				$geTui = new GeTuiTask($pushConfig, $title, $push_data, $geTuiId);
    				$geTui->push_list_android($pushType);
    				$result[] = $geTui->getReturn();
    			}
    		}
    		
    		// ios
    		if(($temp = $clientType & PushMsg::IOS)>0) {
    			if(($temp >> 1)> 0) {
    				$push_data = $this->setPushData($data);
    				//$geTuiId = array('ios_push_clients'=>'461cfe851d29a1776ad8cf04bf55a7fb');
    				$geTuiId = array('ios_push_clients'=>$pushClient);
    				$geTui = new GeTuiTask($pushConfig, $title, $push_data, $geTuiId);
    				$geTui->push_list_ios($pushType);
    				$result[] = $geTui->getReturn();
    			}
    		}
    	}else {
    		$result = "请在管理中心\系统配置下检查gt_android_key,gt_android_secrect,gt_android_appid,gt_ios_key,gt_ios_secrect,gt_ios_appid选项是否已配置";
    	}
	    return $result;
    }
    
    /**
     * 根据推送类型，组装推送数据
     * @param unknown $data 页面上填写的数据
     * @return multitype:unknown 组装后数据
     */
    private function setPushData($data) {
    	$mode = $data['push_mode'];
    	if(isset($data['file'])) $data['push_image'] = $data['file'];
    	switch ($mode) {
    		case 1:													// 直播
    			$push_data = array(
    			'enterType' => $data['push_mode'],					// 推送类型
    			'videoId' => $data['push_id'],						// 直播频道id
    			'image' => cdn_url("image",$data['push_image']),	// 图片url
    			'videoName'=>$data['push_content'],					// 推送内容
    			'shareUrl' => $data['share_url']				//分享地址
    			);
    			return $push_data;
    		case 2:													// 点播
    			$push_data = array(
    			'enterType' => $data['push_mode'],					// 推送类型
    			'videoId' => $data['push_id'],						// 视频ID
    			'channelId' => $data['push_channelid'],			    // 视频通道ID
    			'ablumId' => $data['push_ablumid'],				    // 专辑ID
    			'image' => cdn_url("image",$data['push_image']),	// 图片url
    			'videoName'=>$data['push_content'],					// 推送内容
    			'shareUrl' => $data['share_url']				//分享地址
    			);
    			return $push_data;
    		case 3:														// 专题
    			$push_data = array(
    			'enterType' => $data['push_mode'],						// 推送类型
    			'videoId' => $data['push_id'],							// 专题ID
    			'channelId' => $data['push_channelid'],				    // 视频通道ID
    			'image' => cdn_url("image",$data['push_image']),		// 图片url
    			'videoName'=>$data['push_content'],						// 推送内容
    			'shareUrl' => $data['share_url']				//分享地址
    			);
    			return $push_data;
    		case 4:														// web页
    			$push_data = array(
    			'enterType' => $data['push_mode'],						// 推送类型
    			'image' => cdn_url("image",$data['push_image']),		// 图片url
    			'videoId'=> $data['push_url'],							// url
    			'videoName'=>$data['push_content'],						// 推送内容
    			'shareUrl' => $data['share_url']				//分享地址
    			);
    			return $push_data;
			case 5:														// 全景直播
				$push_data = array(
					'enterType' => $data['push_mode'],					// 推送类型
					'videoId' => $data['push_id'],						// 视频ID
					'image' => cdn_url("image",$data['push_image']),	// 图片url
					'videoName'=>$data['push_content'],					// 推送内容
					'shareUrl' => $data['share_url']				//分享地址
				);
				return $push_data;
			case 6:														// 全景点播
				$push_data = array(
					'enterType' => $data['push_mode'],					// 推送类型
					'videoId' => $data['push_id'],						// 视频ID
					'image' => cdn_url("image",$data['push_image']),	// 图片url
					'videoName'=>$data['push_content'],					// 推送内容
					'shareUrl' => $data['share_url']				//分享地址
				);
				return $push_data;
    		case 10:													// ugc直播
    			$push_data = array(
    			'enterType' => $data['push_mode'],						// 推送类型
    			'videoId' => $data['push_id'],							// 视频ID
    			'url'=> $data['push_url'],								// url
    			'videoName'=>$data['push_content'],						// 推送内容
    			'shareUrl' => $data['share_url']					//分享地址
    			);
    			return $push_data;
    		case 11:													// ugc点播
    			$push_data = array(
    			'enterType' => $data['push_mode'],						// 推送类型
    			'videoId' => $data['push_id'],							// 视频ID
    			'url'=> $data['push_url'],								// url
    			'videoName'=>$data['push_content'],						// 推送内容
    			'shareUrl' => $data['share_url']					//分享地址
    			);
    			return $push_data;
    		case 12:
    			$push_data = array(										// 节目单预约推送
    			'enterType' => "12",//$data['push_mode'],				// 跳转分类
    			'videoId'   => $data['push_id'],						// 节目id  progarm id
    			'videoName' => $data['push_content'],					// 浙江名医馆2016-016（直播版）program title
    			'videoDate' => date("Y-m-d", $data['video_date']),		// 日期   program start_date
    			'channelId' => $data['ac_code'],						// 频道id staitons id
    			'shareUrl' => $data['share_url']						//分享地址
    			);
    			return $push_data;
            case 15:
                $push_data = array(										// 答题直播推送
                    'enterType' => "15",//$data['push_mode'],				// 跳转分类
                    'videoId'   => $data['push_id'],						// 节目id  progarm id
                    'videoName' => $data['push_content'],					// 浙江名医馆2016-016（直播版）program title
                    'videoDate' => date("Y-m-d", $data['video_date']),		// 日期   program start_date
                    'channelId' => $data['ac_code'],						// 频道id staitons id
                    'shareUrl' => $data['share_url']						//分享地址
                );
                return $push_data;
    		default:break;
    	}
    }
    
    /**
     * 获取系统配置的信息
     * @return multitype:string 配置信息
     */
    private function setConfig() {
    	$channel_id = !empty(Session::get('user')) ? Session::get('user')->channel_id : "";
    	if($channel_id == "") {
    		$channel_id = LETV_CHANNEL_ID; 
    	}
    	
    	$pushConfigJson =json_encode(Setting::getByChannel($channel_id, 'getui.push'));
    	$pushConfigArr = json_decode($pushConfigJson);
    	 
    	$push_config= array(
    			'gt_android_key'=> isset($pushConfigArr->gt_android_key) ? trim($pushConfigArr->gt_android_key) : "",
    			'gt_android_secrect' => isset($pushConfigArr->gt_android_secrect) ? trim($pushConfigArr->gt_android_secrect) : "",
    			'gt_android_appid'=> isset($pushConfigArr->gt_android_appid) ? trim($pushConfigArr->gt_android_appid) : "",
    			'gt_ios_key'=> isset($pushConfigArr->gt_ios_key) ? trim($pushConfigArr->gt_ios_key) : "",
    			'gt_ios_secrect' => isset($pushConfigArr->gt_ios_secrect) ? trim($pushConfigArr->gt_ios_secrect) : "",
    			'gt_ios_appid'=> isset($pushConfigArr->gt_ios_appid) ? trim($pushConfigArr->gt_ios_appid) : ""
    	);
    
    	return $push_config;
    }
    
    /**
     * 节目单预约，列表推送
     * @param unknown $type
     * @param unknown $data
     * @param unknown $client
     */
    public function sendMsg($type, $data, $client) {
    	$data['push_single'] = 1;
    	$data['push_mode'] = 12;
    	switch ($type) {
    		case "android":
    			$data['push_terminal'] = 1;
    			break;
    		case "ios":
    			$data['push_terminal'] = 2;
    			break;
    		default:break;
    	}
    	$pushMessage = new PushMessage();
    	$pushResult = $pushMessage->push2Vedio($data, $client);
    	$pushMsg = new PushMsg();
    	$data['admin_id'] = -1;
    	$data['push_timestamp'] = $data['created_at'] = time();
    	$data['push_type'] = 0;
    	$data['push_single_client'] = json_encode($client);
    	$result = $pushMsg->createPushMsg($data,$pushResult);
    }
    
    /**
     * 获取需要推送的cid数组
     * @param unknown $uid
     * @param unknown $iosPushArr
     * @param unknown $androidPushArr
     */
    public function getPushClients($uid, &$iosPushArr, &$androidPushArr) {
    	$clientData = Client::getClientByUserId($uid);
    	if($clientData != false && !empty($clientData)) {
    		switch($clientData['client_type']) {
    			case 'ios':
    				if(!empty($clientData['push_client'])){
    					$push_clients = $clientData['push_client'];
    					if(!in_array($push_clients,$iosPushArr)){
    						array_push($iosPushArr,$push_clients);
    					}
    				}
    				break;
    			case 'android':
    				if(!empty($clientData['push_client'])){
    					$push_clients = $clientData['push_client'];
    					if(!in_array($push_clients,$androidPushArr)){
    						array_push($androidPushArr,$push_clients);
    					}
    				}
    				break;
    			default:break;
    		}
    	}
    }
    
    /**
     * 节目单预约定时推送
     */
	public function pushMsgStationProgram(){
		$temp = false;
	    $iosPushArr = array();
	    $androidPushArr = array();
	    $data = array();
	    $redisKeyTime = 'api::stations::program::order::program::'.time();
	    if(RedisIO::zCard($redisKeyTime) > 0) {
	    	$resProgram = RedisIO::zRange($redisKeyTime, 0, -1);
	    	file_put_contents('pushProgramMsg_'.date('Y_m_d',time()).'.log',json_encode($resProgram),FILE_APPEND);
	    	if(!empty($resProgram)) {
		    	foreach ($resProgram as $id) {
		    		$programData = StationsProgram::findDataById($id);
		    		if (isset($programData) && !empty($programData)) {
		    			$programData = $programData->toarray();
		    		}
		    		else {
		    			continue;
		    		}
		    		$data['push_id'] = $id;
		    		$data['video_name'] = $programData['title'];
		    		$data['push_content'] = $programData['title'];
		    		$data['video_date'] = $programData['start_date'];
		    		$data['ac_code'] = $programData['code'];
					$data['share_url'] = $programData['share_url'];
		    		
		    		
		    		$key2 = 'api::stations::program::order::user::'.$programData['id'];
		    		if(RedisIO::zCard($key2) > 0) {
		    			$resUid = RedisIO::zRange($key2, 0, -1);
		    			foreach ($resUid as $uid) {
		    				$this->getPushClients($uid, $iosPushArr, $androidPushArr);
		    				RedisIO::zDelete($key2, $uid);
		    			}
		    			file_put_contents('pushProgramMsgIos_'.date('Y_m_d',time()).'.log',json_encode($iosPushArr),FILE_APPEND);
		    			if(isset($iosPushArr) && !empty($iosPushArr)) {
		    				$iosPushArr = array_chunk($iosPushArr, 100);
		    				foreach ($iosPushArr as $iosArr) {
		    					$this->sendMsg("ios", $data, $iosArr);
		    				}
		    			}
		    			file_put_contents('pushProgramMsgAndroid_'.date('Y_m_d',time()).'.log',json_encode($androidPushArr),FILE_APPEND);
		    			if(isset($androidPushArr) && !empty($androidPushArr)) {
		    				$androidPushArr = array_chunk($androidPushArr, 100);
		    				foreach ($androidPushArr as $androidArr) {
		    					$this->sendMsg("android", $data, $androidArr);
		    				}
		    			}
		    		}
		    		RedisIO::zDelete($redisKeyTime, $id);
		    	}
		    	$temp = true;
	    	}
	    }
	    return $temp;
	}
}