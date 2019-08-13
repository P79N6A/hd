<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CdnDetailLog extends Model {

    public function getSource() {
        return 'cdn_detail_log';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'main_id', 'task_user_id', 'item_id', 'operation', 'cdn_id', 'file_type', 'source_path', 'publish_path', 'md5', 'file_size', 'ext_option','slice', 'file_level', 'content', 'status', 'status_str', 'update_time', 'admin_user_id', 'admin_user_name', 'is_del',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['main_id', 'task_user_id', 'item_id', 'operation', 'cdn_id', 'file_type', 'source_path', 'publish_path', 'md5', 'file_size', 'ext_option','slice', 'file_level', 'content', 'status', 'status_str', 'update_time', 'admin_user_id', 'admin_user_name', 'is_del',],
            MetaData::MODELS_NOT_NULL => ['id', 'main_id', 'task_user_id', 'item_id', 'operation','slice', 'cdn_id', 'file_type', 'file_level', 'content', 'status', 'update_time', 'admin_user_id', 'is_del',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'main_id' => Column::TYPE_INTEGER,
                'task_user_id' => Column::TYPE_INTEGER,
                'item_id' => Column::TYPE_INTEGER,
                'operation' => Column::TYPE_INTEGER,
                'cdn_id' => Column::TYPE_VARCHAR,
                'file_type' => Column::TYPE_INTEGER,
                'source_path' => Column::TYPE_VARCHAR,
                'publish_path' => Column::TYPE_VARCHAR,
                'md5' => Column::TYPE_VARCHAR,
                'file_size' => Column::TYPE_INTEGER,
                'ext_option' => Column::TYPE_VARCHAR,
                'slice' => Column::TYPE_INTEGER,
                'file_level' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
                'status' => Column::TYPE_INTEGER,
                'status_str' => Column::TYPE_TEXT,
                'update_time' => Column::TYPE_INTEGER,
                'admin_user_id' => Column::TYPE_INTEGER,
                'admin_user_name' => Column::TYPE_VARCHAR,
                'is_del' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'main_id', 'task_user_id', 'item_id', 'operation','slice', 'file_type', 'file_size', 'file_level', 'status', 'update_time', 'admin_user_id', 'is_del',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'main_id' => Column::BIND_PARAM_INT,
                'task_user_id' => Column::BIND_PARAM_INT,
                'item_id' => Column::BIND_PARAM_INT,
                'operation' => Column::BIND_PARAM_INT,
                'cdn_id' => Column::BIND_PARAM_STR,
                'file_type' => Column::BIND_PARAM_INT,
                'source_path' => Column::BIND_PARAM_STR,
                'publish_path' => Column::BIND_PARAM_STR,
                'md5' => Column::BIND_PARAM_STR,
                'file_size' => Column::BIND_PARAM_INT,
                'ext_option' => Column::BIND_PARAM_STR,
                'slice' => Column::BIND_PARAM_INT,
                'file_level' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'status_str' => Column::BIND_PARAM_STR,
                'update_time' => Column::BIND_PARAM_INT,
                'admin_user_id' => Column::BIND_PARAM_INT,
                'admin_user_name' => Column::BIND_PARAM_STR,
                'is_del' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'main_id' => '0',
                'task_user_id' => '1',
                'item_id' => '0',
                'operation' => '1',
                'slice'=>'0',
                'file_type' => '1',
                'file_level' => '0',
                'status' => '1',
                'update_time' => '0',
                'admin_user_id' => '0',
                'is_del' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    //检验表单信息
    public static function makeValidator($inputs){
        $validator = Validator::make(
            $inputs, [
            'main_id' => 'required',
            'task_user_id' => 'required',
            'item_id' => 'required',
            'operation' => 'required',
            'cdn_id' => 'required',
            'content' => 'required',
            'status' => 'required'
        ], [
                'main_id.required' => '主任务不能为空',
                'task_user_id.required' => '任务发起者id不能为空',
                'item_id.required' => '同一条主任务的子任务之间区分id不能为空',
                'operation.required'=>'任务主体不能为空',
                'cdn_id.required'=>'目标cdn不能为空',
                'content.required'=>'文件主体json不能为空',
                'status.required'=>'当前状态不能为空',
            ]
        );
        return $validator;
    }
    
    //增加操作
    //子任务对象进行添加
    public function createCdnDetailLog($data) {
        isset($data['id'])?$data['id']=null:true;
        $this->assign($data);
        return ($this->save()) ? true:false;
    }
    
    public static function getCdnDetailLogByMainId($main_id) {
        $data = self::query()
                    ->columns('id,operation,slice,cdn_id,publish_path,source_path,md5,file_size,ext_option,status,update_time,admin_user_name')
                    ->andWhere("main_id ='{$main_id}'")
                    ->andWhere("status = 1")
                    ->andWhere("is_del = 0")        
                    ->orderBy('id desc')
                    ->execute()         
                    ->toArray();
        return $data;
    }
    
    public static function getCdnDetailByMainId($main_id) {
        $data = self::query()
                    ->columns('id,operation,cdn_id,publish_path,source_path,md5,file_size,ext_option,status,update_time,admin_user_name')
                    ->andWhere("main_id ='{$main_id}'")
                    ->andWhere("is_del = 0")        
                    ->orderBy('id desc')
                    ->execute()         
                    ->toArray();
        return $data;
    }
    
    public static function getDetailByMainId($main_id) {
        $data = self::query()
                    ->columns('id,operation,main_id,item_id,cdn_id,file_type,publish_path,source_path,status,update_time,admin_user_name')
                    ->andWhere("main_id ='{$main_id}'")
                    ->andWhere("is_del = 0")        
                    ->orderBy('id desc')
                    ->execute()         
                    ->toArray();
                    
        return $data;
    }
    
    public static function getDetailById($id) {
        $data = self::query()
                    ->columns('id,operation,publish_path,source_path,md5,file_size,ext_option,status,update_time,admin_user_name')
                    ->andWhere("id ='{$id}'")
                    ->andWhere("is_del = 0")        
                    ->orderBy('id desc')
                    ->execute()         
                    ->toArray();
                    
        return $data;
    }
    
    public function modifyDetailLog($data){
        $this->assign($data);
        return ($this->update())?true:false;
    }
    
    public function findOneObject($id){
        $object = CdnDetailLog::query()->where("id='{$id}'")->first();
        return $object;
    }
    
    public function modifyCdnDetailLog($data){
        $this->assign($data);
        return ($this->update())?true:false;
    }
    
    public function updateById($id,$data,$is_re=FALSE) {
        $detail_log = self::findOneObject($id);
        $detail_log->modifyCdnDetailLog($data);
        if($is_re){
            return $detail_log->main_id;
        }
    }
    
    
    const CDN_TASK_FILE_VIDEO = 1;//视频
    const CDN_TASK_FILE_RADIO = 2;//音频
    const CDN_TASK_FILE_IMG = 3;//图片
    const CDN_TASK_FILE_URL = 4;//网页
    
    protected static $cdnTaskFileType = [
        self::CDN_TASK_FILE_VIDEO => '视频',
        self::CDN_TASK_FILE_IMG => '图片',
        self::CDN_TASK_FILE_RADIO => '音频',
        self::CDN_TASK_FILE_URL => '网页',
    ];
    
    //获取CDN分发任务操作类型
    public static function taskFileTypeList() {
        return self::$cdnTaskFileType;
    }
    
    //获取CDN分发任务操作类型byid
    public static function taskFileTypeListByOne($type_id) {
        return self::$cdnTaskFileType[$type_id];
    }
    
    //根据子任务id 重新分发 下架
    public static function pushById($id,$type){
        $CdnDetailLog = new CdnDetailLog();
        //修改最后一次更新时间和人物
        $detail_data['admin_user_id']  = Session::get('user')->id;
        $detail_data['admin_user_name']  = Session::get('user')->name;
        $detail_data['update_time']  = time();
        $operation = ($type==2) ? 'delete' : '';
        $detail_log = $CdnDetailLog->findOneObject($id);
        $detail_log->modifyCdnDetailLog($detail_data);
        
        $main_id = $detail_log->main_id;
        $cdn_id = $detail_log->cdn_id;
        $item_ar = $CdnDetailLog->getDetailById($id);
        
        switch ($cdn_id) {
                    case 1:
                        $CdnProducer = new CdnProducer();
                        $CdnProducer->pushYF($item_ar,$cdn_id,$main_id,$operation);
                        break;
                    case 2:
                        $CdnProducer = new CdnProducer();
                        $CdnProducer->pushYF2($item_ar,$cdn_id,$main_id);
                    default:
                        break;
        }
        
        $CdnUserOperationLog = new CdnUserOperationLog();
        $data_type = 2;// 操作对象类型 1：主任务 2：子任务
        $CdnUserOperationLog->createUserLog($data_type, $type, $id);
        return TRUE;
          
    }
    
    //根据子任务id逻辑删除
    public static function delById($id) {
        $CdnDetailLog = new CdnDetailLog();
        $detail_log = $CdnDetailLog->findOneObject($id);
        $data['is_del'] = 1;
        $data['admin_user_id']  = Session::get('user')->id;
        $data['admin_user_name']  = Session::get('user')->name;
        $data['update_time']  = time();
        $main_id = $detail_log->main_id;
        if($detail_log->modifyDetailLog($data)){
            
            //子任务隐藏了一个，主任务相应字段-1
            $CdnMainLog = new CdnMainLog();
            $main_log = $CdnMainLog->findOneObject($main_id);
            $data2['item_num'] = $main_log->item_num - 1;
            $main_log->modifyCdnMainLog($data2);
            
            $CdnUserOperationLog = new CdnUserOperationLog();
            $CdnUserOperationLog->createUserLog(2, 3, $id);
            return TRUE;
        }else{
            return FALSE;
        }
    }
    
}