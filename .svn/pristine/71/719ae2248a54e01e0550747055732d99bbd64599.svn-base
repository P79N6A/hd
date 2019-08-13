<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class RunningMan extends Model {

    public function getSource() {
        return 'running_man';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'phase_name', 'sort', 'status', 'createtime',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['phase_name', 'sort', 'status', 'createtime',],
            MetaData::MODELS_NOT_NULL => ['id', 'phase_name', 'sort', 'status', 'createtime',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'phase_name' => Column::TYPE_VARCHAR,
                'sort' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'createtime' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'sort', 'status', 'createtime',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'phase_name' => Column::BIND_PARAM_STR,
                'sort' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'createtime' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'sort' => '0',
                'status' => '0',
                'createtime' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAllWithGuest($status = 0) {
        if ($status)
            $phases = self::query()->andCondition("status", "1")->orderBy('sort ASC,id ASC')->execute()->toArray();
        else
            $phases = self::query()->orderBy('sort ASC,id ASC')->execute()->toArray();

        foreach ($phases as $key => $value) {
            $phases[$key]['guest'] = RunningGuest::query()->where("phase_id={$value['id']}")->orderBy("guest_step Desc")->execute()->toArray();
        }
        return $phases;
    }

    public static function findAllBySelect() {
        $phase = self::query()->orderBy('sort DESC,id DESC')->execute()->toArray();
        return $phase ? array_column($phase, "phase_name", "id") : false;
    }

    public static function makeValidator($data) {
        $validator = Validator::make(
            $data,
            ['phase_name' => "required"],
            ['phase_name.required' => '跑男节目不能为空']
        );
        return $validator;
    }
}