<?php

/**
 * Created by PhpStorm.
 * User: fang
 * Date: 2016/9/3
 * Time: 15:36
 */
class UpdateTokenTask extends Task{
    public function mainAction()
    {
        $url = "http://ssohudong.cztv.com/weixin_auth/updatetoken";
        $res = F::curlRequest($url);
        if ($res) {
            echo $res;
        } else {
            echo "error";
        }
    }
}