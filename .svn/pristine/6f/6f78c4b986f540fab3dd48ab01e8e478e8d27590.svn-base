<?php

use GenialCloud\Helper\IWC;
/**
 * @RoutePrefix("/user")
 */
class UserController extends ApiBaseController {

    static $public_actions = array('register', 'login', 'reset', 'validaitemobile');

    static $baoliaoType = ['jpg', 'jpeg', 'gif', 'png', 'mp4', 'avi', 'mpeg', 'flv', 'wmv', 'swf', 'rm', 'doc', 'docx', 'xls', 'xlsx'];

    public function initialize() {
        parent::initialize();
        $action = $this->dispatcher->getActionName();
        if(!in_array(strtolower($action), self::$public_actions)) {
            $this->checkToken();
        }
    }

    /**
     * @Post('/')
     */
    public function registerAction() {
        $mobile = Request::getPost('mobile');
        $code = Request::getPost('code');
        $password = Request::getPost('password');
        $user = Users::apiGetUserByMobile($this->channel_id, $mobile);
        // TODO 手机号等等长度检验
        if(strlen($password) < 6) {
            $this->_json([], 201, '密码不得少于 6 位');
        }
        if(!empty($user)) {
            $this->_json([], 204, '手机号已被注册过');
        }
        if(!VerifyCode::validate($mobile, $code)) {
            $this->_json([], 202, '验证码错误');
        }
        $uid = Users::apiRegister($this->channel_id, Request::getPost());
        if(!$uid) {
            $this->_json([], 404, Lang::_('error'));
        }
        $info = Users::apiGetUserInfo($uid);
        $return = [
            'token' => $this->createToken($info['uid'], $info),
            'user' => [
                'uid' => $info['uid'],
                'mobile' => $info['mobile'],
                'name' => $info['nickname'],
                'grade' => $info['grade'],
                'avatar' => $info['avatar'],
                'nickname' => $info['nickname'],
                'signature' => $info['signature'],
                'realname' => $info['realname'],
                'email' => $info['email'],
                'qq' => $info['qq'],
                'gender' => $info['gender'],
                'socials'=> $info['socials'],
            ]
        ];
        return $this->_json($return);
    }

    /**
     * 增加收藏
     * @Post('/favorites')
     */
    public function postFavoritesAction() {
        $id = (int) Request::getPost('id');
        $data = Favorites::apiGetFavoritesById($this->channel_id, $id, $this->user->uid, 1);
        if($data) {
            $this->_json([]);
        }
        $this->_json([], 404, 'Not Found');
    }

    /**
     * 删除收藏
     * @Delete('/favorites/{id}')
     */
    public function deleteFavoritesAction($id) {
        $id = intval($id);
        $data = Favorites::apiDelFavoritesById($id, $this->user->uid, 1);
        if($data) {
            $this->_json([]);
        }
        $this->_json([], 404, 'Not Found');
    }

    /**
     * 获取收藏
     * @Get('/favorites')
     */
    public function getFavoritesAction() {
        $data = Favorites::apiGetFavorites($this->channel_id, $this->user->uid,$this->per_page, $this->page);
        $return = [];
        if(!empty($data)) {
            $this->initSmartData();
            foreach($data as $v) {
                $return[] = [
                    "id" => $v['id'],
                    "type" => $v['type'],
                    "title" => $v['title'],
                    "intro" => $v['intro'],
                    "thumb" => cdn_url('image',$v['thumb']),
                    "comments" => $v['comments'],
                    'create_at' => $v['created_at'],
                    'wap_url' => $this->mediaUrl($v),
                    "thumbs" => $v['type'] == 'album'? $this->getAlbumImage($v['source_id']): [],
                ];
            }
        }
        return $this->_json($return);
    }


