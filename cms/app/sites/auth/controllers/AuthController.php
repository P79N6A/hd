<?php

use GenialCloud\Exceptions\DatabaseTransactionException;

class AuthController extends InteractionBaseController
{


    protected function createTemplate()
    {
        $allows = [
            'Request' => [
                'get', 'getPost',
            ],
            'Input' => [
                'init', 'fetch', 'checked', 'selected',
            ],
        ];
        return new CZTVSmarty($this->domain_id, $this->channel_id, $allows, 1, []);
    }

    public function logoutAction()
    {
        if (Auth::check()) {
            Auth::logout();
        }
        redirect(Url::get('auth/login'));
    }

    /**s
     * 登录
     */
    public function loginAction()
    {
        if (Auth::check()) {
            redirect(Url::get('me'));
        }
        $messages = [];
        $inputs = [];
        if (Request::isPost()) {
            $inputs = Request::getPost();
            $v = Users::makeValidator('login', $inputs);
            if (!$v->fails()) {
                if (Auth::attempt(['mobile' => $inputs['mobile'], 'password' => $inputs['password'], 'channel_id' => $this->channel_id])) {
                    if (Auth::user()->status) {
                        redirect(Url::get('me'));
                    } else {
                        Auth::logout();
                        $message[] = Lang::_('account frozen');
                    }
                }
                if (empty($messages)) {
                    $messages[] = Lang::_('wrong username or password');
                }
            } else {
                $msgBag = $v->messages();
                foreach ($msgBag->all() as $msg) {
                    $messages[] = $msg;
                }
            }
        }
        $this->runTemplate(Templates::TPL_LOGIN, compact('messages', 'inputs'));
    }

    /**
     * 注册
     */
    public function registerAction()
    {
        $messages = [];
        $inputs = [];
        if (Request::isPost()) {
            $inputs = Request::getPost();
            $inputs['channel_id'] = $this->channel_id;
            $v = Users::makeValidator('register', $inputs);
            if (!$v->fails()) {
                if ($id = Users::register($inputs, $this->channel_id)) {
                    Auth::login(Users::findFirst($id));
                    redirect(Url::get('me'));
                } else {
                    $messages[] = Lang::_('register failed');
                }
            } else {
                $msgBag = $v->messages();
                foreach ($msgBag->all() as $msg) {
                    $messages[] = $msg;
                }
            }
        }
        $this->runTemplate(Templates::TPL_REGISTER, compact('messages', 'inputs'));
    }

    /**
     * 重置密码
     */
    public function resetAction()
    {
        $messages = [];
        $inputs = [];
        if (Request::isPost()) {
            $inputs = Request::getPost();
            $v = Users::makeValidator('reset', $inputs);
            if (!$v->fails()) {
                $r = Users::resetPassword($inputs, $this->channel_id);
                if ($r) {
                    redirect(Url::get('auth/login'));
                } elseif ($r === 0) {
                    $messages[] = Lang::_('user not exists');
                } else {
                    $messages[] = Lang::_('password reset failed');
                }
            } else {
                $msgBag = $v->messages();
                foreach ($msgBag->all() as $msg) {
                    $messages[] = $msg;
                }
            }
        }
        $this->runTemplate(Templates::TPL_RESET, compact('messages', 'inputs'));
    }

