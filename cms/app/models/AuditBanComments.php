<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AuditBanComments extends Model {
    const PAGE_SIZE = 50;

    public function initialize() {
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'audit_ban_comments';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'ssoid', 'addtime', 'audit_name',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['ssoid', 'addtime', 'audit_name',],
            MetaData::MODELS_NOT_NULL => ['id', 'ssoid', 'addtime', 'audit_name',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'ssoid' => Column::TYPE_INTEGER,
                'addtime' => Column::TYPE_INTEGER,
                'audit_name' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'ssoid', 'addtime',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'ssoid' => Column::BIND_PARAM_INT,
                'addtime' => Column::BIND_PARAM_INT,
                'audit_name' => Column::BIND_PARAM_STR,
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

    public static function getAll() {
        return self::query()
            ->orderBy('addtime desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');

    }

    public static function getOneById($id) {

        return self::query()
            ->andCondition('id', $id)->first();

    }

    public static function deleteAuditBanComments($obj) {
        return $obj->delete();
    }

    public function createAuditBanComments($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function searchSsoid($search) {
        $ssoid = $search['ssoid'];
        $query = self::query();
        if ($ssoid != '') {
            $query = $query->andWhere(" ssoid like '%{$ssoid}%'");
        }
        return $query
            ->orderBy('addtime desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }
}