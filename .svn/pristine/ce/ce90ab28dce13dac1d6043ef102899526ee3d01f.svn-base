<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CdnProducer extends Model {

    public function getSource() {
        return 'cdn_producer';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'producer_name', 'username', 'password', 'push_url', 'return_url',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['producer_name', 'username', 'password', 'push_url', 'return_url',],
            MetaData::MODELS_NOT_NULL => ['id', 'producer_name', 'username',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'producer_name' => Column::TYPE_VARCHAR,
                'username' => Column::TYPE_VARCHAR,
                'password' => Column::TYPE_VARCHAR,
                'push_url' => Column::TYPE_VARCHAR,
                'return_url' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'producer_name' => Column::BIND_PARAM_STR,
                'username' => Column::BIND_PARAM_STR,
                'password' => Column::BIND_PARAM_STR,
                'push_url' => Column::BIND_PARAM_STR,
                'return_url' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [

            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    const PAGE_SIZE = 50;
    
    public static function getAllProducer() {
        $data = self::query()
                    ->columns('id,producer_name,username,password,push_url,return_url')
                    ->orderBy('id asc') 
                    ->execute();
        if($data){
            $data_ar = $data->toArray();
            foreach ($data_ar as $key => $value) {
                $ar[$value['id']]=$value['producer_name'];
            }
            return $ar;
        }else{
            return FALSE;
        }
    }
    
    public static function getAllProducerList(){
         return self::query()
                ->paginate(self::PAGE_SIZE, 'Pagination');
    }
    
    public function modifyPro($data){
        $this->assign($data);
        return ($this->update())?true:false;
    }
    
    public function findOneObject($id){
        $object = CdnProducer::query()->where("id='{$id}'")->first();
        return $object;
    }
    
    public static function getProById($id) {
        $data = self::query()
                    ->columns('id,producer_name,username,password,push_url,return_url,push_url')
                    ->andWhere("id ='{$id}'")
                    ->orderBy('id desc')
                    ->first()
                    ->toArray();
        return $data;
    }
    
    public static function getProducerById($cdn_id) {
        $key = 'redis_cdn_producer_'.$cdn_id;
        $data = RedisIO::get($key);
        if(empty($data)){
            $data_ar = self::query()
                    ->columns('id,producer_name,username,password,push_url')
                    ->andCondition('id',$cdn_id)
                    ->first()
                    ->toArray();
            RedisIO::set($key,json_encode($data_ar));
            RedisIO::expire($key, 60);
            return $data_ar;
        }else{
            return json_decode($data,TRUE);
        }
    }
    
    //增加操作
    //producer对象进行添加
    public function createProducer($data) {
        isset($data['id'])?$data['id']=null:true;
        $this->assign($data);
        return ($this->save()) ? $this->id:false;
    }
    
    public static function getProducerByName($username) {
        $key = 'redis_cdn_producer_'.$username;
        $data = RedisIO::get($key);
        if(empty($data)){
            $data_ar = self::query()
                    ->columns('id,producer_name,username,password,push_url')
                    ->andCondition('username',$username)
                    ->first()
                    ->toArray();
            RedisIO::set($key,json_encode($data_ar));
            RedisIO::expire($key, 60);
            return $data_ar;
        }else{
            return json_decode($data,TRUE);
        }
    }
    
    public static function getCdnName($cdn_id) {
        if ($cdn_id == 0){ return "";}
        $data = CdnProducer::query()->where("id='{$cdn_id}'")->first()->toArray();
        return $data['producer_name'];
    }
    
    //云帆视音频刷新接口
    public function pushYF($param,$cdn_id,$main_id,$operation='',$single=FALSE){
        
        foreach ($param as $a) {
                        $items[] = array(
                        'item_id' => strval($a['id']),
                        'operation' => empty($operation) ? CdnMainLog::getOperationCodeById($a['operation']) : $operation ,
                        'source_path' => strval($a['source_path']),
                        'publish_path' => strval($a['publish_path']),
                        'md5' => strval($a['md5']),
                        //'file_size' => strval($a['file_size']), 跟云帆存在调试分歧，取消这参数
                        'ext_option' => strval($a['ext_option']),
                        'slice'=>  strval($a['slice']), //云帆一定要字符串类型，非int型    
                        );
        }
        
        $producer = CdnProducer::getProducerById($cdn_id);
                $data =array(
                        'username'=>$producer['username'],
                        'password'=>$producer['password'],
                        'fc_sub'=>$items
                        );
        $url = $producer['push_url'];
        
        $return = F::curlRequest($url,'post', $data,TRUE);
        
        //开启回调监控----$cdn_id 对应cdn_id  $type 断点的位置：0:同步回调 1：异步回调
        $open_control = TRUE;
        if($open_control){
            $CdnReturnLog = new CdnReturnLog();
            $CdnReturnLog->createReturnLog($cdn_id, 0, json_encode($data).'|'.'main_id:'.$main_id.'|'.$return);
        }
        $back = json_decode($return,TRUE);
        
        if($back['status']==1){
                    $return_data = $back['result'];
                    
                    $CdnDetailLog = new CdnDetailLog();
                    $CdnMainLog = new CdnMainLog();
                    $main_id_ar = array();
                    //对接过程发现 云帆同步回调只会过滤operation ，其他诸如域名，文件是否存在等等 ，所以我们统一更新为【分发中】9.22
                    foreach ($param as $p) {
                        $CdnDetailLog->updateById($p['id'],array('status'=>2));
                    }
                    $CdnMainLog->updateById($main_id, array('status'=>2,'update_time'=>time()));    
                    
                    //成功
//                    if($return_data['success']){
//                        foreach ($return_data['success'] as $su) {
//                            $re_main_id = $CdnDetailLog->updateById($su['item_id'],array('status'=>2),TRUE);
//                            if($single==TRUE){$CdnMainLog->updateById($re_main_id, array('status'=>2),FALSE);}
//                            $main_id_ar[] = $re_main_id;
//                        }
//                    }
                    
                    //失败
//                    if($return_data['failed']){
//                        foreach ($return_data['failed'] as $fa) {
//                            $re_main_id = $CdnDetailLog->updateById($fa['item_id'],array('status'=>4),TRUE);
//                            if(!in_array($re_main_id, $main_id_ar)){
//                                if($single==TRUE){$CdnMainLog->updateById($re_main_id, array('status'=>4),FALSE);}
//                            }
//                        }
//                    }
                    
        }else{
                    //cdn接受任务出错入库
                    $CdnPushErrorLog = new CdnPushErrorLog();
                    $error_log['content'] = $return;
                    $error_log['create_time'] = time();
                    $CdnPushErrorLog->createCdnPushErrorLog($error_log);
        }
        return $back['status'];
        
    }

    //云帆小文件刷新接口(本接口不提供目录刷新)
    public function pushYF2($param,$cdn_id,$main_id) {
        
//        ini_set('display_errors','on');
//	error_reporting(E_ALL);
        $items = array();
        $item_id_ar = array();
        foreach ($param as $a) {
                $items[] = array(
                    'item_id' => strval($a['id']),
                    'url_name' => strval($a['publish_path'])
                );
                $item_id_ar[] = $a['id'];
        }
            
        $producer = CdnProducer::getProducerById($cdn_id);
        $data =array(
             'username'=>$producer['username'],
             'password'=>$producer['password'],
             'files'=>$items
        );
        $url = $producer['push_url'];
        
        $return = F::curlRequest($url,'post', $data,TRUE);
        
        //开启回调监控----$cdn_id 对应cdn_id  $type 断点的位置：0:同步回调 1：异步回调
        $open_control = TRUE;
        if($open_control){
            $CdnReturnLog = new CdnReturnLog();
            $CdnReturnLog->createReturnLog($cdn_id, 0, json_encode($data).'|'.'main_id:'.$main_id.'|'.$return);
        }
        $back = json_decode($return,TRUE);
        
        $CdnDetailLog = new CdnDetailLog();
        $CdnMainLog = new CdnMainLog();
        /*
                $return ='2222';
                $back['status']=1;
                $back['file_result']=array(
                    'sucess_count'=>3,
                    'error_count'=>0,
                    'error_list'=>array(
                        array('url_name'=>'http://down.fastweb11.com.cn/a/a.html','item_id'=>10,'error'=>'就是出错了'),
                        array('url_name'=>'http://down.fastweb11.com.cn/a/a.html','item_id'=>12,'error'=>'就是出错2222了'),
                        array('url_name'=>'http://down.fastweb11.com.cn/a/a.html','item_id'=>11,'error'=>'就是出错112222了'),
                    )
                );
         */
        if($back['status']==1){
        //cdn已接受 全部成功
                    foreach ($param as $p) {
                            $CdnDetailLog->updateById($p['id'],array('status'=>2));
                        }
                    
                    //查询子任务有无分发失败的 有失败即不改主任务状态
                    $details = CdnDetailLog::getCdnDetailByMainId($main_id);
                    $change_main =1;
                    foreach ($details as $d) {
                        if($d['status']==4){
                            $change_main =0;
                        }
                    }
                    if($change_main==1){
                        $CdnMainLog->updateById($main_id, array('status'=>2,'update_time'=>time()));
                    }
                        
                        
        }else{
        //cdn拒绝接受任务 所有的任务标记失败 status=4
                    foreach ($item_id_ar as $er) {
                        $status_ar = array('status'=>4 );
                        $CdnDetailLog->updateById($er,$status_ar);
                    }
                    $CdnMainLog->updateById($main_id, array('status'=>4,'update_time'=>time(),'end_time'=>time()));
            
                    $CdnPushErrorLog = new CdnPushErrorLog();
                    $error_log['cdn_id'] = $cdn_id;
                    $error_log['content'] = $return.'|'.'main_id:'.$main_id;
                    $error_log['create_time'] = time();
                    $CdnPushErrorLog->createCdnPushErrorLog($error_log);
        }
        return $back['status'];
    }
    
    /*
     * 云帆视音频接口异步回调逻辑处理
     */
    public function backYF($data,$cdn_id){
        $CdnDetailLog = new CdnDetailLog();
        $CdnMainLog = new CdnMainLog();
        foreach ($data as $value) { 
                    $status = ($value['status']=='finish') ?3 :4;
                    $detail_log = $CdnDetailLog->findOneObject($value['item_id']);
                    $detail_data['status'] = $status;
                    $result = $detail_log->modifyCdnDetailLog($detail_data);
                    
                    $main_log = $CdnMainLog->findOneObject($detail_log->main_id);
                    $data2['item_num'] = $main_log->item_num - 1;
                    $data2['status'] = $main_log->status;
                    if($data2['item_num']==0){
                        //查询子任务有无分发失败的
                        $details = CdnDetailLog::getCdnDetailByMainId($detail_log->main_id);
                        $main_status =3;
                        foreach ($details as $d) {
                            if($d['status']==4){
                                $main_status =4;
                            }
                        }
                        $data2['status']=$main_status;
                    }
                    $result2 = $main_log->modifyCdnMainLog($data2);
        }
    }
    
    /*
     * 云帆url刷新接口异步回调逻辑处理
     */
    public function backYF2($data,$cdn_id){
        $CdnDetailLog = new CdnDetailLog();
        $CdnMainLog = new CdnMainLog();
        foreach ($data as $key => $value) {
            $item_id = $key;
            //用来区分新蓝网后台刷新和中心刷新任务-是否为int
            if(is_numeric($item_id)){
                //$status = ($value==-1) ? 4 : ( ($value==100) ? 3 : 2 );
                $status = ($value==-1) ? 4 : 3;
                $detail_log = $CdnDetailLog->findOneObject($item_id);
                $detail_data['status'] = $status;
                $result = $detail_log->modifyCdnDetailLog($detail_data);

                $main_log = $CdnMainLog->findOneObject($detail_log->main_id);
                $data2['item_num'] = $main_log->item_num - 1;
                $data2['status'] = $main_log->status;
                $data2['end_time'] = time();
                $data2['update_time'] = time();
                if($data2['item_num']==0){
                            //查询子任务有无分发失败的 有失败即主任务失败
                            $details = CdnDetailLog::getCdnDetailByMainId($detail_log->main_id);
                            $main_status =3;
                            foreach ($details as $d) {
                                if($d['status']==4){
                                    $main_status =4;
                                }
                            }
                            $data2['status']=$main_status;
                }
                $result2 = $main_log->modifyCdnMainLog($data2);
            }
        }
    }
    
}
