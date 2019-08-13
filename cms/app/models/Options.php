<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Options extends Model {

    public function getSource() {
        return 'options';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'theme_id', 'options_content', 'count',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['theme_id', 'options_content', 'count',],
            MetaData::MODELS_NOT_NULL => ['id', 'theme_id', 'options_content', 'count',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'theme_id' => Column::TYPE_INTEGER,
                'options_content' => Column::TYPE_VARCHAR,
                'count' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'theme_id', 'count',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'theme_id' => Column::BIND_PARAM_INT,
                'options_content' => Column::BIND_PARAM_STR,
                'count' => Column::BIND_PARAM_INT,
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

    public function createOption($data) {
        $this->save($data);
        return $this->id;
    }


    public static function deleteOption($theme_id) {
        Options::find("theme_id=" . $theme_id)->delete();
    }

    public function modifyOption($data_up) {

        return $this->save($data_up);
    }

    public static function getOneOption($option_id) {

        $result = Options::query()->andCondition('id', $option_id)->first();
        return $result;
    }

    public static function getAllOption($theme_id) {

        $result = Options::find("theme_id=" . $theme_id);
        return $result;
    }

    public static function makeValidators(array $inputs, $excluded_id = 0) {
        return Validator::make($inputs, [

            'options_content' => 'required',
            'count' => 'required'
        ], [

            'options_content.required' => '选项内容不能为空',
            'count.required' => '票数不能为空'
        ]);
    }


}