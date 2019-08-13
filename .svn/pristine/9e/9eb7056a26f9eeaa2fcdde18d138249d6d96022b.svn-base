<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CdnMainLog extends Model {

    public function getSource() {
        return 'cdn_main_log';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'title', 'task_user_id', 'task_id', 'operation', 'item_num', 'content', 'cdn_id', 'status', 'create_time', 'admin_user_id', 'admin_user_name', 'update_time', 'end_time', 'is_del',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'title', 'task_user_id', 'task_id', 'operation', 'item_num', 'content', 'cdn_id', 'status', 'create_time', 'admin_user_id', 'admin_user_name', 'update_time', 'end_time', 'is_del',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'title', 'task_user_id', 'task_id', 'operation', 'item_num', 'content', 'cdn_id', 'status', 'create_time', 'update_time', 'end_time', 'is_del',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'task_user_id' => Column::TYPE_INTEGER,
                'task_id' => Column::TYPE_INTEGER,
                'operation' => Column::TYPE_INTEGER,
                'item_num' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
                'cdn_id' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
                'create_time' => Column::TYPE_INTEGER,
                'admin_user_id' => Column::TYPE_INTEGER,
                'admin_user_name' => Column::TYPE_VARCHAR,
                'update_time' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'is_del' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'task_user_id', 'task_id', 'operation', 'item_num', 'status', 'create_time', 'admin_user_id', 'update_time', 'end_time', 'is_del',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'task_user_id' => Column::BIND_PARAM_INT,
                'task_id' => Column::BIND_PARAM_INT,
                'operation' => Column::BIND_PARAM_INT,
                'item_num' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'cdn_id' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'create_time' => Column::BIND_PARAM_INT,
                'admin_user_id' => Column::BIND_PARAM_INT,
                'admin_user_name' => Column::BIND_PARAM_STR,
                'update_time' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'is_del' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'task_user_id' => '1',
                'task_id' => '0',
                'operation' => '1',
                'item_num' => '1',
                'status' => '1',
                'create_time' => '0',
                'admin_user_id' => '0',
                'update_time' => '0',
                'end_time' => '0',
                'is_del' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    const PAGE_SIZE = 50;
    const CDN_OPERATION_NEW = 1;//下发
    const CDN_OPERATION_UPDATE = 2;//更新
    const CDN_OPERATION_DEL = 3;//删除
    const CDN_OPERATION_CHECK = 4;//校验MD5
    const CDN_OPERATION_RENAME = 5;//重命名
    const CDN_OPERATION_UNZIP = 6;//解压
    
    const CDN_TASK_STATUS_WAIT = 1;//待分发
    const CDN_TASK_STATUS_ING = 2;//分发中
    const CDN_TASK_STATUS_SUCESS = 3;//分发成功
    const CDN_TASK_STATUS_FAIL = 4;//分发失败
   
    /**
     * CDN分发任务操作类型 cdnOperation  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $cdnOperation = [
        self::CDN_OPERATION_NEW => '下发',
        self::CDN_OPERATION_UPDATE => '更新',
        self::CDN_OPERATION_DEL => '下架',
    ];
    
    /**
     * CDN分发任务操作类型 cdnOperationCode  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $cdnOperationCode = [
        self::CDN_OPERATION_NEW => 'publish',//发布文件，如果发布已经发布的视频，应该默认为更新
        self::CDN_OPERATION_UPDATE => 'update',
        self::CDN_OPERATION_DEL => 'delete',
        self::CDN_OPERATION_CHECK => 'check',
        self::CDN_OPERATION_RENAME => 'rename',
        self::CDN_OPERATION_UNZIP => 'unzip',
    ];
    
    /**
     * CDN分发任务状态 cdnTaskStatus  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $cdnTaskStatus = [
        self::CDN_TASK_STATUS_WAIT => '待分发',
        self::CDN_TASK_STATUS_ING => '分发中',
        self::CDN_TASK_STATUS_SUCESS => '分发成功',
        self::CDN_TASK_STATUS_FAIL => '分发失败',
    ];
    
    
     //检验表单信息
    public static function makeValidator($inputs){
        $validator = Validator::make(
            $inputs, [
            'title' => 'required',
            'task_id' => 'required',
            'operation' => 'required',
            'content' => 'required',
            'cdn_id' => 'required',
            'status' => 'required'
        ], [
                'title.required' => '标题不能为空',
                'task_id.required' => '来源系统任务唯一标识不能为空',
                'operation.required' => '操作类型不能为空',
                'content.required'=>'任务主体json不能为空',
                'cdn_id.required'=>'目标cdn不能为空',
                'status.required'=>'当前状态不能为空'
            ]
        );
        return $validator;
    }
    
    //增加操作
    //主任务对象进行添加
    public function createCdnMainLog($data) {
        isset($data['id'])?$data['id']=null:true;
        $this->assign($data);
        return ($this->save()) ? $this->id:false;
    }
    
    public static function getCdnMainLogByTime($time) {
        $data = self::query()
                    ->columns('id,title')
                    ->andWhere("create_time >'{$time}'")
                    ->andWhere("status = 1")
                    ->andWhere("is_del = 0")
                    ->orderBy('id asc')
                    ->limit(1)
                    ->execute()        
                    ->toArray();
        return $data;
    }
    
    //该版本仅支持每次取1条主任务
    public static function getCdnMainLogByTimeFirst($time) {
        $data = self::query()
                    ->columns('id,title,cdn_id')
                    ->andWhere("create_time >'{$time}'")
                    ->andWhere("status = 1")
                    ->andWhere("is_del = 0")
                    ->orderBy('id asc')
                    ->first();
        if($data){
            return $data->toArray();
        }else{
            return FALSE;
        }            
                    
    }
    
    
    public function findOneObject($id){
        $object = CdnMainLog::query()->where("id='{$id}'")->first();
        return $object;
    }
    
    public function modifyCdnMainLog($data){
        $this->assign($data);
        return ($this->update())?true:false;
    }
    //获取CDN分发任务操作类型code码
    public static function operationCodeList() {
        return self::$cdnOperationCode;
    }
    
    //获取CDN分发任务操作类型byid
    public static function getOperationCodeById($operation_id) {
        return self::$cdnOperationCode[$operation_id];
    }
    
    //获取CDN分发任务操作类型
    public static function operationList() {
        return self::$cdnOperation;
    }
    
    //获取CDN分发任务操作类型byid
    public static function getOperationById($operation_id) {
        return self::$cdnOperation[$operation_id];
    }
    
    //获取cdn分发状态
    public static function taskStatusList($status_id=false) {
        if($status_id){
            return self::$cdnTaskStatus[$status_id];
        }else{
            return self::$cdnTaskStatus;
        }
    }
    
    public static function findAll($channel_id) {
        return self::query()
                ->andWhere("is_del = 0")
                ->andWhere("channel_id= '{$channel_id}'")
                ->orderBy('id desc')
                ->paginate(self::PAGE_SIZE, 'Pagination');
    }
    
    
    public static function search($data,$channel_id){
        $keyword = $data['keyword'];
        $cdn_id = $data['cdn_id']?:'';
        $operation = $data['operation']?:'';
        $status = $data['status']?:'';
        $admin_user_name = $data['admin_user_name']?:'';
        $create_time = $data['create_time']?  strtotime($data['create_time']):'';
        $end_time = $data['end_time']?  strtotime($data['end_time']):'';
        
        $query = CdnMainLog::query();
        if($keyword){
            $query = $query->andWhere("title like '%$keyword%'");
        }
        if($cdn_id){
            $query = $query->andWhere("cdn_id = '{$cdn_id}'");
        }
        if($operation){
            $query = $query->andWhere("operation = '{$operation}'");
        }
        if($status){
            $query = $query->andWhere("status = '{$status}'");
        }
        if($admin_user_name){
            $query = $query->andWhere("admin_user_name like '%$admin_user_name%'");
        }
        if($create_time){
            $query = $query->andWhere("create_time > '{$create_time}'");
        }
        if($end_time){
            $query = $query->andWhere("end_time < '{$end_time}'");
        }
        return $query->andWhere("channel_id= '{$channel_id}'")->andWhere("is_del = 0")->orderBy('id desc')->paginate(self::PAGE_SIZE, 'Pagination');
    }
    
    /*
     * 逻辑删除
     */
    public static function delById($main_id) {
        $CdnMainLog = new CdnMainLog();
        $main_log = $CdnMainLog->findOneObject($main_id);
        $data['is_del'] = 1;
        $data['admin_user_id']  = Session::get('user')->id;
        $data['admin_user_name']  = Session::get('user')->name;
        $data['update_time']  = time();
        if($main_log->modifyCdnMainLog($data)){
            $CdnUserOperationLog = new CdnUserOperationLog();
            $CdnUserOperationLog->createUserLog(1, 3, $main_id);
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
    public function updateById($id,$data,$update_item=FALSE) {
        $main_log = self::findOneObject($id);
        $main_log->modifyCdnMainLog($data);
        if($update_item){
            $items = CdnDetailLog::getCdnDetailLogByMainId($id);
            foreach ($items as $value) {
               CdnDetailLog::updateById($value['id'],$data);
           }   
        }
        return TRUE;
    }
    
    //根据主任务main_id 重新分发 下架
    public static function pushById($main_id,$type){
        //修改最后一次更新时间和人物
        $main_data['admin_user_id']  = Session::get('user')->id;
        $main_data['admin_user_name']  = Session::get('user')->name;
        $main_data['update_time']  = time();
        $CdnMainLog = new CdnMainLog();
        $main_log = $CdnMainLog->findOneObject($main_id);
        $main_log->modifyCdnMainLog($main_data);
        
        $cdn_id = $main_log->cdn_id;
        $details = CdnDetailLog::getCdnDetailByMainId($main_id); //全部重新发 并不是只发送status=1的
        $operation = ($type==2) ? 'delete' : '';
        switch ($cdn_id) {
                    case 1:
                        $CdnProducer = new CdnProducer();
                        $CdnProducer->pushYF($details,$cdn_id,$main_id,$operation);
                        break;
                    case 2:
                        $CdnProducer = new CdnProducer();
                        $CdnProducer->pushYF2($details,$cdn_id,$main_id);
                    default:
                        break;
        }
        
        $CdnUserOperationLog = new CdnUserOperationLog();
        $data_type = 1;// 操作对象类型 1：主任务 2：子任务
        $CdnUserOperationLog->createUserLog($data_type, $type, $main_id);
        return TRUE;
            
    }
    
}
