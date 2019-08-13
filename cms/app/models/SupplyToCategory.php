<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SupplyToCategory extends Model {

    static $PAGE_SIZE = 50;

    public function getSource() {
        return 'supply_to_category';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'supply_category_id', 'category_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'supply_category_id', 'category_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'supply_category_id', 'category_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'supply_category_id' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'supply_category_id', 'category_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'supply_category_id' => Column::BIND_PARAM_INT,
                'category_id' => Column::BIND_PARAM_INT,
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

    /**
     * @param $channel_id
     * @param $supply_id
     * @return array
     */
    public static function getAimId($channel_id, $supply_id) {
        $rs = [];
        $data = self::query()->andCondition('channel_id', $channel_id)->andCondition('supply_category_id', $supply_id)->execute();
        if (!empty($data)) {
            foreach ($data as $v) {
                $rs[] = $v->category_id;
            }
        }
        return $rs;
    }

    /**
     * @return \GenialCloud\Support\Parcel
     * @throws \Phalcon\Mvc\Model\Exception
     */
    public static function findAll() {
        $channel_id = Session::get('user')->channel_id;
        return self::query()
//            ->andCondition('channel_id', $channel_id)
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }

}