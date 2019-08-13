<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class BaoliaoAttachment extends Model {
    public $curr_time;

    public function initialize() {
        date_default_timezone_set('Asia/Shanghai');
        $this->curr_time = time();
    }

    public function getSource() {
        return 'baoliao_attachment';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'origin_name', 'ext', 'path', 'type', 'created', 'baoliao_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['origin_name', 'ext', 'path', 'type', 'created', 'baoliao_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'origin_name', 'ext', 'path', 'type', 'created', 'baoliao_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'origin_name' => Column::TYPE_VARCHAR,
                'ext' => Column::TYPE_VARCHAR,
                'path' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_VARCHAR,
                'created' => Column::TYPE_INTEGER,
                'baoliao_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'created', 'baoliao_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'origin_name' => Column::BIND_PARAM_STR,
                'ext' => Column::BIND_PARAM_STR,
                'path' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_STR,
                'created' => Column::BIND_PARAM_INT,
                'baoliao_id' => Column::BIND_PARAM_INT,
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

    public function createAttachment($data) {
        $this->assign($data);
        $this->created = time();
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public static function getAttachs($baoliao_id) {
        $data = BaoliaoAttachment::query()
            ->where("baoliao_id = $baoliao_id")
            ->execute()->toarray();
        return $data;
    }

    public static function getAllAttaches($baoliaoArr) {
        $ids = array();
        foreach ($baoliaoArr as $baoliao) {
            array_push($ids, $baoliao['id']);
        }
        return BaoliaoAttachment::query()->inWhere('baoliao_id', $ids)->execute()->toArray();
    }
}