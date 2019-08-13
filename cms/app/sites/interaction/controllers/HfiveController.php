<?php
use GenialCloud\Auth\Signature;
class HfiveController extends \PublishBaseController {

	public $appid = 'wxb80bcbf68dd70ce6';
	public $app_secret = 'f320b683b1689e443915842d3a18d055';
	public $cookie_key = 'H5_USERINFO';
	public $cache_key = 'H5_CACHE';


	//鹿晗生日H5
	public function luhanAction(){
		$free = Request::get('free');
		if($free != "iblue"){
			$code = Request::get('code');
			if(!$code){
		        $redirect_url = 'http://pyun.cztv.com/hfive/luhan?';
		        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=';
		        $url .= $this->appid;
		        $url .= '&redirect_uri=';
		        $url .= urlencode($redirect_url);
		        $url .= '&response_type=code&scope=snsapi_base&state=';
		        $url .= time();
		        $url .= '#wechat_redirect';
	        	header('Location:' . $url);
	    	}
			//TODO 代码疑惑 $url未初始化值?
		    $userInfo = json_decode(file_get_contents($url), true);
		    $openid = isset($userInfo['openid']) ? $userInfo['openid'] : '';

	        //是否展现正常内容
		    $showFlag = false;
		    if ($code) {
		        //获取access_token
		        $urlAccessToken = 'http://192.168.138.36:8080/applet-inner-api/inner/getPublicAccessToken';
		        $jsonAccessToken = F::curlRequest($urlAccessToken);
		        $dataAccessToken = json_decode($jsonAccessToken, true);
		        if ($dataAccessToken['code'] == 200) {
		            $accessToken = $dataAccessToken['data']['accessToken'];
		        } else {
                    error_log('获取accesstoken失败:'.$dataAccessToken['msg']);
		            exit;
		        }
		        
		        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';

				//TODO 统一外网服务接口
				//$h5_userinfo_json = json_decode(file_get_contents($url), true);
				$content = F::curlRequest($url);
				$h5_userinfo_json = json_decode($content, true);
		        if ($h5_userinfo_json['subscribe'] == 1) {
		            $showFlag = true;
		        }else if($h5_userinfo_json['errcode'] == 42001||$h5_userinfo_json['errcode'] == 40001){
                    $urlAccessToken = 'http://192.168.138.36:8080/applet-inner-api/inner/getPublicAccessToken';
                    $jsonAccessToken = F::curlRequest($urlAccessToken);
                    $dataAccessToken = json_decode($jsonAccessToken, true);
                    if ($dataAccessToken['code'] == 200) {
                        $accessToken = $dataAccessToken['data']['accessToken'];
                    } else {
                        error_log('获取accesstoken失败:'.$dataAccessToken['msg']);
                        exit;
                    }
	                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
					//TODO 统一外网服务接口
//					$h5_userinfo_json2 = json_decode(file_get_contents($url), true);
		        	$h5_userinfo_json2 = json_decode(F::curlRequest($url), true);
		        	if ($h5_userinfo_json2['subscribe'] == 1) {
		            	$showFlag = true;
		            }
		        }
		    }

		}else{
			$showFlag = true;
		}

	    $dy_time = MemcacheIO::get("cztv_time_out");
	    if($dy_time){
	    	$dy_time = time();
	    	MemcacheIO::set("cztv_time_out", $dy_time, false, 600);
	    }

	    header("Cache-Control:no-cache");
        View::setVar('showFlag',$showFlag);
        View::setVar('dy_time',$dy_time);
    }

