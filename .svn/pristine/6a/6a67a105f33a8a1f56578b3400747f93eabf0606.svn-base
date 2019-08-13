<?php

class UsersController extends \BackendBaseController {


    const SEARCH_TYPE_USERNAME = 1;
    const SEARCH_TYPE_NICKNAME = 2;
    const SEARCH_TYPE_EMAIL = 4;
    const SEARCH_TYPE_MOBILE = 8;

    private function encodePassword($password, $salt) {
        return Hash::encrypt($password, $salt);
    }

    public function indexAction() {

        $page = $this->request->getQuery('page', 'int');
        $channel_id = Session::get('user')->channel_id;
        $key = "usersmanage201609_".$channel_id."_".$page;

        $datajson = RedisIO::get($key);

        $datapage_render = RedisIO::get($key."_render");
        $datapage_count = RedisIO::get($key."_count");


        if(!$datajson) {
            $data = Users::getAll();
            $datars = array();
            if ($data && isset($data->models)) $models = $data->models;
            if (!empty($models)) :
                foreach ($models as $v) :
                    $temp = array(
                        'id' => $v->uid,
                        'avatar' => $v->avatar,
                        'username' => $v->username,
                        'mobile' => $v->mobile,
                        'email' => $v->email,
                        'nickname' => $v->nickname,
                        'gender' => $v->gender,
                        'credits' => $v->credits,
                        'status' => $v->status
                    );
                    $datars[] = $temp;
                endforeach;
            endif;

            $datapage_render = $data->pagination->render();
            $datapage_count = $data->count;

            RedisIO::set($key."_render", $datapage_render);
            RedisIO::expire($key."_render", 60);
            RedisIO::set($key."_count", $datapage_count);
            RedisIO::expire($key."_count", 60);

            RedisIO::set($key, json_encode($datars));
            RedisIO::expire($key, 60);
        }
        else {
            $datars = json_decode($datajson, true);
        }



        View::setVars(compact('datars', 'datapage_render', 'datapage_count'));
    }

    public function infoAction() {
        View::setMainView('layouts/add');
        $id = $this->request->getQuery('id');
        $user = Users::findFirst(array(
            'uid=:uid:',
            'bind' => array('uid' => $id)
        ));
        View::setVars(compact('user'));
    }

    public function editAction() {
        $messages=[];
        $channel_id = Session::get('user')->channel_id;
        $id = Request::getQuery();
        $user = Users::findOne($id['id'],$channel_id);
        if($data = Request::getPost()){
            $user->updated_at=time();
            if($user->save()){
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('error');
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('user','messages'));
    }

    public function forbiddenAction() {
        $channel_id = Session::get('user')->channel_id;
        $id=Request::getQuery();
        $return=Users::forbiddenUsers($id['id'],$channel_id);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    //以下为未整理
    public function credit_transactionsAction() {
        $uid = $this->request->getQuery('id');
        $channel_id = Session::get("user")->channel_id;
        $data = CreditTransactions::getTransactionsByUid($uid, $channel_id);
        $listtype = CreditRules::listType();
        $listtradertype = CreditTransactions::listTraderType();
        View::setVars(compact('data', 'listtype', 'listtradertype'));
    }

    private function isValidMobile($mobile) {
        return preg_match('/^(1[0-9]{10})$/', $mobile);
    }

    public function deleteAction() {
        $channel_id = Session::get('user')->channel_id;
        $id=Request::getQuery();
        $return=Users::deleteUsers($id['id'],$channel_id);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }


    public function searchAction() {
        $username = Request::get('username');
        $nickname = Request::get('nickname');
        $email = Request::get('email');
        $mobile = Request::get('mobile');
        $channel_id = Session::get('user')->channel_id;

        $searchtype = 0;
        if($username) $searchtype |= self::SEARCH_TYPE_USERNAME;
        if($nickname) $searchtype |= self::SEARCH_TYPE_NICKNAME;
        if($email) {
            $searchtype = 0;
            if(!F::isemail($email)) $email="";
        }
        if($mobile) {
            $searchtype = 0;
            if(!F::ismobile($mobile)) $mobile="";
        }


        if($searchtype) {
            $q = array('username'=>$username, 'nickname'=>$nickname);
            $p = Request::get('p', 'int', 1);
            $d = $channel_id;
            $solr = $this->getDI()->getShared('solr.user');
            $data = SolrEngine::searchUser($solr, $d, $q, $searchtype, $p);
        }
        else {
            $data['rs'] = array();
        }

        if($email||$mobile) {
            $key = "usersmanagesearch".$email."_".$mobile;
            $datajson = MemcacheIO::get($key);
        if(!$datajson) {

            $parameters = array();
            $parameters['conditions'] = "channel_id=".$channel_id." and ";
            if($email) {
                $parameters['conditions'] .= " loginname = '{$email}' and partition_by = ".Users::getHashTable($email);
            }
            else {
                $parameters['conditions'] .= " loginname = '{$mobile}' and partition_by = ".Users::getHashTable($mobile);
            }
            $userlogin = Userlogin::findFirst($parameters);
            $datars = array();
            if($userlogin->uid) {

            $zgltvuser = Users::query()->where("uid = {$userlogin->uid} and partition_by = ".Users::getHashTable($userlogin->uid))->first();
            $temp = array(
                'id' => $zgltvuser->uid,
                'avatar' => $zgltvuser->avatar,
                'username' => $zgltvuser->username,
                'mobile' => $zgltvuser->mobile,
                'email' => $zgltvuser->email,
                'nickname' => $zgltvuser->nickname,
                'gender' => $zgltvuser->gender,
                'credits' => $zgltvuser->credits,
                'status' => $zgltvuser->status
              );
            $datars[] = $temp;
            }

            MemcacheIO::set($key, json_encode($datars), 10 * 60);
        }
        else {
            $datars = json_decode($datajson, true);
        }
            $data['rs'] = $datars;
        }



        View::setVars(compact('data'));
    }

    protected function validateAndUpload(&$messages,$i) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[$i];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/logos');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('oldlogo');

            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }
}