<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Client extends Model {

    public function getSource() {
        return 'client';
    }

    /**
     * @param $channel_id
     * @param $input
     * @return bool|int
     */
    public static function apiFindOrCreateClient($channel_id, $input) {
        $data = self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('origin_id', $input['origin_id'])
            ->first();
        $array = [
            'channel_id' => $channel_id,
            'user_id' => isset($input['user_id']) ? $input['user_id'] : 0,
            'mobile' => null,
            'model' => $input['model'],
            'client_type' => $input['client_type'],
            'app_version' => $input['app_version'],
            'sdk_version' => $input['sdk_version'],
            'origin_id' => $input['origin_id'],
            'push_client' => isset($input['push_client']) ? $input['push_client'] : '',
            'device_token' => isset($input['device_token']) ? $input['device_token'] : '',
            'push_token' => isset($input['push_token']) ? $input['push_token'] : '',
            'updated_at' => time(),
        ];
        GetuiClient::updateSdkVersion($array);
        if ($data) {
            $data->update($array);
            return $data->hash_id;
        } else {
            $model = new self;
            $array['created_at'] = time();
            $array['hash_id'] = isset($input['hash_id']) ? $input['hash_id'] : md5(sha1($channel_id . Config::get('secret') . $input['origin_id']));
            if (!$model->save($array)) {
                return false;
            }
            return $array['hash_id'];
        }
    }

    /**
     * @param int $client_id
     * @param int $channel_id
     * @return array
     */
    public static function apiOne($client_id, $channel_id) {
        $model = self::query()
            ->andCondition('hash_id', $client_id)
            ->andCondition('channel_id', $channel_id)
            ->first();
        if ($model) {
            $model = $model->toArray();
        } else {
            $model = [];
        }
        return $model;
    }

    /**
     * @param int $client_id
     * @param int $channel_id
     * @return array
     */
    public static function apiFindOneByOriginId($origin_id, $channel_id) {
        $key = D::memKey('apiFindOneByOriginId', ['origin_id' => $origin_id, 'channel_id' => $channel_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $model = self::query()
                ->andCondition('origin_id', $origin_id)
                ->andCondition('channel_id', $channel_id)
                ->first();
            if ($model) {
                $data = $model->toArray();
            } else {
                $data = [];
            }

            MemcacheIO::set($key, $data, 1800);
        }
        return $data;
    }

    /*
     * @desc 根据用户ID获取其他信息
     *
     * */
    public static  function getInfoByUserID($user_id)
    {
        $criterial = self::query();
        return $criterial->where("user_id = {$user_id}")
            ->order("id DESC")
            ->first();
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'user_id', 'mobile', 'origin_id', 'hash_id', 'model', 'client_type', 'app_version', 'push_client', 'device_token', 'push_token', 'sdk_version', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'user_id', 'mobile', 'origin_id', 'hash_id', 'model', 'client_type', 'app_version', 'push_client', 'device_token', 'push_token', 'sdk_version', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'user_id', 'origin_id', 'hash_id', 'model', 'client_type', 'app_version', 'push_client', 'push_token', 'device_token', 'sdk_version', 'created_at', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'mobile' => Column::TYPE_VARCHAR,
                'origin_id' => Column::TYPE_VARCHAR,
                'hash_id' => Column::TYPE_VARCHAR,
                'model' => Column::TYPE_VARCHAR,
                'client_type' => Column::TYPE_VARCHAR,
                'app_version' => Column::TYPE_VARCHAR,
                'push_client' => Column::TYPE_VARCHAR,
                'device_token' => Column::TYPE_VARCHAR,
                'push_token' => Column::TYPE_VARCHAR,
                'sdk_version' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'user_id', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'mobile' => Column::BIND_PARAM_STR,
                'origin_id' => Column::BIND_PARAM_STR,
                'hash_id' => Column::BIND_PARAM_STR,
                'model' => Column::BIND_PARAM_STR,
                'client_type' => Column::BIND_PARAM_STR,
                'app_version' => Column::BIND_PARAM_STR,
                'push_client' => Column::BIND_PARAM_STR,
                'device_token' => Column::BIND_PARAM_STR,
                'push_token' => Column::BIND_PARAM_STR,
                'sdk_version' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'user_id' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    /**
     * @param $userId int 用户ID
     * @return Array
     */
    public static function getClientByUserId($userId) {
        $redis_key = D::redisKey('user_ids', $userId);
        $isExists = RedisIO::exists($redis_key);
        if($isExists) {
            return json_decode(RedisIO::get($redis_key), true);
        }
        else {
            return false;
        }
    }



}