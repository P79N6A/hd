<?php

/**
 *  员工管理
 *  model admin
 *  @author     Haiquan Zhang
 *  @created    2015-9-11
 *
 *  status 0:删除/1:正常/2:未激活
 */
class AdminController extends \YearBaseController {

    public $ignore = [
        'login', 'reset', 'forget', 'logout', 'verifycode','captcha','index'
    ];

    public function indexAction() {
        $data = AdminYear::findAll();
        View::setVars(compact('data'));
    }

    public function searchAction() {
        $data=Request::getQuery();
        if(Request::getQuery('pinyin','string')||Request::getQuery('status','string')||Request::getQuery('department','string')){
            $condition['pinyin']=Request::getQuery('pinyin','string')?:'';
            $condition['status']=Request::getQuery('status','string')?:'';
            $condition['department']=Request::getQuery('department','string')?:'';
        }else{
            $condition=isset($data['condition'])?json_decode($data['condition'],true):array('pinyin'=>'','status'=>'','department'=>'');
        }
        if(Request::getQuery('keyword','string')){
            $search['keyword']=Request::getQuery('keyword','string');
        }else{
            $search=array('keyword'=>'');
        }
        $data = AdminYear::search($search,$condition);
        View::pick('admin/index');
        View::setVars(compact('data','condition','search'));
    }

    // 检查入口频道
    private function checkChannel(){
        if(!Request::has('id')){
            abort(403, Lang::_('invalid channel'));
        }
        $tag = Request::get('id');
        $channel = ChannelYear::getOneByTag($tag);
        if($tag!='system' &&  !$channel){
//            abort(403, Lang::_('invalid channel'));
        }
//        die();
        Cookie::set('channelTag', $channel ? $channel->tag : 'system', time() + 86400*365);
        Cookie::send();
        View::setVars(compact('channel'));
        return $channel;
    }

    // 登录
    public function loginAction() {
        $channel = $this->checkChannel();
        View::setMainView('layouts/login');
//        die();
        $message = [];
        $channel_id = $channel? $channel->id: 0;
        $mobile = Request::getPost('mobile');
//        $limit = AdminYear::loginTimes(3);
        if(Request::isPost()) {
            // 3次失败，启用验证码
//            if($limit && !$this->checkCaptcha()) {
//                $message[] = Lang::_('captcha error');
//            } else {
                Auth::setAuthModel("AdminYear");
                if(!empty($msg = $this->checkMessage())) {
                    $message = $msg;
                } else if(Auth::attempt(['mobile' => Request::getPost('mobile'), 'password' => Request::getPost('password'), 'channel_id' => $channel_id])) {
                    if(1 == intval(Auth::user()->status)) {
                        Session::set('channel', $channel);
                        AdminYear::setLastTime(Auth::user()->id, time());
                        redirect(Url::get(''));
                    }
                    $message[] = Lang::_('account frozen');
                } else {
                    AdminYear::addLoginTimes($mobile);
                    $message[] = Lang::_('login faild');
                    if(AdminYear::loginTimes(3)){
                        redirect(Url::get(''));
                    }
                }
//            }
        }
        View::setVars(compact('message'));
    }

    private function checkMessage() {
        $messages = [];
        $open = D::getSetting('is.login.message');
        if($open == 1) {
            if(!VerifyCode::validate(Request::getPost('mobile'), Request::getPost('verifycode'))) {
                $messages[] = Lang::_('VericodeFailed');
            }
        }
        return $messages;
    }

    private function checkCaptcha() {
        $captchaCode = Request::getPost('captcha');
        if($captchaCode===null){
            return true;
        }
        $captcha = new XmasCaptcha();
        return $captcha->check($captchaCode);
    }

    /**
     * 验证码
     */
    public function captchaAction() {
        $captcha = new XmasCaptcha();
        $captcha->generate();
        exit;
    }

