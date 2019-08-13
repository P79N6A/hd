<?php

/**
 * Created by PhpStorm.
 * User: fang
 * Date: 2016/9/3
 * Time: 10:15
 */
class DataStataisticsTask extends Task{
    public function mainAction()
    {
        $data_ids = RedisIO::zRange(DataStatistics::QUEUEDATAID, 0, -1);
        $i = 0;
        if($data_ids) {
            foreach ($data_ids as $data_id) {
                if(intval($data_id) == 0) {
                    RedisIO::zRem(DataStatistics::QUEUEDATAID, $data_id);
                    continue;
                }
                $res = DataStatistics::updateByDataId($data_id);
                if ($res) {
                    RedisIO::zRem(DataStatistics::QUEUEDATAID, $data_id);
                    $i++;
                } else {
                    echo "save fail " . $data_id;
                }
            }
            echo "successed " . $i;
        } else {
            echo "data_id not empty";
        }
    }
}