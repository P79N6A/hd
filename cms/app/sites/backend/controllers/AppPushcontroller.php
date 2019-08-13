<?php
/**
 *  apppush controller 推送控制器
 *  @author    xy
 *  @created    2015-12-9
 *  ispush : 1 即时推送  2 定时推送
 */ 

class AppPushController extends \BackendBaseController {

    public function indexAction() {
        $data = AppPush::findAll();
        View::setVars(compact('data'));
    }

    public function pushAction() {
        $data_id = Request::get('id');
        $listlive = '';
        $listradio = '';
        $appdata = Data::findFirst($data_id);
        if(empty($appdata)) {
        	abort(404);
        }
        $messages = $this->savePush();
        $listlive = Stations::getStationsByType(1);
        $listradio = Stations::getStationsByType(2);

        View::setVars(compact('appdata','messages', 'listlive','listradio'));
    }

    public function savePush() {
    	$data = [];
    	$messgages = [];
    	$data_arr = [];
    	$data_msg = [];
        $url = [];
        $channel_id = Session::get('user')->channel_id;
    	$model = new AppPush();
        if (Request::isPost()) {
        	$data = Request::getPost();
        	$push_type = $data['push_type'];
        	$ispush = $data['ispush'];
        	$data_id = $data['data_id'];
        	if($ispush == 2){
				$strtime = strtotime($data['push_time']);
				$data['push_time'] = $strtime;
				if($strtime < time()){
					$messages[] = lang::_('发送时间有误');
                    exit;
				}
			}
            $data['push_time'] = !empty($data['push_time']) ? $data['push_time'] : time();
            if($data_id > 0) {
                $appdata = Data::findFirst($data_id);
                if($channel_id > 0) {
                    $domains = Domains::findChannelDomains($channel_id);
                    foreach ($domains as $key => $value) {
                        SmartyData::init($channel_id, $value['id']);
                        preg_match('/^http:/', $value['name'],$doname);
                        $domain_name = $doname ? $value['name'] : 'http://'.$value['name']; 
                        $url[] = $domain_name.SmartyData::url(['data_id'=>$data_id],101);
                    }
                }
                $data_arr = array('data_id'=>$data_id,'title'=>$data['title'],'image'=>$appdata->thumb,'url'=>$url,'ispush'=>$ispush);
            }
			if($push_type=='new'){
				$data_msg = array('type'=>$appdata->type);
			}else if($push_type=='live'){
				$data_msg = array('station_id'=>$data['live'],$type=>$push_type);
			}else if($push_type=='radio'){
				$data_msg = array('station_id'=>$data['radio'],$type=>$push_type);
			}
			$msg = array_merge($data_arr,$data_msg);
			$data['content'] = json_encode($msg);
			$data['status'] = ($ispush == 1 ? 0 : 4);
			$validator = AppPush::makeValidator($data);
            if(!$validator->fails()) {
            	if(count($data['terminal']) == 2)
            	    $data['terminal'] = 3;
                if($data['type'] == 'cdn') {
                    $cdn_type = implode(',', $data['cdn_type']);
                    $data['cdn_type'] = $cdn_type;
                }
            	$data['created_at'] = time();
				if($model->save($data)) {
                    //入库后进行推送操作
                    $this->appCdnPush($model->id);
					$messages[] = Lang::_('success');
				}
		    }
		    else{
				foreach($validator->messages()->all() as $msg) {
                    $messages[] = $msg;
                }
			}
			return $messages;
        }
            
    }

    public function appCdnPush($id) {
        $data = AppPush::findFirst($id);
        if($data->type == 'app') {

        }
        if($data->type == 'cdn') {
            $cdn = explode(',', $data->cdn_type);
            $content = json_decode($data->content,true);
            $url = $content['url'];
            foreach ($cdn as $key => $value) {
                if($value == 'kuaiwang' && $content['ispush'] == 1) {
                    $rs = $this->cdnFastPush($url);
                    if($rs->status == 1) {
                        $data->update(['status' => 1,'remark' => $rs->info]);
                    }
                    else {
                        $data->update(['status' => 2,'remark' => $rs->info]);
                    }
                }
                
            }

        }
    }

    public function cdnFastPush($url) {
        $key = 'cdn.fast_web.yao';
        /**
         * @var \GenialCloud\Network\Services\FastWebCDN $cdn
         */
        $cdn = $this->getDI()->getShared($key);
        $token = MemcacheIO::get($key);
        if(!$token) {
            $token = $cdn->requestAccessToken()['token'];
            MemcacheIO::set($key, $token, 43200);
        }
        if($token) {
            $rs = $cdn->addPurge($token, [], $url);
            if($rs->status  == 1) {

            } else {
                MemcacheIO::delete($key);
            }
            return $rs;
        }     
        
    }

}