<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class LotteryGoods extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'lottery_goods';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'goods_name', 'thumb', 'is_real', 'is_rewin', 'is_vericode', 'overtime', 'is_recover', 'content',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'goods_name', 'thumb', 'is_real', 'is_rewin', 'is_vericode', 'overtime', 'is_recover', 'content',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'goods_name', 'is_real', 'is_rewin', 'is_vericode', 'overtime', 'is_recover', 'content',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'goods_name' => Column::TYPE_VARCHAR,
                'thumb' => Column::TYPE_VARCHAR,
                'is_real' => Column::TYPE_INTEGER,
                'is_rewin' => Column::TYPE_INTEGER,
                'is_vericode' => Column::TYPE_INTEGER,
                'overtime' => Column::TYPE_INTEGER,
                'is_recover' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'is_real', 'is_rewin', 'is_vericode', 'overtime', 'is_recover',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'goods_name' => Column::BIND_PARAM_STR,
                'thumb' => Column::BIND_PARAM_STR,
                'is_real' => Column::BIND_PARAM_INT,
                'is_rewin' => Column::BIND_PARAM_INT,
                'is_vericode' => Column::BIND_PARAM_INT,
                'overtime' => Column::BIND_PARAM_INT,
                'is_recover' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'is_real' => '1',
                'is_rewin' => '0',
                'is_vericode' => '0',
                'overtime' => '0',
                'is_recover' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query()
            ->andCondition('channel_id', $channel_id);
        if (!empty(Request::get('name'))) {
            $query = $query->andCondition('goods_name', trim(Request::get('name')));
        }
        $data = $query->paginate(self::PAGE_SIZE, 'Pagination');
        return $data;
    }

    public static function makeValidator($input) {
        $validator = Validator::make(
            $input, [
            'goods_name' => "required",
            'is_real' => 'required',
        ], [
                'goods_name.required' => '奖品名必填',
                'is_real' => '奖品是否实物必填'
            ]
        );
        return $validator;
    }

    public static function listGoods() {
        $channel_id = Session::get('user')->channel_id;
        $data = self::query()->andCondition('channel_id', $channel_id)->orderBy('id desc')->execute()->toArray();
        $return = [];
        if (!empty($data)) {
            $return = array_refine($data, 'id');
        }
        return $return;
    }

    public static function findOne($id) {
        return self::query()->andCondition('id',$id)->first();
    }

}