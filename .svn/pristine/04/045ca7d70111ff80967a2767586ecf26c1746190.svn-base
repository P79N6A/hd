<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Subscription extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'subscription';
    }

    /**
     * 通过uid找出用户的订阅信息
     * @param $uid
     * @return array|mixed
     */
    public static function apiFindSubscriptionByUid($uid){
        $key = D::memKey('apiFindSubscriptionByUid', ['uid'=>$uid]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('uid',$uid)
                ->orderBy("create_time desc")
                ->execute();

            $data_arr = array();
            if ($data) {
                $data_arr = $data->toArray();
            }
            $data = $data_arr;

            MemcacheIO::set($key, $data, 600);
        }
        return $data;
    }

    /**
     * 通过uid和set_id精确查询用户对指定专辑的订阅情况
     * @param $uid
     * @param $set_id
     * @return array|mixed|\Phalcon\Mvc\ModelInterface
     */
    public static function apiFindOneSubscription($uid , $set_id) {
        $key = D::memKey('apiFindOneSubscription', ['uid' => $uid , 'set_id' => $set_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('uid',$uid)
                ->andCondition('set_id',$set_id)->first();
            $data_arr = array();
            if ($data) {
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
                'id', 'uid', 'set_id', 'create_time',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['uid', 'set_id', 'create_time',],
            MetaData::MODELS_NOT_NULL => ['id', 'uid', 'set_id', 'create_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'uid' => Column::TYPE_INTEGER,
                'set_id' => Column::TYPE_INTEGER,
                'create_time' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'uid', 'set_id', 'create_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'uid' => Column::BIND_PARAM_INT,
                'set_id' => Column::BIND_PARAM_INT,
                'create_time' => Column::BIND_PARAM_INT,
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

    public static function findBySetid($set_id){
        return self::query()
            ->columns(array('Users.*'))
            ->leftJoin("Users", "Users.uid=Subscription.uid")
            ->andWhere("Subscription.set_id={$set_id}")
            ->orderBy("Subscription.create_time desc")
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function findSubscriptionByUid($uid){
        return self::query()
            ->andCondition('uid',$uid)
            ->execute();
    }

    public function createSubscription($data){
        $this->assign($data);
        return $this->save();
    }

    public static function deleteSubscriptionOrder($id) {
        $subscription = self::query()->andCondition('id',$id)->first();
        if ($subscription){
            return $subscription->delete();
        }else{
            return false;
        }
    }

}