<?php

/**
 * client_id生成接口,队列处理定时任务
 */
class ClientmnsQueueTask extends Task{

    /**
     * 默认方法,获取消息
     */
    public function mainAction(){
        global $config;		
		if("nhudong_product"!=$config->memprefix) {
            $queueName = $config->memprefix . "clientmns";
		}
		else {
		    $queueName = "clientmns";
		}
        while(1) {
            try {
                $res = $this->queue->getMessage($queueName);
                $data = json_decode($res, true);
                $this->addClient($data['channel_id'], $data);
            } catch (Exception $e) {
                sleep(1);                       //没有任务时休息1秒
                echo "sleep(1)...\n";
            }
        }
    }

    /**
     * @param $this
     * @param $input
     */
    public function addClient($channel_id, $input){
        $model = new Client();
        $hash_id = $model->apiFindOrCreateClient($channel_id, $input);
        if (false !== $hash_id) {
            echo "save success! hash_id=" . $hash_id . PHP_EOL;
        } else {
            echo "save error! hash_id=" . $hash_id . PHP_EOL;
        }

    }


}