    public function connectAction()
    {
        if (!$social = Session::get('user_socials')) {
            redirect(Url::get('auth/login'));
        }
        $messages = [];
        $inputs = [];
        if (Request::isPost()) {
            $inputs = Request::getPost();
            $v = Users::makeValidator('connect', $inputs);
            if (!$v->fails()) {
                try {
                    DB::begin();
                    $user = Users::findByMobile($inputs['mobile'], $this->channel_id);
                    $userSocial = null;
                    if (!$user) {
                        $inputs['password'] = str_random();
                        $inputs['name'] = $inputs['mobile'];
                        $user_id = Users::register($inputs, $this->channel_id);
                    } else {
                        $user_id = $user->id;
                        $userSocial = UserSocials::getByUidAndType($user_id, $social['type']);
                    }
                    if (!$userSocial) {
                        $userSocial = new UserSocials;
                        $social['created_at'] = time();
                    }
                    $social['updated_at'] = time();
                    $social['user_id'] = $user_id;
                    $social['channel_id'] = $this->channel_id;
                    if (!$userSocial->save($social)) {
                        //throw new DatabaseTransactionException('social save error.');
                        throw new Exception("social save error.");

                    }
                    DB::commit();
                    Auth::login($user);
                    Session::remove('user_socials');
                    redirect(Url::get('auth/login'));
                } catch (DatabaseTransactionException $e) {
                    DB::rollback();
                    $messages[] = Lang::_('failed');
                }
            } else {
                $msgBag = $v->messages();
                foreach ($msgBag->all() as $msg) {
                    $messages[] = $msg;
                }
            }
        }
        $this->runTemplate(Templates::TPL_CONNECT, compact('messages', 'inputs'));
    }

    public function verifyCodeAction()
    {
        $msg = Lang::_('invalid request');
        $code = 403;
        if (Request::isPost()) {
            $inputs = Request::getPost();
            $inputs['channel_id'] = $this->channel_id;
            $v = Users::makeValidator('verify_code', $inputs);
            if (!$v->fails()) {
                if (VerifyCode::send($inputs['mobile'])) {
                    $code = 200;
                    $msg = 'OK';
                } else {
                    $msg = Lang::_('send verify code failed');
                }
            } else {
                $msgBag = $v->messages();
                foreach ($msgBag->all() as $msg) {
                }
            }
        }
        $this->jsonp(compact('code', 'msg'));
    }

    public function infoAction()
    {
        $user = Auth::user();
        if (!$user) {
            $user = [];
        } else {
            $user = $user->toarray();
            unset($user['password']);
            unset($user['salt']);
        }
        $this->jsonp(compact('user'));
    }

    public function commentAction()
    {
        $data_id = Request::getQuery('data_id');
        $data = Data::getMediaByDataId($data_id);
        $comment = array();
        if (isset($data[1]->comment_type) && $data[1]->comment_type != 1) {
            $comment = Comment::getCommentsByData($data_id, Comment::ACCEPT);
            if ($data[1]->comment_type != 3) {
                $comment = array_merge($comment, Comment::getCommentsByData($data_id, Comment::UNCHACKED));
            }
            for ($i = 0; $i < count($comment); $i++) {
                $user = Users::findOne($comment[$i]['user_id'], $comment[$i]['channel_id']);
                $comment[$i]['avatar'] = $user->avatar;
                $comment[$i]['create_at'] = date('Y-m-d H:i:s', $comment[$i]['create_at']);
            }
        }
        $this->jsonp($comment);
    }


    public function sendcommentAction()
    {
        $user = Auth::user();
        if (!$user) {
            $this->jsonp(array('403'));//未登入
        }
        if (Request::getQuery('content')) {
            $input = array();
            $input['content'] = Request::getQuery('content');
            $input['data_id'] = Request::getQuery('data_id');
            $user = $user->toarray();
            $input['channel_id'] = $user['channel_id'];
            $input['user_id'] = $user['id'];
            $input['username'] = $user['name'];
            $input['create_at'] = time();
            $input['status'] = Comment::UNCHACKED;
            $input['partition_by'] = date('Y', time());
            $input['client'] = isset($input['client']) ? $input['client'] : 1;
            $input['ip'] = $this->get_real_ip();
            $comment = new Comment();
            $return = $comment->createComment($input);
            if ($return) {
                $this->jsonp(array('200'));
            }
        }
        $this->jsonp(array('404'));
    }

