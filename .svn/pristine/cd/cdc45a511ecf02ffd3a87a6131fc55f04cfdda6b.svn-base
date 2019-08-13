<?php

/**
 * Created by PhpStorm.
 * User: wangdonghao
 * Date: 2016/4/29
 * Time: 9:28
 */
class IndexController extends SsoBaseController {
    public function indexAction() {
        require_once APP_PATH . 'libraries/Plugin/Util.php';
        $next_action = Plugin_Util::removeXss($this->request->get('next_action'));
        /**
         * 如果来源是http://sso.cztv.com/?next_action=http://shop.cztv.com/，则不跳转
         * shop商城有独立的登录页，但是有可能从注册页直接跳转到sso的登录页
         */
        if (isset($next_action) && strripos($next_action, 'shop.cztv.com')) {
            $userModel = new User();
            $userModel->LoginOutSsoCookie();//清cookie
        }
        if (!empty($_COOKIE['loginnamecookie'])) {
            $this->view->loginnamecookie = $_COOKIE['loginnamecookie'];
        }
        $this->view->next_action = urlencode($next_action);
        $this->view->pick('login/index');
    }

    public function testrouteAction() {
        RedisIO::delete("comment*");
        echo $this->dispatcher->getParam("mobile");
        exit;
    }

}