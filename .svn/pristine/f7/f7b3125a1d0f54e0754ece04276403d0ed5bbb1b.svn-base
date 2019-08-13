<?php

/**+
 *  个人资料控制器
 *  Controller PersonalDataController
 *  @author     Zhangyichi
 *  @created    2015-9-22
 */

class PersonalDataController extends \BackendBaseController {

    public function indexAction() {
        $data=Session::get('user');
        $vitae = Vitae::getOneByAdmin($data->id);
        View::setVars(compact('data','vitae'));
    }

    public function modifyAction() {
        $messages=[];
        $data = Session::get('user');
        $vitae = Vitae::getOneByAdmin($data->id);
        if(Request::isPost()){
            $inputs = Request::getPost();
            $validator = Admin::changeValidator($inputs);
            if($validator->passes()) {
                $old_password = Hash::encrypt($inputs['old_password'], $data->salt);
                if ($old_password == $data->password) {
                    $inputs['new_password']=Hash::encrypt($inputs['new_password'], $data->salt);
                    $admin=Admin::getOne($data->id);
                    $admin->changePassword($inputs);
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('PasswordFalse');
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages','vitae'));
    }
//把图片上传分离出来
    public function modifyavatarAction(){
        $messages=[];
        $data = Session::get('user');
        $vitae = Vitae::getOneByAdmin($data->id);
        $inputs = Request::getPost();
        if($avatar = $this->validateAndUpload($messages)) {
            $inputs['avatar']=$avatar;
            $admin=Admin::getOne($data->id);
            $admin->changeAvatar($inputs);
            $data->avatar=$inputs['avatar'];
            $messages[] = Lang::_('success');
        }
        View::setMainView('layouts/add');
        view::pick('personal_data/modify');
        View::setVars(compact('messages','vitae'));
    }
    /*
     * 个人履历修改功能
     */
    public function modifyvitaeAction(){
        $messages=[];
        $inputs = Request::getPost();
        $arr = array();
        $index=0;
        foreach($inputs as $k => $v){
            if(preg_match('/^(start-time-)+/',$k)){
                $i=substr($k,11);
                $arr[$index]=array(
                    'start-time' => $inputs['start-time-'.$i],
                    'end-time' => $inputs['end-time-'.$i],
                    'location' => $inputs['location-'.$i],
                    'description' => $inputs['description-'.$i]
                );
                if($arr[$index]['start-time']=="" && $arr[$index]['end-time']=="" && $arr[$index]['location']==""){
                    unset($arr[$index]);
                    $index--;
                }
                $index++;
            }
        }
        if(!empty($arr)) {
            $inputs['experience'] = json_encode($arr);
        }
        $validator = Vitae::checkForm($inputs);
        if($validator->passes()){
            isset($inputs['recruit_time'])?:$inputs['recruit_time']='';
            $inputs['recruit_time']=strtotime($inputs['recruit_time']);
            $vitae=Vitae::getOneById($inputs['id']);
            $vitae->modifyVitae($inputs);
            $messages[] = Lang::_('success');
        }else{
            foreach($validator->messages()->all() as $msg){
                $messages[]=$msg;
            }
        }
        $vitae = Vitae::getOneByAdmin(Session::get('user')->id);
        View::setMainView('layouts/add');
        view::pick('personal_data/modify');
        View::setVars(compact('messages','vitae'));
    }

    /**
     * @param $messages
     * @return string
     */
    protected function validateAndUpload(&$messages) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/avatars');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('oldavatar');
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }


}