    /*
     * user:fenggu
     * date:2016/4/6
     * time:08:48
     * 提交专题评论
     * */
    public function sendSpecCommentAction()
    {
        if (Request::getQuery('content') && Request::getQuery('data_id')) {
            $data_id = Request::getQuery('data_id');
            if ($this->checkDataIdExists($data_id) === false) {
                $this->jsonp(array('ret' => 0, 'tip' => '评论主题不存在'));
            }

            if (strlen(Request::getQuery('content')) > 450) {
                $this->jsonp(array('ret' => 0, 'tip' => '评论文字不能超过150个中文字符'));
            }
            $ip = $this->get_real_ip();
            $client = Request::getQuery('client') ? Request::getQuery('client') : 1;
            if ($this->checkSubmitCount($data_id, $client, $ip) === false) {
                $this->jsonp(array('ret' => 0, 'tip' => '防刷机制屏蔽本次提交'));
            }
            $comment = new Comment();
            $input = array();
            $input['content'] = Request::getQuery('content');
            $input['channel_id'] = Request::getQuery('channel_id');
            $input['user_id'] = Request::getQuery('user_id') ? Request::getQuery('user_id') : 0;//用户ID
            $input['username'] = Request::getQuery('username') ? Request::getQuery('username') : '';//用户名
            $input['data_id'] = $data_id;//容器ID
            $input['father_id'] = Request::getQuery('father_id') ? Request::getQuery('father_id') : 0;
            $input['create_at'] = time();
            $input['status'] = Comment::ACCEPT;
            $input['likes'] = 0;
            $input['down'] = 0;
            $input['domain'] = Request::getQuery('domain') ? Request::getQuery('domain') : '';
            $input['client'] = $client;
            $input['ip'] = $ip;
            $input['location'] = '';
            $input['partition_by'] = date('Y', time());
            $input['nickname'] = Request::getQuery('nickname') ? Request::getQuery('nickname') : '';
            $input['avart'] = Request::getQuery('avart') ? Request::getQuery('avart') : '';
            $input['browersinfo'] = Request::getQuery('browersinfo') ? Request::getQuery('browersinfo') : '';
            $input['auditerid'] = 0;
            $input['aduit_at'] = 0;
            $input['audit_memo'] = '';
            $input['isspeccomment'] = 1;
            $insertid = $comment->createComment($input, true);
            if ($insertid > 0) {
                $input['id'] = $insertid;
                $cfg = SpecComment::getOneById($input['data_id']);
                Redisio::set(D::redisKey('speccommitem', $insertid), json_encode($input));
                Redisio::set(D::rediskey('speccomcfg', $input['data_id']), json_encode($cfg));
                $this->jsonp(array('ret' => 1, 'tip' => '添加成功'));
            }
        }
        $this->jsonp(array('ret' => 0, 'tip' => '参数不完整'));
    }

    /*
     * user:fenggu
     * date:2016-4-6
     * time:09:32
     * desc:获取主题的评论配置信息
     * */
    public function getSpecCfgAction()
    {
        if (Request::getQuery('themeid')) {
            $themeid = Request::getQuery('themeid');
            $ret = SpecComment::getOneById($themeid);
            Redisio::set(D::rediskey('speccomcfg', $themeid), json_encode($ret));
            $this->jsonp($ret);
        }
        $this->jsonp(array('404'));
    }

    /*
     *user:fenggu
     *date:2016-04-06
     *time:11-04
     *desc:获取主题评论条数
     * @param:$num:获取的数量;$sort:排序方式；$themeid:主题ID
     * */

