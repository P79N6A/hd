<?php

/**
 * @RoutePrefix("/subscription")
 */
class SubscriptionController extends ApiBaseController {

    /**
     * 获取用户的订阅内容
     * @Get("/info/{id:[0-9]+}")
     * @param int $id 用户ID
     */
    public function infoAction($id) {
        $id = intval($id);
        if($id) {
            $user_subscription = Subscription::apiFindSubscriptionByUid($id);
            $subscription_info = SubscriptionSet::apiFindSetAll();
            $subscription_info = json_decode($subscription_info,true);

            $user_subscription_arr = array();
            foreach ($user_subscription as $key => $sub){
                $user_subscription_arr[] = array_merge($sub,$subscription_info[$sub['set_id']]);
            }
            $data = array();
            $data =$user_subscription_arr;

            $this->_jsonzgltv($this->channel_id, $data , 200 , 'success' , true);
        } else {
            $this->_jsonzgltv($this->channel_id, [] , 404 , 'Not Found' );
        }
    }

    /**
     * 获取热词内容
     * @Get("/getkeyword")
     */
    public function getKeywordAction() {
        $keyword_json = RedisIO::get(SubscriptionSetInfo::$get_keywork_key);
        if ($keyword_json) {
            $data = json_decode($keyword_json, true);
        }else{
            $data = array();
        }
        $this->_jsonzgltv($this->channel_id,$data , 200 , 'success' , true);

    }

    /**
     * 专辑订阅功能
     * @Post('/order')
     */
    public function orderAction() {
        $set_id = Request::getPost('set_id');
        $uid = Request::getPost('uid');
        $user_is_order = Request::getPost('user_is_order');
        if($set_id && $uid){
            $set = SubscriptionSet::apiFindOneSetBySetId($set_id);
            if ($set){
                $key1 = 'api::subscription::uid::'.$uid;
                $order = Subscription::apiFindOneSubscription($uid , $set_id);
                $return_data = array();
                $return_data['set_id'] = $set['set_id'];
                $order_status = 0;
                if (!$order || empty($order)){
                    $order_status = 2;//未订阅
                }else{
                    $order_status = 1;//已订阅
                }
                $return_data['user_is_order'] = $order_status;
                if($user_is_order == 1) {//预约操作
                    try{
                        if($order_status == 2) {
                            $data = array();
                            $data['uid'] = $uid;
                            $data['set_id'] = $set_id;
                            $data['create_time'] = time();
                            $subscription = new Subscription();
                            $return = $subscription->createSubscription($data);
                            if ($return) {
                                $sub_arr = $subscription->toarray();
                                $key = D::memKey('apiFindOneSubscription', ['uid' => $uid , 'set_id' => $set_id]);
                                MemcacheIO::set($key, $sub_arr, 600);

                                $key = D::memKey('apiFindSubscriptionByUid', ['uid'=>$uid]);
                                $memcache = MemcacheIO::get($key);
                                if ($memcache) {
                                    array_unshift($memcache, $sub_arr);
                                    MemcacheIO::set($key, $memcache, 600);
                                }
                            }else{
                                throw new Exception();
                            }

                            $set_info = SubscriptionSetInfo::findOneBySetId($set_id);
                            if (!$set_info) {
                                $this->_jsonzgltv($this->channel_id, $return_data , 4006, 'info dont exist' );
                            }

                            $set_info->subscription_number++;
                            $set_info->update();
                            RedisIO::zAdd($key1, 0, $set_id);
                            $order_status = 1;
                            $return_data['user_is_order'] = $order_status;
                        }else{
                            $this->_jsonzgltv($this->channel_id, $return_data , 4005, 'order is exist' );
                        }
                    }catch (Exception $e){
                        $this->_jsonzgltv($this->channel_id, $return_data , 4004, 'order create failed' );
                    }
                }elseif ($user_is_order == 2){//取消预约
                    try{
                        if($order_status == 1){
                            $return = Subscription::deleteSubscriptionOrder($order['id']);
                            if ($return) {
                                $key = D::memKey('apiFindOneSubscription', ['uid' => $uid , 'set_id' => $set_id]);
                                MemcacheIO::delete($key);

                                $key = D::memKey('apiFindSubscriptionByUid', ['uid'=>$uid]);
                                $memcache = MemcacheIO::get($key);
                                if ($memcache) {
                                    foreach ($memcache as $key_arr => $value_arr){
                                        if ($value_arr['set_id'] == $set_id) {
                                            unset($memcache[$key_arr]);break;
                                        }
                                    }
                                    MemcacheIO::set($key, $memcache, 600);
                                }

                            }

                            $set_info = SubscriptionSetInfo::findOneBySetId($set_id);
                            if (!$set_info) {
                                $this->_jsonzgltv($this->channel_id, $return_data , 4006, 'info dont exist' );
                            }

                            $set_info->subscription_number--;
                            $set_info->update();

                            RedisIO::zDelete($key1, $set_id);
                            $order_status = 2;
                            $return_data['user_is_order'] = $order_status;
                        }else{
                            $this->_jsonzgltv($this->channel_id, $return_data , 4006, 'order is not find' );
                        }

                    }catch (Exception $e){
                        $this->_jsonzgltv($this->channel_id, $return_data , 4004, 'order delete failed' );
                    }
                }else{
                    $this->_jsonzgltv($this->channel_id, $return_data , 4003, 'order is error' );
                }
                $this->_jsonzgltv($this->channel_id, $return_data , 200, 'success' );
            } else {
                $this->_jsonzgltv($this->channel_id, [] , 4002, 'Set is not exist ' );
            }
        }else{
            $this->_jsonzgltv($this->channel_id, [] , 4001, 'Parameter is null' );
        }

    }

