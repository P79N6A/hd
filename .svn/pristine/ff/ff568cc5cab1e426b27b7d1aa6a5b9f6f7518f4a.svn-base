<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CdnReturnLog extends Model {

    public function getSource() {
        return 'cdn_return_log';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'cdn_id', 'type', 'content', 'create_time',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['cdn_id', 'type', 'content', 'create_time',],
            MetaData::MODELS_NOT_NULL => ['id', 'cdn_id', 'type', 'create_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'cdn_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
                'create_time' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'cdn_id', 'type',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'cdn_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'create_time' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'cdn_id' => '1',
                'type' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public function createCdnReturnLog($data) {
        isset($data['id'])?$data['id']=null:true;
        $this->assign($data);
        return ($this->save()) ? true:false;
    }
    
    /*
     * $cdn_id 对应cdn_id
     * $type 断点的位置：0:同步回调 1：异步回调
     * $content 内容
     */
    public function createReturnLog($cdn_id,$type,$content) {
        $CdnReturnLog = new CdnReturnLog();
        $ReturnLog =array(
                'cdn_id'=>$cdn_id,
                'type'=>$type,
                'content'=>$content,
                'create_time'=>date('Y-m-d H:i:s',time())
            );
        if($CdnReturnLog->createCdnReturnLog($ReturnLog)){
            return TRUE;
        }else{
            return FALSE;
        }
    }
}