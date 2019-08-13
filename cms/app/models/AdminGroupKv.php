<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AdminGroupKv extends Model {
    const PAGE_SIZE = 25;

    public function getSource() {
        return 'admin_group_kv';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'gid', 'tag', 'key', 'value',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['gid', 'tag', 'key', 'value',],
            MetaData::MODELS_NOT_NULL => ['id', 'gid', 'tag', 'key', 'value',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'gid' => Column::TYPE_INTEGER,
                'tag' => Column::TYPE_VARCHAR,
                'key' => Column::TYPE_VARCHAR,
                'value' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'gid',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'gid' => Column::BIND_PARAM_INT,
                'tag' => Column::BIND_PARAM_STR,
                'key' => Column::BIND_PARAM_STR,
                'value' => Column::BIND_PARAM_STR,
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


    public static function findList($gid) {
        return self::query()->andWhere("gid = :gid:")->bind(array('gid' => $gid))->paginate(self::PAGE_SIZE, 'Pagination');
    }

    /**
     *
     * @param int gid
     * @return string KV json
     *
     * */
    public static function getGroupPair($gid) {
        $arr = self::query()->columns(array('key', 'value'))->where("gid = :gid:")->bind(array('gid' => $gid))->execute()->toArray();
        $arrjson = [];
        foreach ($arr as $v) {
            $arrjson[$v['key']] = $v['value'];
        }
        return json_encode($arrjson);
    }

}