    public function getSpecCommentListAction()
    {
        if (Request::getQuery('data_id') && Request::getQuery('channel_id')) {
            $num = Request::getQuery('num', 'int', 5);
            $sort = Request::getQuery('sort', 'string', 'DESC');
            $themeid = Request::getQuery('data_id');
            $channel_id = Request::getQuery('channel_id');
            $page = Request::getQuery('page', 'int', 1);
            $columns = Request::getQuery('cols', 'string', '*');
            $key = D::redisKey('speccomcfg', $themeid);
            if (RedisIO::exists($key)) {
                $arrcfg = json_decode(Redisio::get($key), true);
                if (intval($arrcfg['publishway']) === 1) {
                    //先发后审
                    $status = Comment::ACCEPT;

                } elseif (intval($arrcfg['publishway']) === 2) {
                    //先审后发
                    $status = Comment::UNCHACKED;
                }
            }
            $itemids = Comment::getItemidsByData($status, $sort, $num, $themeid, $channel_id, $page);
            $ret = array();
            foreach ($itemids as $itemid) {
                if (Redisio::exists(D::redisKey('speccommitem', $itemid)) === true) {

                    $item = Redisio::get(D::redisKey('speccommitem', $itemid));
                    if (empty($item)) {
                        $item = Comment::getItemById($itemid);
                        Redisio::set(D::redisKey('speccommitem', $itemid), json_encode($item));
                    }
                    $ret[] = json_decode($item, true);
                }
            }
            $this->jsonp($ret);
        }
        $this->jsonp(array());
    }


    private function get_real_ip()
    {
        $ip = false;
        if (!empty($_SERVER["HTTP_CLIENT_IP"])) {
            $ip = $_SERVER["HTTP_CLIENT_IP"];
        }
        if (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ips = explode(", ", $_SERVER['HTTP_X_FORWARDED_FOR']);
            if ($ip) {
                array_unshift($ips, $ip);
                $ip = FALSE;
            }
            for ($i = 0; $i < count($ips); $i++) {
                if (!eregi("^(10|172\.16|192\.168)\.", $ips[$i])) {
                    $ip = $ips[$i];
                    break;
                }
            }
        }
        return ($ip ? $ip : $_SERVER['REMOTE_ADDR']);
    }


    /*
   * user:fenggu
   * date:2016-4-6
   * time:22:03
   * desc:根据评论配置数据验证是否满足防刷时间
   * */

    private function checkSubmitCount($data_id, $tertype, $ip)
    {

        $_cfg = Redisio::get(D::redisKey('speccomcfg', $data_id));
        $lastpushtime = Comment::lastitemtime($data_id, $tertype, $ip);
        if ($_cfg)
            $cfg = json_decode($_cfg, true);
        $timeout = $lastpushtime + intval($cfg['interval']);
        if ($cfg['prevent_action'] > 0 && $cfg['interval'] > 0 && $lastpushtime > 0 && $timeout > time()) {
            return false;
        }
        return true;
    }


    /*
     * user:fenggu
     * date:2016-4-7
     * time:01:40
     * desc:检查user_id的有效性     *
     * */
    private function checkDataIdExists($data_id)
    {
        if (RedisIO::exists(D::redisKey('speccomcfg', $data_id)) === false) {
            $ret = SpecComment::getOneById($data_id);
            if (count($ret) > 0) {
                Redisio::set(D::rediskey('speccomcfg', $data_id), json_encode($ret));
            } else {
                return false;
            }
        }
        return true;
    }


