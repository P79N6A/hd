<?php

/**
 * 定时发布消息
 */
class PublishTask extends Task{

	const SLEEP_TIME = 30;		// 定时扫描时间(秒)

	
    /**
     * 直播流 根据时效改变状态
     */
    public function signalstatusAction(){
        while(1) {
            try {
            	$temp = false;
            	$timeTemp = time(); 
				
            	$beginArr = Signals::getBeginTimeCache($timeTemp);
            	$endArr = Signals::getEndTimeCache($timeTemp);
            	if(isset($beginArr) && count($beginArr) > 0) {
            		foreach ($beginArr as $begin) {
            			$data = explode(",", $begin);
            			if(isset($data) && count($data) == 2) {
            				$dataId = $data[0];
            				$channelId = $data[1];
            				$signals = new Signals();
            				$signals->updateLiveStatus($dataId, Signals::LIVE_STATUS_PLAYING, $channelId);
            				Signals::refreshCDN($dataId, $channelId);
            			}
            			Signals::delBeginTimeCache($data);
            		}
            		$temp = true;
            	}
            	if(isset($endArr) && count($endArr) > 0) {
            		foreach ($endArr as $end) {
            			$data = explode(",", $end);
            			if(isset($data) && count($data) == 2) {
            				$dataId = $data[0];
            				$channelId = $data[1];
            				Signals::delBeginTimeCache($dataId, $channelId);
            				$signals = new Signals();
            				$signals->updateLiveStatus($dataId, Signals::LIVE_STATUS_FINISH, $channelId);
            				Signals::refreshCDN($dataId, $channelId);
            			}
            			Signals::delEndTimeCache($data);
            		}
            		$temp = true;
            	}
            	if(!$temp) {
                	sleep(self::SLEEP_TIME);                       //没有任务时休息1秒
                	echo "else sleep...\n";
                }
            }
            catch (Exception $e) {
                sleep(self::SLEEP_TIME);                       //没有任务时休息1秒
                echo "catch sleep...\n";
            }
        }
    }
    
}
