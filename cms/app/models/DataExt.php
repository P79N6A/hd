<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class DataExt extends Model {

    public function getSource() {
        return 'data_ext';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'data_id', 'param_id', 'value', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['data_id', 'param_id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['value',],
            MetaData::MODELS_NOT_NULL => ['data_id', 'param_id', 'value', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'data_id' => Column::TYPE_INTEGER,
                'param_id' => Column::TYPE_INTEGER,
                'value' => Column::TYPE_TEXT,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'data_id', 'param_id', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'data_id' => Column::BIND_PARAM_INT,
                'param_id' => Column::BIND_PARAM_INT,
                'value' => Column::BIND_PARAM_STR,
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

    /**
     * 发布, 没问题返回空数组
     * @param int $data_template_id
     * @param array $data_param_ids
     * @return mix
     */
    public static function setExtValue($data_id, array $data_param_ids, $data, $partition_by) {
        DB::begin();
        try {
            $data_params = self::query()->andCondition('data_id', $data_id)->execute();
            if (!empty($data_params)) {
                $data_params->delete();
            }

            foreach ($data_param_ids as $v) {
                if($v&&isset($data[CustomParams::getParamName($v)])) {
                    $model = new self;
                    $model->save([
                        'data_id' => $data_id,
                        'param_id' => $v,
                        'value' => $data[CustomParams::getParamName($v)],
                        'partition_by' => $partition_by
                    ]);
                }
            }
            DB::commit();
            return true;
            //return self::getDiffCategory($data, $category_id);
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            return false;
        }
    }

    public static function getExtValues($data_id) {
        $data = self::query()
            ->where("data_id= {$data_id}")
            ->execute()->toArray();
        $params_values = [];
        if (!empty($data)) {
            foreach ($data as $v) {
                $param[CustomParams::getParamName($v['param_id'])] = $v['value'];
                $params_values = array_merge($params_values, $param);
            }
        }
        return $params_values;
    }

}