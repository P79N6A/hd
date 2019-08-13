<?php
/**
 * @RoutePrefix("/client")
 */
class ClientController extends ApiBaseController {

    public function initialize() {
        parent::initialize();
    }
    
    /**
     * @Post("/")
     * 获取用户client_id接口
     */
    public function clientIdAction(){
        $input = Request::getPost();
		if("nhudong_product"!=app_site()->memprefix) {
            $queueName = app_site()->memprefix . "clientmns";
		}
		else {
		    $queueName = "clientmns";
		}
        if(!issets($input, ['origin_id', 'model', 'app_version', 'client_type', 'sdk_version'])) {
            $this->_json([], 403, '参数不足');
        }
        if(strlen($input['origin_id']) != 32) {
            $this->_json([], 402, 'key 长度不足');
        }
        if($this->tryToken()) {
            $input['user_id'] = $this->user->id;
            $input['mobile'] = $this->user->mobile;
        }
        $client_id = Request::getQuery('client_id', 'string');
        $arr_data = array(
                'device_token'  => isset($input['device_token']) ? $input['device_token'] : '',
                'push_client'  => $input['push_client'],
                'user_id'      => $input['user_id'],
                'origin_id'    => $input['origin_id'],
                'model'        => $input['model'],
                'mobile'       => $input['model'],
                'app_version'  => $input['app_version'],
                'client_type'  => $input['client_type'],
                'sdk_version'  => $input['sdk_version'],
                'hash_id'      => $client_id,
                'channel_id'   => $this->channel_id
           );

        if(RedisIO::exists($client_id)) {
            $res = RedisIO::get($client_id);
            $res = json_decode($res, true);
            //如果个推ID或user_id不一样就进入队列
            if($res['device_token'] != $input['device_token'] ||$res['sdk_version'] != $input['sdk_version'] ||$res['push_client'] != $input['push_client'] || $res['user_id'] != $input['user_id']){

                //更新client_id
                $json = json_encode($arr_data);
                RedisIO::set($client_id, $json);

                //取消老user_id，client_id客户端信息
                if($res['user_id'] != $input['user_id']){
                    $redis_key = 'user_ids:'.$res['user_id'];
                    RedisIO::delete($redis_key);
                }

                //设置用户client_id
                if(intval($input['user_id'])) {
                    $redis_key = 'user_ids:'.$input['user_id'];
                    RedisIO::set($redis_key,$json);
                }

                //加入队列
                $this->queue->sendMessage($json, $queueName);
            }
            $this->_jsonzgltv($this->channel_id,['hash_id' => urlencode($client_id)]);

        } else {
            if($client_id == null){
                $client_id = md5(sha1($this->channel_id.Config::get('secret').$input['origin_id']));  //生成client_id
                $arr_data['hash_id'] = $client_id;
            }
            $json = json_encode($arr_data);
            RedisIO::set($client_id, $json);

            //设置用户client_id
            $redis_key = 'user_ids:'.$input['user_id'];
            RedisIO::set($redis_key,$json);

            $res = $this->queue->sendMessage($json, $queueName);
            if($res) {
                $this->_jsonzgltv($this->channel_id,['hash_id' => urlencode($client_id)]);
            }
            else {
                $this->_jsonzgltv($this->channel_id,[], 404, '写入失败');
            }
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