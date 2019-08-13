<?php

use Guzzle\Http\Message\Response;
/**
 *  推送蓝tv消息
 *  @author     cjh
 *  @created    2016-08-10
 *  
 */

class PushMsgController extends \BackendBaseController {
	
	/**
	 * 初始页面
	 */
	public function indexAction() {
		$data = PushMsg::findAll();
		View::setVars(compact('data'));
	}

    /**
     * 查看详情
     */
    public function detailAction() {
    	$getId =  (int) Request::get('id');
    	$data = PushMsg::getOne($getId);
    	View::setMainView('layouts/add');
    	View::setVars(compact('data'));
    }
    
    /**
     * 搜索消息
     */
    public function searchAction() {
    	
    	$pushTitle = Request::get('pushTitle');
    	$pushTerminal = Request::get('pushTerminal');
    	$pushMode = Request::get('pushMode');
    	$status = Request::get('status');
    	$mess = array(
    			'pushTitle' => $pushTitle,
    			'pushTerminal' => $pushTerminal,
    			'pushMode' => $pushMode,
    			'status' => $status
    	);
    	
    	if($mess){
			$data = PushMsg::searchPushMsg($mess);	
		}
		
		if($mess['pushTitle'] == "" && $mess['pushTerminal'] == "" && $mess['pushMode'] == "" &&  $mess['status'] == ""){
			$this->response->redirect('push_msg/index');	// 跳转到首页
		}
		
		View::pick('push_msg/index');
		View::setVars(compact('mess','data'));
    }

    /**
     * 新建推送
     */
    public function addAction() {
    	
    	if(Request::isPost()) {
    		$messages = [];
    		$inputs = Request::getPost();

	    	$inputs['push_single'] == 1 ?: $inputs['push_single_client'] = "";
	    	    	
	      	// 移位判断选择推送至的方式
	    	if(!array_key_exists("push_terminal_android",$inputs)) {
	    		$inputs['push_terminal_android'] = 0;
	    	}
	    	if(!array_key_exists("push_terminal_ios",$inputs)) {
	    		$inputs['push_terminal_ios'] = 0;
	    	}
	    	$terminalTemp1 = $inputs['push_terminal_android'] << 0;			// 值为1，android
	    	$terminalTemp2 = $inputs['push_terminal_ios'] << 1;				// 值为2，ios
	    	$terminalTemp = $terminalTemp1 | $terminalTemp2 ;
	    	$inputs['push_terminal'] = $terminalTemp;
	    
	    	// 校验判断
	    	$validator = PushMsg::makeValidator($inputs);
	    	if(!$validator->fails()) {
	    		$inputs['push_mode'] == 2 && $inputs['push_id_video']  			!= "" ? $inputs['push_id'] = $inputs['push_id_video'] 		 : "";
	    		$inputs['push_mode'] == 11 && $inputs['push_id_anchor_video']   != "" ? $inputs['push_id'] = $inputs['push_id_anchor_video'] : "";
	    		$inputs['push_mode'] == 10 && $inputs['push_id_anchor'] 	    != "" ? $inputs['push_id'] = $inputs['push_id_anchor'] 		 : "";
	    		$inputs['push_mode'] == 3 && $inputs['push_id_topic']  			!= "" ? $inputs['push_id'] = $inputs['push_id_topic'] 		 : "";
	    		$inputs['push_mode'] == 5 && $inputs['push_id_video'] 			!= "" ? $inputs['push_id'] = $inputs['push_id_video']		 : "";
	    		$inputs['push_mode'] == 6 && $inputs['push_id_video'] 			!=""  ? $inputs['push_id'] = $inputs['push_id_video']		 : "";
                $inputs['push_mode'] == 15 ? $inputs['push_id'] = '101' : true;
	    		
	    		$inputs['ac_code']  != "" ? $inputs['push_id'] = $inputs['ac_code'] :"";
	    		
	    		if($inputs['push_mode'] < 10) {
		    		// 上传图片文件
		    		if (Request::getUploadedFiles()[0]->getError() == 0) {
		    			if ($this->validateAndUpload($messages)) {
		    				$inputs['file'] = $this->validateAndUpload($messages);
		    			}
		    		}
	    		}else {
	    			$inputs['file'] = "";
	    		}
	    		
	    		if($inputs['push_mode'] == 12 && $inputs['push_id_program'] !="") {
	    			$programData = StationsProgram::findDataById($inputs['push_id_program']);
	    			if(isset($programData) && !empty($programData)) {
	    				$programData = $programData->toarray();
		    			$inputs['push_id'] = $inputs['push_id_program'];
		    			$inputs['push_content'] = $inputs['push_content'];  // $programData['title'];
		    			// $inputs['push_content'] = $programData['title'];
		    			$inputs['video_date'] = $programData['start_date'];
		    			$inputs['ac_code'] = $programData['code'] != null ? $programData['code'] :"0";
		    		}
		    		else {
		    			$messages[] = Lang::_('节目单不存在');
		    			View::setVars(compact('messages','stations','status'));
		    			return;
		    		}
	    		}
	    		if($inputs['push_timestamp'] == "" && $inputs['push_type'] == 1) {
	    			//即时推送
	    			$inputs['push_timestamp'] = time();
	    			$pushMessage = new PushMessage();
	    			$pushResult = $pushMessage->push2Vedio($inputs);
	    		}else {
	    			//定时推送
	    			$inputs['push_timestamp'] = strtotime($inputs['push_timestamp']);
	    			$inputs['status'] = PushMsg::PUSH_BYTIME;
	    		}
	    		$inputs['created_at'] = time();
	    		$inputs['push_image'] = $inputs['file'];
	    		$inputs['share_url'] = $inputs['push_share_url'];
	    		// 存数据表
	    		$pushMsg = new PushMsg();
	    		$inputs['admin_id'] = Auth::user()->id;
	    		$result = $pushMsg->createPushMsg($inputs,$pushResult);
	    		if($result) {
	    			$messages[] = Lang::_('success');
	    		}else {
	    			$messages[] = Lang::_('error');
	    		}

	    	}else {
	    		foreach($validator->messages()->all() as $msg){
	                 $messages[]=$msg;
	             }
	    	}
    	}

    	$stations = Stations::getStations();
    
    	View::setMainView('layouts/add');
    	View::setVars(compact('messages','stations','status'));
    }
    
    /**
     * 上传图片到oss
     * @param unknown $messages
     */
    protected function validateAndUpload(&$messages) {
    	$path = '';
    	if (Request::hasFiles()) {
    		/**
    		 * @var $file \Phalcon\Http\Request\File
    		 */
    		$file = Request::getUploadedFiles()[0];
    		$error = $file->getError();
    		if (!$error) {
    			$ext = $file->getExtension();
    			if (in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
    				$path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/pushMsgImages');
    				$attachmodel = new AttachmentCommon();
    				$attachid = $attachmodel->createAttach(array(
    						'origin_name' => $file->getName(),
    						'name' => $file->getName(),
    						'type' => 2, //1:视频 2:图片 0:未知
    						'path' => $path,
    						'ext' => $file->getType(),
    				));
    			} else {
    				$messages[] = Lang::_('please upload valid header image');
    			}
    		} elseif ($error == 4) {
    			$path = Request::getPost('thumb', null, '');
    			if (!$path) {
    				$messages[] = Lang::_('please choose upload header image');
    			}
    		} else {
    			$messages[] = Lang::_('unknown error');
    		}
    	} else {
    		$messages[] = Lang::_('please choose upload header image');
    	}
    	return $path;
    }

}
