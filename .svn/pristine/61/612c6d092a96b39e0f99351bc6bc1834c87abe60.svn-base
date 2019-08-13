<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Menu extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'menu';
    }

    public static function apiGetMenu($channel_id) {
        $key = D::memKey('apiGetMenu', ['channel_id' => $channel_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('channel_id', $channel_id)
                ->andCondition('status', 1)
                ->orderBy('sort desc')
                ->execute()
                ->toArray();
            MemcacheIO::set($key, $data);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'icon', 'name', 'type', 'sort', 'category_id', 'menu_json', 'url', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'icon', 'name', 'type', 'sort', 'category_id', 'menu_json', 'url', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'icon', 'name', 'type', 'sort', 'category_id', 'menu_json', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'icon' => Column::TYPE_VARCHAR,
                'name' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
                'menu_json' => Column::TYPE_VARCHAR,
                'url' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'type', 'sort', 'category_id', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'icon' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
                'category_id' => Column::BIND_PARAM_INT,
                'menu_json' => Column::BIND_PARAM_STR,
                'url' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'type' => 'news',
                'sort' => '0',
                'category_id' => '0',
                'status' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        $data = Menu::query()
            ->andwhere('channel_id = ' . Session::get('user')->channel_id)
            ->paginate(Menu::PAGE_SIZE, 'Pagination');
        return $data;
    }

    public static function findOne($id) {
        $data = Menu::query()
            ->andwhere('id = ' . $id)
            ->first();
        return $data;
    }

    public function createMenu($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public function modifyMenu($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public function deleteMenu() {
        return ($this->delete()) ? true : false;
    }

    public static function checkForm($inputs) {
        $validator = Validator::make(
            $inputs, [
            'name' => 'required',
        ], [
                'name.required' => '请填写菜单名',
            ]
        );
        return $validator;
    }
}