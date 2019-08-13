<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AuditCommentBlockip extends Model {
    const PAGE_SIZE = 50;

    public function initialize() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'audit_comment_blockip';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'ip', 'addtime', 'audit_name',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['ip', 'addtime', 'audit_name',],
            MetaData::MODELS_NOT_NULL => ['id', 'ip', 'addtime', 'audit_name',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'ip' => Column::TYPE_INTEGER,
                'addtime' => Column::TYPE_INTEGER,
                'audit_name' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'ip', 'addtime',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'ip' => Column::BIND_PARAM_INT,
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

    public static function deleteAuditCommentBlockip($obj) {
        return $obj->delete();
    }

    public function createAuditCommentBlockip($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }


    public static function search($search) {
        $keyword = $search['keyword'];
        return self::query()->andWhere("ip= " . $keyword)
            ->orderBy('id desc')
            ->paginate(UserFeedback::PAGE_SIZE, 'Pagination');
    }

}