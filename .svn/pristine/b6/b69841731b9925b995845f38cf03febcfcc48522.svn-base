<?php
/**
 * qukan视频cdn分发情况确认
 */
class QukanTask extends Task {
    /*
     * 获取Redis内的fileId的保存
     */
    public function getMessageAction(){

    }

    protected function getUnFinishData($channel_id,$activity_id,$status)
    {
        $model = new ActivitySignup();
        $datas = $model->find(
            array("conditions"=>"channel_id = :channel_id: AND activity_id = :activity_id: AND status = :status:",
                "bind"=>array('channel_id'=>$channel_id,'activity_id'=>$activity_id,'status'=>$status),
                "order"=>"id desc"));
        $vids = [];
        foreach($datas as $data)
        {
            $arr = json_decode($data->ext_values,true);
            $vids[] = array('vid'=>$arr['ex_vedioid'],'token'=>$arr['ex_token'],'id'=>$data->id);
        }
        return $vids;
    }



    public function qkQueeHandelAction()
    {
        $ready_arr = $this->getUnFinishData(14,3,-1);

        foreach($ready_arr as $arr)
        {
            $vid = $arr['vid'];             //视频id
            $token = $arr['token'];         //token
            $signupid = $arr['id'];    //报名id
            $ret = $this->callqk($vid);
            if($ret['hlsUrl'])
            {
                //取到数据值
                $qk_path = $ret['hlsUrl'];
                $data_id = $this->qKViedioUpdate($ret);
                if($data_id) {
                    $ex_data = array('ex_vediourl' => $qk_path, 'ex_dataid' => $data_id);
                    $status = 0;
                    //更新报名数据表
                    ActivitySignup::UpdateSignupData($signupid, $status, $ex_data);
                    $mobtokenkey = D::redisKey('mobtoken',$token);
                    $arr = json_decode(RedisIO::get($mobtokenkey), true);
                    $arr['vedioid'] = $vid;
                    $arr['vediourl'] = $qk_path;
                    $arr['data_id'] = $data_id;
                    RedisIO::set($mobtokenkey, json_encode($arr));
                }
            }else
            {

            }
        }
    }
    /*
     * 通过fileId请求qukan接口，如果成功删除Redis内fileId并调用保存视频方法
     * 如果失败则不做处理
     */
    protected function callqk($fileId){
        $data = array('fileId'=>$fileId);
        $data_string = json_encode($data);

        $ch = curl_init('http://sz.qukanvideo.com/cloud/services/video/getOne');
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS,$data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        curl_close($ch);
        if($result){
            $return_arr = json_decode($result,true);
            return $return_arr['value'];
        }else{
            return false;
        }
    }

    protected function qKViedioUpdate($arr){
        $arr['video_id'] = $this->createVideo($arr);
        $this->cpVideoFile($arr['video_id'],$arr);
        $data_id = $this->createData($arr,'video');
        if($data_id) {
            return $data_id;
        }else{
            return false;
        }
    }

    protected function createData($v, $type) {
        $model = new Data();
        $data['type'] = $type;
        $data['channel_id'] = 14;
        $data['source_id'] = $v['video_id'];
        $data['title'] = 'qukan视频';
        $data['intro'] = 'qukan视频';
        $data['thumb'] = '';
        $data['created_at'] = time();
        $data['updated_at'] = time();
        $data['author_id'] = 1;
        $data['author_name'] = '';
        $data['hits'] = 0;
        $data['data_data'] = '[]';
        $data['status'] = 0;
        $data['partition_by'] = date("Y", time());
        $data_id = $model->saveGetId($data);
        if($data_id) {
            $data['data_id'] = $data_id;
        } else {
            return false;
        }
        return $data_id;
    }

    protected function createVideo($v) {
        $model = new Videos();
        return $model->saveGetId([
            'keywords' => '',
            'channel_id' => 14,//暂时定义为7
            'collection_id' => 0,
            'supply_id' => $v['id'],
            'duration' => $v['duration'],
            'created_at' => time(),
            'updated_at' => time(),
            'partition_by' => date('Y', time()),
        ]);
    }

    protected function cpVideoFile($video_id, $v) {
        $model = new VideoFiles();
        $model->save([
            'video_id' => $video_id,
            'path' => $v['hlsUrl'],
                    'rate' => 500,
                    'format' => 'mp4',
                    'width' => 640,
                    'height' => 480,
            'partition_by' => date("Y", time())
        ]);
        return true;
    }
}