    /*
     * user:fenggu
     * date:2016-4-11
     * time:16:01
     * desc:大歌神活动报名
     * */
    public function activitySignupAction()
    {
        if (Request::getQuery('mobile') &&
            Request::getQuery('code') &&
            Request::getQuery('actid') &&
            Request::getQuery('channelid') &&
            Request::getQuery('ex_token') &&
            Request::getQuery('ex_hash')
        ) {
            $mobile = Request::getQuery('mobile');//手机号码
            $smscode = Request::getQuery('code');//短信code
            $activity_id = Request::getQuery('actid'); //活动ID
            $channel_id = Request::getQuery('channelid');
            $token = Request::getQuery('ex_token');
            $hash = Request::getQuery('ex_hash');
            /*验证手机号码是否已经报名过*/
//            if (!ActivitySignup::valMobileIsEmpty($channel_id, $activity_id, $mobile)) {
//                $this->jsonp(array('errno' => 201, 'tip' => '已报名过', 'success' => 0));
//            }
//            if (!VerifyCode::validate($mobile, $smscode)) {
//                $this->jsonp(array('errno' => 202, 'tip' => '手机验证码不正确', 'success' => 0));
//            }

            /*获取扩展字段信息*/
            $extkey = D::redisKey('actsignupextfields', join('_', array($channel_id, $activity_id)));
            if (RedisIO::exists($extkey)) {
                $str_ext_fields = RedisIO::get($extkey);
                $arr_ext_fields = json_decode($str_ext_fields, true);
            } else {
                $arr_ext_fields = ActivityExtModel::getExtFiledsNameListByActIDAndChannelId($channel_id, $activity_id);
                $str_ext_fields = json_encode($arr_ext_fields); //字段
                RedisIO::set($extkey, $str_ext_fields);
            }
            $ext_def_value_key = D::redisKey('actsignupextdefvalue', join('_', array($channel_id, $activity_id))); //默认值
            if (Redisio::exists($ext_def_value_key)) {
                $arr_defvalues = json_decode(RedisIO::get($ext_def_value_key), true);
            } else {
                $arr_defvalues = ActivityExtModel::getExtFiledsDefValue($channel_id, $activity_id);
                RedisIO::set($ext_def_value_key, json_encode($arr_defvalues));
            }
            $data_id = 0;
            /*插入视频结束*/
            $arr_ext_fieldvalues = $this->generateExValues($arr_ext_fields, $arr_defvalues);
            $signup_data['activity_id'] = $activity_id;
            $signup_data['channel_id'] = $channel_id;
            $signup_data['mobile'] = $mobile;
            $signup_data['name'] = '';
            $signup_data['user_id'] = 0;
            $signup_data['user_name'] = '';
            $signup_data['create_at'] = time();
            $signup_data['status'] = '-1';
            $signup_data['ext_fields'] = $str_ext_fields;
            $signup_data['ext_values'] = json_encode($arr_ext_fieldvalues);
            $model = new ActivitySignup();
            $id = $model->saveGetId($signup_data);
            if ($id) {
                $mobtokenkey = D::redisKey('mobtoken', $token);
                $arr = array();
                if (RedisIO::exists($mobtokenkey)) {
                    $arr = json_decode(Redisio::get($mobtokenkey), true);
                }
                $arr['signupid'] = $id;
                RedisIO::set($mobtokenkey, json_encode($arr));

                //视频上传的地址如果提前送达处理 ex_video_url和ugc_status值都是从云帆调用接口获取到的。
                if (array_key_exists('ex_video_url', $arr) &&
                    !empty($arr['ex_video_url']) &&
                    array_key_exists('ugc_status') &&
                    !empty($arr['ugc_status'])
                )
                    ActivitySignup::UpdateSignupData($id, '0', array('ex_vediourl' => $arr['ex_video_url']));

                $this->jsonp(array('errno' => 0, 'tip' => '成功', 'success' => 1));
            }
            $this->jsonp(array('errno' => 202, 'tip' => '保存失败', 'success' => 0));
        }
        $this->jsonp(array('errno' => 400, 'tip' => '参数错误', 'success' => 0));
    }

    private function generateExValues($exFields, $defFields)
    {
        $arr_ext_fieldvalues = array();
        foreach ($exFields as $field) {
            $arr_ext_fieldvalues[$field] = Request::getQuery($field) ? Request::getQuery($field) : $defFields[$field];
        }
        $arr_ext_fieldvalues['ex_hash'] = Request::getQuery('ex_hash');
        $arr_ext_fieldvalues['ex_token'] = Request::getQuery('ex_token');
        //$arr_ext_fieldvalues['ex_vediourl'] = $this->generateYFVideoUrl(Request::getQuery('ex_hash'));//YF生成一个新的url
        return $arr_ext_fieldvalues;
    }


    private function generateYFVideoUrl($hash)
    {
        $cdnHOST = 'http://yf.v.cztv.com';
        $path = "/cztv/vod/" . date('Y/m/d/', time()) . $hash . "/h264_000k_mp4.mp4";
        return $cdnHOST . $path;
    }


