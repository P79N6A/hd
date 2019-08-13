<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Salary extends Model {

    public function getSource() {
        return 'salary';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'admin_id', 'number', 'salary',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['admin_id', 'number', 'salary',],
            MetaData::MODELS_NOT_NULL => ['id', 'admin_id', 'number', 'salary',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'number' => Column::TYPE_VARCHAR,
                'salary' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'admin_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
                'number' => Column::BIND_PARAM_STR,
                'salary' => Column::BIND_PARAM_STR,
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

    public function modifySalary($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public static function getOneSalary($id) {
        $result = self::query()->andCondition('id', $id)->first();
        return $result;
    }

    public static function findAll($id) {
        $salary = Salary::query()
            ->where("admin_id = '{$id}'")
            ->paginate(50, 'Pagination');
        return $salary;
    }

    public function createSalary($data) {
        $this->assign($data);
        return $this->save();
    }

    public static function deleteSalary($id) {
        return Salary::findFirst($id)->delete();
    }

    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'number' => 'required|min:2|max:50',
            'salary' => 'required|min:2|max:50',
        ], [
            'number.required' => '请填写编号',
            'number.min' => '编号不得小于 2 个字符',
            'number.max' => '编号不得大于 50 个字符',
            'salary.required' => '请填写薪水',
            'salary.min' => '薪水不得小于 2 个字符',
            'salary.max' => '薪水不得大于 50 个字符',
        ]);
    }


}