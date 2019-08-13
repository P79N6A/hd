<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class VideoCollections extends Model {

    public function getSource() {
        return 'video_collections';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'type', 'extra', 'keywords', 'comment_type', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'type', 'extra', 'keywords', 'comment_type', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'keywords', 'comment_type', 'created_at', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'extra' => Column::TYPE_TEXT,
                'keywords' => Column::TYPE_VARCHAR,
                'comment_type' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'type', 'comment_type', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'extra' => Column::BIND_PARAM_STR,
                'keywords' => Column::BIND_PARAM_STR,
                'comment_type' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'keywords' => '',
                'comment_type' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * @param $id
     * @return array|bool
     */
    public static function getWithVideos($id) {
        $r = self::findFirst($id);
        if ($r) {
            $r = $r->toArray();
            $videos = Videos::getByCollection($id);
            $r['videos'] = [];
            if ($videos) {
                $r['videos'] = $videos;
            }
        }
        return $r;
    }

    public static function getWithData($id) {
        $id = (int)$id;
        if (!$id) {
            return [];
        }
        $r = self::query()
            ->andWhere('VideoCollections.id = :id:', ['id' => $id])
            ->leftJoin('Data', "d.source = VideoCollections.id AND d.type = 'video_collection'", 'd')
            ->first();
        if ($r) {
            $r = $r->toArray();
        } else {
            $r = [];
        }
        return $r;
    }

}