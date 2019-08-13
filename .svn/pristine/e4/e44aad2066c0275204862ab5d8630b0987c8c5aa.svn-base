<?php

//use Phalcon\Mvc\Controller;

class FavoriteBaseController extends BaseController {
    //视频点赞, 视频收藏相关json输出
    protected static function favorite_json_output($code = 200, $data = array(), $callback = '') {
        if (empty($callback) || !preg_match('/^\w+$/', $callback)) {
            exit(json_encode(array('code' => $code, 'data' => $data)));
        } else {
            exit($callback . '(' . json_encode(array('code' => $code, 'data' => $data)) . ')');
        }
    }

}
