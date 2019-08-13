<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class BackendLogs extends Model {
    const DELETE = 0;
    const INSERT = 1;
    const UPDATE = 2;
    const PAGE_SIZE = 50;

    public $notBackendLog = true;

    public function getSource() {
        return 'backend_logs';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'user_id', 'ip', 'channel_id', 'controller', 'type', 'remark', 'created_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['user_id', 'ip', 'channel_id', 'controller', 'type', 'remark', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'user_id', 'ip', 'channel_id', 'controller', 'type', 'remark', 'created_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'ip' => Column::TYPE_VARCHAR,
                'channel_id' => Column::TYPE_INTEGER,
                'controller' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_INTEGER,
                'remark' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'user_id', 'channel_id', 'type', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'ip' => Column::BIND_PARAM_STR,
                'channel_id' => Column::BIND_PARAM_INT,
                'controller' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_INT,
                'remark' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
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

    public static function toLog() {
        $model = new self;
        $controller = $_GET['_url'];
        $ip = Request::getClientAddress();
        $channel_id = Session::get('user')->channel_id;
        $type = $GLOBALS["backend_logs_type"];
        $user_id = $user_id = Session::get('user')->id;
        if ($GLOBALS["backend_logs_remark"]) {
            $remark = json_encode($GLOBALS["backend_logs_remark"]);
            $model->save([
                'user_id' => $user_id,
                'ip' => $ip,
                'channel_id' => $channel_id,
                'controller' => $controller,
                'type' => $type,
                'remark' => $remark,
                'created_at' => time()
            ]);
        }
        unset($GLOBALS["backend_logs_type"]);
        unset($GLOBALS["backend_logs_remark"]);
        return $model;
    }

    public static function readLogs() {

    }
}