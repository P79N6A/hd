<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class PrivateCategoryData extends Model {
    public static $PAGE_SIZE = 50;

    public function getSource() {
        return 'private_category_data';
    }

    /**
     * 发布, 没问题返回空数组
     * @param int $data_id
     * @param int $partition_by
     * @return array
     */
    public static function publish($data_id, $partition_by) {
		$return =[];
        $messages = [];
        $ids = Request::getPost('private_category_id');
        $id = $ids[0];
        if (!$id) {
            $messages[] = '请选择要投放的私有栏目';
        } else {
            $channel_id = Auth::user()->channel_id;
            if ($channel_id > -1) {
                $category = PrivateCategory::channelQuery($channel_id)
                    ->andCondition('id', $id)
                    ->first();
                if (!$category) {
                    $messages[] = '非法的私有栏目数据';
                }
            } else {
                $messages[] = '请选择要投放的私有栏目.';
            }
        }
        if (empty($messages)) {
            $model = self::query()->andCondition('data_id', $data_id)->first();
            if (!$model) {
                $model = new self;
                $model->data_id = $data_id;
                $model->partition_by = $partition_by;
            }
            $model->category_id = $id;
            if (!$model->save()) {
                $msgs = $model->getMessages();
                foreach ($msgs as $msg) {
                    $return[] = $msg->getMessage();
                }
            }
        }
        return $return;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'data_id', 'category_id', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['data_id', 'category_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'data_id', 'category_id', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'data_id', 'category_id', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'category_id' => Column::BIND_PARAM_INT,
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

    public static function findAll() {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query()
            ->columns(['PrivateCategoryData.data_id', 'Data.*', 'PrivateCategory.*', 'PrivateCategoryData.*'])
            ->leftJoin("Data", "Data.id = PrivateCategoryData.data_id")
            ->leftJoin("PrivateCategory", "PrivateCategory.id = PrivateCategoryData.category_id")
            ->andWhere("Data.channel_id = {$channel_id}")
            ->orderBy('Data.created_at desc');
        $query = self::findAllFilter($query);
        return $query->orderBy('Data.created_at desc')
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    private static function findAllFilter($query) {
        if ($r = Request::get('private_category_id')) {
            $query->andWhere("PrivateCategoryData.category_id = " . q(intval($r)));
        } else {
            $query->andWhere("PrivateCategoryData.category_id = 0");
        }
        if ($r = Request::get('id')) {
            $query->andWhere("data.id = " . q(intval($r)));
        }
        //TODO 使用第三方搜索待补充
        if ($r = Request::get('title')) {
            $query->andWhere("data.title =" . q(intval($r)));
        }
        return $query;
    }

    public static function getCategoryId($data_id) {
        $r = PrivateCategoryData::query()
            ->andCondition('data_id', $data_id)
            ->leftJoin('Data', 'Data.id = PrivateCategoryData.data_id')
            ->columns(['PrivateCategoryData.category_id'])
            ->first();
        if ($r) {
            return $r->category_id;
        } else {
            return 0;
        }
    }

    public static function getIdByData($id) {
        $data = self::query()->andCondition('data_id', $id)->execute()->toArray();
        return !empty($data) ? array_values(array_refine($data, 'id', 'category_id')) : [];
    }

    public static function createAndModify($data_id, $category_id) {
        $model = self::query()->andCondition('data_id', $data_id)->first();
        if ($model) {
            $model->category_id = $category_id;
            $model->update();
        } else {
            $model = new self;
            $model->data_id = $data_id;
            $model->category_id = $category_id;
            $model->partition_by = date('Y');
            $model->save();
        }
        return true;
    }

}