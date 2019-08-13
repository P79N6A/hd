<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Users extends Model {
    const CHANNEL_ID_OF_ZGLTV = 1;//频道3为中国蓝tv
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'users';
    }

    public static function getUserByTvuid($tvuid) {
        $memprefix = app_site()->memprefix;
        if (99999999 > $tvuid) {
            return false;
        } else {
            $key = $memprefix . 'credit.channel_' . self::CHANNEL_ID_OF_ZGLTV . '.user_' . $tvuid;
            $data = MemcacheIO::get($key);
            if (!$data) {
                $zgltvuser = Users::query()->where("uid = {$tvuid} and partition_by = " . Users::getHashTable($tvuid))->first();
                if ($zgltvuser) {
                    $fake_mobile = '';
                    $data = array(
                        'uid' => isset($zgltvuser->uid) ? $zgltvuser->uid : 0,
                        'id' => isset($zgltvuser->uid) ? $zgltvuser->uid : 0,
                        'mobile' => $fake_mobile,
                        'name' => $fake_mobile,
                        'gender' => 1,
                        'channel_id' => self::CHANNEL_ID_OF_ZGLTV,
                        'partition_by' => date("Y"),
                        'created_at' => time(),
                        'updated_at' => time(),
                        'grade' => 0,
                        'credits' => $zgltvuser->credits,
                        'avatar' => '',
                    );
                    MemcacheIO::set($key, $data, 86400 * 30);
                } else {
                    $data = NULL;
                }
            }
            return $data;
        }
    }

    public static function getOne($uid) {
        $parameters = array();
        $parameters['conditions'] = "uid=" . $uid . " and partition_by=".self::getHashTable($uid);
        return Users::findFirst($parameters);
    }

    public static function getUserinfoByUid($uid) {
        $memprefix = app_site()->memprefix;
        $key = $memprefix . 'users.userinfo_' . $uid;
        $data = MemcacheIO::get($key);
        if (!$data) {
            $userinfo = Users::query()->where("uid = {$uid} and partition_by = " . Users::getHashTable($uid))->first();
            if($userinfo) {
                $data_arr = array(
                    'uid' => $uid,
                    'nickname' => ($userinfo) ? $userinfo->nickname : "未知",
                    'mobile' => $userinfo->mobile,
                    'email' => $userinfo->email,
                    'realname' => $userinfo->realname,
                    'gender' => $userinfo->gender,
                    'channel_id' => $userinfo->channel_id,
                    'created_at' => $userinfo->created_at,
                    'updated_at' => $userinfo->updated_at,
                    'credits' => $userinfo->credits,
                    'avatar' => $userinfo->avatar,
                );
            }else{
                $data_arr = array();
            }
            $data = json_encode($data_arr);
            MemcacheIO::set($key, $data, 86400);
        }
        return $data;
    }

    public static function changeCredit($uid, $credit) {
        $memprefix = app_site()->memprefix;
        $user = Users::getOne($uid);
        if ($credit < 0 && ($user->credits + $credit) < 0) {//积分不足
            return false;
        }
        $user->credits = $user->credits + $credit;
        $user->save();
        $key = $memprefix . 'credit.channel_' . self::CHANNEL_ID_OF_ZGLTV . '.user_' . $uid;
        $data = MemcacheIO::get($key);
        $data['credits'] = $user->credits;


        MemcacheIO::set($key, $data, 86400 * 30);
        return true;
    }

    /**
     * @param $channel_id
     * @param $mobile
     * @return bool|array
     */
    public static function apiGetUserByMobile($channel_id, $mobile) {
        $rs = Userlogin::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('loginname', $mobile)
            ->andCondition('partition_by', self::getHashTable($mobile))
            ->first();
        return $rs ? $rs->toArray() : false;
    }

    /**
     * @param $channel_id
     * @param $mobile
     * @param $password
     * @return bool
     * 迁移过后可能不使用这些接口
     */
    public static function apiRestPassword($channel_id, $mobile, $password) {
        $model = new Userlogin();
        $user = $model->query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('loginname', $mobile)
            ->andCondition('partition_by', self::getHashTable($mobile))
            ->first();
        if ($user) {
            return $user->save([
                'password' => Hash::encrypt($password, $user->salt),
            ]);
        }
        return false;
    }
    /**
     * @param $channel_id
     * @param $mobile
     * @param $password
     * @return bool
     * 迁移过后可能不使用这些接口
     */
    public static function restPassword($channel_id, $mobile, $password) {
        $model = new Userlogin();
        $user = $model->query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('loginname', $mobile)
            ->andCondition('partition_by', self::getHashTable($mobile))
            ->first();
        if ($user) {
            return $user->save([
                'password' => Hash::encrypt($password, $user->salt),
            ]);
        }
        return false;
    }

    /**
     * @param $channel_id
     * @param $input
     * @return int
     */
    public static function apiRegister($channel_id, $input) {
        //市县用户前缀
        $town_prefix = "town".$channel_id."_";

        //生成唯一uid
        $dataUid = array('nickname' => $input['mobile']."_".$channel_id, 'username' => $town_prefix.$input['mobile']);
        $useridModel = new Userid();
        $uid = $useridModel->saveGetId($dataUid);

        //创建登录表记录
        $mobileAuthTable = self::getHashTable($input['mobile']);
        //设置用户绑定uid zhanghaiquan:bind_uid
        $userloginModel = new Userlogin();
        $cdkey = str_random();
        $dataAuth = array(
            'uid' => $uid,
            'channel_id' => $channel_id,
            'loginname' => $input['mobile'],
            'password' => Hash::encrypt($input['password'], $cdkey),
            'salt' => $cdkey,
            'type' => Userlogin::LOGIN_TYPE_MOBILE,
            'bind_uid' => 0,
            'status' => 1,
            'partition_by' => $mobileAuthTable,
        );
        $userloginModel->save($dataAuth);



        $userBaseinfoTable = self::getHashTable($uid);
        $user = new Users();
        $user->uid = $uid;
        $user->channel_id = $channel_id;
        $user->username = $input['mobile'];
        $user->nickname = $input['mobile'];
        $input['email'] = "";
        foreach (['avatar', 'grade', 'name', 'signature', 'realname', 'mobile', 'email', 'qq', 'gender'] as $v) {
            if (isset($input[$v])) {
                $user->$v = $input[$v];
            }
        }
        $user->regist_service = 'my';
        $user->regist_ip = isset($data['regist_ip']) ? $data['regist_ip'] : self::getClientIp();
        $user->created_at = time();

        $user->updated_at = time();
        $user->partition_by = $userBaseinfoTable;
        $user->save();

        return  $user->uid;
    }

    /**
     * @param $id
     * @return array
     */
    public static function apiGetUserInfo($uid) {
        $user = self::findFirst(array('conditions' => 'uid=' . $uid. " and partition_by=".self::getHashTable($uid)))->toArray();
        $user['socials'] = UserSocials::query()
            ->columns('open_id, type, token')
            ->andCondition('uid', $uid)
            ->execute()
            ->toArray();
        return $user;
    }

    /**
     * 统一登入
     * @param $id
     * @return array
     */
    public static function getUserInfo($uid) {
        $user = self::findFirst(array('conditions' => 'uid=' . $uid. " and partition_by=".self::getHashTable($uid)))->toArray();
        return $user;
    }

    /**
     * @param $input
     * @return mixed
     */
    public static function apiUserValidator($input) {
        $validator = Validator::make(
            $input, [
            'mobile' => 'required|digits:11',
            'name' => 'required|max:20',
            'code' => 'required|min:4|max:4',
            'password' => 'required|min:6|max:20',
        ], [
                'mobile.required' => 'mobile required',
                'mobile.digits' => 'mobile is not digits',
                'code.required' => 'code required',
                'name.required' => 'name required',
                'name.max' => 'name max 20',
            ]
        );
        return $validator;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'uid', 'channel_id', 'partition_by', 'signature', 'realname', 'qq', 'regist_ip', 'birthday', 'regist_service', 'province', 'city', 'last_modify_pwd_time', 'is_vip', 'activity_id', 'begin_time', 'end_time', 'status', 'created_at', 'updated_at', 'avatar', 'gender', 'grade', 'credits', 'nickname', 'username', 'email', 'mobile',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['uid', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'signature', 'realname', 'qq', 'regist_ip', 'birthday', 'regist_service', 'province', 'city', 'last_modify_pwd_time', 'is_vip', 'activity_id', 'begin_time', 'end_time', 'status', 'created_at', 'updated_at', 'avatar', 'gender', 'grade', 'credits', 'nickname', 'username', 'email', 'mobile',],
            MetaData::MODELS_NOT_NULL => ['uid', 'channel_id', 'partition_by', 'status', 'created_at', 'updated_at', 'gender', 'grade', 'credits', 'email', 'mobile',],
            MetaData::MODELS_DATA_TYPES => [
                'uid' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
                'signature' => Column::TYPE_VARCHAR,
                'realname' => Column::TYPE_VARCHAR,
                'qq' => Column::TYPE_VARCHAR,
                'regist_ip' => Column::TYPE_VARCHAR,
                'birthday' => Column::TYPE_DATE,
                'regist_service' => Column::TYPE_VARCHAR,
                'province' => Column::TYPE_VARCHAR,
                'city' => Column::TYPE_VARCHAR,
                'last_modify_pwd_time' => Column::TYPE_INTEGER,
                'is_vip' => Column::TYPE_INTEGER,
                'activity_id' => Column::TYPE_INTEGER,
                'begin_time' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'avatar' => Column::TYPE_VARCHAR,
                'gender' => Column::TYPE_INTEGER,
                'grade' => Column::TYPE_INTEGER,
                'credits' => Column::TYPE_INTEGER,
                'nickname' => Column::TYPE_VARCHAR,
                'username' => Column::TYPE_VARCHAR,
                'email' => Column::TYPE_VARCHAR,
                'mobile' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'uid', 'channel_id', 'partition_by', 'last_modify_pwd_time', 'is_vip', 'activity_id', 'begin_time', 'end_time', 'status', 'created_at', 'updated_at', 'gender', 'grade', 'credits', 'mobile',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'uid' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
                'signature' => Column::BIND_PARAM_STR,
                'realname' => Column::BIND_PARAM_STR,
                'qq' => Column::BIND_PARAM_STR,
                'regist_ip' => Column::BIND_PARAM_STR,
                'birthday' => Column::BIND_PARAM_STR,
                'regist_service' => Column::BIND_PARAM_STR,
                'province' => Column::BIND_PARAM_STR,
                'city' => Column::BIND_PARAM_STR,
                'last_modify_pwd_time' => Column::BIND_PARAM_INT,
                'is_vip' => Column::BIND_PARAM_INT,
                'activity_id' => Column::BIND_PARAM_INT,
                'begin_time' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'avatar' => Column::BIND_PARAM_STR,
                'gender' => Column::BIND_PARAM_INT,
                'grade' => Column::BIND_PARAM_INT,
                'credits' => Column::BIND_PARAM_INT,
                'nickname' => Column::BIND_PARAM_STR,
                'username' => Column::BIND_PARAM_STR,
                'email' => Column::BIND_PARAM_STR,
                'mobile' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'status' => '1',
                'gender' => '0',
                'grade' => '0',
                'credits' => '0',
                'username' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public function createUsers($data) {
        $this->channel_id = intval($data['channel_id']);
        $this->partition_by = intval(self::getHashTable($data['uid']));
        $this->created_at = time();
        $this->updated_at = time();
        $this->regist_ip = self::getClientIp();
        foreach (['uid', 'channel_id', 'signature', 'realname', 'qq', 'regist_ip', 'birthday', 'regist_service', 'province', 'city', 'last_modify_pwd_time', 'is_vip', 'activity_id', 'begin_time', 'end_time', 'status', 'avatar', 'gender', 'grade', 'credits', 'nickname', 'username', 'email', 'mobile'] as $v) {
            if (isset($data[$v]) && !is_array($data[$v])) {
                $this->$v = $data[$v];
            }
        }
        return $this->save();

    }

    public static function getHashTable($uid) {
        $userid = strtolower($uid);
        $str = crc32($userid);
        if ($str < 0) {
            $hash = "0" . substr(abs($str), 0, 1);
        } elseif ($str < 10) {
            $hash = "0" . $str;
        } else {
            $hash = substr($str, (strlen($str) - 2));
        }
        return $hash;
    }

    /**
     * @desc 用户注册时，分配密码加密key，一个用户一个key
     * @version 2015-06-01
     * @return string
     */
    public function createCdkey() {
        $str = 'abcde012fghij345klmno678pqrstuvw9xyz';
        $rndstr = '';    //用来存放生成的随机字符串
        for ($i = 0; $i < 8; $i++) {
            $rndcode = rand(0, 35);
            $rndstr .= $str[$rndcode];
        }
        return $rndstr;
    }

    public static function modifyStatus($uid, $partition_by, $status) {
        $user = self::query()->andCondition('uid', $uid)->andCondition('partition_by', $partition_by)->first();
        $user->status = $status;
        return $user->update();
    }

    public static function getUserByUid($uid) {
        return self::query()->andCondition('uid', $uid)->first();
    }


    //以下为后台操作使用到的方法
    public static function getAll() {
        $channel_id = Session::get("user")->channel_id;

        return Users::query()
            ->andCondition('channel_id', $channel_id)
            ->orderBy(' Users.updated_at desc')
            ->paginate(Users::PAGE_SIZE, 'Pagination');

    }

    public static function findOne($uid, $channel_id) {
        $result = self::query()->where("uid = '{$uid}' and channel_id='{$channel_id}'")->first();
        return $result;
    }

    public static function forbiddenUsers($uid, $channel_id) {
        $use = Users::query()->where("uid='{$uid}' and channel_id='{$channel_id}'")->first();
        if ($use->status == 1) {
            $use->status = 2;
        } elseif ($use->status == 2) {
            $use->status = 1;
        }
        return $use->save();
    }

    //原始model方法

    public static function register($params, $channel_id) {
        $user = new self;
        $user->name = strip_tags($params['name']);
        $user->mobile = $params['mobile'];
        $user->salt = str_random();
        $user->password = Hash::encrypt($params['password'], $user->salt);
        $user->channel_id = $channel_id;
        $user->created_at = $user->updated_at = time();
        $user->partition_by = date('Y');
        return $user->saveGetId();
    }

    public static function findByMobile($mobile, $channel_id) {
        return Users::findFirst(['mobile = :mobile: AND channel_id = :channel_id: AND status = 1', 'bind' => [
            'mobile' => $mobile,
            'channel_id' => $channel_id,
        ]]);
    }

    public static function createUser($mobile, $passowrd, $channel_id) {
        $user = new Users();
        if ($user) {
            $user->name = time();
            $user->mobile = $mobile;
            $user->salt = rand(0, 99999);
            $user->password = Hash::encrypt($passowrd, $user->salt);
            $user->channel_id = $channel_id;
            $user->partition_by = date_format(date_create(), "Y");
            if ($user->save()) {
                return $user;
            }
        }
        return false;
    }

    public static function createSocialUser($channel_id) {
        return Users::createUser("", time(), $channel_id);
    }

    public static function deleteUsers($id, $channel_id) {
        $use = Users::query()->where("id='{$id}' and channel_id='{$channel_id}'")->first();
        if (!$use->avatar) {
            $use->avatar = ' ';
        }
        $use->status = 0;
        return $use->save();
    }

    public static function search($data, $channel_id) {
        $name = $data['name'];
        $mobile = $data['mobile'] ?: '';
        $nickname = $data['nickname'] ?: '';
        $query = Users::query()->andwhere("Users.status=1 and Users.channel_id='{$channel_id}'");
        if ($name) {
            $query = $query->andwhere("Users.name like '%$name%'");
        }
        if ($mobile) {
            $query = $query->andwhere("Users.mobile like '%$mobile%'");
        }
        if ($nickname) {
            $query = $query->andwhere("Users.nickname like '%$nickname%'");
        }
        return $query->paginate(50, 'Pagination');
    }

    public static function findUsers($user_ids) {
        return Users::query()->inWhere('uid', $user_ids)->execute()->toArray();
    }

    public function addUser($data) {
        $this->assign($data);
        return $this->save();
    }

    public function modifyUserInfo($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    /*
     * @desc 获取频道下面的所有正常用户列表
     * @param $channel_id int
     * @return ArrayList
     * */
    public static function getUserListByChannel($channel_id) {
        return self::query()
            ->where('channel_id=:channel_id: AND status != 0')
            ->bind(array('channel_id' => $channel_id))
            ->execute()
            ->toArray();
    }


    /**
     * @desc 获取客户端ip公用方法 HTTP_LEPROXY_FORWARDED_FOR ： 小运营商代理ip
     * @return mixed
     */
    public static function getClientIp() {
        if (isset($_SERVER['HTTP_LEPROXY_FORWARDED_FOR']) && !empty($_SERVER['HTTP_LEPROXY_FORWARDED_FOR'])) {
            $clientIp = $_SERVER['HTTP_LEPROXY_FORWARDED_FOR'];
        } else {
            $clientIp = self::_getIP();
            //$clientIp = $_SERVER['REMOTE_ADDR'];
        }
        return $clientIp;
    }

    /**
     * 2016-07-08 饶佳添加
     * @desc 获取客户端真实IP
     * @return mixed
     */
    public static function _getIP() {
        if (getenv('HTTP_CLIENT_IP')) {
            $ip = getenv('HTTP_CLIENT_IP');
        } elseif (getenv('HTTP_X_FORWARDED_FOR')) {
            $ip = getenv('HTTP_X_FORWARDED_FOR');
        } elseif (getenv('HTTP_X_FORWARDED')) {
            $ip = getenv('HTTP_X_FORWARDED');
        } elseif (getenv('HTTP_FORWARDED_FOR')) {
            $ip = getenv('HTTP_FORWARDED_FOR');
        } elseif (getenv('HTTP_FORWARDED')) {
            $ip = getenv('HTTP_FORWARDED');
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    /**
     * @param $channel_id
     * @param $input
     * @param $uid
     * @param $data
     * @param $userBaseinfoTable
     * @return array
     */
    protected static function createUsers_bak($channel_id, $input, $uid)
    {
        $user = new Users();
        $user->uid = $uid;
        $user->channel_id = $channel_id;
        $user->username = $input['mobile'];
        $user->nickname = $input['nickname'];
        $input['email'] = "";
        foreach (['avatar', 'grade', 'name', 'signature', 'realname', 'mobile', 'email', 'qq', 'gender'] as $v) {
            if (isset($input[$v])) {
                $user->$v = $input[$v];
            }
        }
        $user->regist_service = 'my';
        $user->regist_ip = Users::getClientIp();
        $user->created_at = time();

        $user->updated_at = time();
        $user->partition_by = Users::getHashTable($input['mobile']);
        $user->save();
        return array($user, $input);
    }

    /**
     * 获取用户信息
     * @param $channel_id
     * @param $open_id
     * @param $partition_by
     * @return array
     */
    public static function getUserInfoByUserName($channel_id, $username){
        $userinfo = Users::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('username', $username)
            ->execute()->toArray();
        return $userinfo[0];
    }

    /**
     * 获取用户信息
     * @param $channel_id
     * @param $open_id
     * @param $partition_by
     * @return array
     */
    public static function getUsersByUid($uid){
        $userinfo = Users::findFirst("uid = {$uid}")->toArray();
        return $userinfo;
    }



}
