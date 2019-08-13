<?php

class CdnYffController extends \BackendBaseController {

    protected $redis_cdn_yff_list_key = 'cdn_yff_user_task_1';
    protected $queue_cdn_yff_key = 'cdnyff';
   
    /*
     * 默认列表页
     */
    public function indexAction() {
        $channel_id = Session::get('user')->channel_id;
        $data = CdnMainLog::findAll($channel_id);  
        $pro_list = CdnProducer::getAllProducer();
        $operation_list = CdnMainLog::operationList();
        $task_status_list = CdnMainLog::taskStatusList();
        $sys_cdn_user_list = CdnUser::getAllUser();
        //print_r(Session::get('user')->id);exit;
        View::setVars(compact('data','operation_list','pro_list','task_status_list','sys_cdn_user_list'));
    }
    
    /*
     * 搜索
     */
    public function searchAction() {
        $channel_id = Session::get('user')->channel_id;
        if($mess = Request::getPost()){
            $data = CdnMainLog::search($mess,$channel_id);
            $pro_list = CdnProducer::getAllProducer();
            $operation_list = CdnMainLog::operationList();
            $task_status_list = CdnMainLog::taskStatusList();
            $sys_cdn_user_list = CdnUser::getAllUser();
            View::pick('cdn_yff/index');
            View::setVars(compact('mess','data','operation_list','pro_list','task_status_list','sys_cdn_user_list'));
        }
    }
    
    /*
     *  重发、强制下架 主任务
     */
    public function resendAction() {
        $id = Request::get("id");
        $type = Request::get("type");
        $msg = CdnMainLog::pushById($id,$type);
        $msg = $msg ? $this->_json([], 200) : $this->_json(400, Lang::_('error'));
        echo $msg;exit;
    }
    
    /*
     *  逻辑删除主任务
     */
    public function deltaskAction() {
        $id = Request::get("id");
        $msg = CdnMainLog::delById($id);
        $msg = $msg ? $this->_json([], 200) : $this->_json(400, Lang::_('error'));
        echo $msg;exit;
    }
    
    /*  
     *   详情页
     */
    public function viewAction() {
        $this->initFormView();
        $id = Request::get('id');
        
        $details = CdnDetailLog::getDetailByMainId($id);
        $pro_list = CdnProducer::getAllProducer();
        $operation_list = CdnMainLog::operationList();
        $task_status_list = CdnMainLog::taskStatusList();
        
        View::setVars(compact('details','pro_list','operation_list','task_status_list'));
    }
    
    /*
     *  重发、强制下架 子任务
     */
    public function resenditemAction() {
        $id = Request::get("id");
        $type = Request::get("type");
        $msg = CdnDetailLog::pushById($id,$type);
        $msg = $msg ? $this->_json([], 200) : $this->_json(400, Lang::_('error'));
        echo $msg;exit;
    }
    
    /*
     *  逻辑删除子任务
     */
    public function delitemAction() {
        $id = Request::get("id");
        $msg = CdnDetailLog::delById($id);
        $msg = $msg ? $this->_json([], 200) : $this->_json(400, Lang::_('error'));
        echo $msg;exit;
    }
    
    /*
     *  CDN产商配置-列表
     */
    public function proindexAction(){
        $data = CdnProducer::getAllProducerList();
        View::setVars(compact('data'));
    }

    /*
     *  CDN产商配置-编辑
     */
    public function proeditAction(){
        $id = Request::get('id');
        $detail = CdnProducer::getProById($id);
        View::setVars(compact('detail'));
    }
    
    /*
     *  CDN产商配置-保存
     */
    public function prosaveAction(){
       if (Request::isPost()) {
           $data = Request::getPost();
           $detail = $data;
           $CdnProducer = new CdnProducer();
           $pro = $CdnProducer->findOneObject($data['id']);    
           unset($data['id']);
           $msg = $pro->modifyPro($data);
           $messages[] = $msg ? Lang::_('success') : Lang::_('error');
           
       }
         
       View::pick('cdn_yff/proedit');
       View::setVars(compact('messages','detail'));
    }
    
    /*
     *  CDN产商配置-新增
     */
    public function proaddAction() {
        if(Request::isPost()){
            $data = Request::getPost();
            $CdnProducer = new CdnProducer();
            $pro_id = $CdnProducer->createProducer($data);
            $detail = CdnProducer::getProById($pro_id);
            View::setVars(compact('detail'));
            View::pick('cdn_yff/proedit');
        }
    }
    
