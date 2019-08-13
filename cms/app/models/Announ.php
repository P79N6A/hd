<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Announ extends Model {

    public function getSource() {
        return 'announ';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'content', 'time', 'return', 'user', 'title', 'name', 'rednum', 'pic',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['content', 'time', 'return', 'user', 'title', 'name', 'rednum', 'pic',],
            MetaData::MODELS_NOT_NULL => ['id', 'content', 'time', 'user', 'title', 'name', 'rednum',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_VARCHAR,
                'time' => Column::TYPE_VARCHAR,
                'return' => Column::TYPE_TEXT,
                'user' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'name' => Column::TYPE_VARCHAR,
                'rednum' => Column::TYPE_INTEGER,
                'pic' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'user', 'rednum',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'time' => Column::BIND_PARAM_STR,
                'return' => Column::BIND_PARAM_STR,
                'user' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'name' => Column::BIND_PARAM_STR,
                'rednum' => Column::BIND_PARAM_INT,
                'pic' => Column::BIND_PARAM_STR,
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

    public static function findOne($notice_id) {
        $notice = self::query()
            ->where("id = {$notice_id}")
            ->execute()->toarray();
        return $notice;
    }

    public static function findOneObj($notice_id) {
        $notice = self::query()
            ->where("id = {$notice_id}")
            ->first();
        return $notice;
    }

    public function modifyNotice($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public static function findOneO($notice_id) {
        $notice = self::query()
            ->where("id = {$notice_id}")->first();
        return $notice;
    }

    public static function findContent($notice_id) {
        $content = self::query()
            ->where("notice_id = {$notice_id}")->execute()->toarray();
        return $content;
    }

    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'title' => 'required|min:2|max:20',
            'content' => 'required|min:2|max:250',
        ], [
            'title.required' => '请填写标题',
            'title.min' => '标题不得小于 2 个字符',
            'title.max' => '标题不得大于 20 个字符',
            'content.required' => '请填写内容',
            'content.min' => '内容不得小于 2 个字符',
            'content.max' => '内容不得大于 250 个字符',
        ]);
    }

    public function createNotice($data) {
        $this->assign($data);
        $this->save();
        return $this->id;
    }
}