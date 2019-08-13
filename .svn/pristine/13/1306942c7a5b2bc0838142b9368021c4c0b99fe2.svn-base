<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class StationsProgramOrder extends Model {

    public function getSource() {
        return 'stations_program_order';
    }

    /**
     * 获取某个预约
     * @param $program_id
     * @param $user_id
     * @return array|mixed
     */
    public static function apiFindStationsProgramOrder($program_id,$user_id){
        $key = D::memKey('apiFindStationsProgramOrder', ['program_id' => $program_id, 'user_id' => $user_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()->andCondition('program_id',$program_id)
                ->andCondition('user_id',$user_id)
                ->first();
            $data_arr = array();
            if($data){
                $data_arr = $data->toArray();
            }
            $data = $data_arr;
            $time = 1800;
            MemcacheIO::set($key, $data, $time);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'program_id', 'user_id', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'program_id', 'user_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'program_id', 'user_id', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'program_id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'program_id', 'user_id', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'program_id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
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

    public function createStationsProgramOrder($data){
        $this->assign($data);
        return $this->save();
    }

    public static function deleteStationsProgramOrder($id){
        $order = self::query()->andCondition('id',$id)->first();
        if(isset($order->id)){
            return $order->delete();
        }
        return false;
    }

}