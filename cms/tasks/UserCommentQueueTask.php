<?php

/**
 * 互动评论接口,队列处理任务
 */
class UserCommentQueueTask extends Task{

    /**
     * 默认方法,获取消息接
     */
    public function mainAction(){
        global $config;
        if("nhudong_product"!=$config->memprefix) {
            $queueName = $config->memprefix . UserComments::QUEUENAME;
        }
        else {
            $queueName = UserComments::QUEUENAME;
        }
        while(1) {
            try {
                $res = $this->queue->getMessage($queueName);
                $data = json_decode($res, true);
                //保存到数据库并存入redis
                $this->save($data);
            } catch (Exception $e) {
                sleep(1);   //没有任务时休息1秒
                echo "sleep(1)...\n";
            }
        }
    }

    /**
     * @param $data
     * @param $res
     */
    protected function save($data){
        $comment = new UserComments();
        if ($comment->createComment($data,true)) {
            echo "mysql create error!";
        } else {
            echo "mysql create error!";
        }
    }


}
