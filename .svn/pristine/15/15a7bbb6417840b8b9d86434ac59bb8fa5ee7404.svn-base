<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Favorites extends Model {

    public function getSource() {
        return 'favorites';
    }

    /**
     * @param $channel_id
     * @param $data_id
     * @param $user_id
     * @param int $type
     * @return bool
     */
    public static function apiGetFavoritesById($channel_id, $data_id, $user_id, $type = 1) {
        $data = self::query()
            ->andCondition('data_id', $data_id)
            ->andCondition('user_id', $user_id)
            ->andCondition('type', $type)
            ->first();
        if (!$data) {
            $model = new Favorites();
            return $model->save([
                'channel_id' => $channel_id,
                'user_id' => $user_id,
                'type' => $type,
                'data_id' => $data_id,
                'create_at' => time()
            ]);
        } else {
            return true;
        }
    }

    /**
     * @param $data_id
     * @param $user_id
     * @param int $type
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function apiIsFavorite($data_id, $user_id, $type = 1) {
        return self::query()
            ->andCondition('data_id', $data_id)
            ->andCondition('user_id', $user_id)
            ->andCondition('type', $type)
            ->first();
    }

    /**
     * @param $channel_id
     * @param $user_id
     * @param int $per_page
     * @param int $page
     * @return mixed
     */
    public static function apiGetFavorites($channel_id, $user_id, $per_page = 10, $page = 1) {
        $key = D::memKey('apiGetFavorites', [
            'channel_id' => $channel_id,
            'user_id' => $user_id,
            'per_page' => $per_page,
            'page' => $page,
        ]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $query = self::query()
                ->columns(['Data.*'])
                ->leftJoin("Data", "Data.id = Favorites.data_id")
                ->andWhere("Favorites.channel_id = {$channel_id}")
                ->andWhere("Favorites.user_id = {$user_id}")
                ->andWhere("Data.status=1");
            $query = $query->orderBy('Favorites.create_at desc');
            $rs = $query
                ->paginate($per_page, '\GenialCloud\Helper\Pagination', $page)
                ->models;
            $data = [];
            if (!empty($rs)) {
                $data = $rs->toArray();
            }
            MemcacheIO::set($key, $data);
        }
        return $data;
    }

    /**
     * @param $data_id
     * @param $user_id
     * @param int $type
     * @return bool
     */
    public static function apiDelFavoritesById($data_id, $user_id, $type = 1) {
        $data = self::query()
            ->andCondition('data_id', $data_id)
            ->andCondition('user_id', $user_id)
            ->andCondition('type', $type)
            ->execute();
        return $data->delete();
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'user_id', 'type', 'data_id', 'create_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'user_id', 'type', 'data_id', 'create_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'user_id', 'type', 'data_id', 'create_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'create_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'user_id', 'type', 'data_id', 'create_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'create_at' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'type' => '1',
                'create_at' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

}