    private function constructInfoResult($comments) {
        $result = array();
        $keys = array('id' => 'name', 'user_id' => 'user_id',
            'location' => 'location', 'create_at' => 'create_at', 'likes' => 'likes', 'status' => 'status', 'down' => 'down', 'content' => 'content');
        $userIds = array();
        foreach($comments as $comment) {
            if (!in_array($comment['user_id'], $userIds)) {
                array_push($userIds, $comment['user_id']);
            }
        }
        array_push($userIds, 16);
        $users = Users::findUsers($userIds);
        $users = array_refine($users, 'uid');
        foreach($comments as $comment) {
            $commentArr = array();
            foreach($comment as $key => $value) {
                if (array_key_exists($key, $keys)) {
                    if ($key == 'create_at') {
                        $value = IWC::timeTransform(intval($value));
                    }
                    $commentArr[$keys[$key]] = $value;
                }
            }
            //TODO upstairs 如果把所有的comment加载到内存中查询，将会占用很大内存
            //如果用sql语句查询，将耗费RDS资源。可在评论中加个tree id。
            $commentArr['upstairs'] = array();
            $commentArr['avatar'] = isset($users[$comment['user_id']]) ? $users[$comment['user_id']]['avatar'] : "";
            if (!empty($commentArr['avatar'])){
                $commentArr['avatar'] = Oss::url($commentArr['avatar']);
            }
            $commentArr['username'] = isset($users[$comment['user_id']]) ? $users[$comment['user_id']]['username'] : "";
            array_push($result, $commentArr);
        }
        return $result;
    }

    /**
     * @Get('/comments')
     */
    public function commentsAction() {
        $comments = UserComments::getCommentsByUser($this->user->uid, $this->page, $this->per_page);
        if($comments) {
            $result = $this->constructInfoResult($comments);
            return $this->_json($result);
        } else {
            return $this->_json([]);
        }
    }

    /**
     * @Post('/login')
     */
    public function loginAction() {
        $open_id = Request::getPost('open_id');
        $type = Request::getPost('type');
        $mobile = Request::getPost('mobile');
        $password = Request::getPost('password');
        // 检验必须字段
        if(!$mobile && !$open_id){
            $this->_json([], '404', 'Not Found');
        }
        if(!$type && !$password){
            $this->_json([], '404', 'Not Found');
        }
        if($mobile) {
            $user = Users::apiGetUserByMobile($this->channel_id, $mobile);
            if(!$user) {
                $this->_json([], '401', '手机号码不存在');
            }
            if(!Hash::check($user['password'], $password, $user['salt'])) {
                $this->_json([], '402', '账号密码错误');
            }
            $id = $user['uid'];
            //返回用户绑定uid zhanghaiquan:bind_uid
            if($user['bind_uid']>0) $id = $user['bind_uid'];

        } else {
            $id = UserSocials::apiGetUserByToken($this->channel_id, $open_id, $type);
            if(!$id) {
                $this->_json([], '405', '无社交登录信息');
            }
        }
        $info = Users::apiGetUserInfo($id);
        if(!$info['status']) {
            $this->_json([], '403', 'Forbidden');
        }
        $this->_json([
            'token' => $this->createToken($info['uid'], $info),
            'user' => [
                'uid' => $info['uid'],
                'mobile' => $info['mobile'],
                'name' => $info['nickname'],
                'grade' => $info['grade'],
                'avatar' => $info['avatar'],
                'nickname' => $info['nickname'],
                'signature' => $info['signature'],
                'realname' => $info['realname'],
                'email' => $info['email'],
                'qq' => $info['qq'],
                'gender' => $info['gender'],
                'socials'=> $info['socials'],
            ]
        ]);
    }

    /**
     * 手机号码验证
     * @Post('/validate_mobile')
     */
    public function validaiteMobileAction() {
        $type = Request::getPost('type');
        $mobile = Request::getPost('mobile');//密码重置
        if($type == 'reset') {
            $user = Users::apiGetUserByMobile($this->channel_id, $mobile);
            if(!$user) {
                $this->_json([], 404, '无效的手机号');
            }
            if(VerifyCode::send($mobile)) {
                $this->_json([]);
            } else {
                $this->_json([], 404, '发送失败');
            }
        } else if($type == 'register') {
            if(VerifyCode::send($mobile)) {
                $this->_json([]);
            } else {
                $this->_json([], 404, '发送失败');
            }
        }
    }

