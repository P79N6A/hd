<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UserSocials extends Model {
    use HasChannel;

    public function getSource() {
        return 'user_socials';
    }

    /**
     * @param $channel_id
     * @param $open_id
     * @param $type
     * @return mixed
     */
    public static function apiGetUserByToken($channel_id, $open_id, $type) {
        $data = self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('open_id', $open_id)
            ->andCondition('type', $type)
            ->first();
        if($data&&$data->bind_uid>0) {
            return $data->bind_uid;
        }
        else {
            return $data ? $data->uid : false;
        }
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'uid', 'type', 'open_id', 'refresh_token', 'token', 'nickname', 'from', 'bind_uid', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'uid', 'type', 'open_id', 'refresh_token', 'token', 'nickname', 'from', 'bind_uid',],
            MetaData::MODELS_NOT_NULL => ['id', 'type', 'open_id', 'refresh_token', 'token', 'nickname', 'from', 'bind_uid', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'uid' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'open_id' => Column::TYPE_VARCHAR,
                'refresh_token' => Column::TYPE_VARCHAR,
                'token' => Column::TYPE_VARCHAR,
                'nickname' => Column::TYPE_VARCHAR,
                'from' => Column::TYPE_INTEGER,
                'bind_uid' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'uid', 'type', 'from', 'bind_uid', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'uid' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'open_id' => Column::BIND_PARAM_STR,
                'refresh_token' => Column::BIND_PARAM_STR,
                'token' => Column::BIND_PARAM_STR,
                'nickname' => Column::BIND_PARAM_STR,
                'from' => Column::BIND_PARAM_INT,
                'bind_uid' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'type' => '0',
                'open_id' => '',
                'refresh_token' => '',
                'token' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getByUidAndType($user_id, $type) {
        return self::query()
            ->andCondition('uid', $user_id)
            ->andCondition('type', $type)
            ->first();
    }

    /**
     * @param $channel_id
     * @param $type
     * @param $open_id
     * @return bool|int|Users
     */
    public static function getAndRefreshByOpenId($channel_id, $data) {
        $social = self::channelQuery($channel_id)
            ->andCondition('type', $data['type'])
            ->andCondition('open_id', $data['open_id'])
            ->first();
        if ($social) {
            $user = Users::findFirst($social->uid);
            if ($user->status) {
                $social->assign($data);
                $social->save();
                return $user;
            } else {
                return false;
            }
        }
        return 0;
    }


    /**
     * @desc 获取第三方关联用户信息
     * @version 2015-06-04
     * @param unknown $oauth_uid 连接平台的用户id
     * @param unknown $appid 1=>新浪, 2=>QQ, 3=>微信
     * @return null
     */
    public function getOAuthUser($oauth_uid, $appid) {
        $partition_by = User::getHashTable($oauth_uid);
        $result = $this->query()
            ->andwhere("open_id = :open_id:")
            ->andwhere("type = :type:")
            ->andWhere("partition_by = :partition_by:")
            ->bind(array('open_id' => $oauth_uid, 'type' => $appid, 'partition_by' => $partition_by))
            ->execute()
            ->toArray();
        return isset($result[0])?$result[0]:null;
    }

    /**
     * @desc 第三方入库操作
     * @param $row
     * @return bool
     */
    public function passport_insertData($row) {
        //注意：插入成功后，返回自增ID,如果表没有自增ID，则返回0,row插入的数据
        return $this->create($row);
    }

    /**
     * @desc 删除多余的用户信息
     * @param $oauth_uid
     * @param $appid
     * @return mixed
     */
    public function delOAuthUser($oauth_uid, $appid) {
        $partition_by = User::getHashTable($oauth_uid);
        $oauthUser = $this->findFirst(array("conditions" => "type='{$appid}' and open_id='{$oauth_uid}' and partition_by='{$partition_by}'"));
        if ($oauthUser) {
            return $oauthUser->delete();
        }
        return false;
    }

    /**
     * @desc 绑定第三方
     * @param $oauth_uid
     * @param $appid
     * @param $uid
     * @return bool
     */
    public function bindOAuthUser($oauth_uid, $appid, $uid) {
        $partition_by = User::getHashTable($oauth_uid);
        $data = array('uid' => $uid);
        $oauthUser = $this->findFirst(array("conditions" => "type='{$appid}' and open_id='{$oauth_uid}' and partition_by='{$partition_by}'"));
        if ($oauthUser) {
            return $oauthUser->save($data);
        }
        return false;
    }

    /**
     * @param $channel_id
     * @param $input
     * @param $uid
     */
    public static function createUserSocials($input)
    {
        $socials = new UserSocials();
        $res = $socials->save([
            'channel_id' => $input['channel_id'],
            'uid' => $input['uid'],
            'type' => $input['sns_type'],
            'nickname' => $input['nickname'],
            'from' => $input['from'],
            'bind_uid' => 0,
            'open_id' => $input['openid'],
            'refresh_token' => isset($input['refresh_token']) ? $input['refresh_token'] : '',
            'token' => $input['sns_token'],
            'partition_by' => Users::getHashTable($input['openid']),
            'created_at' => time(),
            'updated_at' => time(),
        ]);
        if($res){
            return $socials;

        } else {
            return false;

        }
    }

    /**
     * 获取第三方信息
     */
    public static function getUserSocials($channel_id, $open_id, $partition_by){
         $result = UserSocials::query()
                ->andCondition('channel_id',$channel_id)
                ->andCondition('open_id',$open_id)
                ->andCondition('partition_by',$partition_by)
                ->execute()
                ->toArray();
         return $result[0];
    }


}