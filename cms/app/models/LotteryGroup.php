<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class LotteryGroup extends Model {

    static $PAGE_SIZE = 50;

    public function getSource() {
        return 'lottery_group';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'sub_name', 'thumb', 'top_banner', 'open_time', 'close_time', 'is_single', 'win_type', 'intro', 'content', 'rule', 'copyright', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'sub_name', 'thumb', 'top_banner', 'open_time', 'close_time', 'is_single', 'win_type', 'intro', 'content', 'rule', 'copyright', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'open_time', 'close_time', 'is_single', 'win_type', 'intro', 'content', 'rule', 'copyright', 'created_at', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'sub_name' => Column::TYPE_VARCHAR,
                'thumb' => Column::TYPE_VARCHAR,
                'top_banner' => Column::TYPE_VARCHAR,
                'open_time' => Column::TYPE_INTEGER,
                'close_time' => Column::TYPE_INTEGER,
                'is_single' => Column::TYPE_INTEGER,
                'win_type' => Column::TYPE_INTEGER,
                'intro' => Column::TYPE_TEXT,
                'content' => Column::TYPE_TEXT,
                'rule' => Column::TYPE_TEXT,
                'copyright' => Column::TYPE_TEXT,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'open_time', 'close_time', 'is_single', 'win_type', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'sub_name' => Column::BIND_PARAM_STR,
                'thumb' => Column::BIND_PARAM_STR,
                'top_banner' => Column::BIND_PARAM_STR,
                'open_time' => Column::BIND_PARAM_INT,
                'close_time' => Column::BIND_PARAM_INT,
                'is_single' => Column::BIND_PARAM_INT,
                'win_type' => Column::BIND_PARAM_INT,
                'intro' => Column::BIND_PARAM_STR,
                'content' => Column::BIND_PARAM_STR,
                'rule' => Column::BIND_PARAM_STR,
                'copyright' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'is_single' => '1',
                'win_type' => '1'
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
            $query = $query->andCondition('name', trim(Request::get('name')));
        }
        $data = $query->paginate(self::$PAGE_SIZE, 'Pagination');
        return $data;
    }

    public static function makeValidator($input, $excluded_id = 0) {
        $validator = Validator::make(
            $input, [
            'name' => "required",
            'open_time' => 'required',
            'close_time' => 'required',
        ], [
                'name.required' => '名称不能为空',
                'open_time.required' => '开始时间不能为空',
                'close_time.required' => '结束时间不能为空'
            ]
        );
        return $validator;
    }

    public static function listGroups() {
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