    //跑男投票
    public function toupiaoAction(){
		$code = Request::get('code');
		if(!$code){
	        $redirect_url = 'http://pyun.cztv.com/hfive/toupiao';
	        $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=';
	        $url .= $this->appid;
	        $url .= '&redirect_uri=';
	        $url .= urlencode($redirect_url);
	        $url .= '&response_type=code&scope=snsapi_base&state=';
	        $url .= time();
	        $url .= '#wechat_redirect';
        	header('Location:' . $url);
    	}
    	$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
		//TODO 统一外网服务接口
//	    $userInfo = json_decode(file_get_contents($url), true);
	    $userInfo = json_decode(F::curlRequest($url), true);
	    $openid = isset($userInfo['openid']) ? $userInfo['openid'] : '';
        if($openid){
			$this->session->set('wxuseropenid', $openid);
        }

	    //是否展现正常内容
	    $showFlag = false;
	    if ($code || $openid) {
	        //获取access_token
            $urlAccessToken = 'http://192.168.138.36:8080/applet-inner-api/inner/getPublicAccessToken';
            $jsonAccessToken = F::curlRequest($urlAccessToken);
            $dataAccessToken = json_decode($jsonAccessToken, true);
            if ($dataAccessToken['code'] == 200) {
                $accessToken = $dataAccessToken['data']['accessToken'];
            } else {
                error_log('获取accesstoken失败:'.$dataAccessToken['msg']);
                $accessToken = false;
            }

			//TODO 统一外网服务接口
	        $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
//	        $h5_userinfo_json = json_decode(file_get_contents($url), true);
	        $h5_userinfo_json = json_decode(F::curlRequest($url), true);
	        if ($h5_userinfo_json['subscribe'] == 1) {
	            $showFlag = true;
	        }else if($h5_userinfo_json['errcode'] == 42001||$h5_userinfo_json['errcode'] == 40001){
				//TODO 统一外网服务接口
                $urlAccessToken = 'http://192.168.138.36:8080/applet-inner-api/inner/getPublicAccessToken';
                $jsonAccessToken = F::curlRequest($urlAccessToken);
                $dataAccessToken = json_decode($jsonAccessToken, true);
                if ($dataAccessToken['code'] == 200) {
                    $accessToken = $dataAccessToken['data']['accessToken'];
                } else {
                    error_log('获取accesstoken失败:'.$dataAccessToken['msg']);
                    $accessToken = false;
                }
				//TODO 统一外网服务接口
                $url = 'https://api.weixin.qq.com/cgi-bin/user/info?access_token=' . $accessToken . '&openid=' . $openid . '&lang=zh_CN';
	        	$h5_userinfo_json2 = json_decode(F::curlRequest($url), true);
	        	if ($h5_userinfo_json2['subscribe'] == 1) {
	            	$showFlag = true;
	            }
	        }
	    }
        

	    $dy_time = MemcacheIO::get("cztv_time_out");
	    if($dy_time){
	    	$dy_time = time();
	    	MemcacheIO::set("cztv_time_out", $dy_time, false, 600);
	    }


	    $this->showPhase();
	    header("Cache-Control:no-cache");
        View::setVar('showFlag',$showFlag);
        View::setVar('fengxiang',$this->_getShareUrl());
        View::setVar('openid',$openid);
        View::setVar('dy_time',$dy_time);
    }

    //跑男投票备份
    public function toupiaobackupAction(){
    	$openid = rand(100,10000);
		$this->session->set('wxuseropenid', $openid);
        if(!$openid){

            $code = Request::get('code');
            if(!$code){
                $redirect_url = 'http://pyun.cztv.com/runman';
                $url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=';
                $url .= $this->appid;
                $url .= '&redirect_uri=';
                $url .= urlencode($redirect_url);
                $url .= '&response_type=code&scope=snsapi_base&state=';
                $url .= time();
                $url .= '#wechat_redirect';
                header('Location:' . $url);
            }
			//TODO 统一外网服务接口
            $url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . $this->appid . '&secret=' . $this->app_secret . '&code=' . $code . '&grant_type=authorization_code';
//            $userInfo = json_decode(file_get_contents($url), true);
            $userInfo = json_decode(F::curlRequest($url), true);
            $openid = isset($userInfo['openid']) ? $userInfo['openid'] : '';
            if($openid){
				Session::set('wxuseropenid', $openid);
            }
        
        }
        
	    $this->showPhase();
	    $showFlag = true;
	    $dy_time = MemcacheIO::get("cztv_time_out");
	    if($dy_time){
	    	$dy_time = time();
	    	MemcacheIO::set("cztv_time_out", $dy_time, false, 600);
	    }
	    header("Cache-Control:no-cache");
        View::setVar('showFlag',$showFlag);
        View::setVar('fengxiang',$this->_getShareUrl());
        View::setVar('openid',$openid);
        View::setVar('dy_time',$dy_time);
        $this->view->render('hfive','toupiao');
    }

    protected function showPhase(){
        $topGuest = RedisIO::get('H5:toupiao:topGuest');
        if(!$topGuest){
            $topGuest = RunningGuest::getTopFive();
            RedisIO::set('H5:toupiao:topGuest',serialize($topGuest));
            RedisIO::expire('H5:toupiao:topGuest', 30);
        }

        $phases = RedisIO::get('H5:toupiao:phases');
        if(!$phases){
            $phases = RunningMan::findAllWithGuest(1);
            RedisIO::set('H5:toupiao:phases',serialize($phases));
            RedisIO::expire('H5:toupiao:phases', 600);
        }

    
    	View::setVar('topGuest',unserialize($topGuest));//总榜数据
    	View::setVar('phases',unserialize($phases));//节目数据
    }


    //请求抽奖
    public function winAction(){
		$openid = $this->session->get('wxuseropenid');;
    	if(!$openid) $this->_json([],401,'openid not found!');
    	$resp = $this->curlWin($openid);
    	if($callback = Request::get('callback')) {
            echo htmlspecialchars($callback)."({$resp});";
        }else{
        	echo $resp;
        }
    }

    protected function curlWin($openid){
    	$url="https://pyun.cztv.com:81/wx_lottery/draw/19?";
    	$app_secret = '533536ec9ac8665b17238c3453c5049d';
    	$ch = curl_init();
    	$params = array(
    		'app_id'=>'776d0a3681142ed9fa4af34c9fa757b3', 
    		'client_id'=>$openid."ddddddd",
    		'timestamp'=>time(), 
    		'token'=>$openid,
    	);
    	$params = $this->createSignature($params,$app_secret);
    	$url .= Signature::buildQuery($params);
    	curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }


