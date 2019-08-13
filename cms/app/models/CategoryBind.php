<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CategoryBind extends Model {

    public function getSource() {
        return 'category_bind';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'category_id', 'bind_id', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['category_id', 'bind_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'category_id', 'bind_id', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
                'bind_id' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'category_id', 'bind_id', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'category_id' => Column::BIND_PARAM_INT,
                'bind_id' => Column::BIND_PARAM_INT,
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


    public static function getbindByCategoryid($category_id) {
        $data = self::query()->andCondition('category_id', $category_id)->execute()->toArray();
        return !empty($data) ? array_values(array_refine($data, 'id', 'bind_id')) : [];
    }


    /**
     * 栏目绑定
     * @param int $category_id
     * @param array $bind_id
     * @return boolean
     */
    public static function bind($category_id, array $bind_id) {
        DB::begin();
        try {
            $data = self::query()->andCondition('category_id', $category_id)->execute();
            if (!empty($data)) {
                $data->delete();
            }
            foreach ($bind_id as $v) {
                if ($category_id == $v) continue;
                $model = new self;
                $model->save([
                    'category_id' => $category_id,
                    'bind_id' => $v,
                    'partition_by' => date("Y")
                ]);
            }
            DB::commit();
            return true;
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            return false;
        }
    }

}