<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CdnUser extends Model {

    public function getSource() {
        return 'cdn_user';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'user_name', 'app_id', 'app_secret',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['user_name', 'app_id', 'app_secret',],
            MetaData::MODELS_NOT_NULL => ['id', 'user_name', 'app_id', 'app_secret',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'user_name' => Column::TYPE_VARCHAR,
                'app_id' => Column::TYPE_VARCHAR,
                'app_secret' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'user_name' => Column::BIND_PARAM_STR,
                'app_id' => Column::BIND_PARAM_STR,
                'app_secret' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [

            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getCdnUserByAppid($app_id) {
        $key = 'redis_cdn_user_'.$app_id;
        $data = RedisIO::get($key);
        if(empty($data)){
            $data_ar = self::query()
                    ->columns('id,user_name,app_id,app_secret')
                    ->andCondition('app_id',$app_id)
                    ->first()
                    ->toArray();
            RedisIO::set($key, json_encode($data_ar));
            RedisIO::expire($key, 60);
            return $data_ar;
        }else{
            return json_decode($data,TRUE);
        }
    }
    
    public static function getAllUser() {   
        $data = self::query()
                    ->columns('id,user_name')
                    ->orderBy('id asc') 
                    ->execute()
                    ->toArray();
        foreach ($data as $key => $value) {
            $ar[$value['id']]=$value['user_name'];
        }
        return $ar;
    }
}