    //请求投票
    public function voteAction(){
    	$guest_id = (int)Request::get("id");
    	$_token = Request::get("_token");

		$openid = $this->session->get('wxuseropenid');;
    	if(!$openid) $this->_json([],401,'openid not found!');
    	$key = "runningMan:".$openid;

    	//中奖投票
    	if($_token){
    		//验证token是否被使用过
    		$redis_key = "token:{$_token}"; 
    		if(RedisIO::get($redis_key)) $this->_json(false,401,'token been used');

    		//获取中奖信息
    		$contact = LotteryContacts::getByToken($_token);
    		if(!$contact) {
                $this->_json([],-401,'token wrong!');
            }
            if($contact->prize_level == 6){
            	$count = 10;
            }elseif($contact->prize_level == 7){
            	$count = 5;
            }else{
            	$count = 0;
            }
            // 添加中奖步数
            $rtArr = RunningGuest::vote($guest_id,$count);
            // 设置token过期时间
            RedisIO::set($redis_key,1);
    		RedisIO::expire($key, 86400*300);
            $this->_json($rtArr,200,'success');
    	}

    	//每日投票
    	if(!RedisIO::get($key)){
    		//投票
    		$rtArr = RunningGuest::vote($guest_id);
    		//一天只能投一次
    		$time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time();
    		RedisIO::set($key,1);
    		RedisIO::expire($key, $time);
    		$this->_json($rtArr,200,'success');
    	}else{
    		$this->_json([],401,'user already vote!');
    	}
    	

    }

    public function getGuestAction(){
    	$guests = RunningGuest::getTopThree();
    	foreach ($guests as $key => $value) {
    		$guests[$key]['guest_img'] = cdn_url("image",$value["guest_img"]);
    	}
    	$this->_json($guests,200);
    }

    public function contactsAction(){
    	error_reporting(E_ALL ^ E_NOTICE);
    	try {
            $inputs = Request::get();
            foreach(['real_name', 'mobile', 'address'] as $key) {
                $inputs[$key] = urldecode($inputs[$key]);
            }
            /**
             * @var \Illuminate\Validation\Validator $validator
             */
            $validator = Validator::make($inputs, [
                '_token' => 'required',
                'mobile' => 'required',
                'address' => 'required',
                'real_name' => 'required'
            ]);
            if($validator->fails()) {
                $this->_json([],-1,'params required');
            }
            //校验手机验证码
            $mobile = $inputs['mobile'];

            DB::begin();
            $contact = LotteryContacts::getByToken($inputs['_token']);

            //非法的数据
            if(!$contact) {
                DB::rollback();
                $this->_json([],-2,'invalid info');
            }
            //校验中奖联系人数据
            if($contact->mobile) {
                DB::rollback();
                $this->_json([],-3,'info has been set');
            }


            $inputs['name'] = $inputs['real_name'];
            unset($inputs['real_name']);
       		foreach(['mobile', 'name', 'address'] as $key) {
            	$contact->$key = $inputs[$key];
            }
            $contact->updated_at = time();
            $contact->status = 1;
            if(!$contact->save()) {
                DB::rollback();
                $this->_json([],-4,'retry');
            }
            DB::commit();

            LotteryContacts::saveRedisWinners(
                $contact->id,
                $contact->lottery_group_id,
                $mobile,
                $inputs['name'],
                $contact->prize_name,
                $contact->prize_level,
                $contact->status
            );
            

            //优化中奖搜索查询
            
            $msg = 'ok';
        } catch(LotteryException $e) {
            $msg = $e->getMessage();
            $this->_json([],0,$msg);
        }
        $this->_json([],1,$msg);
    }

    


    //生成签名
    protected function createSignature($params,$app_secret){
    	$timestamp = (string) $params['timestamp'];
        $params['key'] = substr($app_secret,$timestamp[strlen($timestamp) - 1]);
        if(isset($params['client_id'])){
            $params['client_id'] = urlencode($params['client_id']);
        }
        ksort($params);
        $signature = Signature::buildQuery($params);
        $params['signature'] = sha1(base64_encode($signature));
        unset($params['key']);
        return $params;
    }






    protected function defaultDomainCheck($host) {
        $this->host = $host;
        $this->domain = Domains::tplByDomainAndType($host, 'interaction');
        if(!$this->domain) {
            abort(403);
        }
        $this->domain_id = $this->domain->id;
        $this->channel_id = $this->domain->channel_id;
        return true;
    }


    protected function _getShareUrl(){
    	return Request::getScheme() . "://" . Request::getHttpHost() . Request::getURI();
    }

    protected function _json($data, $code = 200, $msg = "success") {
        header('Content-type: application/json');
        $resp = json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);

        if($callback = Request::get('callback')) {
            echo htmlspecialchars($callback)."({$resp});";
        } else {
            echo $resp;
        }
        exit;
    }


}