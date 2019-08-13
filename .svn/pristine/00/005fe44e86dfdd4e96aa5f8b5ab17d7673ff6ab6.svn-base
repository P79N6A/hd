<?php

/**
 * Created by PhpStorm.
 * User: fang
 * Date: 2016/8/17
 * Time: 15:14
 */
class PkhsyController extends BackendBaseController
{
    /**
     * 刷点赞数
     */
    public function addYearGoodAction(){
        $vid = Request::getQuery('vid','int');
        $val = Request::getQuery('val','int');
        $pwd = Request::getQuery('pwd','string');
        if($pwd == "pkhsy2345") {
            $key = 'pkhsy_user_good:' . $vid;
            $res = RedisIO::set($key, $val);   //设置点赞数
            if ($res) {
                echo RedisIO::get($key);
            } else {
                echo "刷新出错！";
            }
        } else {
            echo "密码错误";
        }

    }

    /**
     * 刷点赞数周
     */
    public function addWeekGoodAction(){
        $vid = Request::getQuery('vid','int');
        $val = Request::getQuery('val','int');
        $pwd = Request::getQuery('pwd','string');
        if($pwd == "pkhsy2345") {
            $key = 'pkhsy_user_good:' . date('W') . ':' . $vid;
            $res = RedisIO::set($key, $val);   //设置点赞数
            if ($res) {
                echo RedisIO::get($key);
            } else {
                echo "刷新出错！";
            }
        } else {
            echo "密码错误";
        }

    }


}