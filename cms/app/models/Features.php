<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Features extends Model {

    const PAGE_SIZE = 50;
    const DEFPOSITION = 1;
    public function getSource() {
        return 'features';
    }

    public static function tplFeatures($channel_id, $position, $category_id, $region_id = 0, $count = 10) {
        $key = D::memKey('tplFeatures', [
            'p' => $position,
            'ch' => $channel_id,
            'c' => $category_id,
            'r' => $region_id,
            'co' => $count,
        ]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $query = self::query()
                ->andCondition('position', $position)
                ->andCondition('channel_id', $channel_id)
                ->andCondition('status', 1)
                ->andCondition('category_id', $category_id);
            if ($region_id) {
                $query = $query->andCondition('region_id', $region_id);
            }
            $rs = $query->first();
            $data = [];
            if ($rs) {
                $data = FeaturedData::apiGetFeaturedData($rs->id, $count);
            }
            MemcacheIO::set($key, $data);
        }
        return $data;
    }

    /**
     * @param $channel_id
     * @param int $category_id
     * @param int $region_id
     * @return mixed
     */
    public static function apiGetFeatures($channel_id, $category_id, $region_id = 0) {
        $key = D::memKey('apiGetFeatures', [
            'channel_id' => $channel_id,
            'category_id' => $category_id,
            'region_id' => $region_id,
        ]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $query = self::query()
                ->andCondition('channel_id', $channel_id)
                ->andCondition('status', 1)
                ->andCondition('category_id', $category_id);

            if ($region_id) {
                $query = $query->andCondition('region_id', $region_id);
            }
            $rs = $query->first();
            $data = [];
            if ($rs) {
                $data = FeaturedData::apiGetFeaturedData($rs->id);
            }
            MemcacheIO::set($key, $data);
        }
        return $data;
    }

	public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'position', 'category_id', 'region_id', 'type', 'title', 'author_id', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'position', 'category_id', 'region_id', 'type', 'title', 'author_id', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'position', 'category_id', 'region_id', 'title', 'author_id', 'created_at', 'updated_at', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'position' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
                'region_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'author_id' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'position', 'category_id', 'region_id', 'type', 'author_id', 'created_at', 'updated_at', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'position' => Column::BIND_PARAM_INT,
                'category_id' => Column::BIND_PARAM_INT,
                'region_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'author_id' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'region_id' => '0',
                'title' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    

    public static function getCategoryId($id) {
        $data = self::query()
            ->andCondition('id', $id)
            ->first();
        return $data->category_id;
    }

    public static function findAll($channel_id) {
        return self::query()
            ->columns(['Features.*', 'Category.*', 'Admin.*'])
            ->leftjoin('Category', 'Category.id = Features.category_id')
            ->leftjoin('Admin', 'Admin.id = Features.author_id')
            ->andwhere("Features.channel_id=" . $channel_id)
            ->orderBy('Features.updated_at desc, Features.id ')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function featuresList($feature_type = 'category') {
        return self::query()
            ->columns(['Features.*', 'Category.*'])
            ->leftjoin('Category', 'Category.id = Features.category_id')
            ->andwhere("Features.channel_id=" . Session::get('user')->channel_id)
            ->andwhere("Features.type='{$feature_type}'")
            ->orderBy('Features.updated_at desc, Features.id ')
            ->execute()->toarray();
    }

    public static function findById($id) {
        return self::query()
            ->columns(['Features.*', 'Category.*', 'Admin.*'])
            ->leftjoin('Category', 'Category.id = Features.category_id')
            ->leftjoin('Admin', 'Admin.id = Features.author_id')
            ->andwhere("Features.id=" . $id)
            ->andwhere('Features.channel_id=' . Session::get('user')->channel_id)
            ->first();
    }

    public static function findByType($feature_type) {
    	return self::query()
	    	->columns(['Features.*'])
	    	->andwhere("Features.channel_id=" . Session::get('user')->channel_id)
	    	->andwhere("Features.type='{$feature_type}'")
	    	->first();
    }
    
    public function modifyFeatures($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public function createFeatures($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function findFeatureRow($category_id,$position=1){
        return self::findFirst(
            array(
                "category_id = :cid: AND position=:position:",
                "bind"=>array("cid"=>$category_id,"position"=>$position),
            ));
    }

    public static function approveFeatures($id) {
        $features = self::query()->andwhere('id=' . $id)->first();
        if ($features->status == 1) {
            $features->status = 0;
        } else if ($features->status == 0) {
            $features->status = 1;
        }
        return ($features->update()) ? true : false;
    }

    public static function makeValidator($inputs, $excluded_id = 0) {
        return Validator::make(
            $inputs,
            [
                'position' => 'required|numeric|max:9|min:0',
                'category_id' => 'required',
                'status' => 'required'
            ],
            [
                'position.required' => '请填写位置',
                'position.numeric' => '位置序号必须为数字',
                'position.max' => '位置序号不正确',
                'position.min' => '位置序号不正确',
                'category_id.required' => '请填写栏目ID',
                'status.required' => '请填写状态'
            ]
        );
    }

}