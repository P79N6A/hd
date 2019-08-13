<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Album extends Model {
    use HasChannel;

    public function getSource() {
        return 'album';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'keywords', 'comment_type', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'keywords', 'comment_type', 'created_at', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'keywords', 'comment_type', 'created_at', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'keywords' => Column::TYPE_VARCHAR,
                'comment_type' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'comment_type', 'created_at', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
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
     * 安全更新的字段
     * @return array
     */
    public static function safeUpdateFields() {
        return ['updated_at', 'keywords', 'no_comment',];
    }

    /**
     * 携带频道ID查询
     *
     * @param $id
     * @param $channel_id
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function findWithChannel($id, $channel_id) {
        return self::channelQuery($channel_id)
            ->andCondition('id', $id)
            ->first();
    }

    /**
     * @param $id
     * @return array|bool
     */
    public static function getWithImages($id) {
        $r = self::findFirst($id);
        if ($r) {
            $r = $r->toArray();
            $r['images'] = AlbumImage::query()->andCondition('album_id', $id)->orderBy('sort DESC')->execute()->toArray();
        }
        return $r;
    }

    public static function findById($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        $data = Album::findFirst($parameters);
        return $data;
    }
}