    public function logoutAction() {
        Auth::logout();
        $id = Session::get('channel') ? Session::get('channel')->tag : 'system';
        redirect(Url::get('admin/login', ['id' => $id]));
    }

    /**
     * 重置密码
     */
    public function resetAction() {
        $channel = $this->checkChannel();
        $channel_id = $channel? $channel->id: 0;
        $messages = [];
        if($inputs = Request::getPost()) {
            $validator = AdminYear::resetValidator($inputs);
            if($validator->passes()) {
                if(VerifyCode::validate($inputs['mobile'], $inputs['verifycode']) && $admin = AdminYear::findByMobile($inputs['mobile'], $channel_id)) {
                    $inputs['new_password'] = Hash::encrypt($inputs['new_password'], $admin->salt);
                    $update = $admin->update([
                        'password' => $inputs['new_password'],
                        'status' => 1
                    ]);
                    if($update) {
                        VerifyCode::remove($inputs['mobile']);
                        $messages[] = Lang::_('reset success');
                    } else {
                        $messages[] = Lang::_('error');
                    }
                } else {
                    $messages[] = Lang::_('VericodeFailed');
                }
            } else {
                foreach($validator->messages()->all() as $msg) {
                    $messages[] = $msg;
                }
            }
        }
        View::setMainView('layouts/login');
        View::setVars(compact('messages'));
    }

    /**
     * 短信验证
     */
    public function verifycodeAction() {
        $mobile = Request::getPost('mobile');
        $admin = AdminYear::findByMobile($mobile);
        $return = 400;
        
        if ($admin && VerifyCode::send($mobile)) {
            $return = 200;
        }/*
        $model = AppPush::pushMobile($mobile);
        if($admin) {
            if(VerifyCode::send($mobile)) {
                $return = 200;
                AppPush::approve($model->id,1);
            }   
        }
        if($return == 400) {
            AppPush::approve($model->id,2);
        }*/
        echo $return;
        exit;
    }

    /**
     * 查看员工
     */
    public function detailAction() {
        $adminId = (int) Request::get('id');
        $channel_id = Session::get('user')->channel_id;
        $admin = AdminYear::getOne($adminId);
        if (!$admin) {
            abort(404);
        }
        if ($admin->channel_id != $channel_id) {
            $this->accessDenied();
        }
        $adminExt = AdminExtYear::ext($adminId);
        list($assignElement, $assignRoleElement) = AuthAssignYear::getAuth($admin);
        $roleids = AuthAssignYear::getRoleId($admin);
        $role = [];
        $listRoles = AuthRole::roleList();
        if (!empty($roleids)) {
            foreach ($roleids as $id) {
                $role[] = $listRoles[$id];
            }
        }
        $parents = array();
        if ($adminExt) {
            if ($adminExt->department) {
                $department = DepartmentYear::findById($adminExt->department);
                if ($department)
                    $parents = $department->getParents();
            }
            $duty = DutyYear::getOne($adminExt->duty);
        }
        View::setMainView('layouts/add');
        View::setVars(compact('admin', 'role', 'duty', 'parents', 'assignElement','assignRoleElement'));
    }

