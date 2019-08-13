<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Multimedia extends Model {

    use HasChannel;

    public function getSource() {
        return 'multimedia';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', ],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id',  'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
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

    /**
     * @param $inputs
     * @param int $excluded_id
     * @return \Illuminate\Validation\Validator
     */
    public static function makeValidator($inputs, $excluded_id = 0) {
        return Validator::make(
            $inputs,
            [
                //'content' => 'required',
            ],
            [
               // 'content.required' => '请填写新闻正文内容',
            ]
        );
    }








}