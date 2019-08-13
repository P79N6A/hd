<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Queues extends Model {

	const PAGE_SIZE = 50;
	
    //新增
    const STATUS_NEW = 0;

    //完成
    const STATUS_DONE = 1;

    //执行中
    const STATUS_WORKING = 2;

    //执行失败
    const STATUS_FAILED = 9;

    /**
     * 任务 - 推送
     */
    const TASK_PUSH = 'push';

    /**
     * 任务 - 发布
     */
    const TASK_PUBLISH = 'publish';

    public function getSource() {
        return 'queues';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'task_type', 'task_data', 'created_at', 'updated_at', 'remark', 'status','single','push_single_client','push_terminal','push_content',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'task_type', 'task_data', 'created_at', 'updated_at', 'remark', 'status','single','push_single_client','push_terminal','push_content',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'task_type', 'task_data', 'created_at', 'updated_at', 'remark', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'task_type' => Column::TYPE_VARCHAR,
                'task_data' => Column::TYPE_TEXT,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'remark' => Column::TYPE_TEXT,
                'status' => Column::TYPE_INTEGER,
                'single'=> Column::TYPE_INTEGER,
                'push_single_client'=> Column::TYPE_VARCHAR,
                'push_terminal'=> Column::TYPE_INTEGER,
                'push_content'=> Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'created_at', 'updated_at', 'status','single','push_terminal',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'task_type' => Column::BIND_PARAM_STR,
                'task_data' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'remark' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'single'=> Column::BIND_PARAM_INT,
                'push_single_client'=> Column::BIND_PARAM_STR,
                'push_terminal'=> Column::BIND_PARAM_INT,
                'push_content'=> Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'task_type' => '',
                'status' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * 添加推送任务
     *
     * @param $channel_id
     * @param $data_id
     */
    public static function addPushTask($channel_id, $data_id) {
        self::addTask($channel_id, self::TASK_PUSH, compact('data_id'));
    }

    public static function addPublishTask($channel_id, $data_id) {
        self::addTask($channel_id, self::TASK_PUBLISH, compact('data_id'));
    }

    /**
     * 添加任务
     *
     * @param $channel_id
     * @param $type
     * @param array $task_data
     */
    public static function addTask($channel_id, $type, array $task_data) {
        $task_data = json_encode($task_data);
        $time = time();
        $data = [
            'channel_id' => $channel_id,
            'task_type' => $type,
            'task_data' => $task_data,
            'request_data' => '',
            'response_data' => '',
            'created_at' => $time,
            'updated_at' => $time,
            'remark' => '',
            'status' => self::STATUS_NEW,
        ];
        $queue = new self;
        $queue->save($data);
    }
    
    /**
     * 保存推送数据
     *
     * @param $channel_id
     * @param $type
     * @param array $task_data
     */
    public static function savePushTask($channel_id, $type, array $task_data,$status,$remark,array $clientArr) {
    	$task_data = json_encode($task_data);
    	$time = time();
    	$data = [
    	'channel_id' => $channel_id,
    	'task_type' => $type,
    	'task_data' => $task_data,
    	'created_at' => $time,
    	'updated_at' => $clientArr['push_timestamp'],
    	'remark' => $remark,
    	'status' => $status,
    	'single' => $clientArr['push_single'],
    	'push_single_client' => $clientArr['push_single_client'],
    	'push_terminal' => $clientArr['push_terminal'],
    	'push_content' => $clientArr['push_content'],
    	];
    	$queue = new self;
    	return $queue->save($data);
    }
    
    public static function getUnprocessedTasks($type, $limit = 10) {
        DB::begin();
        $rs = self::query()
            ->andCondition('task_type', $type)
            ->andCondition('status', 0)
            ->orderBy('created_at ASC')
            ->limit($limit)
            ->execute();
        if (count($rs)) {
            foreach ($rs as $r) {
                $r->status = self::STATUS_WORKING;
                $r->save();
            }
        }
        DB::commit();
        return $rs;
    }

    /**
     * 根据updateed_at字段  定时定时推送
     * @param unknown $type
     * @param number $limit
     * @return unknown
     */
    public static function findUnprocessTasks($type) {
    	DB::begin();
    	$pushTime = time();
    	$rs = self::query()->where("Queues.status = 0");
    	$rs = $rs->andWhere("Queues.updated_at <= '{$pushTime}'");
    	$rs = $rs->andWhere("Queues.task_type = '{$type}'");
    	$rs = $rs->orderBy('created_at ASC')
    	->execute();
    	if (count($rs)) {
    		foreach ($rs as $r) {
    			$r->status = self::STATUS_WORKING;
    			$r->save();
    		}
    	}
    	DB::commit();
    	return $rs;
    }
    
    /**
     * @param string $remark
     */
    public function failedFor($remark) {
        $this->status = self::STATUS_FAILED;
        $this->remark = $remark;
        $this->updated_at = time();
        return $this->save();
    }

    /**
     * @param string $remark
     */
    public function done($remark = '') {
        $this->status = self::STATUS_DONE;
        $this->remark = $remark;
        $this->updated_at = time();
        return $this->save();
    }

    /**
     * 查询所有推送信息
     * @return unknown
     */
    public static function findAll($channelId) {
    	$result = self::query()
    		->andCondition('channel_id', $channelId)
    		->orderBy('created_at desc')
   			->paginate(self::PAGE_SIZE, 'Pagination');
    	return $result;
    }
    
    /**
     * 获取一条数据
     * @param unknown $id
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function getOne($id) {
    	return self::query()
    	->andCondition('id', $id)
    	->first();
    }
    
    /**
     * 搜索数据
     * @param unknown $data
     */
    public static function searchPushMsg($data) {
    	$push_content = $data['pushTitle'];
    	$status = $data['status'];
    	 
    	$query = self::query();
    	if ($push_content) {
    		$query = $query->orWhere("Queues.push_content like '%$push_content%'");
    	}
    	 
    	if($status >= 0) {
    		$query = $query->orWhere("Queues.status = '{$status}'");
    	}
    	 
    	return $query->paginate(self::PAGE_SIZE, 'Pagination');
    }
    
    /**
     * 提给客户端显示
     * @return unknown
     */
    public static function apiFindAll($page, $per_page) {
    	return self::find(array(
    			'columns' => "id,task_data",
    			'limit' => $per_page,
    			'order' => "created_at desc",
    			'offset' => ($page - 1) * $per_page
    	));
    }
}