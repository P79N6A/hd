<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class MessageTask extends Model {

    public function getSource() {
        return 'message_task';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'send_id', 'rec_id', 'task_id', 'message', 'status', 'timestamp',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['send_id', 'rec_id', 'task_id', 'message', 'status', 'timestamp',],
            MetaData::MODELS_NOT_NULL => ['id', 'send_id', 'rec_id', 'task_id', 'message', 'status', 'timestamp',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'send_id' => Column::TYPE_INTEGER,
                'rec_id' => Column::TYPE_INTEGER,
                'task_id' => Column::TYPE_INTEGER,
                'message' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
                'timestamp' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'send_id', 'rec_id', 'task_id', 'status', 'timestamp',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'send_id' => Column::BIND_PARAM_INT,
                'rec_id' => Column::BIND_PARAM_INT,
                'task_id' => Column::BIND_PARAM_INT,
                'message' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'timestamp' => Column::BIND_PARAM_INT,
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


    public function sendMessage($task, $message) {
        $this->send_id = $task->creator;
        $this->rec_id = $task->receiver;
        $this->task_id = $task->id;
        $this->message = $message;
        $this->status = '0';
        $this->timestamp = time();
        $this->create();
    }

    public static function createNext($cur, $message) {
//        $user_id = $user_id = Session::get('user')->id;
        $user_id = '86';
        $next = new MessageTask();
        $next->send_id = $user_id;
        $next->rec_id = $user_id == $cur->send_id ? ($cur->rec_id) : ($cur->send_id);
        $next->message = $message;
        $next->task_id = $cur->task_id;
        $next->timestamp = time();
        $next->status = '0';
        $next->create();
        return $next;
    }

    public static function findLatest($user_id) {
        $data = MessageTask::query()
            ->columns(array('MessageTask.id', 'Tasks.id as task_id',
                'Admin.name as send_name', 'r.name as rec_name', 'c.name as creator',
                'rec.name as receiver', 'c.avatar as creaAva', 'Admin.avatar as sendAva',
                'Tasks.created', 'MessageTask.message', 'MessageTask.status',
                'MessageTask.timestamp', 'Tasks.title'))
            ->Where("MessageTask.send_id=$user_id  or MessageTask.rec_id=$user_id")
            ->andWhere("MessageTask.status=0")
            ->leftJoin('Tasks', 'Tasks.id = MessageTask.task_id')
            ->leftJoin('Admin', 'Admin.id = MessageTask.send_id')
            ->leftJoin('Admin', 'r.id = MessageTask.rec_id', 'r')
            ->leftJoin('Admin', 'c.id = Tasks.creator', 'c')
            ->leftJoin('Admin', 'rec.id = Tasks.receiver', 'rec')
            ->orderBy("Tasks.id, timestamp DESC")
            ->execute()->toarray();
        return $data;
    }

    public static function findAllMes($task_id) {
        $data = MessageTask::query()
            ->columns(array('Admin.name as sender', 'Admin.avatar as avatar', 'MessageTask.message', 'MessageTask.timestamp as date'))
            ->where("task_id=$task_id")
            ->leftJoin('Admin', 'Admin.id = MessageTask.send_id')
            ->execute()->toArray();
        return $data;
    }

    public static function taskInfo($task_id) {
        $data = Tasks::query()
            ->columns(array('Admin.name as creator', 'r.name as receiver', 'Tasks.title'))
            ->where("Tasks.id=$task_id")
            ->leftJoin('Admin', 'Admin.id = Tasks.creator')
            ->leftJoin('Admin', 'r.id = Tasks.receiver', 'r')
            ->execute()->toArray();
        return $data;
    }
}