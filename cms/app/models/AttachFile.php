<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AttachFile extends Model {

    const  TYPE_OTHER = 0; //未知
    const  TYPE_IMAGE = 1; //图片
    const  TYPE_VIDEO = 2; //视频
    const  TYPE_DOCUMENT = 3; //文档



    private static $extToFile = [
        self::TYPE_IMAGE => "jpeg,jpg,png,gif,webp,bmp", //图片后缀名
        self::TYPE_VIDEO => "mp4,ogg,mov,flv", //视频后缀名
        self::TYPE_DOCUMENT => "txt,pdf,docx,doc,ppt,pptx,xls,xlsx", //文档后缀名
    ];

    public function getSource() {
        return 'attach_file';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'attach_id', 'path', 'intro', 'ext', 'file_type', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['attach_id', 'path', 'intro', 'ext', 'file_type',],
            MetaData::MODELS_NOT_NULL => ['id', 'attach_id', 'path', 'intro', 'ext', 'file_type', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'attach_id' => Column::TYPE_INTEGER,
                'path' => Column::TYPE_VARCHAR,
                'intro' => Column::TYPE_TEXT,
                'ext' => Column::TYPE_VARCHAR,
                'file_type' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'attach_id', 'file_type', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'attach_id' => Column::BIND_PARAM_INT,
                'path' => Column::BIND_PARAM_STR,
                'intro' => Column::BIND_PARAM_STR,
                'ext' => Column::BIND_PARAM_STR,
                'file_type' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'intro' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }


    /** 创建附件文件
     * @function createAttachFile
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $attach_id
     * @param $files  文件路径和简介数组 如[ ["path"=>"aa.txt","intro"=>"aa"],["path"=>"bb.txt","intro"=>"bb"]  ] intro可以没有
     */
    public static function createAttachFile($attach_id, $files) {
        foreach ($files as $file){
            $attachFile = new AttachFile();
            $attachFile->attach_id = $attach_id;
            $attachFile->path = $file["path"];
            $attachFile->ext = self::getFileExt($file["path"]);
            if(isset($file["intro"]) ){
                $attachFile->intro = $file["intro"];
            }
            $attachFile->file_type = self::getFileType($attachFile->ext);
            $attachFile->partition_by = date("Y");
            $attachFile->save();
        }
    }

    /**获取文件后缀名
     * @function getFileExt
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $filename
     * @return string
     */
    public  static function getFileExt($filename) {
        return substr($filename, strrpos($filename, '.') + 1);
    }

    /**根据后缀名获取文件类型
     * @function getFileType
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $ext 文件后缀
     * @return int
     */
    public static function getFileType($ext) {
        $type = 0;
        foreach (self::$extToFile as $key => $extStr){
            if (strpos($extStr,strtolower($ext)) !== false) {
                $type = intval($key);
                break;
            }
        }
        return $type;
    }
}