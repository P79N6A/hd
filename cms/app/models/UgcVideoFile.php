<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UgcVideoFile extends Model {

    public function getSource() {
        return 'ugc_video_file';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'stream_id', 'created_at', 'start_time', 'end_time', 'duration', 'video_url','req_str','rep_str'
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['stream_id', 'created_at', 'start_time', 'end_time', 'duration',
                'video_url','req_str','rep_str'],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'stream_id' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'start_time' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'duration' => Column::TYPE_INTEGER,
                'video_url' => Column::TYPE_VARCHAR,
                'req_str' => Column::TYPE_VARCHAR,
                'rep_str' => Column::TYPE_VARCHAR,

            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'stream_id', 'created_at', 'start_time', 'end_time',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'stream_id' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'start_time' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'duration' => Column::BIND_PARAM_INT,
                'video_url' => Column::BIND_PARAM_STR,
                'req_str' => Column::BIND_PARAM_STR,
                'rep_str' => Column::BIND_PARAM_STR,
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

    public static function findAll($conditions = []) {
        $criteria = UgcVideoFile::query();
        $criteria
            ->join("UgcStream", "UgcStream.id = UgcVideoFile.stream_id")
            ->join("Admin", "UgcStream.admin_id = Admin.id")
            ->columns(array('UgcStream.*', 'UgcVideoFile.*', 'Admin.*'))
            ->order("UgcVideoFile.id DESC");
        if(!empty($conditions))
        {
            foreach($conditions as $c)
            {
                $criteria->andWhere($c);
            }
        }
        return $criteria->paginate(25, 'Pagination');
    }

    public static function findByStreamId($stream_id) {
        return self::query()->where(array("stream_id ={$stream_id}"))->order("id DESC")->paginate(25, 'Pagination');
    }

    /*
     * @desc 获取点播文件地址
     * */
    public static function getVideoUrlByStream($stream_id, $start_time, $end_time) {
        return UgcVideoFile::query()->where("stream_id = $stream_id")
            ->andWhere("start_time=$start_time")
            ->andWhere("end_time=$end_time")
            ->first();
    }


}