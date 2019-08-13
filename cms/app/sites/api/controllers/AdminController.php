<?php

use GenialCloud\Helper\IWC;

/**
 * @RoutePrefix("/admin")
 */
class AdminController extends ApiBaseController
{


    static $public_actions = array('login', 'channels', 'reset', 'validaitemobile', 'getchannelinfobymob', 'adminlist', 'searchmembers', 'getapiupdate','lista');//todo::

    public function initialize()
    {
        parent::initialize();
        $action = $this->dispatcher->getActionName();
        if (!in_array(strtolower($action), self::$public_actions)) {
            $this->checkToken();
        }
    }

    /**
     * @Get('/list')
     */
    public function adminlistAction() 
    {
		// 返回同一频道下的用户列表信息
    	$this->checkToken();
    	$admin = $this->user;
    	$channelId = $admin->channel_id;
    	if($admin != null && isset($channelId)) {
    		$page = $this->page;
    		$size = $this->per_page;
    		$values = Admin::apiGetAdmin($channelId, $page, $size);
    		$this->returnJson($values);
    	}
    	else{
    		$this->_json(array("admin_id"=>0), '404', 'Not Found');
    	}
    }
    
    /**
     * @Get('/searchmembers')
     */
    public function searchmembersAction() {
    	$contact = array();
    	if(Request::getQuery('keyword')){
    		$search['keyword']=Request::getQuery('keyword');
    	}else{
    		$search=array('keyword'=>'');
    	}
    	$departmentId = Request::getQuery('department') ?: "";
    	$channelId = Request::getQuery('channelId') ?: "1";
    	$condition = array(
    		'department' => $departmentId,
   			'contacts'   => -1,
    		'status'     => '',
    		'pinyin'     => '',
    	);
    	
    	$page = $this->page;
    	$size = $this->per_page;
    	$data = Admin::apiSearchMembers($search, $condition,$channelId, $contact, $size, $page);
    	$this->returnJson($data);
    }
    
    /**
     * @Get('/getapiupdate')
     */
    public function getapiupdateAction()
    {
    	// 返回同一频道下的用户列表信息
    	$this->checkToken();
    	$admin = $this->user;
    	$channelId = $admin->channel_id;
    	if($admin != null && isset($channelId)) {
    		$key = Admin::UPDATETIME_REDIS_KEY.$channelId;
    		
    		$updateTime = RedisIO::get($key);
    		$data = array();
    		$data["status"] = 1;
    		$data["key"] = "member_update_time";
    		$data["value"] = $updateTime;
    		$this->returnJson($data);
    	}
    	else{
    		$this->_json(array("admin_id"=>0), '404', 'Not Found');
    	}
    }
    
    /**
     * 返回json格式
     * @param unknown $values
     */
    private function returnJson($values) {
    	if(isset($values) && !empty($values)) {
    		$this->_json($values);
    	}
    	else {
    		$this->_json(array("admin_id"=>$channelId), '404', 'Not Found');
    	}
    }

    /**
     * @Post('/login')
     */
    public function loginAction()
    {
        echo 1;die();
        $channel_id = Request::getPost('channel_id');
        $mobile = Request::getPost('mobile');
        $password = Request::getPost('password');
        // 检验必须字段
        if (!$mobile && !$password) {
            $this->_json(array("admin_id"=>0), '404', 'Not Found');
        }
        if ($mobile) {
            $admin = Admin::apiGetAdminByMobile($channel_id, $mobile);
            if (!$admin) {
                $this->_json(array("admin_id"=>0), '401', '手机号码不存在');
            }
            if (!Hash::check($admin['password'], $password, $admin['salt'])) {
                $this->_json(array("admin_id"=>0), '402', '账号密码错误');
            }
            $id = $admin['id'];
        }
        $info = Admin::apiGetAdminInfo($id);
        $this->_json([
            'token' => $this->createToken($info['id'], $info),
            'admin' => [
                'admin_id' => $info['id'],
                'mobile' => $info['mobile'],
                'name' => $info['name'],
            ]
        ]);
    }

    /**
     * @Get('/channels')
     */
    public function channelsAction()
    {
        $mobile = Request::getQuery('mobile');
        $admins = Admin::getAdminByMobile($mobile);
        $channelids = array();
        foreach ($admins->models as $model) {
            array_push($channelids, $model->channel_id);
        }
        if (!count($channelids)) {
            $this->_json([], '401', '手机号码不存在');
        }
        $channel_results = array();
        if (in_array(0, $channelids)) {
            $channel_results[] = array(
                'channel_id' => '0',
                'channel_name' => "系统",
            );
        }
        $channels = Channel::query()
            ->where("id in(" . implode(',', $channelids) . ")")
            ->paginate(50, 'Pagination');
        foreach ($channels->models as $model) {
            $channel_results[] = array(
                'channel_id' => $model->id,
                'channel_name' => $model->name,
                'channel_logo' => cdn_url('image', $model->channel_logo),
                'channel_watermark' => cdn_url('image', $model->watermark),
            );
        }
        $this->_json($channel_results);
    }

    /**
     * 手机号码验证
     * @Post('/validate_mobile')
     */
    public function validaiteMobileAction()
    {
        $type = Request::getPost('type');
        $mobile = Request::getPost('mobile');
        if ($type == 'reset') {
            $user = Users::apiGetUserByMobile($this->channel_id, $mobile);
            if (!$user) {
                $this->_json([], 404, '无效的手机号');
            }
            if (VerifyCode::send($mobile)) {
                $this->_json([]);
            } else {
                $this->_json([], 404, '发送失败');
            }
        } else if ($type == 'register') {
            if (VerifyCode::send($mobile)) {
                $this->_json([]);
            } else {
                $this->_json([], 404, '发送失败');
            }
        }
    }

    /**
     * 重置密码
     * @Post('/reset')
     */
    public function resetAction()
    {
        $password = Request::getPost('password');
        $code = Request::getPost('code');
        $mobile = Request::getPost('mobile');
        if (!VerifyCode::validate($mobile, $code)) {
            $this->_json([], 202, '验证码错误');
        }
        if (strlen($password) < 6) {
            $this->_json([], 201, '密码不得少于 6 位');
        }
        $data = Users::apiRestPassword($this->channel_id, $mobile, $password);
        if ($data) {
            $this->_json([]);
        } else {
            $this->_json([], 202, 'Not Found');
        }
    }

    /*
     * @desc 获取主播的频道消息
     * @param $mob 主播的手机号码
     * @data 2016-6-14
     * @author 冯固
     * */
    public function getChannelInfoByMobAction()
    {
        $mob = Request::getQuery('mobile');
        $admin_info = Admin::apiGetAdminInfoByMob($mob);
        if ($admin_info && $admin_info['channel_id']) {
            $channel_id = $admin_info['channel_id'];
            $channel_info = Channel::getOneChannel($channel_id);
            $ret_data = array('id' => $channel_id, 'name' => $channel_info->name);
            $this->_json($ret_data);
        } else {
            $this->_json([], 201, 'Not Found');
        }
    }
    
    
    
}