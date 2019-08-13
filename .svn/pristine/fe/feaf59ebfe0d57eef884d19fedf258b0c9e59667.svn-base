<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SupplySources extends Model {

    public function getSource() {
        return 'supply_sources';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'type', 'name', 'eshort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['type', 'name', 'eshort',],
            MetaData::MODELS_NOT_NULL => ['id', 'type', 'name',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'eshort' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'type',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'eshort' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'type' => 'video',
                'name' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function allSource() {
        $sou_name = self::query()->execute()->toarray();
        return $sou_name;
    }

    public static function findOneSource($source_id) {
        $result = SupplySources::query()->where("SupplySources.id='{$source_id}'")->execute()->toarray();
        return $result;
    }

    public static function findAll() {
        $result = SupplySources::query()->paginate(50, 'Pagination');
        return $result;
    }

    public function addSource($data) {
        $this->assign($data);
        return $this->save();
    }

    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'name' => 'required|min:2|max:50',
            'type' => 'required'
        ], [
            'name.required' => '请填写名称',
            'name.min' => '名称不得小于 2 个字符',
            'name.max' => '名称不得大于 10 个字符',
            'type.required' => '请选择类型'
        ]);
    }

    public static function findOne($source_id) {
        $result = SupplySources::query()->where("SupplySources.id='{$source_id}'")->first();
        return $result;
    }

    public static function deleteSource($id) {
        return self::findFirst($id)->delete();
    }

    public function modifySource($data) {
        $this->assign($data);
        return $this->update();
    }

   
}