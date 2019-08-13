<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class VideoFiles extends Model {

    public function getSource() {
        return 'video_files';
    }

    /**
     * @param $video_id
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function apiGetFileByVideo($video_id) {
        return self::query()->andCondition('video_id', $video_id)->andCondition('format', 'mp4')->orderBy('rate desc')->execute();
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'video_id', 'path', 'rate', 'format', 'height', 'width', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['video_id', 'path', 'rate', 'format', 'height', 'width',],
            MetaData::MODELS_NOT_NULL => ['id', 'video_id', 'path', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'video_id' => Column::TYPE_INTEGER,
                'path' => Column::TYPE_VARCHAR,
                'rate' => Column::TYPE_VARCHAR,
                'format' => Column::TYPE_VARCHAR,
                'height' => Column::TYPE_VARCHAR,
                'width' => Column::TYPE_VARCHAR,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'video_id', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'video_id' => Column::BIND_PARAM_INT,
                'path' => Column::BIND_PARAM_STR,
                'rate' => Column::BIND_PARAM_STR,
                'format' => Column::BIND_PARAM_STR,
                'height' => Column::BIND_PARAM_STR,
                'width' => Column::BIND_PARAM_STR,
                'partition_by' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'path' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findByVideoId($video_id) {
        return self::query()->andCondition('video_id', $video_id)->first();
    }
    
    public static function findVideoByVideoId($video_id) {
    	$query = self::query()
    		->andCondition('video_id', $video_id)
    		->execute()->toArray();
    	return $query;
    }
    

}