    /*
     * @desc 大歌神生成一个用户token+时间值的MD5加密值
     * @return string json对象。
     *
     * */
    public function gernalHashAction()
    {
        if ($token = Request::getQuery('token')) {
            $key = D::redisKey('mobtoken', $token);
            $hash = md5($token . time());
            if (!RedisIO::exists($key)) {
                $_v['hash'] = $hash;
            } else {
                $_v = json_decode(RedisIO::get($key));
                $_v['hash'] = $hash;
            }
            RedisIO::set($key, json_encode($_v));
            $this->jsonp(array('errno' => 0, 'tip' => '成功', 'success' => 1, 'hash' => $hash));
        }
        $this->jsonp(array('errno' => 400, 'tip' => '参数错误', 'success' => 0));
    }

    protected function QkQueeHandel()
    {
        while (RedisIO::lSize('QKQuee') > 0) {
            $strQuee = Redis::rPop('QKQuee');
            $arr = json_decode($strQuee, true);
            $vid = $arr['vid'];             //视频id
            $token = $arr['token'];         //token
            $signupid = $arr['singupid'];   //报名id
            $ret = callqk($vid);
            if ($ret['vediourl']) {
                //取到数据值
                $qk_path = $ret['vediourl'];
                $data_id = QKViedioUpdate($ret);
                $ex_data = array('token' => $token, 'vediourl' => $qk_path, 'data_id' => $data_id);
                $status = 0;
                UpdateSignupModel($signupid, $status, $ex_data);    //更新报名数据表
                $mobtokenkey = D::redisKey('mobtoken');
                $arr = json_decode(RedisIO::get($mobtokenkey), true);
                $arr['vedioid'] = $vid;
                $arr['vediourl'] = $qk_path;
                $arr['data_id'] = $data_id;
                RedisIO::set($mobtokenkey, json_encode(json_encode($arr)));
            } else {
                RedisIO::lPush('QKQuee', $strQuee);
            }
        }
    }

    /*
     * user:fenggu
     * date:2016-4-11
     * time:19:10
     * desc:获取手机号码验证码,用于报名
     * */
    public function getActitvitySingupSmsAction()
    {
        if (Request::getQuery('token') && Request::getQuery('mobile')) {
            $token = D::redisKey('mobtoken', Request::getQuery('token'));
            $mobile = Request::getQuery('mobile');
            if (!RedisIO::exists($token)) {
                $arr = json_decode(Redisio::get($token), true);
                $arr['mob'] = $mobile;
                RedisIO::set($token, json_encode($arr));
            }
            if (VerifyCode::sendDGS($mobile)) {
                $this->jsonp(array('errno' => 0, 'success' => 1));
            } else {
                $this->jsonp(array('errno' => 0, 'success' => 0, 'tip' => '发送失败'));
            }
        }
        $this->jsonp(array('errno' => 404, 'success' => 0, 'tip' => '参数错误，发送失败'));
    }


    public function getSignupInfoAction()
    {
        if (Request::getQuery('token') && Request::getQuery('channel_id') && Request::getQuery('activity_id')) {
            $token = Request::getQuery('token');
            $channel_id = Request::getQuery('channel_id');
            $activity_id = Request::getQuery('activity_id');
            $keytokenmob = D::redisKey('mobtoken', $token);
            if (RedisIO::exists($keytokenmob)) {
                $arr = json_decode(RedisIO::get($keytokenmob), true);
                $mob = $arr['mob'];
                $ret = ActivitySignup::getonebymobile($channel_id, $activity_id, $mob);
                if ($ret['mobile']) {
                    $ret['ext_values'] = json_encode($ret['ext_values']);
                    $ret['ext_fields'] = json_encode($ret['ext_fields']);
                    $this->jsonp(array('errno' => 0, 'success' => 1, 'tip' => '', 'data' => $ret));
                }
                $this->jsonp(array('errno' => 201, 'success' => 0, 'tip' => '未找到合适的数据'));
            }
            $this->jsonp(array('errno' => 202, 'success' => 0, 'tip' => 'token无效'));

        }
        $this->jsonp(array('errno' => 400, 'success' => 0, 'tip' => '输入参数错误'));
    }


