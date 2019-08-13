<?php

/**
 * @RoutePrefix("/sobey")
 */
class SobeyController extends SobeyBaseController {

    static $user2Channel = [
        '878' => 3,  //蓝tv,
        '903' => 1,  //浙江新昌网
        '902' => 6,  //湘湖网
        '897' => 12, //嵊州视听网
        '898' => 13, //诸暨视听网
        '893' => 14, //上虞视听网
        '899' => 15, //桐乡视听网
        '896' => 16, //衢州信息港
        '892' => 17, //余姚视听网
    ];

    /**
     * @Post('/')
     */
    public function getPostAction() {
        $input = file_get_contents("php://input");
        $input = json_decode($input,true);
        if(!issets($input, ['status', 'catalogId', 'id'])) {
            $json_data = array ('id'=>$input['id'],'partnerCode'=>'letvCMS','status'=>404,"message"=>'params error');
            echo json_encode($json_data);exit;
        }
        $user = $input['siteId'];
        if(!isset(self::$user2Channel[$user])){
            $json_data = array ('id'=>$input['id'],'partnerCode'=>'letvCMS','status'=>403,"message"=>'user_name not match channel_id');
            echo json_encode($json_data);exit;
        }
        if(!in_array($input['status'], [0,1,4])) {
            $json_data = array ('id'=>$input['id'],'partnerCode'=>'letvCMS','status'=>402,"message"=>'video_status not in 7 or 8');
            echo json_encode($json_data);exit;
        }
        $model = new Sobeysupplies();
        $id = $model->saveGetId([
            'channel_id' => self::$user2Channel[$user],
            'source_id' => $input['id'],
            'supply_category_id' => $input['catalogId'],
            'origin_content' => serialize($input),
            'created_at' => time(),
            'updated_at' => time(),
            'status' => 0,
            'partition_by' => date("Y"),
        ]);
        if($id) {
            $json_data = array ('id'=>$input['id'],'partnerCode'=>'letvCMS','status'=>1,"message"=>'ok');
            echo json_encode($json_data);exit;
        } else {
            $json_data = array ('id'=>$input['id'],'partnerCode'=>'letvCMS','status'=>0,"message"=>'fail');
            echo json_encode($json_data);exit;
        }
    }



}