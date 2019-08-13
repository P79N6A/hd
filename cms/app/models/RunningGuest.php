<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class RunningGuest extends Model {


    public function getSource() {
        return 'running_guest';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'phase_id', 'guest_name', 'guest_img', 'guest_step', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['phase_id', 'guest_name', 'guest_img', 'guest_step', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'phase_id', 'guest_name', 'guest_img', 'guest_step', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'phase_id' => Column::TYPE_INTEGER,
                'guest_name' => Column::TYPE_VARCHAR,
                'guest_img' => Column::TYPE_VARCHAR,
                'guest_step' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'phase_id', 'guest_step', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'phase_id' => Column::BIND_PARAM_INT,
                'guest_name' => Column::BIND_PARAM_STR,
                'guest_img' => Column::BIND_PARAM_STR,
                'guest_step' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'guest_step' => '10000',
                'sort' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }


    public static function makeValidator($data) {
        $validator = Validator::make(
            $data,
            ['phase_id' => "required", "guest_name" => "required"],
            ['phase_id.required' => '跑男节目必选', "guest_name.required" => "嘉宾名称必填"]
        );
        return $validator;
    }

    public static function vote($id, $count = 1) {
        $model = RunningGuest::find($id);
        $guest = $model->toArray();
        $guest = $guest[0];
        if (!$guest) return false;
        $data['guest_step'] = $guest['guest_step'] + 10 * $count;

        if (!$model->update($data)) {
            return false;
        }
        return self::query()->andCondition('id', $id)->columns('id,guest_step')->first()->toArray();
    }

    public static function getTopFive() {
        return self::query()->orderBy("guest_step Desc")->limit(5)->execute()->toArray();
    }

    public static function getTopThree() {
        return self::query()->orderBy("guest_step Desc")->limit(3)->execute()->toArray();
    }

}