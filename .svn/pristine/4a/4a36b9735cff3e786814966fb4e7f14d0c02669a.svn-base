<?php

class CdnYffTask extends Task {

    protected $redis_cdn_yff_list_key = 'cdn_yff_user_task_1';
    protected $queue_cdn_yff_key = 'cdnyff';//queue key
    
    /*
     * 从redis 取任务分解入mysql
     * error_type:
     * 1 empty main data
     * 2 wrong app_secret
     * 3 Redis fail connect 
     * 4 wrong json type
     * 5 主任务入库出错 
     * 6 子任务入库出错
     * 
     */
    public function insertAction() {
        
        while (1){
            try {
                $msgstr ='';
                $error_type = 0;
                $json = RedisIO::LPOP($this->redis_cdn_yff_list_key);
                
                if(!empty($json)){
                    if($ar = json_decode($json,TRUE)){
                        $cdn_id = $ar['cdn_id'];
                        $channel_id  = $ar['channel_id'];
                        $cdnuser = CdnUser::getCdnUserByAppid($ar['app_id']);
                            $main['channel_id'] = $channel_id;
                            $main['title'] = $ar['title'];
                            $main['task_user_id'] = $cdnuser['id'];
                            $main['task_id'] = $ar['task_id'];
                            $main['operation'] = $ar['operation'];
                            $main['item_num'] = count($ar['fc_sub']);
                            $main['content'] = json_encode($ar['fc_sub']);
                            $main['cdn_id'] = $cdn_id;   
                            $main['status'] = 1;
                            $main['create_time'] = time();

                                    $cdnmainlog = new CdnMainLog();
                                    if($main_id = $cdnmainlog->createCdnMainLog($main)){

                                    foreach ($ar['fc_sub'] as $key => $value){
                                            $item = array(
                                                'main_id'=>$main_id,
                                                'task_user_id'=>$cdnuser['id'],
                                                'item_id'=>$value['item_id'],
                                                'operation'=>$value['operation'],
                                                'cdn_id'=>$cdn_id,
                                                'file_type'=>$value['file_type'],
                                                'publish_path'=>$value['publish_path'],
                                                'source_path'=>$value['source_path'],
                                                'md5'=>$value['md5'],
                                                'file_size'=>$value['file_size'],
                                                'content'=>json_encode($value),
                                                'status'=>1,
                                                'update_time'=>time(),
                                                'slice' => isset($value['slice'])?$value['slice']:0,
                                            );

                                                $cdndetaillog = new CdnDetailLog();
                                                if(!($cdndetaillog->createCdnDetailLog($item))){
                                                        $msgstr .= '子任务:'.$value['item_id'].Lang::_('failed').'|';
                                                        $error_type = '6';
                                                        $msgstr = '子任务入库出错';
                                                    }
                                        }
                                    }else{
                                        $error_type = '5';
                                        $msgstr = '主任务入库出错';
                                    }
                            $task_id = $ar['task_id'];
                    }else{
                        $error_type = '4';
                        $task_id = '';
                    }

                    //错误入库
                    if($error_type>0){
                        $data =array(
                            'error_type'=>$error_type,
                            'task_id'=>$task_id,
                            'msg'=>$msgstr,
                            'data'=>$json,
                            'create_time'=>time(),
                        );
                    $cdninserterrorlog = new CdnInsertErrorLog();
                    $cdninserterrorlog->createCdnInsertErrorLog($data);
                    }
                    usleep(100);
                }else{
                    sleep(4);
                }
            }catch (Exception $ex) {
                sleep(4);
                echo "sleep(4)...\n";
            }
         }
    }

    /*
     * 从mysql取主任务&子任务分发给cdn
     * 取最近86400秒内未分发的主任务&status=1&limit=1
     * 并处理实时响应数据
     */
    public function pushAction(){
        while (1){
            try {
                $main = CdnMainLog::getCdnMainLogByTimeFirst(time()-86400);
                $cdn_id = trim($main['cdn_id']);
                $main_id = $main['id'];     
                if(!empty($main)){
                        $details = CdnDetailLog::getCdnDetailLogByMainId($main_id);
                        switch ($cdn_id) {
                            case 1:
                                $CdnProducer = new CdnProducer();
                                $CdnProducer->pushYF($details,$cdn_id,$main_id);
                                break;
                            case 2:
                                $CdnProducer = new CdnProducer();
                                $CdnProducer->pushYF2($details,$cdn_id,$main_id);
                                break;
                            default:
                                break;
                        }
                    usleep(100);
                }else{
                    sleep(4);
                }
                
            }catch (Exception $ex) {
                 sleep(4);   
                 echo "sleep(4)...\n";
            }
        }
    }
    
    /*
     * 从队列取cdn异步回调数据入库
     */
    public function queueinsertAction(){
        global $config;	
        $queueName = $config->memprefix  . $this->queue_cdn_yff_key;
        
        while (1) {
            try {
                    //从队列取数据
                $data = $this->queue->getMessage($queueName);
                if(!empty($data)){
                    $data_ar = json_decode($data,TRUE);
                    $cdn_id = $data_ar['cdn_id'];
                    switch ($cdn_id){
                        case 1:
                            $CdnProducer = new CdnProducer();    
                            $CdnProducer->backYF($data_ar['data'],$cdn_id);    
                        break;
                        case 2:
                            $CdnProducer = new CdnProducer();    
                            $CdnProducer->backYF2($data_ar['data'],$cdn_id);  
                        break;
                        default:
                            break;
                    }
                    usleep(100);
                }else{
                    sleep(4);
                }
            }catch (Exception $ex) {
                sleep(4);   //没有任务时休息4秒
                //echo "sleep(4)...\n";
            }
        }
    }

}
