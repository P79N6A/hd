<?php

use Guzzle\Http\Message\Response;
/**
 *  推送市县消息
 *  @author     cjh
 *  @created    2016-08-22
 *  
 */

class PushCountyMsgController extends \BackendBaseController {
	
	public $ignore = [
			'getTitle'
		];
	
	/**
	 * 根据媒资Id获取媒资标题
	 */
	public function getTitleAction() {
		$mediaId = Request::getPost('id','int');
		if(!$mediaId){
			$mediaId=0;
		}
		$channelId=Auth::user()->channel_id;
		$mediaData = Data::getById($mediaId, $channelId);
		$mediaData ? $data = $mediaData->title : $data = "";
		echo json_encode($data);
		exit;
	}
	
	/**
	 * 初始页面
	 */
	public function indexAction() {
		$channelId=Auth::user()->channel_id;
		$data = Queues::findAll($channelId);
		View::setVars(compact('data'));
	}

    /**
     * 查看详情
     */
    public function detailAction() {
    	$getId =  (int) Request::get('id');
    	$data = Queues::getOne($getId);
    	View::setMainView('layouts/add');
    	View::setVars(compact('data'));
    }
    
    /**
     * 搜索消息
     */
    public function searchAction() {
    	
    	$pushTitle = Request::get('pushTitle');
       	$status = Request::get('status');
    	$mess = array(
    			'pushTitle' => $pushTitle,
    			'status' => $status
    	);
    	
    	if($mess){
			$data = Queues::searchPushMsg($mess);
		}
		
		if($mess['pushTitle'] == "" &&  $mess['status'] == ""){
			$this->response->redirect('push_county_msg/index');	// 跳转到首页
		}
		
		View::pick('push_county_msg/index');
		View::setVars(compact('mess','data'));
    }

    /**
     * 新建推送
     */
    public function addAction() {
    	
    	if(Request::isPost()) {
    		$messages = [];
    		$inputs = Request::getPost();
    		$clientArr = array();
    		if(!array_key_exists("push_terminal_android",$inputs)) {
    			$inputs['push_terminal_android'] = 0;
    		}
    		if(!array_key_exists("push_terminal_ios",$inputs)) {
    			$inputs['push_terminal_ios'] = 0;
    		}
    		$terminalTemp = $inputs['push_terminal_android'] | $inputs['push_terminal_ios'];
    		$clientArr['push_terminal'] = $inputs['push_terminal'] = $terminalTemp;
    		if($inputs['push_single'] == 1) {
    			$clientArr['push_single_client'] = $inputs['push_single_client'];
    		}
    		$data_id = $inputs['push_id_media'];
    		$pushMessage = new PushMessage();
	    	$data = Data::getById($data_id,Auth::user()->channel_id);
	    	$titleValue = $data->title; 
	    	$data->title = $inputs['push_content'];
	    	$inputs['push_content'] = $titleValue;
	    	$result = "";
	    	if($inputs['push_type'] == 0) {
	    		// 定时推送
	    		$inputs['push_timestamp'] =  strtotime($inputs['push_timestamp']);
	    		$status = Queues::STATUS_NEW;
	    		$result = Queues::savePushTask(Auth::user()->channel_id, Queues::TASK_PUSH, array('data_id' => $data_id, 'data_title' => $data->title), $status, "", $inputs);
	    	}else{
	    		// 即时推送
		        if($data->status==1) {
		        	$rep_return = $pushMessage->doPush($data, $clientArr);
		        	
		        	$remark = "";
		        	$status = Queues::STATUS_DONE;
		        	if(is_array($rep_return)){
		        		foreach ($rep_return[0] as $v) {
		        			$rep = json_decode($v,true);
		        			if($rep != null) {
		        				if($rep['result'] == 'ok'){
		        					$status = Queues::STATUS_DONE;
		        				}else {
		        					$status = Queues::STATUS_FAILED;
		        				}
		        				$remark .= json_encode($v);
		        			}
		        		}
		        	}
		        	$remark = stripslashes($remark); 
		        	$inputs['push_timestamp'] = time();
		            $result = Queues::savePushTask(Auth::user()->channel_id, Queues::TASK_PUSH, array('data_id' => $data_id, 'data_title' => $data->title), $status, $remark, $inputs);
		        }
	    	}
		    if($result) {
	    		$messages[] = Lang::_('success');
	    	}else {
	    		$messages[] = Lang::_('error');
	    	}
	    	
    	}
    	
    	View::setMainView('layouts/add');
    	View::setVars(compact('messages','stations','status'));
    }
    
    
}
