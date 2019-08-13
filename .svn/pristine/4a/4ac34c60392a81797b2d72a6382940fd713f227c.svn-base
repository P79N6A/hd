<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SubscriptionSetInfo extends Model {
    public static $get_status_key = 'subscription_set_info::get_status';//0可以获取，1等待获取，2获取中
    public static $get_keywork_key = 'subscription_set_info::get_keyword';//获取热词列表

    public function getSource() {
        return 'subscription_set_info';
    }

    public static function apiFindAllIsKeyword() {
        $key = D::memKey('apiFindAllIsKeyword', []);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->columns(array('SubscriptionSet.*', 'SubscriptionSetInfo.*'))
                ->leftJoin("SubscriptionSet", "SubscriptionSet.set_id=SubscriptionSetInfo.set_id")
                ->andWhere("SubscriptionSetInfo.is_keyword = 1")
                ->orderBy('SubscriptionSet.set_id desc')
                ->execute();

            $data_arr = array();
            foreach ($data as $sub) {
                $set = $sub->subscriptionSet->toArray();
                $set_info = $sub->subscriptionSetInfo->toArray();
                $data_arr[] = array_merge($set_info,$set);
            }
            $data = $data_arr;

            MemcacheIO::set($key, $data, 300);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'set_id', 'set_cover', 'subscription_number', 'is_keyword', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['set_id', 'set_cover', 'subscription_number', 'is_keyword', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'set_id', 'subscription_number', 'is_keyword', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'set_id' => Column::TYPE_INTEGER,
                'set_cover' => Column::TYPE_VARCHAR,
                'subscription_number' => Column::TYPE_INTEGER,
                'is_keyword' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'set_id', 'subscription_number', 'is_keyword', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'set_id' => Column::BIND_PARAM_INT,
                'set_cover' => Column::BIND_PARAM_STR,
                'subscription_number' => Column::BIND_PARAM_INT,
                'is_keyword' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'subscription_number' => '0',
                'is_keyword' => '0',
                'sort' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findOneBySetId($set_id) {
        return self::query()->andCondition('set_id',$set_id)->first();
    }

    public static function modifySubscription($set_id , $data) {
        $set_info = self::query()->andCondition('set_id',$set_id)->first();
        if (!$set_info) return false;

        if ($data) {
            $set_info->assign($data);
            $key = D::memKey('apiFindSetAll', []);
            RedisIO::delete($key);
        }else{
            return false;
        }
        return $set_info->update();
    }

    public static function modifyKeyword($set_id) {
        $set_info = self::query()->andCondition('set_id',$set_id)->first();
        if (!$set_info) return false;

        if ($set_info->is_keyword == 0) {
            $set_info->is_keyword = 1;
        }else{
            $set_info->is_keyword = 0;
        }
        return $set_info->update();
    }

    public static function modifyCover($set_id , $cover) {
        $set_info = self::query()->andCondition('set_id',$set_id)->first();
        if (!$set_info) return false;

        if ($cover) {
            $set_info->set_cover = $cover;
        }else{
            return false;
        }
        return $set_info->update();
    }

}