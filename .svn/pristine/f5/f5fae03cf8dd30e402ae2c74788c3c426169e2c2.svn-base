<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Recycle extends Model {
    use HasChannel;

    public function getSource() {
        return 'recycle';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'data_id', 'publish_info', 'user_id', 'created_at', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'data_id', 'publish_info', 'user_id', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'data_id', 'publish_info', 'user_id', 'created_at', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'publish_info' => Column::TYPE_TEXT,
                'user_id' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'data_id', 'user_id', 'created_at', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'publish_info' => Column::BIND_PARAM_STR,
                'user_id' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
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

    public function createRecycleData($data) {
        $this->assign($data);
        return $this->save() ? true : false;
    }

    public static function delRecycleData($data_id) {
        $rs = Recycle::findFirst("data_id ='{$data_id}'")->delete();
        return $rs;
    }

    /**
     *  根据data_id获取下架数据
     * @param $data_id
     */
    public static function getDataByDataId($data_id) {
        $data = self::query()
            ->andCondition("data_id",$data_id)
            ->first();
        return $data;
    }



}