<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AlbumTmp extends Model {

    public function getSource() {
        return 'album_tmp';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'author_id', 'code', 'path', 'intro', 'sort', 'created_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['author_id', 'code', 'path', 'intro', 'sort', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'author_id', 'code', 'path', 'intro', 'sort', 'created_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'author_id' => Column::TYPE_INTEGER,
                'code' => Column::TYPE_VARCHAR,
                'path' => Column::TYPE_VARCHAR,
                'intro' => Column::TYPE_TEXT,
                'sort' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'author_id', 'sort', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'author_id' => Column::BIND_PARAM_INT,
                'code' => Column::BIND_PARAM_STR,
                'path' => Column::BIND_PARAM_STR,
                'intro' => Column::BIND_PARAM_STR,
                'sort' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'sort' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * 重新排序
     * @param array $ids
     * @param Data $data
     * @return array
     */
    public static function reSort(array $ids) {
        DB::begin();
        $messages = [];
        try {
            if (!empty($ids)) {
                foreach ($ids as $key => $id) {
                    if (empty($id)) continue;
                    $model = self::findFirst($id);
                    $model->sort = $key;
                    $msg = $model->update();
                }
            }
            DB::commit();
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            $_m = $e->getMessage();
            $msgs = $$_m->getMessages();
            foreach ($msgs as $msg) {
                $messages[] = $msg->getMessage();
            }
        }
        return $messages;

    }

}