<?php

class FrontendBaseController extends BaseController {

    private static $FRONTEND_USER = 'frontend_user';

    public function initialize() {
        parent::initialize();
        $this->testLogin();
    }

    private function testLogin() {
        $action_filter_array = ['login', 'qq', 'weixin', 'sina', 'reset', 'validate_code'];
        $action_name = $this->router->getActionName();
        if (!in_array($action_name, $action_filter_array)) {
            if (!$this->testLoginSession()) {
                redirect(Url::get('userauth/login'));
            }
        }
    }

    private function testLoginSession() {
        return Session::has(FrontendBaseController::$FRONTEND_USER) && Session::get(FrontendBaseController::$FRONTEND_USER);
    }

    protected function saveUser($user) {
        Session::set(FrontendBaseController::$FRONTEND_USER, $user);
    }

    protected function clearUser() {
        Session::set(FrontendBaseController::$FRONTEND_USER, null);
    }
}