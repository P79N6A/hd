<?php

use GenialCloud\Helper\IWC;

/**
 * @RoutePrefix("/user")
 */
class UserController extends ApiBaseController {

    static $public_actions = array('register', 'login', 'reset');

    public function initialize() {
        //parent::initialize();
        $action = $this->dispatcher->getActionName();
        if (!in_array(strtolower($action), self::$public_actions)) {
            $this->checkToken();
        }
    }

    /**
     * @Get('/')
     */
    public function registerAction() {
        $userid = new Userid();

        echo $userid->createUserid("dddd", "dcccc");
        exit;


        $uu = new User();
        echo $uu->createUploadAvatar("http://s.cimg.163.com/cnews/2016/5/3/20160503161344b50c9.jpg.670x270.jpg");

        exit;

    }

}