    public function getSingupTotalAction()
    {
        if (RedisIO::exists('dgsPlayerAmount')) {
            RedisIO::incrby('dgsPlayerAmount', intval(Request::getQuery('addamount')));
        } else {
            RedisIO::set('dgsPlayerAmount', 500);
        }
        $this->jsonp(array('players' => RedisIO::get('dgsPlayerAmount')));
    }

    public function getUserDataByTokenAction()
    {
        if (Request::getQuery('token')) {
            $token = Request::getQuery('token');
            $tokenkey = D::redisKey('mobtoken', $token);
            if (Redisio::exists($tokenkey)) {
                echo RedisIO::get($tokenkey);
                exit();
            }
            $this->jsonp(array('errno' => 201, 'success' => 0, 'tip' => '系统错误'));
        }
        $this->jsonp(array('errno' => 401, 'success' => 0, 'tip' => '参数错误'));
    }

    public function getVediolistAction()
    {
        if (RedisIO::exists('dgsVedioList')) {
            $pageno = Request::getQuery('pageno');
            $pagesize = Request::getQuery('pagesize');
            foreach (RedisIO::lRange('dgsVedioList', ($pageno - 1), ($pageno - 1) * $pagesize - 1) as $json) {
                $ret[] = json_decode($json, true);
            }
            $this->jsonp(array('errno' => 0, 'success' => 1, 'data' => $ret));
        }
        $this->jsonp(array('errorno' => 404, 'success' => 0, 'data' => []));
    }

    public function valSmsCodeAction()
    {

        if (Request::getQuery('mob') && Request::getQuery('code')) {
            $mob = Request::getQuery('mob');
            $code = Request::getQuery('code');
            $this->jsonp(array('errno' => 0, 'success' => 1, 'ret' => VerifyCode::validate($mob, $code)));
        }
        $this->jsonp(array('errno' => 1, 'tip' => '参数错误'));
    }


    public function getVedioHomepageAction()
    {
        $pagesize = Request::getQuery('pagesize', 'int', 6);
        $pageno = Request::getQuery('pageno', 'int', 1);
        $redios_vediolist_key = 'DgsAviVedioList';
        $lindex = $pagesize * ($pageno - 1);
        $rindex = $pagesize * ($pageno - 1) + $pagesize - 1;
        $vlist = RedisIO::lRange($redios_vediolist_key, $lindex, $rindex);
        $datalist = array();

        foreach ($vlist as $data_id) {
            $redios_dgsfile_key = D::redisKey('DgsVedioFile', $data_id);
            $datalist[] = json_decode(RedisIO::get($redios_dgsfile_key), true);
        }
        $this->jsonp(array('errno' => 0, 'success' => 1, 'data' => $datalist));
    }

    /**
     * 校验验证码
     * @return bool|int
     */
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

    /**
     * 手机是否已经注册
     */
    public function validateMobileAction(){
        $channel_id = Request::getPost('channel_id','int');
        $mobile = Request::getPost('mobile','int');
        $user = Users::apiGetUserByMobile($channel_id, $mobile);

        if(!$this->checkCaptcha()){
            $this->_json([], 504, '验证码错误');
        }
        if(empty($user)) {
            $this->_json([], 204, '该手机号未被注册');
        }


    }
    /**
     * 重设置帐号密码
     */
    public function restPwdAction(){
        $channel_id = Request::getPost('channel_id','int');
        $mobile = Request::getPost('mobile','int');
        $pwd = Request::getPost('password','string');
        $code = Request::getPost('code','string');

        if(!VerifyCode::validate($mobile, $code)) {
            $this->_json([], 202, '验证码错误');
        }
        $res = Users::apiRestPassword($channel_id,$mobile,$pwd);
        if($res){
            $this->_json([]);
        } else {
            $this->_json([],4006,'重置失败');
        }

    }

    /**
     * @param $data
     * @param int $code
     * @param string $msg
     */
    protected function _json($data, $code = 200, $msg = "success")
    {
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }




}