<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class PhoneList extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'phone_list';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'phone_num', 'create_time', 'audit_name',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['phone_num', 'create_time', 'audit_name',],
            MetaData::MODELS_NOT_NULL => ['id', 'phone_num', 'create_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'phone_num' => Column::TYPE_INTEGER,
                'create_time' => Column::TYPE_DATETIME,
                'audit_name' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'phone_num',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'phone_num' => Column::BIND_PARAM_INT,
                'create_time' => Column::BIND_PARAM_STR,
                'audit_name' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'create_time' => 'CURRENT_TIMESTAMP'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getAll() {

        return self::query()
            ->paginate(self::PAGE_SIZE, 'Pagination');

    }

    public function createPhoneList($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function deletePhoneList($id) {
        $use = self::query()->andCondition('id', $id)->first();
        return $use->delete();
    }

    public static function makeValidator($inputs) {
        $validator = Validator::make(
            $inputs, [
            'phone_num' => 'unique:phone_list'
        ], [
                'phone_num.unique' => '手机号段不唯一',
            ]
        );
        return $validator;
    }

    public static function search($search) {
        $keyword = $search['keyword'];
        return self::query()->andWhere("phone_num like '%$keyword%' ")
            ->orderBy('id desc')
            ->paginate(UserFeedback::PAGE_SIZE, 'Pagination');
    }

}