<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SupplyoutRsync extends Model {

    public function getSource() {
        return 'supplyout_rsync';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'channel_id', 'origin_type', 'origin_id', 'data_id', 'category_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['channel_id', 'origin_type', 'origin_id', 'data_id', 'category_id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['',],
            MetaData::MODELS_NOT_NULL => ['channel_id', 'origin_type', 'origin_id', 'data_id', 'category_id',],
            MetaData::MODELS_DATA_TYPES => [
                'channel_id' => Column::TYPE_INTEGER,
                'origin_type' => Column::TYPE_INTEGER,
                'origin_id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'channel_id', 'origin_type', 'origin_id', 'data_id', 'category_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'channel_id' => Column::BIND_PARAM_INT,
                'origin_type' => Column::BIND_PARAM_INT,
                'origin_id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
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
     * ��Ӧ��ķ����ѯ��������������ʱ��飬���շ��Ƿ�����ѽ��չ���data_id
     * origin_type = 100 �ǻ���ɽ��Ŀ
     * @param $origin_type
     * @param $data_id
     * @param $channel_id
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function findOneByDataId($origin_type, $data_id, $channel_id, $category_id) {
        return self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('origin_type', $origin_type)
            ->andCondition('data_id', $data_id)
            ->andCondition('category_id', $category_id)
            ->first();
    }

    public static function createByDataId($data) {
        $supply_data = new SupplyoutRsync();
        $supply_data->assign($data);
        return ($supply_data->save()) ? true : false;
    }

    public static function findAllByDataId($origin_type, $data_id, $channel_id) {
        return self::query()
            ->andCondition('channel_id', $channel_id)
            ->andCondition('origin_type', $origin_type)
            ->andCondition('data_id', $data_id)
            ->execute();
    }

}