    /**
     * 添加员工
     */
    public function addAction() {
        if (Request::isPost()) {
            $messages = [];
            $input = Request::getPost();
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                if ($this->validateAndUpload($messages)) {
                    $input['avatar'] = $this->validateAndUpload($messages);
                }
            }
            $validator = AdminYear::makeValidator($input);
            if (!$validator->fails()) {
                $admin = new Admin();
                $messages = $admin->createAdmin($input);
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $duty = DutyYear::dutyList();
        View::setMainView('layouts/add');
        View::setVars(compact('duty','messages'));
    }
    
    /**
     * 编辑员工
     */
    public function editAction() {
        $admin_id = Request::get('id', 'int');
        $channel_id = Session::get('user')->channel_id;
        $is_admin = Session::get('user')->is_admin;
        $admin = AdminYear::getOne($admin_id);
        if(!$admin) {
            abort(404);
        }
        // 禁止非频道管理员修改权限
        if($is_admin != 1) {
            $this->accessDenied();
        }
        if($admin->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (Request::isPost()) {
            DB::begin();
            try {
                $messages = [];
                $input = Request::getPost();
                if (Request::getUploadedFiles()[0]->getError() == 0) {
                    if ($this->validateAndUpload($messages)) {
                        $input['avatar'] = $this->validateAndUpload($messages);
                    }
                }
                $validator = AdminYear::makeValidator($input, $admin->id);
                if (!$validator->fails()) {
                    $update = [
                        'mobile'=>$input['mobile'],
                        'name' => $input['name'],
                        'avatar' => $input['avatar'],
                    ];
                    // 创建频道为0的员工，默认就是is_admin =1
                    if($channel_id == 0) {
                        $update['is_admin'] = 1;
                    }
                    if (!empty($input['password'])) {
                        $update['password'] = Hash::encrypt($input['password'], $admin->salt);
                    }
                    if ($admin->update($update)) {
                        if(isset($input['roleid'])){
                            AuthAssignYear::resetRole($admin,(int)$input['roleid']);
                        }
                        if(!empty($input['element_id'])){
                            AuthAssignYear::resetElement($admin, $input['element_id']);
                        }
                        AdminExtYear::resetExt($admin, $input);
                        DB::commit();
                        $messages[] = Lang::_('success');
                    } else {
                        DB::rollback();
                        $messages[] = Lang::_('error');
                    }
                } else {
                    $messages = $validator->messages()->all();
                    DB::rollback();
                }
            } catch (\Exception $e) {
                DB::rollback();
                $messages[] = Lang::_('error');
            }
        }
        $adminExt = AdminExtYear::ext($admin_id);
        list($assignElement,$assignRoleElement) = AuthAssignYear::getAuth($admin);
        $roleids = AuthAssignYear::getRoleId($admin);
        $role = AuthRole::roleList();
        $duty = DutyYear::dutyList();
        $parents = array();
        if ($adminExt) {
            if ($adminExt->department) {
                $department = DepartmentYear::findById($adminExt->department);
                if ($department)
                    $parents = $department->getParents();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('admin','messages', 'role', 'roleids','duty', 'parents', 'adminExt','assignElement','assignRoleElement'));
    }

    public function deleteAction() {
        $adminId = (int)Request::get('id');
        $data = AdminYear::getOne($adminId);
        if (!empty($data)
            && $data->id !== Session::get("user")->id
            && $data->channel_id==Session::get("user")->channel_id
            ) {
            if($data->status!=0){
                $data->deleteAdmin();
            }else{
                $data->activeAdmin();
            }
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }
        exit;
    }


    protected function validateAndUpload(&$messages) {
        $path = '';
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if (!$error) {
                $ext = $file->getExtension();
                if (in_array($ext, ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload($ext, $file->getTempName(), Auth::user()->channel_id.'/logos');
                    $attachmodel = new AttachmentCommon();
                    $attachid = $attachmodel->createAttach(array(
                        'origin_name' => $file->getName(),
                        'name' => $file->getName(),
                        'type' => 2, //1:视频 2:图片 0:未知
                        'path' => $path,
                        'ext' => $file->getType(),
                    ));
                } else {
                    $messages[] = Lang::_('please upload valid header image');
                }
            } elseif ($error == 4) {
                $path = Request::getPost('thumb', null, '');
                if (!$path) {
                    $messages[] = Lang::_('please choose upload header image');
                }
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload header image');
        }
        return $path;
    }

     /*
     * 批量生成用户扩展数据
     */
    public function adminextAction() {
        $testadmins = AdminYear::find();
        foreach ($testadmins as $aaa) {
                $aaa->modifyAdmin(array(
                    'name' => $aaa->name,
                    'dept_id' => 0,
                    'duty_id' => 0,
                    ));
        }
        exit;
    }

}
