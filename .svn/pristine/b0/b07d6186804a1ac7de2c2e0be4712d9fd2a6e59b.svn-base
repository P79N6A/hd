<?php

/**
 * @RoutePrefix("/guanzhi")
 */
class GuanzhiController extends SobeyBaseController {

    static $user2Channel = [
        'xinchang'=>1,
        'shengzhou'=>12,
        'zhuji'=>13,
        'shangyu'=>14,
        'tongxiang'=>15,
        'panxia'=>15,
        'fm971'=>15,
        'kuangwanzhong'=>15,
        'wangzhijie'=>15,
        'xieweifeng'=>15,
        'quzhou'=>16,
        'yuyao'=>17,
    ];

    /**
     * @Post('/')
     */
    public function getPostAction() {
        $input = Request::getPost();
        if(!issets($input, ['video_status', 'column_id', 'video_id'])) {
            $this->_json([], 404, 'params error');
        }
        $user = $input['user_name'];
        if(!isset(self::$user2Channel[$user])){
            $this->_json([],403,'user_name not match channel_id');
        }
        if(!in_array($input['video_status'], [7, 8])) {
            $this->_json([], 402, 'video_status not in 7 or 8');
        }
        $model = new Supplies();
        $id = $model->saveGetId([
            'channel_id' => self::$user2Channel[$user],
            'source_id' => $input['video_id'],
            'supply_category_id' => $input['column_id'],
            'origin_content' => serialize($input),
            'created_at' => time(),
            'updated_at' => time(),
            'status' => 0,
            'partition_by' => date("Y"),
        ]);
        if($id) {
            $this->_json([]);
        } else {
            $this->_json([], 400, 'sync error');
        }
    }



}