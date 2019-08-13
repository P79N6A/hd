<?php

class ActivityVoteQueueTask extends Task{

    /**
     * 默认方法,获取消息
     */
    public function mainAction(){
        global $config;
        if("nhudong_product"!=$config->memprefix) {
            $queueName = $config->memprefix . "actvotemns";
        }
        else {
            $queueName = "actvotemns";
        }
        while(1) {
            try {
                $res = $this->queue->getMessage($queueName);
                $data = json_decode($res, true);
				ActivitySignup::setWorkVote($data['work_id'], $data['work_vote']);
            } catch (Exception $e) {
                sleep(1);                       //没有任务时休息1秒
                echo "sleep(1)...\n";
            }
        }
    }
}
