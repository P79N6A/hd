<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AnnounStatus extends Model {

    public function getSource() {
        return 'announ_status';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'notice_id', 'admin_id', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['notice_id', 'admin_id', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'notice_id', 'admin_id', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'notice_id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'notice_id', 'admin_id', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'notice_id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
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

    public static function findSNoticeStatus($id) {
        $notice = self::query()
            ->columns(array('AnnounStatus.notice_id', 'AnnounStatus.status', 'announ.id', 'announ.title', 'announ.content', 'announ.name', 'announ.return', 'announ.time', 'announ.pic'))
            ->Where("(status = 1 or status = 2) and (admin_id = {$id}) ")
            ->leftJoin('announ', 'AnnounStatus.notice_id=announ.id')
            ->execute()->toarray();
        return $notice;
    }

    public static function findOne($id, $notice_id) {
        $result = self::query()
            ->where("admin_id = '{$id}'and notice_id = '{$notice_id}'")
            ->execute()->toarray();
        return $result;
    }

    public function modifyNotice($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public static function findOneO($notice_id, $admin_id) {
        $nots = self::query()->where("notice_id = {$notice_id} and admin_id={$admin_id}")->first();
        return $nots;
    }

    public function findAll($id) {
        $notice = self::find(array("admin_id" => $id, "status" => 1));
        return $notice;
    }

    public function addNotice($data) {
        $this->assign($data);
        return $this->save();
    }

    public function modifyStatusAll($admin_id) {
        $phql = "UPDATE notice_status SET status = 2 WHERE status = 1 AND admin_id = ?1";
        $this->modelsManager->executeQuery(
            $phql,
            array(
                1 => $admin_id
            )
        );
    }

}