    /*
     *  应用接入配置-列表
     */
    public function authindexAction(){
        
    }

    


    public function testvideoAction() {//die('stop');
//        $time=time();
//        $signature = md5('cztv_inject_test_yf2'.'yf@@0721@cztvinject'.$time);
//        $url = 'http://test-payyun.cztv.com/cdn_yff/yunfanrt?timestamp='.$time.'&app_id=cztv_inject_test_yf2&signature='.$signature;
//        print_r($url);exit;
        
        //$url = 'http://dev-iyun.cztv.com/cdn_yff/task';
        //$url = 'http://test-iyun.cztv.com/cdn_yff/task';
        $time = time();
        $signature = md5('app_1'.'b065fc08ceabcff9b2d38f1d7bfc05fa'.$time);
        //$url ='http://api.newsxcms.com/cdn_yff/task?app_id=app_1&signature='.$signature.'&timestamp='.$time;
        $url = 'http://test-i.cztvcloud.com/cdn_yff/task?timestamp='.$time.'&app_id=app_1&signature='.$signature;
        //$url = 'https://iyun.cztv.com/cdn_yff/task?timestamp='.$time.'&app_id=app_1&signature='.$signature;

        $ar = array(
                'app_id'=>'app_1',
                'app_secret'=>md5('app_1'),
                'channel_id'=>1,
                'cdn_id'=>1,    //特指云帆文件视音频接口
                'title'=>'研发-测试视音频接口-1216003',
                'task_id'=>00001,//int 唯一 有就填没有就0
                'operation'=>1,//刷新接口固定值
                
                'fc_sub'=>array(
                    array(
                        'item_id'=>1,//int 唯一
                        'operation'=>1,//刷新接口固定值
                        'file_type'=>1,//3图片  4网页url
                        'source_path'=>'http://source.v.cztvcloud.yfcdn.net/video/xhw/vod/2016/12/05/e1872b1af5d2424d90035104a53aeb7a/e1872b1af5d2424d90035104a53aeb7a_h264_1000k_mp4.mp4',
                        'publish_path'=>'http://v1.cztvcloud.com/video/xhw/vod/2016/12/05/e1872b1af5d2424d90035104a53aeb7a/e1872b1af5d2424d90035104a53aeb7a_h264_1000k_mp4.mp4',
                        'md5'=>'',//md5(publish_path)
                        'file_size'=>0,//非空，可填0，不可填字符串'0'
                        'slice'=>'1',
                    ),
//                    array(
//                        'item_id'=>2,
//                        'operation'=>1,
//                        'file_type'=>1,
//                        'source_path'=>'http://video.xianghunet.com/video/201608/03/11474335-bbeb-482e-ff7f-f63ba6cbe9de/transcode_8ddaa2ee-d175-df71-fd14-0aa0505a.mp4',
//                        'publish_path'=>'http://yf.cloudvideo.cztv.com/video/201608/03/11474335-bbeb-482e-ff7f-f63ba6cbe9de/transcode_8ddaa2ee-d175-df71-fd14-0aa0505a.mp4',
//                        'md5'=>'',
//                        'file_size'=>'0',
//                    ),
                ),
            );
        
        
        $return = F::curlRequest($url,'post', $ar,TRUE,FALSE); //参数5 TRUE 为出外网
        print_r($return);exit;
    }

    public function testurlAction() {
        //die('stop');
        $time = time();
        $signature = md5('app_1'.'b065fc08ceabcff9b2d38f1d7bfc05fa'.$time);
        //$url ='http://api.newsxcms.com/cdn_yff/task?app_id=app_1&signature='.$signature.'&timestamp='.$time;
        $url = 'http://test-i.cztvcloud.com/cdn_yff/task?timestamp='.$time.'&app_id=app_1&signature='.$signature;
        //$url = 'https://iyun.cztv.com/cdn_yff/task?timestamp='.$time.'&app_id=app_1&signature='.$signature;

        $ar = array(
                'app_id'=>'app_1',
                'app_secret'=>md5('app_1'),
                'channel_id'=>1,
                'cdn_id'=>2,    //特指云帆文件刷新接口
                'title'=>'研发-测试刷新接口-12160011',
                'task_id'=>00001,//int 唯一 有就填没有就0
                'operation'=>1,//刷新接口固定值
                
                'fc_sub'=>array(
                    array(
                        'item_id'=>1,//int 唯一
                        'operation'=>1,//刷新接口固定值
                        'file_type'=>3,//3图片  4网页url
                        'source_path'=>'http://www.cztv.com/funs/uibase/videoplaybox_v2.js',//跟publish_path 一致
                        'publish_path'=>'http://www.cztv.com/funs/uibase/videoplaybox_v2.js',
                        'md5'=>'',//md5(publish_path)
                        'file_size'=>'0',//非空，可填0
                    ),
//                    array(
//                        'item_id'=>2,
//                        'operation'=>1,
//                        'file_type'=>4,
//                        'source_path'=>'http://img01.cztv.com/201304/02/jcl-test.html',
//                        'publish_path'=>'http://img01.cztv.com/201304/02/jcl-test.html',
//                        'md5'=>'',
//                        'file_size'=>'0',
//                    ),
                ),
            );
        //print_r($url);exit;
        
        $return = F::curlRequest($url,'post', $ar,TRUE,FALSE); //参数5 TRUE 为出外网
        print_r($return);exit;
    }
    
