<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class DataTemplateParams extends Model {

    public function getSource() {
        return 'data_template_params';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'data_template_id', 'param_id', 'is_required', 'param_default', 'param_order',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['data_template_id', 'param_id', 'is_required', 'param_default', 'param_order',],
            MetaData::MODELS_NOT_NULL => ['id', 'data_template_id', 'param_id', 'is_required', 'param_default', 'param_order',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'data_template_id' => Column::TYPE_INTEGER,
                'param_id' => Column::TYPE_INTEGER,
                'is_required' => Column::TYPE_INTEGER,
                'param_default' => Column::TYPE_TEXT,
                'param_order' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'data_template_id', 'param_id', 'is_required', 'param_order',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'data_template_id' => Column::BIND_PARAM_INT,
                'param_id' => Column::BIND_PARAM_INT,
                'is_required' => Column::BIND_PARAM_INT,
                'param_default' => Column::BIND_PARAM_STR,
                'param_order' => Column::BIND_PARAM_INT,
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



    public static function resetParams($data_template_id, $params) {
        $data_template = DataTemplates::getOne($data_template_id);
        if(!$data_template->status) {
            self::query()
                ->andCondition('data_template_id', $data_template_id)
                ->execute()
                ->delete();
            if (!empty($params)) {
                foreach ($params as $v) {
                    $model = new self;
                    $model->save([
                        'data_template_id' => $data_template_id,
                        'param_id' => (int) $v,
                        'is_required' => 0,
                        'param_default' => "",
                        'param_order' => 0
                    ]);
                }
            }
        }
        return true;
    }


    static function getParams($data_template_id) {
        $data = self::query()
            ->where("data_template_id= {$data_template_id}")
            ->execute()->toArray();
        $template_params = [];
        if (!empty($data)) {
            foreach ($data as $v) {
                $param = [$v['param_id']];
                $template_params = array_merge($template_params, $param);
            }
        }
        return $template_params;
    }

}