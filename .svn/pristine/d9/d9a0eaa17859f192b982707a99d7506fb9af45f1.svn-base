<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UgcyunLiveVideo extends Model {

    public function getSource() {
        return 'ugcyun_live_video';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'rate', 'data_id','start_time','end_time', 'stream_id', 'file_url', 'file_flv_url',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['rate', 'data_id','start_time','end_time', 'stream_id', 'file_url', 'file_flv_url',],
            MetaData::MODELS_NOT_NULL => ['id', 'stream_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'rate' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'start_time' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'stream_id' => Column::TYPE_INTEGER,
                'file_url' => Column::TYPE_VARCHAR,
                'file_flv_url' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'rate', 'data_id','start_time','end_time', 'stream_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'rate' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'start_time' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'stream_id' => Column::BIND_PARAM_INT,
                'file_url' => Column::BIND_PARAM_STR,
                'file_flv_url' => Column::BIND_PARAM_STR,
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

    public static function findAll($streamids=0)
    {
        return self::query()->where('stream_id in ('.$streamids.')')->order('id DESC')->paginate(25,'Pagination');
    }


    public static function findInfoWithAnchor($admin_id=0)
    {
        if($admin_id) {
            $criterial = self::query();
            return $criterial->join("UgcLive","UgcLive.id = UgcLiveVideo.stream_id")
                ->join("Admin","Admin.id = UgcLive.admin_id")
                ->columns(array("Admin.*","UgcLive.*","UgcLiveVideo.*"))
                ->where("UgcLive.admin_id=".$admin_id)
                ->order('UgcLiveVideo.id DESC')->paginate(20,'Pagination');
        }
        else {
            $criterial = self::query();
            return $criterial->join("UgcLive","UgcLive.id = UgcLiveVideo.stream_id")
                ->join("Admin","Admin.id = UgcLive.admin_id")
                ->columns(array("Admin.*","UgcLive.*","UgcLiveVideo.*"))
                ->order('UgcLiveVideo.id DESC')->paginate(20,'Pagination');
        }
    }

    public static function deleteViedeoFile($id)
    {
        return UgcyunLiveVideo::findFirst($id)->delete();
    }


    public static function getVideosByStreamId($stream_id, $page, $per_page) {
        return self::find(array(
            "stream_id=:stream_id: and file_url <>'' ",
            'bind' => array('stream_id' => $stream_id),
            'limit' => $per_page,
            'order' => 'start_time desc',
            'offset' => ($page - 1) * $per_page
        ));
    }

}