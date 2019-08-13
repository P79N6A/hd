<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class FeaturedData extends Model {
    const DEFPOSITION = 1;
    public function getSource() {
        return 'featured_data';
    }
    /*
     * @重新排序
     * */
    public static function resort($ids){
        DB::begin();
        foreach($ids as $key=>$id){
            $data = self::findFirst($id);
            $sort = count($ids)-$key;
            $data->sort = $sort;
            if(!$data->update())
            {
                DB::rollback();
                return false;
            }
        }
        DB::commit();
        return true;
    }

    /*
     * @取消推荐位
     * */
    public static function pull($fd_id,$category_id){
        $feature_data = self::findById($fd_id);
        if(!$feature_data)
            return false;
        DB::begin();
        $data_id = $feature_data->data_id;
        if(!$feature_data->delete()){
            DB::rollback();
            return false;
        }
        $category_ids = array_merge(CategoryData::listDataCategoryList($data_id),[$category_id]);
        if(!CategoryData::publish($feature_data->data_id,$category_ids)){
            DB::rollback();
            return false;
        }
        if(RedisIO::exists("FEATURE:DATA:IDS:{$feature_data->feature_id}"))
        {
            RedisIO::zrem("FEATURE:DATA:IDS:{$feature_data->feature_id}",$data_id);
        }
        DB::commit();
        return true;
    }


    public static function top($fd_id,$f_id,$sort){
        $top_sort = self::maximum(array("column"=>"sort","feature_id=:f_id:","bind"=>array("f_id"=>$f_id)));
        if($top_sort != $sort){
            $feature_data = self::findFirst($fd_id);
            $feature_data->sort = $top_sort+1;
            return $feature_data->update()?true:false;
        }
    }
	
    private static function DepartmentData($data_id) {
        $governmentData = GovernmentDepartmentData::fetchGovernmentDepartmentId($data_id);
        if(isset($governmentData)){
            foreach ($governmentData as $v){
                $government_id = $v['government_department_id'];
            }
        }
        return GovernmentDepartment::fetchById($government_id);
    }

    /**
     * @param $feature_id
     * @param int $count
     * @return array
     */
    public static function apiGetFeaturedData($feature_id, $count = 0) {
        $return = [];
        $query = self::query()
            ->columns(['FeaturedData.*,Data.*'])
            ->leftJoin("Data", "Data.id = FeaturedData.data_id")
            ->andWhere("FeaturedData.feature_id = {$feature_id} AND Data.status = 1")
            ->orderBy("FeaturedData.sort desc");
        if ($count) {
            $query->limit($count);
        }
        $data = $query->execute();
        if ($data) {
            foreach ($data as $v) {
                $tmp = $v->data;
                $fea = $v->featuredData;
                if (!empty($fea->feature_title)) {
                    $tmp->title = $fea->feature_title;
                }
                if (!empty($fea->feature_thumb)) {
                    $tmp->thumb = $fea->feature_thumb;
                }
                $tmparr = $tmp->toArray();
                $param_values = DataExt::getExtValues($tmparr['id']);
                $tmparr = array_merge($tmparr, $param_values);
				$government = self::DepartmentData($tmparr['id']);
                if($tmparr['type']=="multimedia") $tmparr['type'] = "news";
                if($government) {
                    $tmparr['government_id'] =$government->id;
                    $tmparr['government'] =$government->name;
                }
                $return[] = $tmparr;
            }
        }
        return $return;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'feature_id', 'data_id', 'feature_title', 'feature_thumb', 'created_at', 'updated_at', 'sort',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['feature_id', 'data_id', 'feature_title', 'feature_thumb', 'created_at', 'updated_at', 'sort',],
            MetaData::MODELS_NOT_NULL => ['id', 'feature_id', 'data_id', 'feature_title', 'feature_thumb', 'created_at', 'updated_at', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'feature_id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'feature_title' => Column::TYPE_VARCHAR,
                'feature_thumb' => Column::TYPE_VARCHAR,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'feature_id', 'data_id', 'created_at', 'updated_at', 'sort',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'feature_id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'feature_title' => Column::BIND_PARAM_STR,
                'feature_thumb' => Column::BIND_PARAM_STR,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'feature_title' => '',
                'feature_thumb' => '',
                'sort' => '1'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findByData($data_id) {
        $data = self::query()
            ->andCondition('data_id', $data_id)
            ->execute();
        return $data;
    }

    /**
     * 根据推荐位类型获取数据
     * @param unknown $data_type
     * @param unknown $channel_id
     */
    public static function findAllByType($type, $channel_id) {
    	$params = self::query()
	    	->columns(['FeaturedData.*', 'Data.*', 'Signals.*'])
	    	->leftjoin('Features', 'Features.id = FeaturedData.Feature_id')
	    	->leftjoin("Data","FeaturedData.data_id=Data.id")
	    	->leftJoin("Signals","Signals.id=Data.source_id")
	    	->andwhere("Signals.isfeatured ='1'")
	    	->andwhere("Features.type = '{$type}'" )
	    	->andwhere("Features.channel_id ='{$channel_id}'")
	    	->orderBy('FeaturedData.sort desc')
	    	->getParams();
    	return self::find($params);
    }
    
    /**
     * 搜索推荐位数据
     * @param unknown $data  搜索条件集合
     */
    public static function searchFeatureData($data, $channel_id) {
    	$query = self::query()
	    	->columns(array('Data.*', 'FeaturedData.*', 'Signals.*'))
	    	->leftjoin("Data","FeaturedData.data_id=Data.id")
	    	->leftJoin("Signals","Signals.id=Data.source_id")
	    	->where("Signals.isfeatured ='1'")
    		->andwhere("Data.type = 'live'")
    		->andWhere("Data.channel_id={$channel_id}");
    	if ($data['title']) {
    		$query = $query->andWhere("Data.title like '%{$data['title']}%'");
    	}
    	$liveStatus = $data['liveStatus']-1;
    	if ($liveStatus != -1) {
    		$query = $query->andWhere("Signals.live_status = {$liveStatus}");
    	}
    	
    	if($data['liveTypes'] != 0) {
    		$query = $query->andWhere("Signals.live_type = {$data['liveTypes']}");
    	}
    	
    	if($data['created_at_from'] != 0 && $data['created_at_from'] != $data['created_at_to'] ) {
    		$query = $query->andWhere("Data.created_at >= '{$data['created_at_from']}'");
    		$query = $query->andWhere("Data.created_at <= '{$data['created_at_to']}'");
    	}
    	$query = $query->orderBy('FeaturedData.sort desc')->getParams();
    	return self::find($query);
    }
    
    public function createData($data) {
    	$top_sort = self::maximum(array("column"=>"sort"));
    	$data['sort'] = $top_sort + 1;
    	$this->assign($data);
    	return ($this->save()) ? true : false;
    }
    
    public static function findById($id) {
        $data = self::query()
            ->andCondition('id', $id)
            ->first();
        return $data;
    }

    public function modifyData($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    public static function findAllByFeature($feature_id) {
        return self::query()
            ->columns(['FeaturedData.*', 'Features.*', 'Data.*'])
            ->leftjoin('Features', 'Features.id = FeaturedData.feature_id')
            ->leftjoin('Data', 'Data.id = FeaturedData.data_id')
            ->andwhere('FeaturedData.feature_id=' . $feature_id)
            ->orderBy('FeaturedData.sort desc')
            ->paginate(50, 'Pagination');
    }

    public static function deleteFeaturedData($id) {
        $featruedData = self::query()->andwhere('id=' . $id)->first();
        return ($featruedData->delete()) ? true : false;
    }

    public static function makeValidator($inputs) {
        return Validator::make(
            $inputs,
            [
                'data_id' => 'required'
            ],
            [
                'data_id.required' => '没有找到绑定的数据'
            ]
        );
    }

    public static function findFeaturedCategoryData($category_id,$channel_id){
        $params =  self::query()
            ->columns(['FeaturedData.*', 'Features.*', 'Data.*','DataStatistics.*'])
            ->leftJoin("Features","Features.id = FeaturedData.feature_id")
            ->leftJoin("Data","FeaturedData.data_id=Data.id")
            ->leftJoin("DataStatistics","DataStatistics.data_id = Data.id")
            ->andWhere("Features.channel_id = $channel_id")
            ->andWhere("Features.category_id = $category_id")
            ->andWhere("Features.position = ".self::DEFPOSITION)
            ->orderBy("FeaturedData.sort Desc,FeaturedData.created_at Desc")
            ->getParams();
        return self::find($params);
    }



    Public static function firstFeaturedCategoryData($data_id){
        $params =  self::query()
            ->columns(['FeaturedData.*', 'Features.*', 'Data.*','DataStatistics.*'])
            ->leftJoin("Features","Features.id = FeaturedData.feature_id")
            ->leftJoin("Data","FeaturedData.data_id=Data.id")
            ->leftJoin("DataStatistics","DataStatistics.data_id = Data.id")
            ->andWhere("Data.id = $data_id")
            ->getParams();
        return self::find($params)->first();
    }



    public static function publish($feature_id,$category_id,$data_id){
        $category_data = CategoryData::findFirst("data_id=$data_id AND category_id=$category_id");
        if(!$category_data)
            return false;
        $data = Data::findFirst($data_id);
        $max_sort_num = self::maximum(array('column'=>'sort',"feature_id = $feature_id"));
        if($category_data !== false){
            DB::begin();
            $f_data = array(
                'feature_id'=>$feature_id,
                'data_id'=>$data_id,
                'feature_title'=>$data->title,
                'created_at'=>time(),
                'updated_at'=>time(),
                'sort'=>$max_sort_num+1
            );
            $model = new self;
            $model->save($f_data);
            $category_data->delete();
            $category_data->afterDelete();
            DB::commit();
            return true;
        }
    }

    /**
     * 保存手动拖动排序值
     * @param array $ids
     * @param array $sorts
     * @return boolean
     */
    public static function sortBySorts(array $ids,array $sorts){
    
    	foreach($ids as $key=>$cat_id){
    		$featureddata = self::findFirst($cat_id);
    		$featureddata->sort = $sorts[$key];
    		if(!$featureddata->update())
    			return false;
    	}
    	return true;
    }



    public function afterSave(){
        $this->updateDataRedis();
        $this->updateSortRedis();
    }

    private function updateDataRedis()
    {
        $feature_id = $this->feature_id;
        $data_id = $this->data_id;
        $key = "FEATUREDATA:FID:{$feature_id}:DATAID:{$data_id}";
        $metaData = new Phalcon\Mvc\Model\MetaData\Memory();
        $attributes = $metaData->getAttributes(new self());
        $row = $this->toarray();
        foreach($attributes as $attr)
        {
            RedisIO::hset($key,$attr,$row[$attr]);
            RedisIO::expire($key,parent::MAX_REDIS_TTL_DAY);
        }
    }

    private function updateSortRedis(){
        $feature_id = $this->feature_id;
        $redis_ids_key = "FEATURE:DATA:IDS:{$feature_id}";
        $sort  = $this->sort;
        $data_id = $this->data_id;
        RedisIO::zAdd($redis_ids_key,$sort,$data_id);
        RedisIO::expire($redis_ids_key,parent::MAX_REDIS_TTL_WEEK);
    }


    public function deleteAfterSave(){

        $redis_ids_key = "FEATURE:DATA:IDS:{$this->feature_id}";
        RedisIO::zrem($redis_ids_key,$this->data_id);
    }
    

   
}