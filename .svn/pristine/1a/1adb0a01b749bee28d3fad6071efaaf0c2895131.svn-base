<?php

/**
 * @RoutePrefix("/cdn_yff")
 */
class CdnyffController extends ApiBaseController {

    protected $redis_cdn_yff_list_key = 'cdn_yff_user_task_1';//redis list key
    protected $queue_cdn_yff_key = 'cdnyff';//queue key
    protected $pro_memo1=array(
        'yf'=>1,
        'ali'=>3,
        'dl'=>4,
        'yp'=>5,
        'st'=>6,
        'fastweb'=>7,
    );

    public function initialize()
    {
        $action = $this->dispatcher->getActionName();
        if ($action != 'task' && $action != 'yunfanrt' && $action !='yunfanurlrt') {
            parent::initialize();
        }
    }

    /**
     * @Put("/task")
     * VMS 预分发任务进入redis任务池
     * error_type:
     * 1 empty main data
     * 2 wrong app_secret
     * 3 Redis fail connect   
     */
    public function taskAction(){
        
        parent::checkSignatureCdnUser();
        $data = file_get_contents("php://input"); 
        
        $error_log = array();
        $error_log['error_type'] = 0;
        
        if(!empty($data)){
            //校验授权账户信息
            $data_ar = json_decode($data,TRUE);
            
            $cdnuser = CdnUser::getCdnUserByAppid($data_ar['app_id']);
            if($cdnuser['app_secret']==$data_ar['app_secret']){
                if(RedisIO::lPush($this->redis_cdn_yff_list_key,$data)){
                    $return  =  $this->_json([]); 
                }else{
                    $error_log['error_type'] = 3;
                    $error_log['msg'] = 'Redis fail connect';
                    $return =  $this->_json([], 1001, 'Redis fail connect'); 
                }
            }else{
                $error_log['error_type'] = 2;
                $error_log['msg'] = 'app_secret error';
                $return =  $this->_json([], 1002, 'app_secret error');
            }
        }else{
            $error_log['error_type'] = 1;
            $error_log['msg'] = 'empty main data';
            $return =  $this->_json([], 1003, 'data empty'); 
        }
        
        if($error_log['error_type']>0){
            $error_log['data'] = json_encode($data);
            $error_log['create_time'] = time();
            $cdninserterrorlog = new CdnInsertErrorLog();
            $cdninserterrorlog->createCdnInsertErrorLog($error_log);
        }
        return $return;
    }
    
    /**
     * @Put("/yunfanrt")
     * CDN异步回调进入队列 -视音频文件
     */
    public function yunfanrtAction() {
        parent::checkSignatureCdnPro();
        //cdn异步返回 数据进入队列
        //$return = Request::getPost();
        $return = file_get_contents("php://input");
        //$cdn_id = 1; 因为文档v1.2调整 所有预分发视音频类的统一异步回源，将cdn区分加入了返回的数据里，所以cdn_id挪到下面取出
        $type = 1;
        
        
        $data = json_decode($return,TRUE);
        $cdn_id = $this->pro_memo1[$data['memo1']];
        
        //开启回调监控----$cdn_id 对应cdn_id  $type 断点的位置：0:同步回调 1：异步回调
        $open_control = TRUE;
        if($open_control){
            $CdnReturnLog = new CdnReturnLog();
            $CdnReturnLog->createReturnLog($cdn_id, $type, $return);
        }
        
        $producer = CdnProducer::getProducerById($cdn_id);
        if($producer['username']==$data['username'] && $producer['password']==$data['password']){
            if(!empty($data['result'])){
                //直接进入队列
                $queue_data = array(
                    'cdn_id'=>$cdn_id,
                    'data'=>$data['result']
                );
                $queueName = app_site()->memprefix . $this->queue_cdn_yff_key;
                if($this->queue->sendMessage(json_encode($queue_data), $queueName)){
                    return $this->_json([]);
                }else{
                    return $this->_json([], 1004, 'queue insert error');  
                }
            }else{
                return $this->_json([], 1005, 'data result empty');
            }
        }else{
            return $this->_json([], 1006, 'username or password error'); 
        }
        
    }

    
    /**
     * @Put("/yunfanurlrt")
     * CDN异步回调进入队列-小文件-(刷新接口)
     */
    public function yunfanurlrtAction(){
        parent::checkSignatureCdnPro();
        //cdn异步返回 数据进入队列
        $return = file_get_contents("php://input");
        //$return =  '{"data_list":{"1":100},"password":"cztv@06_01","username":"cztv@yunfan.com"}';
        $cdn_id = 2; 
        $type = 1;
        
        if(!empty($return)){
            //开启回调监控---- $cdn_id 对应cdn_id  $type 断点的位置：0:同步回调 1：异步回调
            $open_control = TRUE;
            if($open_control){
                $CdnReturnLog = new CdnReturnLog();
                $CdnReturnLog->createReturnLog($cdn_id, $type, $return);
            }
            
            $data = json_decode($return,TRUE);
            $producer = CdnProducer::getProducerById($cdn_id);
            if($producer['username']==$data['username'] && $producer['password']==$data['password']){
                if(!empty($data['data_list'])){
                    //直接进入队列
                    $queue_data = array(
                        'cdn_id'=>$cdn_id,
                        'data'=>$data['data_list']
                    );
                    
                    $queueName = app_site()->memprefix . $this->queue_cdn_yff_key;
                    //print_r($queueName);exit;
                    if($this->queue->sendMessage(json_encode($queue_data), $queueName)){
                        return $this->_json([]);
                    }else{
                        return $this->_json([], 1004, 'queue insert error');  
                    }
                }else{
                    return $this->_json([], 1005, 'data result empty');
                }
            }else{
                return $this->_json([], 1006, 'username or password error'); 
            }
        }else{
            return $this->_json([], 1007, 'data empty'); 
        }
        
    }
}
