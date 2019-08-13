<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AuthRole extends Model {

    const PAGE_SIZE = 50;

    public function getSource() {
        return 'auth_role';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'element',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'element',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'element',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'element' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'element' => Column::BIND_PARAM_STR,
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

    public static function getRoleElement($id) {
        $channel_id = Session::get('user')->channel_id;
        $return = [];
        $data = self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('id', $id)
            ->first();
        if (!empty($data)) {
            $return = explode(",", trim($data->element, ","));
        }
        return $return;
    }

    public static function makeValidator($input) {
        return Validator::make(
            $input, [
            'name' => 'required',
        ], [
                'name.required' => '角色名称',
            ]
        );
    }

    public static function findAll() {
        return self::query()->andCondition('channel_id', Session::get('user')->channel_id)->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function roleList() {
        $data = self::query()->andCondition('channel_id', Session::get('user')->channel_id)->execute()->toArray();
        $return = [];
        if (!empty($data)) {
            $return = array_refine($data, 'id');
        }
        return $return;
    }

}
