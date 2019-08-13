<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SubscriptionSet extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'subscription_set';
    }

    public static function apiFindSetAll() {
        $key = D::memKey('apiFindSetAll', []);
        $data = RedisIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->columns(array('SubscriptionSet.*', 'SubscriptionSetInfo.*'))
                ->leftJoin("SubscriptionSetInfo", "SubscriptionSet.set_id=SubscriptionSetInfo.set_id")
                ->orderBy('SubscriptionSet.set_id desc')
                ->execute();

            $data_arr = array();
            foreach ($data as $sub) {
                $set = $sub->subscriptionSet->toArray();
                $set_info = $sub->subscriptionSetInfo->toArray();
                $data_arr[$set['set_id']] = array_merge($set_info,$set);
            }
            $data = $data_arr;
            $data = json_encode($data);
            RedisIO::set($key, $data);
        }
        return $data;
    }

    public static function apiFindOneSetBySetId($set_id) {
        $key = D::memKey('apiFindOneSetBySetId', ['set_id' => $set_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('set_id',$set_id)
                ->first();

            $data_arr = array();
            if($data){
                $data_arr = $data->toArray();
            }
            $data = $data_arr;

            MemcacheIO::set($key, $data, 600);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'set_id', 'name',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'set_id', 'name',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'set_id', 'name',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'set_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'set_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'set_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
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

    public static function findAll(){
        $query = self::query()
            ->columns(array('SubscriptionSet.*', 'SubscriptionSetInfo.*'))
            ->leftJoin("SubscriptionSetInfo", "SubscriptionSet.set_id=SubscriptionSetInfo.set_id");
        if (! isset(Auth::user()->channel_id)) {
            abort(404,"用户信息不存在");
        }else{
            return $query->andWhere("SubscriptionSet.channel_id=".Auth::user()->channel_id)
                ->orderBy('SubscriptionSetInfo.sort desc ,SubscriptionSet.set_id desc')
                ->paginate(self::PAGE_SIZE, 'Pagination');
        }
        return false;
    }

    public static function deleteAll() {
        $set_all = self::query()->execute();
        db::begin();
        try {
            foreach ($set_all as $set_one) {
                $set_one->delete();
            }
        }catch (Exception $e){
            db::rollback();
            return false;
        }
        db::commit();
        return true;
    }

    public static function findOneBySetId($set_id) {
        return self::query()->andCondition('set_id',$set_id)->first();
    }

    public static function searchByName($name) {
        $query = self::query()
            ->columns(array('SubscriptionSet.*', 'SubscriptionSetInfo.*'))
            ->leftJoin("SubscriptionSetInfo", "SubscriptionSet.set_id=SubscriptionSetInfo.set_id");
        if (! isset(Auth::user()->channel_id)) {
            abort(404,"用户信息不存在");
        }else{
            return $query->andWhere("SubscriptionSet.channel_id=".Auth::user()->channel_id)
                ->andwhere("name like '%{$name}%'")
                ->orderBy('SubscriptionSetInfo.sort desc ,SubscriptionSet.set_id desc')
                ->paginate(self::PAGE_SIZE, 'Pagination');
        }
        return false;
    }

}