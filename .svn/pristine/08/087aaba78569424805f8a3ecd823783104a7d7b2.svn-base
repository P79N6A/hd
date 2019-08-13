<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SpecialTemplates extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'special_templates';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'fields',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'fields',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'fields',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'fields' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'fields' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'name' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        $data = self::query()
            ->andWhere('channel_id=' . Session::get('user')->channel_id)
            ->paginate(SpecialTemplates::PAGE_SIZE, 'Pagination');
        return $data;
    }

    public static function findAllForm() {
        $data = self::query()
            ->andWhere('channel_id=' . Session::get('user')->channel_id)
            ->execute()->toarray();
        return $data;
    }

    public static function getOne($templates) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $templates;
        return SpecialTemplates::findFirst($parameters);
    }

    public function modifyTemplates($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public function createTemplates($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function deleteTemplates($id) {
        return SpecialTemplates::findFirst($id)->delete();
    }

    //检验表单信息
    public static function checkForm($inputs) {
        $validator = Validator::make(
            $inputs, [
            'channel_id' => 'required',
            'name' => 'required',
            'fields' => 'required',
        ], [
                'channel_id.required' => '请填写所属频道',
                'name.required' => '请填写模板名',
                'fields.required' => '请填写字段集',
            ]
        );
        return $validator;
    }
}