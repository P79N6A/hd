<?php

/**
 *  频道切换
 *  controller switchingChannel
 * @author     Zhangyichi
 * @created    2015-10-21
 *
 */
class SwitchingChannelController extends \BackendBaseController {

    public function indexAction() {
        $messages = [];
        if($inputs = Request::getPost()) {
            $user = Admin::findByMobile(Session::get('user')->mobile, $inputs['channel_id']);
            if(!$user){
                $messages[] = Lang::_('error');
            }
            $password = Hash::encrypt($inputs['password'], $user->salt);
            if($password == $user->password) {
                Auth::login($user);
                Admin::setLastTime(Auth::user()->id, time());
                $messages[] = Lang::_('success');
            } else {
                $messages[] = Lang::_('PasswordFalse');
            }
        }
        $data = Admin::findAllByMobile(Session::get('user')->mobile);
        $listChannel = Channel::listChannel();
        View::setVars(compact('data', 'messages','listChannel'));
    }

}