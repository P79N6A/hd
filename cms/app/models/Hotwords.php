<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Hotwords extends Model {

    public function getSource() {
        return 'hotwords';
    }

    /**
     * @param $channel_id
     * @return mixed
     */
    public static function apiGetHotwords($channel_id) {
        $key = D::memKey('apiGetHotwords', ['id' => $channel_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('channel_id', 0)
                ->orderBy('weight desc,createtime desc')
                ->execute()
                ->toArray();
            MemcacheIO::set($key, $data, 1800);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'user_id', 'user_name', 'createtime', 'weight', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'user_id', 'user_name', 'createtime', 'weight', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'user_id', 'user_name', 'createtime', 'weight', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'user_id' => Column::TYPE_INTEGER,
                'user_name' => Column::TYPE_VARCHAR,
                'createtime' => Column::TYPE_INTEGER,
                'weight' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'user_id', 'createtime', 'weight', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'user_id' => Column::BIND_PARAM_INT,
                'user_name' => Column::BIND_PARAM_STR,
                'createtime' => Column::BIND_PARAM_INT,
                'weight' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'weight' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        return Hotwords::query()->paginate(10, 'Pagination');
    }

    public static function findorder() {
        return Hotwords::query()->orderBy("weight DESC")->paginate(10, 'Pagination');
    }

    public static function makeValidators($inputs) {
        return Validator::make(
            $inputs, [
            'name' => 'required',
            'weight' => 'required',
            'status' => 'required',
        ], [
            'name.required' => '请填写关键词',
            'weight' => '请填写权重',
            'status' => '请选择状态',
        ]);
    }
}