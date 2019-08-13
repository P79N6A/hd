<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Userlogin extends Model {

    const LOGIN_TYPE_MOBILE = 1;
    const LOGIN_TYPE_EMAIL = 2;
    const LOGIN_TYPE_USERNAME = 3;

    public function getSource() {
        return 'userlogin';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'uid', 'channel_id', 'loginname', 'password', 'salt', 'partition_by', 'type', 'bind_uid', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['uid', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'loginname', 'password', 'salt', 'type','bind_uid','status',],
            MetaData::MODELS_NOT_NULL => ['uid', 'channel_id', 'loginname', 'salt', 'type', 'bind_uid', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'uid' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'loginname' => Column::TYPE_VARCHAR,
                'password' => Column::TYPE_VARCHAR,
                'salt' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_INTEGER,
                'bind_uid' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'uid', 'channel_id', 'partition_by', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'uid' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'loginname' => Column::BIND_PARAM_STR,
                'password' => Column::BIND_PARAM_STR,
                'salt' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_INT,
                'bind_uid' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'status' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function checkMobile($mobile, $partition_by) {
        $re = self::query()->andCondition('loginname', $mobile)->andCondition('partition_by', $partition_by)->first();
        return $re;
    }

    /**
     * 检查用户是否存在
     * @param $mobile
     * @param $channel_id
     * @param $partition_by
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function hasMobile($mobile, $channel_id, $partition_by) {
        $re = self::query()
            ->andCondition('loginname', $mobile)
            ->andCondition('channel_id', $channel_id)
            ->andCondition('partition_by', $partition_by)
            ->first();
        return $re;
    }

    public static function modifyStatus($uid, $partition_by, $status) {
        $user = self::query()->andCondition('uid', $uid)->andCondition('partition_by', $partition_by)->first();
        if (!empty($user)) {
            $user->status = $status;
            return $user->update();
        }
    }


    /**
     * @param $channel_id
     * @param $input
     * @param $uid
     * @param $mobileAuthTable
     */
    public static function createUserLogin($input)
    {
        $userloginModel = new Userlogin();
        $cdkey = str_random();
        $dataAuth = array(
            'uid' => $input['uid'],
            'channel_id' => $input['channel_id'],
            'loginname' => $input['mobile'],
            'password' => Hash::encrypt($input['password'], $cdkey),
            'salt' => $cdkey,
            'type' => Userlogin::LOGIN_TYPE_MOBILE,
            'bind_uid' => 0,
            'status' => 1,
            'partition_by' => Users::getHashTable($input['mobile']),
        );

        return $userloginModel->save($dataAuth);
    }

}