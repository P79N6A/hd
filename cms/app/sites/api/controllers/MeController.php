<?php

/**
 * @RoutePrefix("/me")
 */
class MeController extends ApiBaseController {

    /**
     * 初始化，校验令牌
     */
    public function initialize() {
        parent::initialize();
        $this->checkToken();
    }

    /**
     * @Post('/avatar')
     */
    public function avatarAction() {
        if(!Request::hasFiles()) {
            $this->_json([], 403, 'Forbidden');
        }
        $file = Request::getUploadedFiles()[0];
        $error = $file->getError();
        if($error) {
            $this->_json([], 403, 'Forbidden');
        }
        $ext = $file->getExtension();
        if(!in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
            $this->_json([], 403, 'Forbidden');
        }
        $path = Oss::uniqueUpload($ext, $file->getTempName(), 'avatars');
        $user = Users::getOne($this->user->uid);
        $image_url = Oss::url($path);
        if($user && $user->save(['avatar' => $image_url])) {
            $this->_json(['image_url' => $image_url]);
        }
        $this->_json([], 403, 'Forbidden');
    }

    /**
     * @Post('/password')
     */
    public function passwordAction() {
        $oldPassword = Request::getQuery('old_password');
        $password = Request::getQuery('password');
        $user = Users::getOne($this->user->uid);
        if(!$user) {
            $this->_json([], '403', 'Forbidden');
        }
        $check = Hash::check($user->password, $oldPassword, $user->salt);
        if(!$check) {
            $this->_json([], '403', 'Forbidden');
        }
        if(!$user->save(['password' => Hash::encrypt($password, $user->salt)])) {
            $this->_json([], '403', 'Forbidden');
        }
        $this->createToken();
        $this->_json([]);
    }

    /**
     * 签名，例如这个人很懒，什么都没留下
     * @Post('/signature')
     */
    public function signatureAction() {
        $signature = Request::getQuery('signature');
        if($signature == null) {
            $this->_json([], 403, 'Forbidden');
        }
        $user = Users::getOne($this->user->uid);
        if(!$user->save(['signature' => $signature])) {
            $this->_json([], '403', 'Forbidden');
        }
        $this->_json([]);
    }

    /**
     * 获取用户短信息列表
     * @Get('/messages')
     */
    public function messageAction() {

    }

    /**
     * 获取用户短信息详情
     * @Get('/messages/{id:[0-9]+}')
     */
    public function viewMessageAction() {

    }


}