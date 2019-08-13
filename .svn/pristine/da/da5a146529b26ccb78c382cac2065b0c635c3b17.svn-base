<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Asset extends Model {

    public function getSource() {
        return 'asset';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'admin_id', 'name', 'number', 'time', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['admin_id', 'name', 'number', 'time', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'admin_id', 'name', 'number', 'time', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'number' => Column::TYPE_VARCHAR,
                'time' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'admin_id', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'number' => Column::BIND_PARAM_STR,
                'time' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
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

    public static function findUserAll($id) {
        $salary = Asset::query()
            ->where("admin_id = '{$id}'")
            ->paginate(50, 'Pagination');
        return $salary;
    }

    public static function findAll() {
        $salary = Asset::query()->paginate(50, 'Pagination');
        return $salary;
    }

    public function createAsset($data) {
        $this->assign($data);
        return $this->save();
    }

    public static function getOneAsset($id) {
        $result = self::query()->andCondition('id', $id)->first();
        return $result;
    }

    public function modifyAsset($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public static function deleteAsset($id) {
        return Asset::findFirst($id)->delete();
    }

    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'number' => 'required|min:2|max:50',
            'name' => 'required|min:2|max:50',
            'time' => 'required',
            'status' => 'required'
        ], [
            'number.required' => '请填写编号',
            'number.min' => '编号不得小于 2 个字符',
            'number.max' => '编号不得大于 50 个字符',
            'name.required' => '请填写薪水',
            'name.min' => '薪水不得小于 2 个字符',
            'name.max' => '薪水不得大于 50 个字符',
            'time.required' => '请填写申请时间',
            'status.required' => '请填写状态'
        ]);
    }

}