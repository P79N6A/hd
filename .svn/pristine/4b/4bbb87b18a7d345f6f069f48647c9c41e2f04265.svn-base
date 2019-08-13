<?php

use GenialCloud\Exceptions\DatabaseTransactionException;

class MeController extends InteractionBaseController {
    const FILE_NOT_UPLOAD = 4;

    protected function createTemplate() {
        $allows = [
            'Request' => [
                'get', 'getPost',
            ],
            'Input' => [
                'init', 'fetch', 'checked', 'selected',
            ],
        ];
        $page = Request::get('page', 'int', 1);
        if($page < 1) $page = 1;
        return new CZTVSmarty($this->domain_id, $this->channel_id, $allows, $page, []);
    }

    public function initialize() {
        parent::initialize();
        if(!Auth::user()) {
            redirect(Url::get('auth/login'));
        }
    }

    /**
     * 用户首页
     */
    public function indexAction() {
        $user = Auth::user();
        if(!$user){
            redirect(Url::get(''));
        }
        $this->runTemplate(Templates::TPL_MEMBER_INFO, compact('user'));
    }

    /**
     * 编辑信息
     */
    public function editAction() {
        $user = Auth::user();
        if(Request::isPost()){
            $input=Request::getPost();
            $return=$user->modifyUserInfo($input);
            if(!$return){
                redirect(Url::get(''));
            }
        }
        $this->runTemplate(Templates::TPL_MEMBER_EDIT, compact('user'));
    }

    /**
     * 我要爆料
     */
    public function tipOffAction() {
        $msg = "";
            $user = Auth::user();
        if ($this->request->isPost()) {
            $data = $this->request->getPost();            
            if ($this->isFileTypeValid()) {
                $uploadFile = '';
                $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'tipoff_file');
                if (!$isFileEmpty) {
                    $data['tipoff_file'] = $this->uploadFile($uploadFile);
                }
            }
            $data['channel_id'] = $user->channel_id;
            $data['user_id'] = $user->id;
            $data['username'] = $user->name;
            $data['ip'] = $this->get_real_ip();
            $data['client'] = 1;//1:web 2:app 3.wap
            $validator = Baoliao::makeValidator($data);
            if(!$validator->fails()) {
                $baoliao = new Baoliao();
                $baoliao->createBaoliao($data);
                if(isset($data['tipoff_file'])) {
                $attachdata['origin_name'] = $data['tipoff_file']['origin_name'];
                $attachdata['path'] = $data['tipoff_file']['path'];
                $attachdata['ext'] = $data['tipoff_file']['ext'];
                $attachdata['type'] = 2;
                $attachdata['baoliao_id'] = $baoliao->id;
                $attach = new BaoliaoAttachment();
                $attach->createAttachment($attachdata);
                }
                $msg[] = Lang::_('success');
            } else {
                $msg[] = Lang::_('error');
            }
        }
        $tipofflist = Baoliao::getBaoliaoByUser($user->id, $user->channel_id);
        if($tipofflist&&count($tipofflist->models)) {
            $tipoffdata = $tipofflist->models->toArray();
        }
        else {
            $tipoffdata = array();   
        }
        $messages = $msg;
        $this->runTemplate(Templates::TPL_MEMBER_TIP_OFF, compact('user', 'messages', 'tipoffdata'));
    }

    /**
     * 我的爆料列表
     */
    public function tipOffListAction() {
        $user = Auth::user();
        Baoliao::getBaoliaoByUser($user->id);
        $this->runTemplate(Templates::TPL_MEMBER_TIP_OFF_LIST, compact('user'));
    }

    /**
     * 用户评论列表
     */
    public function commentsAction() {
        $user = Auth::user();
        $page = Request::getQuery('page','int')?:1;
        $comment = Comment::getCommentsByUser($user->id,$page,50);
        $this->runTemplate(Templates::TPL_MEMBER_COMMENTS, compact('user','comment'));
    }


    /**
     * 获取用户IP地址
     */
    private function get_real_ip(){
        $ip=false;
        if(!empty($_SERVER["HTTP_CLIENT_IP"])){
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode (", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) { array_unshift($ips, $ip); $ip = FALSE; }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi ("^(10|172\.16|192\.168)\.", $ips[$i])) {
                $ip = $ips[$i];
                break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }


    private function isFileTypeValid() {
        if (Request::hasFiles(true)) {
            $files = Request::getUploadedFiles(true);
            foreach ($files as $file) {
                $fileType = $file->getRealType();
                return in_array($fileType, ['image/jpg', 'image/jpeg', 'image/gif', 'image/png']);
            }
        }
        return true;
    }

    private function isFileUploadEmpty(&$uploadFile, $name) {
        $uploadFile = '';
        if(Request::hasFiles()) {
            $files = Request::getUploadedFiles();
            foreach ($files as $file) {
                if ($file->getKey() == $name) {
                    $error = $file->getError();
                    if (!$error) {
                        $uploadFile = $file;
                    }
                    return $error == self::FILE_NOT_UPLOAD;
                }
            }
        }
        return true;
    }
    
    protected function uploadFile($file) {
        $ext = $file->getExtension();
        $path = Oss::uniqueUpload($ext, $file->getTempName(), Auth::user()->channel_id.'/baoliao');
        return array('origin_name'=>$file->getName(), 'path'=>$path, 'ext'=>$ext);

    }

}