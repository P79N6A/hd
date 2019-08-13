<?php

/**
 * client_id生成接口,队列处理定时任务
 */
class xml2QueueTask extends Task{

    /**
     * 默认方法,获取消息接
     */
    public function mainAction(){
        while(1) {
            try {
                $res = $this->queue->getMessage("xml2");
                $data = json_decode($res, true);
                //var_dump($data);
                $this->addClient($data['channel_id'], $data);
            } catch (Exception $e) {
                sleep(1);   //没有任务时休息1秒
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
            echo "save succesed! hash_id=" . $hash_id . PHP_EOL;
        } else {
            echo "save error! hash_id=" . $hash_id . PHP_EOL;
        }

    }


}
