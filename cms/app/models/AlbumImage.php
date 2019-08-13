<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AlbumImage extends Model {

    public function getSource() {
        return 'album_image';
    }

    public static function apiFindByData($album_id) {
        $key = D::memKey('apiFindByData', [
            'album_id' => $album_id,
        ]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('album_id', $album_id)
                ->execute()
                ->toArray();
            MemcacheIO::set($key, $data);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'album_id', 'path', 'intro', 'uploaded_time', 'sort', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['album_id', 'path', 'intro', 'uploaded_time', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'album_id', 'path', 'intro', 'uploaded_time', 'sort', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'album_id' => Column::TYPE_INTEGER,
                'path' => Column::TYPE_VARCHAR,
                'intro' => Column::TYPE_TEXT,
                'uploaded_time' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'album_id', 'uploaded_time', 'sort', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'album_id' => Column::BIND_PARAM_INT,
                'path' => Column::BIND_PARAM_STR,
                'intro' => Column::BIND_PARAM_STR,
                'uploaded_time' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
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
     * 保存单个图片
     *
     * @param int $album_id
     * @param array $r
     * @param int $partition_by
     * @return int
     */
    public function saveOne($album_id, &$r, $partition_by) {
        return $this->saveGetId([
            'album_id' => $album_id,
            'path' => $r['path'],
            'intro' => $r['intro'],
            'author_id' => Auth::user()->id,
            'author_name' => Auth::user()->name,
            'uploaded_time' => time(),
            'sort' => $r['sort'],
            'partition_by' => $partition_by,
        ]);
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
                    $model->sort = count($ids) - $key;
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

    public static function findByAlbumId($album_id) {
        return self::query()->andCondition('album_id', $album_id)->execute()->toArray();
    }


    public static function getAlbumImage($albumId) {
        $data = self::apiFindByData($albumId);
        $return = [];
        if (!empty($data)) {
            foreach ($data as $v) {
                $return[] = (false===stripos($v['path'], "image.xianghunet.com"))?cdn_url('image',$v['path']):$v['path'];
            }
        }
        return $return;
    }

    /**
     * 保存单个图片
     *
     * @param int $album_id
     * @param array $imgData
     * @param int $partition_by
     * @return int
     */
    public function saveImg($album_id, $imgData, $partition_by) {
        return $this->saveGetId([
            'album_id' => $album_id,
            'path' => $imgData['path'],
            'intro' => $imgData['intro'],
            'author_id' => $imgData['id'],
            'author_name' => $imgData['name'],
            'uploaded_time' => time(),
            'sort' => $imgData['sort'],
            'partition_by' => $partition_by,
        ]);
    }
}