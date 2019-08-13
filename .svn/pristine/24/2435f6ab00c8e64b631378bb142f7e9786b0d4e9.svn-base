<?php

/**
 * 索贝定时发送收录拆条数据
 */
class VmsVideoTask extends Task {

    /**
     * 定时发送收录请求
     */
    public function sendVmsVideoAction() {
		// 根据redis 发送
    	$sendData = array();
		try {
    		$sendData = RedisIO::HGetAll(VmsVideo::REDIS_KEY_SET);
		}catch (Exception $e) {
			
		}
    	if(isset($sendData) && count($sendData) > 0) {
    		foreach ($sendData as $k => $value) {
    			$sendDatas = json_decode($value);
    			if(($sendDatas->start_time <= $sendDatas->end_time) && time() > ($sendDatas->end_time / 1000) ) {
    				$vmsVideo = new VmsVideo();
    				$result = $vmsVideo->sendVmsRough($k, VmsVideo::SET_VIDEO, $value);
    				sleep(1);
    				$this->log($result);
    			}
    		}
    	}else {
    		// 根据DB 发送
	    	$sendData = StationsProgram::findSendData(VmsVideo::RATE_STATUS_NEW);					// 查询新建，发送失败的收录记录
	        if(isset($sendData) && !empty($sendData)) {
	            foreach($sendData as $k => $v) {
	            	if(($v['start_time'] <= $v['end_time']) && time() > ($v['end_time'] / 1000) ) {
		                $vmsVideo = new VmsVideo();
		                $result = $vmsVideo->sendVmsRough($v['id'], VmsVideo::SET_VIDEO, $v);
		                sleep(1);
		                $this->log($result);
	            	}
	            }
	        }
    	}
    }

    /**
     * 定时发送查询收录状态请求
     */
    public function searchVmsVideoAction() {
    	// 根据redis 发送
    	$sendData = array();
    	try {
    		$sendData = RedisIO::HGetAll(VmsVideo::REDIS_KEY_SEARCH);
    	}catch (Exception $e) {
    			
    	}
    	if(isset($sendData) && count($sendData) > 0) {
    		foreach ($sendData as $k => $value) {
    			$vmsVideo = new VmsVideo();
    			$result = $vmsVideo->sendVmsRough($k, VmsVideo::GET_VIDEO, $value);
    			sleep(1);
    			$this->log($result);
    		}
    	}else {
    		// 根据DB 发送
	    	$sendData = StationsProgram::findSendData(VmsVideo::RATE_STATUS_COLLECTION_SUCCESS);					// 查询发送成功的收录记录
	    	if(isset($sendData) && !empty($sendData)) {
	    		foreach($sendData as $k => $v) {
	    			$vmsVideo = new VmsVideo();
	    			$result = $vmsVideo->sendVmsRough($v['id'], VmsVideo::GET_VIDEO, $v);
	    			sleep(1);
	    			$this->log($result);
	    		}
	    	}
    	}
    }
    
    /**
     * @param $msg
     */
    protected function log($msg) {
        echo $msg, PHP_EOL;
    }

}