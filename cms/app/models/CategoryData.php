<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;
use GenialCloud\Exceptions\DatabaseTransactionException;

class CategoryData extends Model {

    public static $PAGE_SIZE = 25;
    public static $MAX_REDIS_ITEM = 500;

    const  PAGE_CACHE_NUMBER = 2;

    const  category_data_sort_key = 'backend::data::sort:';//
    const  category_data_publish_num_key = 'backend::data::publishnumbers:';
    const  category_id_data_id_key = 'backend::category_data::data::category_ids::data_id:';

    public function getSource() {
        return 'category_data';
    }

    /**
     * @param int $channel_id
     * @param int $category_id
     * @param int $per_page
     * @param int $page
     * @return \Phalcon\Mvc\Model\ResultsetInterface
     */
    public static function apiFindByCategory($channel_id, $category_id, $per_page = 10, $page = 1, $weight=0)
    {
        $input = Request::getQuery();
        $region_id = isset($input['region_id']) ? (int)$input['region_id'] : 0;
        $type = isset($input['type']) && in_array($input['type'], array_keys(Data::$type2model)) ? $input['type'] : '';
        $keymain = "columns_list_id:".$category_id;
        $datacachemain = MemcacheIO::get($keymain);
        if(!$datacachemain) {
            for($i=0; $i<10; $i++) {
                $key = D::memKey('apiFindByCategory', [
                    'channel_id' => $channel_id,
                    'category_id' => $category_id,
                    'per_page' => $per_page,
                    'page' => $i,
                    'region_id' => $region_id,
                    'type' => $type,
                ]);
                MemcacheIO::set($key, false, 86400);
            }
            MemcacheIO::set($keymain, true, 86400);
        }
        $key = D::memKey('apiFindByCategory', [
            'channel_id' => $channel_id,
            'category_id' => $category_id,
            'per_page' => $per_page,
            'page' => ($page > 0) ? ($page - 1) : 0,
            'region_id' => $region_id,
            'type' => $type,
        ]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $query = self::query()
                ->columns(['Data.*'])
                ->leftJoin("Data", "Data.id = CategoryData.data_id")
                ->andWhere("Data.channel_id = {$channel_id}")
                ->andWhere("CategoryData.category_id = " . q($category_id))
                ->andWhere("Data.status=1 and CategoryData.weight=".$weight);
            // 解析类型
            if ($type) {
                if($type=='news') {
                    $query->andWhere("(Data.type='news' or Data.type='multimedia')");
                }
                else {
                    $query->andWhere("Data.type='{$type}'");
                }
            }
            // 解析地区ID
            if ($region_id) {
                $r = Regions::fetchById($region_id);
                if ($r) {
                    $key = $r->level . '_id';
                    $query = $query->rightJoin('RegionData', 'RegionData.data_id = Data.id')
                        ->andWhere("RegionData.{$key} = :{$key}:", [$key => $region_id]);
                }
            }
            $query = $query->orderBy('CategoryData.sort desc, Data.created_at desc');
            $rs = $query
                ->paginate($per_page, '\GenialCloud\Helper\Pagination', $page)
                ->models;
            $data = [];
            if (!empty($rs)) {
                $data = $rs->toArray();
				$data2 = array();
				foreach($data as $v) {
				    if($v['type']=="special") continue;
					if($v['type']=="multimedia") $v['type']="news";
					$data2[] = $v;
				}
				$data = $data2;
            }
            MemcacheIO::set($key, $data, 86400);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'data_id', 'category_id', 'sort', 'weight', 'partition_by', 'publish_at', 'publish_status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['data_id', 'category_id', 'sort', 'weight', 'publish_at', 'publish_status',],
            MetaData::MODELS_NOT_NULL => ['id', 'data_id', 'category_id', 'sort', 'weight', 'partition_by', 'publish_status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
                'weight' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
                'publish_at' => Column::TYPE_INTEGER,
                'publish_status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'data_id', 'category_id', 'sort', 'weight', 'partition_by', 'publish_at', 'publish_status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'category_id' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
                'weight' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
                'publish_at' => Column::BIND_PARAM_INT,
                'publish_status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'sort' => '0',
                'weight' => '0',
                'publish_at' => '0',
                'publish_status' => '0'
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
            ->columns(['CategoryData.data_id', 'Data.*', 'CategoryData.*','DataStatistics.*'])
            ->leftJoin("Data", "Data.id = CategoryData.data_id")
            ->leftJoin("DataStatistics","DataStatistics.data_id = Data.id")
            ->andWhere("Data.channel_id = {$channel_id}")
            ->orderBy('Data.created_at desc');
        $query = self::findAllFilter($query);
        return $query->orderBy('CategoryData.weight desc,CategoryData.sort desc, Data.created_at desc')
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    public static function findTop() {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query()
            ->columns(['CategoryData.data_id', 'Data.*', 'CategoryData.*','DataStatistics.*'])
            ->leftJoin("Data", "Data.id = CategoryData.data_id")
            ->leftJoin("DataStatistics","DataStatistics.data_id = Data.id")
            ->andWhere("Data.channel_id = {$channel_id} and CategoryData.weight=1")
            ->andWhere("CategoryData.publish_status <> 3")
            ->orderBy('Data.created_at desc');
        $query = self::findAllFilter($query);
        return $query->orderBy('CategoryData.weight desc,CategoryData.sort desc, Data.created_at desc')
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    public static function findList() {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query()
            ->columns(['CategoryData.data_id', 'Data.*', 'CategoryData.*','DataStatistics.*'])
            ->leftJoin("Data", "Data.id = CategoryData.data_id")
            ->leftJoin("DataStatistics","DataStatistics.data_id = Data.id")
            ->andWhere("Data.channel_id = {$channel_id} and CategoryData.weight=0")
            ->andWhere("CategoryData.publish_status <> 3")
            ->orderBy('Data.created_at desc');
        $query = self::findAllFilter($query);
        return $query->orderBy('CategoryData.weight desc,CategoryData.sort desc, Data.created_at desc')
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }


    public static function findSimpleList($category_id, $channel_id, $weight=0) {
        $query = self::query()
            ->columns(['CategoryData.*'])
            ->leftJoin("Data", "Data.id = CategoryData.data_id")
            ->andWhere("Data.channel_id = {$channel_id} and CategoryData.weight=".$weight." and CategoryData.category_id = " . intval($category_id))
            ->andWhere("CategoryData.publish_status <> 3")
			->orderBy('CategoryData.sort desc, Data.created_at desc');
        return $query->limit(self::$PAGE_SIZE * self::PAGE_CACHE_NUMBER)->execute();
    }


    public static function deleteListRedis($category_id, $channel_id) {
        $key = Data::category_data_list_key."recommend:".$channel_id .":". $category_id;
        RedisIO::delete($key);
        $key = Data::category_data_list_key.":".$channel_id .":". $category_id;
        RedisIO::delete($key);
    }


    public static function deleteCacheJson($category_id) {
        $channel_id = Session::get('user')->channel_id;
        if(!isset($channel_id)){
            $channel_id = Request::get("channel_id");
        }
        for($i=1; $i<=self::PAGE_CACHE_NUMBER; $i++) {
            $key_cache_json_key = "Backend:cache_json:".$channel_id .":". $category_id.":".$i;
            RedisIO::delete($key_cache_json_key);
        }
        $key_cache_json_recommend_key = "Backend:cache_json_recommend:".$channel_id .":". $category_id;
        RedisIO::delete($key_cache_json_recommend_key);
    }


    public static function findTopFromRedis() {
        $channel_id = Session::get('user')->channel_id;
        $category_id = Request::get('category_id');
        $key = Data::category_data_list_key."recommend:".$channel_id .":". $category_id;
        $page = (Request::get('page'))?intval(Request::get('page')):1;
        if($page > self::PAGE_CACHE_NUMBER) {
            return CategoryData::findTop();
        }
        else {
            $key_cache_json_key = "Backend:cache_json_recommend:".$channel_id .":". $category_id;
            //RedisIO::delete($key_cache_json_key);
            if(!RedisIO::exists($key_cache_json_key)) {
                RedisIO::delete($key);
                if(!RedisIO::exists($key)) {
                    $cds = CategoryData::findSimpleList($category_id, $channel_id, 1);
                    foreach($cds as $cd) {
                        RedisIO::zAdd($key, $cd->sort, $cd->data_id);
                        $key_sort = self::category_data_sort_key.$category_id.":".$cd->data_id;
                        RedisIO::set($key_sort, json_encode($cd));
                    }

                }
                $list  =  RedisIO::zRevRange($key, ($page - 1) * self::$PAGE_SIZE, $page * self::$PAGE_SIZE - 1);
                $category_data = array();
                foreach($list as $data_id) {
                    $key_sort = self::category_data_sort_key.$category_id.":".$data_id;
                    $cd = RedisIO::get($key_sort);
                    $model = Data::getDataRedis($data_id, $channel_id);
                    array_push($category_data , array('cd'=>json_decode($cd), 'model'=>$model));
                }
                $jsonData = json_encode($category_data);
                RedisIO::set($key_cache_json_key, $jsonData, 3600);
                return json_decode($jsonData);
            }

            $category_data_json = RedisIO::get($key_cache_json_key);

            return json_decode($category_data_json);
        }
    }

    public static function findListFromRedis() {
        $channel_id = Session::get('user')->channel_id;
        $category_id = Request::get('category_id');
        $key = Data::category_data_list_key.":".$channel_id .":". $category_id;
        $page = (Request::get('page'))?intval(Request::get('page')):1;
        if($page > self::PAGE_CACHE_NUMBER) {
            return CategoryData::findList();
        }
        else {
            $key_cache_json_key = "Backend:cache_json:".$channel_id .":". $category_id.":".$page;
            //RedisIO::delete($key_cache_json_key);
            if(!RedisIO::exists($key_cache_json_key)) {
                 RedisIO::delete($key);
                if(!RedisIO::exists($key)) {
                    $cds = CategoryData::findSimpleList($category_id, $channel_id);
                    foreach($cds as $cd) {
                        RedisIO::zAdd($key, $cd->sort, $cd->data_id);
                        $key_sort = self::category_data_sort_key.$category_id.":".$cd->data_id;
                        RedisIO::set($key_sort, json_encode($cd));
                    }
                }
                $list  =  RedisIO::zRevRange($key, ($page - 1) * self::$PAGE_SIZE, $page * self::$PAGE_SIZE - 1);
                $category_data = array();
                foreach($list as $data_id) {
                    $key_sort = self::category_data_sort_key.$category_id.":".$data_id;
                    $cd = RedisIO::get($key_sort);
                    $model = Data::getDataRedis($data_id, $channel_id);
                    array_push($category_data , array('cd'=>json_decode($cd), 'model'=>$model));
                }
                $jsonData = json_encode($category_data);
                RedisIO::set($key_cache_json_key, $jsonData, 3600);
                return json_decode($jsonData);
            }
            $category_data_json = RedisIO::get($key_cache_json_key);
            return  json_decode($category_data_json);
        }
    }

    public static function findCategoryPublishInfo() {
        $channel_id = Session::get('user')->channel_id;
        $category_id = Request::get('category_id');
        $key_all = self::category_data_publish_num_key.":all:".$channel_id.":".$category_id;
        $key_approve = self::category_data_publish_num_key.":approve:".$channel_id.":".$category_id;
        if(!RedisIO::exists($key_all)||!RedisIO::exists($key_approve)) {
            $result_all = self::query()
                ->columns(['CategoryData.*'])
               // ->leftJoin("Data", "Data.id = CategoryData.data_id")
                ->andWhere("CategoryData.category_id = " . intval($category_id))
                ->execute();
            RedisIO::set($key_all, $result_all->count(), 600);
            $result_approve = self::query()
                ->columns(['CategoryData.*'])
                // ->leftJoin("Data", "Data.id = CategoryData.data_id")
                ->andWhere("CategoryData.publish_status=1 and CategoryData.category_id = " . intval($category_id))
                ->execute();
            RedisIO::set($key_approve, $result_approve->count(), 600);
        }

        $all_number =RedisIO::get($key_all);
        $approve_number = RedisIO::get($key_approve);
        return [$all_number, $approve_number];
    }

    public static function firstOne($data_id){
        $query = self::query()
            ->columns(['CategoryData.data_id', 'Data.*', 'CategoryData.*','DataStatistics.*'])
            ->leftJoin("Data", "Data.id = CategoryData.data_id")
            ->leftJoin("DataStatistics","DataStatistics.data_id = Data.id")
            ->andWhere("Data.id = {$data_id}");
        return $query->first();
    }



    private static function findAllFilter($query)
    {
        if ($r = Request::get('category_id')) {
            $query->andWhere("CategoryData.category_id = " . q(intval($r)));
        } else {
            $query->andWhere("CategoryData.category_id = 0");
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

    /**
     * 发布, 没问题返回空数组
     * @param int $id
     * @param array $category_id
     * @return mix
     */
    public static function publish($id, array $category_id,$weight = 0) {
        DB::begin();
        try {
            $data = self::query()->andCondition('data_id', $id)->execute();
            if (!empty($data)) {
                foreach ($data as $v) {
                    SmartyData::delCategoryDataRedis($v->category_id);
                    self::deleteCacheJson($v->category_id);
                    $v->delete();
                }
            }
            foreach ($category_id as $v) {
                if($v)
                {
                    $category = new Category();
                    $comment_status = $category->getPublishStatus($v);
                    $model = new self;
                    $model->save([
                        'data_id' => $id,
                        'category_id' => $v,
                        'publish_at'=>time(),
                        'weight' =>$weight,
                        'partition_by' => date("Y"),
                        'publish_status' => $comment_status != -1 ? $comment_status : 1
                    ]);
                    self::top($v, $id);
                    $cd = self::query()
                        ->andCondition('category_id', $v)
                        ->andCondition('data_id', $id)
                        ->first();
                    $key_sort = self::category_data_sort_key.$v.":".$id;
                    RedisIO::set($key_sort, json_encode($cd));
                    self::updateRedisList($v, $id, $cd->sort);
                    self::deleteCacheJson($v);
                }
            }
            $data = Data::findFirst($id);
            if (!$data->status) {
                $data->update(['status' => 1]);
            }

            DB::commit();
            self::setRedisCategoryName($id);
            return true;
            //return self::getDiffCategory($data, $category_id);
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            return false;
        }
    }

    /*
     *  @desc 增量发布
     *  @author 冯固
     *  @date 2016-12-1
     *
     * */
    public static function addPublish($data_id,array $category_id) {
        $channel_id = Session::get('user')->channel_id;
        foreach($category_id as $v) {
            SmartyData::delCategoryDataRedis($v);
            self::deleteCacheJson($v);
            $last_modified_key = "media/latest:" . $v;
            F::_clearCache($last_modified_key, $channel_id);
        }
        $published_catids = self::listDataCategoryList($data_id);
        $category_id_plus = array_diff($category_id, $published_catids); //增加发布
        $category_id_sub = array_diff($published_catids, $category_id); //删除发布
        DB::begin();
        try {
            if(count($category_id_sub) >0) {
                foreach($category_id_sub as $cat_id) {
                    $data = self::query()->andCondition('data_id', $data_id)->andWhere("category_id =$cat_id")->execute();
                    if (!empty($data)) {
					    $cds = $data->toArray();
					    $cd = $cds[0];
                        if($cd['weight'] == 1) {
                            self::deleteRedisRecommend($cat_id, $cd['data_id']);
                        }
                        else {
                            self::deleteRedisList($cat_id, $cd['data_id']);
                        }
                        SmartyData::delCategoryDataRedis($cat_id);
                        self::deleteCacheJson($cat_id);
                        $last_modified_key = "media/latest:" . $cat_id;
                        F::_clearCache($last_modified_key, $channel_id);
                        $data->delete();
                    }
                }
            }
            if(count($category_id_plus)){
                foreach ($category_id_plus as $v) {
                    if($v) {
                        $category = new Category();
                        $comment_status = $category->getPublishStatus($v);
                        $model = new self;
                        $model->save([
                            'data_id' => $data_id,
                            'category_id' => $v,
                            'publish_at' => time(),
                            'partition_by' => date("Y"),
                            'publish_status' => $comment_status != -1 ? $comment_status : 1
                        ]);
                        self::top($v,$data_id);
                        $cd = self::query()
                            ->andCondition('category_id', $v)
                            ->andCondition('data_id', $data_id)
                            ->first();
                        $key_sort = self::category_data_sort_key.$v.":".$data_id;
                        RedisIO::set($key_sort, json_encode($cd));
                        self::updateRedisList($v, $data_id, $cd->sort);
                    }
                }
                $data = Data::findFirst($data_id);
                if (!$data->status) {
                    $data->update(['status' => 1]);
                }
            }
            DB::commit();
            self::setRedisCategoryName($data_id);
            return true;
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            return false;
        }
    }

    public static function getIdByData($id) {
        $data = self::query()->andCondition('data_id', $id)->execute()->toArray();
        return !empty($data) ? array_values(array_refine($data, 'id', 'category_id')) : [];
    }

    //取消推荐位
    private static function updateRedisList($category_id, $data_id, $sort) {
        $channel_id = Session::get('user')->channel_id;
        $redis_key = Data::category_data_list_key .":". $channel_id .":". $category_id;
        RedisIO::zAdd($redis_key, $sort, $data_id);

        $key_sort = self::category_data_sort_key.$category_id.":".$data_id;
        $cd = json_decode(RedisIO::get($key_sort));
        $cd->weight = 0;
        RedisIO::set($key_sort, json_encode($cd));
    }

    private static function deleteRedisList($category_id, $data_id) {
        $channel_id = Session::get('user')->channel_id;
        $redis_key = Data::category_data_list_key .":". $channel_id .":". $category_id;
        RedisIO::zRem($redis_key, $data_id);
    }

    //设置推荐位
    private static function updateRedisRecommend($category_id, $data_id, $sort) {
        $channel_id = Session::get('user')->channel_id;
        $redis_key = Data::category_data_list_key ."recommend:". $channel_id .":". $category_id;
        RedisIO::zAdd($redis_key, $sort, $data_id);
        $key_sort = self::category_data_sort_key.$category_id.":".$data_id;
        $cd = json_decode(RedisIO::get($key_sort));
        $cd->weight = 1;
        RedisIO::set($key_sort, json_encode($cd));
    }

    // 删除推荐位列表中的媒资
    private static function deleteRedisRecommend($category_id, $data_id) {
        $channel_id = Session::get('user')->channel_id;
        $redis_key = Data::category_data_list_key ."recommend:". $channel_id .":". $category_id;
        RedisIO::zRem($redis_key, $data_id);
    }

    public static function sortBySorts(array $ids,array $sorts) {
        foreach($ids as $key=>$cat_data_id){
            $cateogrydata = self::findFirst($cat_data_id);
            $cateogrydata->sort = $sorts[$key];
            if($cateogrydata->weight) {
                self::updateRedisRecommend($cateogrydata->category_id, $cateogrydata->data_id, $cateogrydata->sort);
            }
            else {
                self::updateRedisList($cateogrydata->category_id, $cateogrydata->data_id, $cateogrydata->sort);
            }
			$key_sort = self::category_data_sort_key.$cateogrydata->category_id.":".$cateogrydata->data_id;
			
			
                        RedisIO::set($key_sort, json_encode($cateogrydata));
			
            self::deleteCacheJson($cateogrydata->category_id);
            if(!$cateogrydata->update())
                return false;
        }
        return true;
    }
    /**
     * 重新排序
     * @param array $ids
     * @param Data $data
     * @return array
     */
    public static function reSort(array $ids, $data) {
        DB::begin();
        $messages = [];
        try {
            if (!empty($ids) && !empty($data->models)) {
                foreach ($ids as $key => $id) {
                    foreach ($data->models as $v) {
                        if ($v->data->id == $id) {
                            $model = self::query()
                                ->andCondition('id', $v->categoryData->id)
                                ->first();
                            $model->sort = count($ids) - $key;
                            self::updateRedisSort($model->category_id,$model->data_id,'sort',$model->sort);
                            $model->update();
                        }
                    }
                }
            }
            DB::commit();
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            $_m = $e->getMessage();
            $msgs = $$_m->getMessages();
            foreach ($msgs as $msg) {
                $messages[] = $msg->getMessage();
            }
        }
        return $messages;

    }

    /**
     * 置顶(暂不用)
     * @param int $category_id
     * @param int $id
     * @return bool
     */
    public static function top($category_id, $id) {
        $data = self::query()
            ->andCondition('category_id', $category_id)
            ->andCondition('data_id', $id)
            ->first();
        if ($data) {
            $oldTop = self::query()
                ->andCondition('category_id', $category_id)
                ->orderBy("sort desc")
                ->first();
            $data->sort = $oldTop ? $oldTop->sort + 1 : 1;
            return $data->save();
        }
        return false;
    }

    /**
     * 推荐
     * @param int $category_id
     * @param int $id
     * @return bool
     */
    public static function recommend($category_id, $id) {
        $data = self::query()
            ->andCondition('category_id', $category_id)
            ->andCondition('data_id', $id)
            ->first();
        if ($data) {
            $oldTop = self::query()
                ->andCondition('category_id', $category_id)
                ->orderBy("sort desc")
                ->first();
            $data->sort = $oldTop ? $oldTop->sort + 1 : 1;
            $data->weight = 1;
            self::updateRedisRecommend($category_id, $id, $data->sort);
            self::deleteRedisList($category_id, $id);
			$key_sort = self::category_data_sort_key.$data->category_id.":".$data->data_id;
			RedisIO::set($key_sort, json_encode($data));
            self::deleteCacheJson($category_id);
            return $data->save();
        }
        return false;
    }

    /**
     * 取消推荐
     * @param int $category_id
     * @param int $id
     * @return bool
     */
    public static function cancelRecommend($category_id, $id) {
        $data = self::query()
            ->andCondition('category_id', $category_id)
            ->andCondition('data_id', $id)
            ->first();
        if ($data) {
            $key_sort = self::category_data_sort_key.$category_id.":".$id;
            $sortval = json_decode(RedisIO::get($key_sort));
            self::updateRedisList($category_id, $id, $sortval->sort);
            self::deleteRedisRecommend($category_id, $id);
            self::deleteCacheJson($category_id);
            $data->weight = 0;
			$key_sort = self::category_data_sort_key.$data->category_id.":".$data->data_id;
			RedisIO::set($key_sort, json_encode($data));
            return $data->save();
        }
        return false;
    }

    /**
     * 根据data_id, category_id获取数据
     * @param $id 为data_id
     * @param $categoryId 为category_id
     */
    public static function findCategoryDataById($id, $categoryId) {
        $data = CategoryData::query()
            ->andCondition('category_id', $categoryId)
            ->andCondition('data_id', $id)
            ->first();
        return $data;
    }

    /**
     * 设置媒资发布的数量
     * @param $category_id 栏目id
     */
    public static function setApproveNum($category_id) {
        $channel_id = Session::get('user')->channel_id;
        $key_approve = self::category_data_publish_num_key.":approve:".$channel_id.":".$category_id;
        $key_all = self::category_data_publish_num_key.":all:".$channel_id.":".$category_id;
        RedisIO::delete($key_all);
        RedisIO::delete($key_approve);
        if(!RedisIO::exists($key_approve)) {
            $result_approve = self::query()
                ->columns(['CategoryData.*'])
                ->andWhere("CategoryData.publish_status=1 and CategoryData.category_id = " . intval($category_id))
                ->execute();
            RedisIO::set($key_approve, $result_approve->count(), 600);
        }
        if(!RedisIO::exists($key_all)) {
            $result_approve = self::query()
                ->columns(['CategoryData.*'])
                ->andWhere("CategoryData.publish_status<>3 and CategoryData.category_id = " . intval($category_id))
                ->execute();
            RedisIO::set($key_all, $result_approve->count(), 600);
        }
    }

    /**
     * 栏目发布
     * @param int $id
     * @return bool
     */
    public static function approve($id, $categoryId , $status=-1) {
        $categoryData = self::findCategoryDataById($id, $categoryId);
        if(isset($categoryData) && !empty($categoryData)) {
            if($status == -1) {
                $categoryData->publish_status = $categoryData->publish_status == 1 ? 0 : 1;
            }
            else if($status == 0 || $status == 1) {
                $categoryData->publish_status = $status;
            }
            $res = $categoryData->save();
            if($res) {
                $key_sort = self::category_data_sort_key.$categoryId.":".$id;
                RedisIO::set($key_sort, json_encode($categoryData));
                //self::updateRedisList($categoryId, $id, $categoryData->sort);
                self::deleteCacheJson($categoryId);

                // 修改已发布数量统计
                self::setApproveNum($categoryId);
            }
            return $res;
        }
        return false;
    }

    /**
     * 单条锁定位置
     * @param int $id
     * @return bool
     */
    public static function nail($id) {
        $data = self::findFirst($id);
        if ($data) {
            $data->weight = $data->weight ? 0 : 1;
            return $data->save();
        }
        return false;
    }

    public static function search($mess) {
        $channel_id = Session::get('user')->channel_id;
        $category_id = $mess['c_id'];
        $title = $mess['keyword'] ?: '';
        $source_id = $mess['source_id'] ?: '';
        $start_time = strtotime($mess['created_at_from'] ?: '');
        $end_time = strtotime($mess['created_at_to'] ?: '');
        $name = $mess['author_name'] ?: '';
        $status = $mess['status'];
        $query = self::query()
            ->columns(['CategoryData.data_id', 'Data.*', 'CategoryData.*'])
            ->leftJoin("Data", "Data.id = CategoryData.data_id")
            ->andWhere("CategoryData.category_id = '{$category_id}'");
        if ($title) {
            $query = $query->andWhere("Data.title like '%$title%'");
        }
        if ($source_id) {
            $query = $query->andWhere("Data.referer_id = '{$source_id}'");
        }
        if ($start_time) {
            $query = $query->andWhere("Data.updated_at > '{$start_time}'");
        }
        if ($end_time) {
            $query = $query->andWhere("Data.updated_at < '{$end_time}'");
        }
        if ($start_time && $end_time) {
            $query = $query->andWhere("Data.updated_at < '{$end_time}' AND Data.updated_at > '{$start_time}' ");
        }
        if ($name) {
            $query = $query->andWhere("Data.author_name = '{$name}'");
        }
        if ($status != '') {
            $query = $query->andWhere("Data.status = '{$status}'");
        }
        return $query->andWhere("Data.channel_id = {$channel_id}")
            ->orderBy('CategoryData.sort desc, Data.updated_at desc')
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    /**
     * Ajax获取发布途径时使用
     */
    public static function getTerminal($data_id) {
        return self::query()
            ->columns(['Category.terminal'])
            ->leftjoin('Category', 'Category.id=CategoryData.category_id')
            ->andCondition('data_id', $data_id)
            ->execute()->toarray();
    }

    /*
     * @desc计算媒资数量
     *
     * */
    public static function calCount($channel_id,$category_id,$data_type,$status){
        $criteral =  self::query()
            ->leftJoin("Data", "Data.id = CategoryData.data_id")
            ->columns(['CategoryData.*'])
            ->andWhere("Data.channel_id = {$channel_id}")
            ->andWhere("CategoryData.category_id = " . q($category_id))
            ->andWhere("Data.type= ".q($data_type));
        if($status >= 0)
            $criteral->andWhere("Data.status= ".q($status));
        return count($criteral->execute()->toarray());
    }

    /*
     * @重置所有SORT值
     * */
    public  static function initSort(){
        $category_ids = self::listCategoryList();
        foreach($category_ids as $category_id){
            self::initSortCat($category_id);
        }
    }

    public static function listDataCategoryList($data_id){
        $category_datas = self::query()->columns("DISTINCT category_id")->where("data_id = :data_id:")->bind(array('data_id'=>$data_id))->execute();
        $category_ids = [];
        foreach($category_datas as $category_data)
            $category_ids[] = $category_data->category_id;
        return $category_ids;
    }


    private static function initSortCat($cat_id){
        $datas = self::query()->where("category_id = :cid:")->bind(array("cid"=>$cat_id))->orderBy("id DESC")->execute();
        $sort_value = $datas->count();
        foreach($datas as $data){
            $data->sort = $sort_value--;
            $data->save();
        }
    }

    private static function listCategoryList(){
        $category_datas = self::query()->columns("DISTINCT category_id")->execute();
        $category_ids = [];
        foreach($category_datas as $category_data)
            $category_ids[] = $category_data->category_id;
        return $category_ids;
    }

    /** 从data同步时间
     * @function syncTimeFromData
     * @version 1.0
     * @date
     */
    public  function syncTimeFromData() {
        $sum = self::count("publish_at = 0");
        $pageSize = 1000;
        $pageSum = ceil($sum/$pageSize);

        echo "begin to sync time,please wait ...\n";
        echo "there are {$pageSum} pages\n";
        try{

            $page = 1;
            while (self::count("publish_at = 0")>0){
                DB::begin();
                $sql = "SELECT * FROM CategoryData WHERE publish_at = 0 ORDER BY id LIMIT {$pageSize}";
                $categoryDatas=$this->modelsManager->executeQuery($sql)->toArray();
                foreach ($categoryDatas as $model){
                    $data = Data::findFirst(["id"=>$model["data_id"]]);
                    if(is_object($data)){
                        $upateSql = "UPDATE CategoryData SET publish_at = {$data->created_at} WHERE id = {$model["id"]}";
                        $this->modelsManager->executeQuery($upateSql);
                    }else{
                        echo "id got ".$model["data_id"]." error ,please try again\n";
                    }

                }
                ++$page;

                DB::commit();
                echo "The ".($page-1)." page has done\n";
            }


        }catch (\Exception $e){
            echo $e->getTraceAsString()."\n";
            DB::rollback();
            throw  $e;
        }
        echo "success! \n";
    }

    /**
     * 数据更新后的操做
     */
    public function afterSave(){
        //修改缓存时间
        $channel_id = Session::get('user')->channel_id;
        $last_modified_key = "media/latest:" . $this->category_id;
        F::_clearCache($last_modified_key, $channel_id);
    }

    /**
     * 删除栏目名称信息
     * @param $data_id
     */
    public static function delRedisCategoryName($data_id) {
        $key = self::category_id_data_id_key.$data_id;
        if(Redisio::exists($key)) {
            RedisIO::delete($key);
        }
    }

    /**
     * 根据data_id 存储栏目id,用于在媒资库的全部媒资中显示发布栏目
     * @param $data_id
     * @param array $category_id
     */
    public static function setRedisCategoryName($data_id) {
        $key = self::category_id_data_id_key.$data_id;
        if(Redisio::exists($key)) {
            RedisIO::delete($key);
        }
        $publishData = self::getPublishStatus($data_id);
        if(isset($publishData) && !empty($publishData)) {
            RedisIO::set($key, json_encode($publishData), 1800);
        }
        return $publishData;
    }

    /**
     * 获取 redis存储的栏目名称, 用于媒资列表用所在栏目列显示
     * @param $data_id
     * @return mixed
     */
    public static function getRedisCategoryName($data_id) {
        $data = array();
        $key = self::category_id_data_id_key.$data_id;
        if(RedisIO::exists($key)) {
            $data = RedisIO::get($key);
            $data = json_decode($data, true);
        }
        else {
            $data = self::setRedisCategoryName($data_id);
        }
        return $data;
    }

    /**
     * 根据data_id 获取category_data表的id，栏目id，发布状态
     * @param $data_id
     */
    public static function getPublishStatus($data_id) {
        $data = self::query()
            ->columns(['CategoryData.id', 'CategoryData.category_id', 'CategoryData.publish_status','Category.name'])
            ->leftjoin('Category', 'Category.id=CategoryData.category_id')
            ->andCondition('data_id', $data_id)
            ->execute()->toarray();
        return $data;
    }

    /**
     * 更新发布状态
     * @param $id
     * @param $publishStatus
     * @return bool
     */
    public function updatePublishStatus($id, $publishStatus) {
       $data = self::query()
           ->andCondition('id', $id)
           ->first();
        $data->publish_status = $publishStatus;
        return ($data->update()) ? true : false;
    }

    /**
     * 删除推荐位列表，有序列表的redis 数据
     * @param $category_id
     * @param $data_id
     */
    public static function deleteRedisCache($category_id, $data_id) {
        $channel_id = Auth::user()->channel_id;
        CategoryData::deleteCacheJson($category_id);
        CategoryData::deleteListRedis($category_id, $channel_id);

        // 修改已发布数量统计
        self::setApproveNum($category_id);

        SmartyData::delDataRedis($data_id);
        SmartyData::delCategoryDataRedis($category_id);

        // 删除媒资
        $key = Data::data_detail_key .":". $data_id;
        if(RedisIO::exists($key)) {
            $result = RedisIO::del($key);
        }

        $key_sort = self::category_data_sort_key.$category_id.":".$data_id;
        if(RedisIO::exists($key_sort)) {
            RedisIO::delete($key_sort);
        }

        if(CategoryData::PAGE_CACHE_NUMBER > 0) {
            for($i = 0; $i < CategoryData::PAGE_CACHE_NUMBER; $i++) {
                $page = $i + 1;
                $key_cache_json_key = "Backend:cache_json:" . $channel_id . ":" . $category_id . ":" . $page;
                if (RedisIO::exists($key_cache_json_key)) {
                    RedisIO::delete($key_cache_json_key);
                }
            }
        }

       $res =  self::deleteRedisList($category_id, $data_id);
       $res1 =  self::deleteRedisRecommend($category_id, $data_id);

    }

    public static function getCategoryAdminWithLiveAndGps($category_id, $channel_id, $page, $size = 20) {

        $listKey = __FUNCTION__."admin.list.live.gps".$category_id.$channel_id.$page.$size;
        $countKey = __FUNCTION__."admin.list.live.gps.count";
        if (RedisIO::exists($listKey)){
            $categoryDatas = json_decode(RedisIO::get($listKey),true);
            $sum = intval(RedisIO::get($countKey));
        }else {
            $model = new CategoryData();

            $countSql = "SELECT COUNT(*) from CategoryData  LEFT JOIN Data on CategoryData.data_id = Data.id  
                        WHERE CategoryData.category_id = {$category_id} and CategoryData.publish_status = 1  and Data.status = 1 and Data.channel_id = {$channel_id} GROUP BY Data.author_id ";
            $sum = $model->modelsManager->executeQuery($countSql)->count() ;

            $authorSumSql = "SELECT Data.author_id,count(Data.author_id) as total from CategoryData  LEFT JOIN Data on CategoryData.data_id = Data.id  
                        WHERE CategoryData.category_id = {$category_id} and CategoryData.publish_status = 1  and Data.status = 1 and Data.channel_id = {$channel_id} GROUP BY Data.author_id ";
            $authorSumArr = $model->modelsManager->executeQuery($authorSumSql)->toArray();

            $offset = ($page - 1) * $size;
            $sql = "SELECT Data.author_id  from CategoryData  LEFT JOIN Data on CategoryData.data_id = Data.id  
                        WHERE CategoryData.category_id = {$category_id} and CategoryData.publish_status = 1 and Data.status = 1 and Data.channel_id = {$channel_id} GROUP BY Data.author_id  LIMIT {$offset}, {$size}";

            $categoryDatas = $model->modelsManager->executeQuery($sql)->toArray();

            $mediaSum = [];
            foreach ($authorSumArr as $authorSum){
                $mediaSum[$authorSum["author_id"]] = $authorSum["total"];
            }

            //$s['stream_event'] == 'start' ? '开始' : $s['stream_event'] == 'end' ? '结束' : ''
            foreach ($categoryDatas as &$cd) {
                $adminId = $cd["author_id"];

                //gps 坐标
                $lastData = Data::query()
                    ->columns(["Data.longitude,Data.latitude,Data.id"])
                    ->leftJoin("CategoryData","CategoryData.data_id = Data.id")
                    ->andWhere("CategoryData.category_id= {$category_id}")
                    ->andWhere("CategoryData.publish_status = 1")
                    ->andWhere("Data.status = 1")
                    ->andWhere("Data.author_id = {$adminId}")
                    ->andWhere("Data.longitude > 0")
                    ->orderBy("Data.created_at DESC")
                    ->execute()
                    ->getFirst();
                if(!$lastData){
                    continue;
                }
                $cd["longitude"] = $lastData->longitude;
                $cd["latitude"] = $lastData->latitude;
                $cd["data_id"] = $lastData->id;

                $cd["media_sum"] = $mediaSum[$adminId];
                //直播状态
                $ugc = UgcyunLive::query()
                    ->columns(["stream_event"])
                    ->andWhere("admin_id = {$adminId}")
                    ->orderBy("id desc")
                    ->limit(1)
                    ->execute()
                    ->toArray();
                $ugc = reset($ugc);
                if (isset($ugc["stream_event"])) {
                    switch ($ugc["stream_event"]) {
                        case "start":
                            $cd["live_status"] = 1;
                            break;
                        case "end":
                            $cd["live_status"] = 2;
                            break;
                    }
                } else {
                    $cd["live_status"] = 0;
                }

                //头像
                $adminData = Admin::query()
                    ->columns(["avatar","name"])
                    ->andWhere("id = {$adminId}")
                    ->execute()
                    ->getFirst();
                $cd["avatar"] = cdn_url("image",$adminData->avatar);
                $cd["name"] = $adminData->name;
            }

            RedisIO::set($countKey,$sum);
            RedisIO::set($listKey,json_encode($categoryDatas),5);
        }
        return [$categoryDatas,$sum];
    }

    /**
     * 根据栏目id获取栏目媒资关联数据
     * @param $data_id
     */
    public static function findCategoryDataByDataId($data_id) {
        $data = self::query()
            ->andCondition('data_id', $data_id)
            ->first();
        return $data;
    }

    /**修改栏目下某个媒资的发布状态
     * @function changePublishStatus
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $categoryId
     * @param $dataId
     * @param $status
     */
    public static function changePublishStatus($categoryId, $dataId, $status) {
        $query = self::query()
            ->where("data_id = {$dataId} and category_id = {$categoryId}")
            ->execute()
            ->getFirst();

        $query->publish_status = $status;
        if(!$query->save()){
            return false;
        }
       return true;
    }

}