    /**
     * 搜索专辑功能
     * @Post('/search')
     */
    public function searchAction() {
        $search_msg = Request::getPost('search_msg');
        $set_id = Request::getPost('set_id');
//        $search_msg = htmlentities($search_msg ,ENT_NOQUOTES);//暂时好像不需要
        if ($set_id) {//存在set_id时，进行精确查询
            $set_arr = SubscriptionSet::apiFindSetAll();
            $set_arr = json_decode($set_arr,true);
            $data = array();

            $set_id_arr = explode(',',$set_id);
            foreach ($set_id_arr as $key => $value) {
                $set_id_arr[$key] = (int)$set_id_arr[$key];
                if ($set_id_arr[$key]===0) {
                    unset($set_id_arr[$key]);continue;
                }
                if (isset($set_arr[$value])) {
                    $data[] = $set_arr[$value];
                }
            }
            $this->_jsonzgltv($this->channel_id, $data , 200 , 'success',true);
        } elseif ($search_msg) {//对查询内容进行模糊查询
            $set_arr = SubscriptionSet::apiFindSetAll();
            $set_arr = json_decode($set_arr,true);
            $data = array();
            foreach ($set_arr as $key => $set){
                if (strpos($set['name'],$search_msg) !== false) {
                    $data[] = $set;
                }
            }
            $this->_jsonzgltv($this->channel_id, $data , 200 , 'success',true);
        }else {
            $this->_jsonzgltv($this->channel_id, [] , 4001, 'Search message is null' );
        }
    }

    /**
     * @param $channel_id
     * @param $data
     * @param int $code
     * @param string $msg
     * @param bool $aleradyarray
     */
    protected function _jsonzgltv($channel_id, $data, $code = 200, $msg = "success", $aleradyarray=false) {
        if($channel_id==LETV_CHANNEL_ID) {
            header('Content-type: application/json');
            $listdata = [];
            if($data!=[]) $listdata[] = $data;
            if($aleradyarray) $listdata = $data;
            echo json_encode([
                'alertMessage' => "数据获取成功",
                'state' => ($code==200)?0:$code,
                'message' => $msg,
                'content' => ['list'=>$listdata],
            ]);
            exit;
        }
        else {
            $this->_json($data, $code, $msg);
        }
    }
}