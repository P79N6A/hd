<?php

use Mns\Tests\AliQueue;

class getQueue extends AliQueue {
    /**
     * 初始化
     */
    public function say() {
        echo "OK";
    }
}

$queue = new getQueue();
var_dump($queue->getMessage());