<?php

class DebugTask extends Task {
    
    public function winAction(){
        $mobile = '13888888888';
        $key = 'debug_win:'.$mobile;
        echo '保存前, 共计: ', count(RedisIO::zRevRange($key, 0, -1)), PHP_EOL;
        for($i=0;$i<10;$i++) {
            self::saveRedisWinners($i, $mobile, 1, '我', '1'.$i.'G流量', 3, 1);
        }
        echo '保存后, 共计: ', count(RedisIO::zRevRange($key, 0, -1)), PHP_EOL;
    }

    public static function saveRedisWinners($id, $mobile, $name, $prize_name, $level, $status) {
        $key = 'debug_win:'.$mobile;
        $data = [
            'name' => $name,
            'prize_name' => $prize_name,
            'level' => $level,
            'status' => $status,
        ];
        $data = json_encode($data);
        RedisIO::multi();
        RedisIO::zRemRangeByScore($key, $id, $id);
        RedisIO::zAdd($key, $id, $data);
        RedisIO::expire($key, 300);
        RedisIO::exec();
    }

}