    /**
     * 重置密码
     * @Post('/reset')
     */
    public function resetAction() {
        $password = Request::getPost('password');
        $code = Request::getPost('code');
        $mobile = Request::getPost('mobile');
        if(!VerifyCode::validate($mobile, $code))
        {
            $this->_json([], 202, '验证码错误');
        }
        if(strlen($password) < 6) {
            $this->_json([], 201, '密码不得少于 6 位');
        }
        $data = Users::apiRestPassword($this->channel_id, $mobile, $password);
        if($data){
            $this->_json([]);
        }else{
            $this->_json([], 202, '手机号未注册');
        }
    }

    /**
     *
     * @Get('/baoliaos')
     */
    public function baoliaoListAction() {
        $user_id = $this->user->uid;
        $rs = Baoliao::apiGetBaoliaoByUser($this->channel_id, $user_id, $this->per_page, $this->page);
        $return = [];
        if(!empty($rs)) {
            foreach($rs as $v) {
                $return[] = [
                    'id' => $v['id'],
                    'title' => $v['title'],
                    'content' => $v['content'],
                    'reply' => $v['reply'],
                    'status' => $v['status'],
                ];
            }
            $this->_json($return);
        } else {
            $this->_json([]);
        }
    }

    /**
     * 爆料限制
     * @Get('/baoliao_limit')
     */
    public function baoliaoLimitAction() {
        $this->_json([
            'ext' => self::$baoliaoType,
            'size' => '50MB',
        ]);
    }

    /**
     *
     * @Post('/baoliao')
     */
    public function baoliaoAddAction() {

        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $title = Request::getPost('title');
        $content = Request::getPost('content');
        $client = Request::getPost('client');
        $ip = Request::getClientAddress();
        if(!$title && !$content) {
            $this->_json([], '404', 'Not Found');
        }
        $baoliaoInfo = array(
            'channel_id' => $this->channel_id,
            'title' => $title,
            'content' => $content,
            'user_id' => $this->user->uid,
            'username' => $this->user->nickname? $this->user->nickname: $this->user->mobile,
            'create_at' => time(),
            'status' => 2,
            'client' => $client,
            'ip' => $ip,
        );
        $model = new Baoliao;
        $id = $model->saveGetId($baoliaoInfo);
        if($id) {
            $this->validateAndUpload($id);
            $key = "baoliao_user_id:" . $this->user->uid;
            MemcacheIO::set($key, false, 86400 * 30);
            return $this->_json([]);
        } else {
            return $this->_json([], 404, '发送失败');
        }
    }

    /**
     * @param $baoliao_id
     */
    protected function validateAndUpload($baoliao_id) {
        file_put_contents('./debugfiles.txt', json_encode($_FILES));
        if(Request::hasFiles()) {
            foreach(Request::getUploadedFiles() as $file) {
                $error = $file->getError();
                if(!$error) {
                    $ext = $file->getExtension();
                    if(in_array(strtolower($ext), self::$baoliaoType)) {
                        $path = Oss::uniqueUpload($ext, $file->getTempName(), $this->channel_id.'/baoliao');
                        $attachmodel = new BaoliaoAttachment();
                        $attachtype =0;
                        if(in_array(strtolower($ext),['jpg', 'jpeg', 'gif', 'png'])) {
                            $attachtype =2;
                        }else if(in_array(strtolower($ext),['3gp', 'mkv', 'mov', 'mpg', 'mp4', 'avi', 'mpeg', 'flv', 'wmv', 'swf', 'rm'])) {
                            $attachtype =1;
                        }
                        $attachmodel->saveGetId([
                            'origin_name' => $file->getName(),
                            'name' => $file->getName(),
                            'type' => $attachtype, //1:视频 2:图片 0:未知
                            'path' => $path,
                            'baoliao_id' => $baoliao_id,
                            'ext' => $ext,
                            'created'=>time(),
                        ]);
                    }
                }
            }
        }
    }
}