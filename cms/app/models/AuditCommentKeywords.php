<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AuditCommentKeywords extends Model {
    const PAGE_SIZE = 50;

    public function initialize() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'audit_comment_keywords';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'type', 'keyword', 'addtime', 'audit_name',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['type', 'keyword', 'addtime', 'audit_name',],
            MetaData::MODELS_NOT_NULL => ['id', 'type', 'keyword', 'addtime', 'audit_name',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_CHAR,
                'keyword' => Column::TYPE_VARCHAR,
                'addtime' => Column::TYPE_INTEGER,
                'audit_name' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'addtime',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_STR,
                'keyword' => Column::BIND_PARAM_STR,
                'addtime' => Column::BIND_PARAM_INT,
                'audit_name' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'type' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getAll($type = 'filter') {

        return self::query()
            ->andCondition('type', $type)
            ->orderBy('addtime desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');

    }

    public static function findOneByKeywords($keyword, $type = 'filter') {
        return self::query()
            ->andCondition('keyword', $keyword)
            ->andCondition('type', $type)
            ->first();
    }

    public function createAuditCommentKeywords($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function deleteAuditCommentKeywords($id) {
        $use = self::query()->andCondition('id', $id)->first();
        return $use->delete();
    }

    public static function searchKeywords($search, $type = 'filter') {
        $keyword = $search['keyword'];
        $query = self::query();
        if ($keyword != '') {
            $query = $query->andWhere(" keyword like '%{$keyword}%'");
        }
        return $query->andCondition('type', $type)
            ->orderBy('addtime desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

}