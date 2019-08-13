<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UgcStream extends Model {

    public function getSource() {
        return 'ugc_stream';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'admin_id', 'stream', 'hls_url', 'play_url', 'rtmp_url', 'start_time', 'end_time', 'cdn_url1', 'cdn_url2', 'cdn_url3','is_pause',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['admin_id', 'stream', 'hls_url', 'play_url', 'rtmp_url', 'start_time', 'end_time', 'cdn_url1', 'cdn_url2', 'cdn_url3','is_pause',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'stream' => Column::TYPE_VARCHAR,
                'hls_url' => Column::TYPE_VARCHAR,
                'play_url' => Column::TYPE_VARCHAR,
                'rtmp_url' => Column::TYPE_VARCHAR,
                'start_time' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'cdn_url1' => Column::TYPE_VARCHAR,
                'cdn_url2' => Column::TYPE_VARCHAR,
                'cdn_url3' => Column::TYPE_VARCHAR,
                'is_pause' => Column::TYPE_INTEGER,

            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'admin_id', 'start_time', 'end_time','is_pause'
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
                'stream' => Column::BIND_PARAM_STR,
                'hls_url' => Column::BIND_PARAM_STR,
                'play_url' => Column::BIND_PARAM_STR,
                'rtmp_url' => Column::BIND_PARAM_STR,
                'start_time' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'cdn_url1' => Column::BIND_PARAM_STR,
                'cdn_url2' => Column::BIND_PARAM_STR,
                'cdn_url3' => Column::BIND_PARAM_STR,
                'is_pause' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'stream' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getAll($conditions = []) {

        $criteria = UgcStream::query();
        $criteria->join('Admin', "Admin.id = UgcStream.admin_id")
            ->order("Admin.mobile DESC")
            ->columns(array('Admin.*', 'UgcStream.*'));
        if(!empty($conditions))
        {
            foreach($conditions as $c)
            {
                $criteria->where($c);
            }
        }
        return $criteria->paginate(25, 'Pagination');
    }


    /*
     * @desc 按流名称获取主播推流的记录
     * */
    public static function getStreamByname($stream) {
        return UgcStream::query()->where("stream = '$stream'")->first();
    }


    public static function getOneByAdminId($admin_id)
    {
        $criterial = self::query();
        return $criterial->where("admin_id = {$admin_id}")->first();
    }
}