    public function testinsertAction() {
               
    //RedisIO::lPush($this->redis_cdn_yff_list_key,'test jcl');
    //$json = RedisIO::LPOP($this->redis_cdn_yff_list_key);print_r($json);exit;
    
    //die('stop');
        //try {
                $msgstr ='';
                $error_type = 0;
                $json = RedisIO::LPOP($this->redis_cdn_yff_list_key);
                print_r($json);
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
echo'-----';print_r($item);
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
                }else{
                    echo 'empty redis';exit;
                    sleep(4);
                }
//            }catch (Exception $ex) {
//                sleep(4);
//                echo "sleep(4)...\n";
//            }
    }
    
    
    //取最近86400秒内未分发的主任务&status=1
    public function pushAction(){
        die('stop');
       $main = CdnMainLog::getCdnMainLogByTimeFirst(time()-86400);print_r($main);
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
                        echo 'done';exit;
                }else{
                    echo 'main enpty';exit;
                    sleep(4);
                }
    }
        
    public function queueinsertAction() {die('stop');
        global $config;	
        $queueName = $config->memprefix  . $this->queue_cdn_yff_key;
        print_r($queueName);
                    //从队列取数据
                $data = $this->queue->getMessage($queueName);
                                var_dump($queueName);exit;
                if(!empty($data)){
                    print_r($data);
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
                }else{
                    echo'empty';exit;
                    
                }
    }
    
    public function backAction(){die('stop');
        global $config;	
        $queueName = $config->memprefix  . $this->queue_cdn_yff_key;
        
        $return ='{"username":"cztv_inject_test_yf2","password":"yf@@0721@cztvinject","memo1":"yf","result":[{"item_id":"124","operation":"publish","status":"finish","detail":""}]}';
        $data = json_decode($return,TRUE);
        $cdn_id = 1;
        $queue_data = array(
                    'cdn_id'=>$cdn_id,
                    'data'=>$data['result']
                );
        
        if($this->queue->sendMessage(json_encode($queue_data), $queueName)){
            echo 'sucess';exit;
        }else{
            echo 'fail';exit;
        }
    }

    public function insertAction() {
            die('stop');
//            $queueName = app_site()->memprefix  . $this->queue_cdn_yff_key;
//            $data = $this->queue->getMessage($queueName);
            $data = '{"cdn_id":2,"data":{"12":98}}';
            
            if(!empty($data)){
                $data_ar = json_decode($data,TRUE);
                $cdn_id = $data_ar['cdn_id'];
                switch ($cdn_id) {
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
            }else{
                sleep(4);
            }
    }
    
    /**
     * 手动刷新CDN
     */
    public function refreshAction() {
    	
    	if (Request::isPost()) {
    		$filePath = Request::get('filepath');
	    	$data = $this->sendData($filePath);
	    	$receive = F::cdnProxy("手动刷新",1,$data,Session::get('user')->channel_id);
    	}
    	
    	View::setVars(compact('receive'));
    }
    
    private function sendData($filepath) {
    	//$filepath = cdn_url('image', $filePath);
    	$fileType = 4;
    	$data[0] = array(
    			"item_id"	   => 1,
    			"operation"    => 1,
    			"file_type"    => $fileType,
    			"source_path"  => $filepath,
    			"publish_path" => $filepath,
    			"md5"          => md5($filepath),
    			"file_size"    => "0"
    	);
    	return $data;
    }
}
