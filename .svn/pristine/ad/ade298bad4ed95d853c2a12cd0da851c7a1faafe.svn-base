<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class WxKeyword extends Model {

    public function getSource() {
        return 'wx_keyword';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'wx_keyword', 'callback_type', 'title', 'keyword_code', 'answer_text',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['wx_keyword', 'callback_type', 'title', 'keyword_code', 'answer_text',],
            MetaData::MODELS_NOT_NULL => ['id', 'wx_keyword', 'callback_type', 'title', 'keyword_code',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'wx_keyword' => Column::TYPE_VARCHAR,
                'callback_type' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'keyword_code' => Column::TYPE_VARCHAR,
                'answer_text' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'callback_type',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'wx_keyword' => Column::BIND_PARAM_STR,
                'callback_type' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'keyword_code' => Column::BIND_PARAM_STR,
                'answer_text' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'callback_type' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getCdkeyKeyword($content) {
        return self::query()->andCondition('wx_keyword', $content)
//            ->andCondition('callback_type', 2)
            ->first();
    }

    public static function getSubscribeEvent() {
        return self::query()
//            ->andCondition('wx_keyword', $content)
            ->andCondition('callback_type', 4)
            ->first();
    }

    public static function getOneById($id) {
        return self::query()->andCondition('id', $id)->first();
    }

    public static function validators(array $inputs, $excluded_id = 0) {
        return Validator::make($inputs, [
            'wx_keyword' => 'required|max:100',
            'callback_type' => 'required',
            'title' => 'required|max:100',
            'keyword_code' => 'required'
        ], [
            'wx_keyword.required' => '请填写关键词',
            'wx_keyword.max' => '关键词不得多于 100 个字符',
            'callback_type.required' => '请选择响应类型',
            'title.required' => '请填写关键词描述',
            'title.max' => '关键词描述不得多于 100 个字符',
            'keyword_code.required' => '请填写关键词编号'
        ]);
    }

}