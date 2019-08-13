<?php

/**
 * 用户中心-用户信息展示
 */
class SettingController extends MyBaseController {
    private $userModel = null;
    private $currentUserinfo = array();
    protected static $lang = 'zh';

    public function initialize() {
        disableBrowserCache();

        $this->userModel = new User();
        $this->currentUserinfo = $this->userModel->getCurrentUserinfo(static::$lang);
        if (empty($this->currentUserinfo)) {
            redirect($this->_ssoUrl . '?next_action=' . urlencode(getCurrentUrl()));
        } else {
            $this->view->verifyCode = $this->userModel->getVerifyCode(array(
                $this->currentUserinfo ['uid'],
                $this->currentUserinfo ['username'],
                $this->currentUserinfo ['uid']
            ));
        }
    }

    /**
     * @descc 个人中心-账号信息-基本资料
     * @version 2015-06-19
     */
    public function indexAction() {
        $this->view->pick('setting/index');
    }

    /**
     * @desc 个人中心-账号信息-头像信息
     * @version 2015-06-19
     */
    public function iconAction() {
        $this->view->pick('setting/icon');
    }


    /**
     * @desc 个人中心-账号信息-密码信息
     * @version 2015-06-19
     */
    public function passwordAction() {
        $this->view->pick('setting/setpassword');
    }

}