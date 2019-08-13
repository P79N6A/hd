<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Board extends Model {

    public function getSource() {
        return 'board';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'time', 'contents', 'user_group',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['time', 'contents', 'user_group',],
            MetaData::MODELS_NOT_NULL => ['id', 'time', 'contents', 'user_group',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'time' => Column::TYPE_INTEGER,
                'contents' => Column::TYPE_TEXT,
                'user_group' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'time' => Column::BIND_PARAM_INT,
                'contents' => Column::BIND_PARAM_STR,
                'user_group' => Column::BIND_PARAM_STR,
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

    public static function getBoardById($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return Board::findFirst($parameters);
    }

    public static function getBoardByUser($user_group) {
        $parameters = array();
        $parameters['conditions'] = "user_group='$user_group'";
        return Board::findFirst($parameters);
    }

    public function modifyBoard($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public function createBoard($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

}