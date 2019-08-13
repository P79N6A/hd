<?php
namespace GenialCloud\Helper;

/**
 * Class IWC
 *
 * IWC 名称来自万国表
 *
 * @package GenialCloud\Helper
 */
class IWC {

    /**
     * 当前时间
     * @return int
     */
    public static function now() {
        return time();
    }

    /**
     * @param int $time
     * @return bool|string
     */
    public static function full($time=null) {
        return self::format('Y-m-d H:i:s', $time);
    }

    /**
     * @param int $time
     * @return bool|string
     */
    public static function date($time=null) {
        return self::format('Y-m-d', $time);
    }

    /**
     * @param int $time
     * @return bool|string
     */
    public static function short($time=null) {
        return self::format('Ymd H:i:s', $time);
    }

    /**
     * @param int $time
     * @return bool|string
     */
    public static function shortDate($time=null) {
        return self::format('Ymd', $time);
    }

    /**
     * 本月开头
     *
     * @return bool|string
     */
    public static function monthBegin() {
        return self::format('Y-m-01 00:00:00');
    }

    /**
     * 本月结束
     *
     * @return bool|string
     */
    public static function monthEnd() {
        return self::format('Y-m-t 23:59:59');
    }

    /**
     * @param string $foramt
     * @param int $time
     * @return bool|string
     */
    public function format($foramt, $time=null) {
        if(is_null($time)) {
            $time = time();
        }
        return date($foramt, $time);
    }


    /**
     * 本周起始时间
     * @return array
     */
    public static function thisWeek(){
        $data[] = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1,date("Y"));
        $data[] = mktime(23,59,59,date("m"),date("d")-date("w")+7,date("Y"));
        return $data;
    }

    /**
     * 上周起始时间
     * @return array
     */
    public static function lastWeek(){
        $data[] = mktime(0, 0 , 0,date("m"),date("d")-date("w")+1-7,date("Y"));
        $data[] = mktime(23,59,59,date("m"),date("d")-date("w")+7-7,date("Y"));
        return $data;
    }

    /**
     * 上月起始时间
     * @return array
     */
    public static function lastMonth(){
        $data[] = mktime(0, 0 , 0,date("m")-1,1,date("Y"));
        $data[] = mktime(23,59,59,date("m") ,0,date("Y"));
        return $data;
    }

    /**
     * 本季度起始时间
     * @return array
     */
    public static function thisSeason(){
        $season = ceil((date('n'))/3);
        $data[] = mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
        $data[] = mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'));
        return $data;
    }

    /**
     * 上季度起始时间
     * @return array
     */
    public static function lastSeason(){
        $season = ceil((date('n'))/3)-1;
        $data[] = mktime(0, 0, 0,$season*3-3+1,1,date('Y'));
        $data[] = mktime(23,59,59,$season*3,date('t',mktime(0, 0 , 0,$season*3,1,date("Y"))),date('Y'));
        return $data;
    }

    /**
     * 早于一个月
     * @return mixed
     */
    public static function earlierMonth(){
        $data[0] = mktime(0, 0 , 0,date("m")-1,1,date("Y"));
        $data[1] = '';
        return $data;
    }


    public static function timeTransform($time) {
        $now = strtotime(date('Y-m-d H:i:s', time()));
        $dur = $now - $time;
        if ($dur < 0) {
            return "刚刚";
        } else if ($dur < 60) {
            return $dur . '秒前';
        } else if ($dur < 3600) {
            return floor($dur / 60) . '分钟前';
        } else if ($dur < 86400) {
            return floor($dur / 3600) . '小时前';
        } else if ($dur < 1296000) {
            return floor($dur / 86400) . '天前';
        } else if ($dur < 2592000) {
            return "半个月前";
        } else if ($dur < 31104000){
            return floor($dur / 2592000) . '个月前';
        } else {
            return floor($dur / 31104000) . '年前';
        }
    }

}