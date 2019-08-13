<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CdnUserOperationLog extends Model {

    public function getSource() {
        return 'cdn_user_operation_log';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'admin_user_id', 'admin_user_name', 'data_type', 'data_id', 'data_status', 'create_time',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['admin_user_id', 'admin_user_name', 'data_type', 'data_id', 'data_status', 'create_time',],
            MetaData::MODELS_NOT_NULL => ['id', 'admin_user_id', 'admin_user_name', 'data_type', 'data_id', 'data_status', 'create_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'admin_user_id' => Column::TYPE_INTEGER,
                'admin_user_name' => Column::TYPE_VARCHAR,
                'data_type' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'data_status' => Column::TYPE_INTEGER,
                'create_time' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'admin_user_id', 'data_type', 'data_id', 'data_status', 'create_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'admin_user_id' => Column::BIND_PARAM_INT,
                'admin_user_name' => Column::BIND_PARAM_STR,
                'data_type' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'data_status' => Column::BIND_PARAM_INT,
                'create_time' => Column::BIND_PARAM_INT,
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

    public static function findOneObject($id){
        $object = CdnUserOperationLog::query()->where("id='{$id}'")->first();
        return $object;
    }
    
    public function createCdnUserOperationLog($data) {
        isset($data['id'])?$data['id']=null:true;
        $this->assign($data);
        return ($this->save()) ? true:false;
    }
    
    public function modifyCdnUserOperationLog($data){
        $this->assign($data);
        return ($this->update())?true:false;
    }
    
    /*
     * $data_type 操作对象类型 1：主任务 2：子任务
     * $data_status 操作类型 1重发 2强制下架 3逻辑删除
     * $id 被操作的数据id
     */
    public function createUserLog($data_type,$data_status,$id) {
        $CdnUserOperationLog = new CdnUserOperationLog();
        $OperationLog =array(
                'admin_user_id'=>Session::get('user')->id,
                'admin_user_name'=>Session::get('user')->name,
                'data_type'=>$data_type,
                'data_id'=>$id,
                'data_status'=>$data_status,
                'create_time'=>time()
            );
        if($CdnUserOperationLog->createCdnUserOperationLog($OperationLog)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}