<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Attachs extends Model {

    public function getSource() {
        return 'attachs';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
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

    /** 获取附件
     * @function getAttachFile
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $id
     * @return array|bool
     */
    public static function getAttachFileBySourceId($id) {
        $query = self::query()
            ->columns(["AttachFile.*"])
            ->leftJoin("AttachFile","AttachFile.attach_id = Attachs.id")
            ->where("Attachs.id = {$id}")
            ->execute();
        if($query){
            return $query->toArray();
        }else{
            return false;
        }
    }

    /** 获取附件
     * @function getAttachFile
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $data
     * @return array|bool
     */
    public static function getAttachFileByData($data) {
        $sourceIds = [];
        if($data->type == "attach"){
            $sourceIds[] = $data->source_id;
        }else if($data->type == "multimedia"){
            $data_data_ext = json_decode($data->data_data_ext,true);
            if(!$data_data_ext || !isset($data_data_ext["attach"])){
                return false;
            }

            foreach ($data_data_ext["attach"] as $attach){
                $dataId = $attach["data_id"];
                $attachData = Data::getReadQuery($dataId)->execute()->getFirst();
                if ($attachData){
                    $sourceIds[] = $attachData->source_id;
                }
            }
        }

        if(empty($sourceIds)){
            return false;
        }
        $attachFiles  = [];
        foreach ($sourceIds as $id){
            $tmpFiles = self::getAttachFileBySourceId($id);
            $attachFiles = array_merge($attachFiles,$tmpFiles);
        }
        return $attachFiles;
    }

    /**
     * @function createAttach
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $channel_id
     * @param $files 文件路径和简介数组 如[ ["path"=>"aa.txt","intro"=>"aa"],["path"=>"bb.txt","intro"=>"bb"]  ] intro可以没有
     * @return bool
     */
    public static function createAttach($channel_id, $files) {
        $attach = new Attachs();
        $attach->channel_id = $channel_id;
        $attach->partition_by = date("Y");

        $attach_id = $attach->saveGetId();
        if (!$attach_id){
            return false;
        }
        AttachFile::createAttachFile($attach_id,$files);
        return $attach_id;
    }

}