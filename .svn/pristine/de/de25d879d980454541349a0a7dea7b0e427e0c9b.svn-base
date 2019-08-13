<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class GetuiClient extends Model {

    public function getSource() {
        return 'getui_client';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'sdk_version', 'device_token', 'push_client', 'updated_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['sdk_version', 'device_token', 'push_client', 'updated_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'sdk_version', 'device_token', 'push_client', 'updated_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'sdk_version' => Column::TYPE_VARCHAR,
                'device_token' => Column::TYPE_VARCHAR,
                'push_client' => Column::TYPE_VARCHAR,
                'updated_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'updated_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'sdk_version' => Column::BIND_PARAM_STR,
                'device_token' => Column::BIND_PARAM_STR,
                'push_client' => Column::BIND_PARAM_STR,
                'updated_at' => Column::BIND_PARAM_INT,
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
	
    public function updateSdkVersion($data) {
        $getuiclient = self::query()
            ->andCondition('device_token', $data['device_token'])
            ->first();
        if($getuiclient) {
            $getuiclient->sdk_version = $data['sdk_version'];
            $getuiclient->updated_at = time();
            $getuiclient->save();
        }
        else if($data['device_token']!="" &&  $data['push_client'] != "" &&  $data['push_client'] != "-1") {
            $new_client = new GetuiClient();
            $new_client->device_token = $data['device_token'];
            $new_client->sdk_version = $data['sdk_version'];
            $new_client->push_client = $data['push_client'];
            $new_client->updated_at = time();
            $new_client->save();
        }
        return true;
	}

}