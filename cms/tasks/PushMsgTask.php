<?php

/**
 * 定时推送消息
 */
class PushMsgTask extends Task{

	const TIME_PUSH = 0;		// 定时推送
	
	const NO_PUSH = 4;			// 未推送
	
    /**
     * 默认方法,获取消息
     */
    public function mainAction(){
        while(1) {
            try {
            	$timeTemp = time(); 
                $pushData = PushMsg::findMsgByType($timeTemp, self::TIME_PUSH, self::NO_PUSH);
                if(isset($pushData) && !empty($pushData) && count($pushData) > 0) {
              		foreach ($pushData as $v) {
              			// 推送信息
		           		$pushMessage = new PushMessage();
			           	$pushResult = $pushMessage->push2Vedio($v);
		           		// 存数据表
		           		$pushMsg = new PushMsg();
		           		$result = $pushMsg->createPushMsg($v,$pushResult);
	              	}
                }else {
                	sleep(1);                       //没有任务时休息1秒
                	echo "sleep(1)...\n";
                }
            }
            catch (Exception $e) {
                sleep(1);                       //没有任务时休息1秒
                echo "sleep(1)...\n";
            }
        }
    }
    
    /**
     * 节目单预约定时推送
     */
    public function pushprogramsAction() {
    	while(1) {
    		try {
    			$pushmsg = new PushMessage();
    			$temp = $pushmsg->pushMsgStationProgram();
    			if($temp == false) {
    				sleep(1);
    				echo "sleep(1)...\n";
    			}
    			else{
    				usleep(100);
    				echo "sleep(0.1)...\n";
    			}
		    }catch (Exception $e) {
    			sleep(1);                       //没有任务时休息1秒
    			echo "sleep(1)...\n";
    		}
   		}
	}
}
