<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;
use GenialCloud\Exceptions\DatabaseTransactionException;

class SpecialCategoryData extends Model {

    public static $PAGE_SIZE = 25;

    const  PAGE_CACHE_NUMBER = 2;

    const  special_category_data_sort_key = 'backend::special::data::sort:';//
    const  special_category_data_publish_num_key = 'backend::special::data::publishnumbers:';

    public function getSource() {
        return 'special_category_data';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'special_category_id', 'data_id', 'sort', 'weight',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['special_category_id', 'data_id', 'sort', 'weight',],
            MetaData::MODELS_NOT_NULL => ['id', 'special_category_id', 'data_id', 'sort', 'weight',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'special_category_id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
                'weight' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'special_category_id', 'data_id', 'sort', 'weight',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'special_category_id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
                'weight' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'sort' => '0',
                'weight' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findSpCategory($id) {
        return self::query()
            ->columns(['SpecialCategory.*', 'SpecialCategoryData.*'])
            ->leftjoin('SpecialCategory', 'SpecialCategory.id = SpecialCategoryData.special_category_id')
            ->andwhere('SpecialCategoryData.data_id =' . $id)
            ->first();
    }

    public static function findAllBySpecial($special_id, $spec_category_id=null) {
        $criteral =  self::query()
            ->columns(['SpecialCategoryData.*', 'SpecialCategory.*', 'Data.*','DataStatistics.*'])
            ->leftjoin('SpecialCategory', 'SpecialCategory.id = SpecialCategoryData.special_category_id')
            ->leftjoin('Data', 'Data.id = SpecialCategoryData.data_id')
            ->leftJoin("DataStatistics","Data.id = DataStatistics.data_id")
            ->orderBy("SpecialCategoryData.sort DESC")
            ->andwhere('SpecialCategory.special_id=' . $special_id);
        if(!empty($spec_category_id) && (int)$spec_category_id >0)
            $criteral->andwhere('SpecialCategory.id='.$spec_category_id);
        return $criteral->paginate(50, 'Pagination');
    }


    public static function findSpecialTop() {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query()
            ->columns(['SpecialCategoryData.data_id', 'Data.*', 'SpecialCategoryData.*', 'DataStatistics.*'])
            ->leftJoin("Data", "Data.id = SpecialCategoryData.data_id")
            ->leftJoin("DataStatistics", "DataStatistics.data_id = Data.id")
            ->andWhere("Data.channel_id = {$channel_id} and SpecialCategoryData.weight=1")
            ->orderBy('Data.created_at desc');
        $query = self::findAllFilter($query);
        return $query->orderBy('SpecialCategoryData.weight desc, SpecialCategoryData.sort desc, Data.created_at desc')
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    public static function findSpecialList() {
        $channel_id = Session::get('user')->channel_id;
        $query = self::query()
            ->columns(['SpecialCategoryData.data_id', 'Data.*', 'SpecialCategoryData.*', 'DataStatistics.*'])
            ->leftJoin("Data", "Data.id = SpecialCategoryData.data_id")
            ->leftJoin("DataStatistics", "DataStatistics.data_id = Data.id")
            ->andWhere("Data.channel_id = {$channel_id} and SpecialCategoryData.weight=0")
            ->orderBy('Data.created_at desc');
        $query = self::findAllFilter($query);
        return $query->orderBy('SpecialCategoryData.weight desc, SpecialCategoryData.sort desc, Data.created_at desc')
            ->paginate(self::$PAGE_SIZE, 'Pagination');
    }

    public static function findSpecialSimpleList($special_category_id, $channel_id, $weight=0) {
        $query = self::query()
            ->columns(['SpecialCategoryData.*'])
            ->leftJoin("Data", "Data.id = SpecialCategoryData.data_id")
            ->andWhere("Data.channel_id = {$channel_id} and SpecialCategoryData.weight=".$weight." and SpecialCategoryData.special_category_id = " . intval($special_category_id))
            ->orderBy('SpecialCategoryData.sort desc, Data.created_at desc');
        return $query->limit(self::$PAGE_SIZE * self::PAGE_CACHE_NUMBER)->execute();
    }

    private static function findAllFilter($query) {
        if ($r = Request::get('special_category_id')) {
            $query->andWhere("SpecialCategoryData.special_category_id = " . q(intval($r)));
        } else {
            $query->andWhere("SpecialCategoryData.special_category_id = 0");
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

    public static function findTopFromRedis() {
        $channel_id = Session::get('user')->channel_id;
        $sc_id = Request::get('special_category_id');
        $key = Data::category_data_list_key."::special::recommend:".$channel_id .":". $sc_id;
        $page = (Request::get('page'))?intval(Request::get('page')):1;
        if($page > self::PAGE_CACHE_NUMBER) {
            return SpecialCategoryData::findSpecialTop();
        }
        else {
            RedisIO::delete($key);
                if(!RedisIO::exists($key)) {
                    $cds = SpecialCategoryData::findSpecialSimpleList($sc_id, $channel_id, 1);
                    foreach($cds as $cd) {
                        RedisIO::zAdd($key, $cd->sort, $cd->data_id);
                        $key_sort = self::special_category_data_sort_key.$sc_id.":".$cd->data_id;
                        RedisIO::set($key_sort, json_encode($cd));
                    }
                }
                $list  =  RedisIO::zRevRange($key, ($page - 1) * self::$PAGE_SIZE, $page * self::$PAGE_SIZE - 1);
                $special_category_data = array();
                foreach($list as $data_id) {
                    $key_sort = self::special_category_data_sort_key.$sc_id.":".$data_id;
                    $cd = RedisIO::get($key_sort);
                    $model = Data::getDataRedis($data_id, $channel_id);
                    array_push($special_category_data , array('cd'=>json_decode($cd), 'model'=>$model));
                }
            $special_category_data = json_encode($special_category_data);
            return  json_decode($special_category_data);
        }
    }

    public static function findListFromRedis() {
        $channel_id = Session::get('user')->channel_id;
        $sc_id = Request::get('special_category_id');
        $key = Data::category_data_list_key."::special:".$channel_id .":". $sc_id;
        $page = (Request::get('page'))?intval(Request::get('page')):1;
        if($page > self::PAGE_CACHE_NUMBER) {
            return SpecialCategoryData::findSpecialList();
        }
        else {

            RedisIO::delete($key);
                if(!RedisIO::exists($key)) {
                    $cds = SpecialCategoryData::findSpecialSimpleList($sc_id, $channel_id);
                    foreach($cds as $cd) {
                        RedisIO::zAdd($key, $cd->sort, $cd->data_id);
                        $key_sort = self::special_category_data_sort_key.$sc_id.":".$cd->data_id;
                        RedisIO::set($key_sort, json_encode($cd));
                    }
                }
                $list = RedisIO::zRevRange($key, ($page - 1) * self::$PAGE_SIZE, $page * self::$PAGE_SIZE - 1);
                $special_category_data = array();
                foreach($list as $data_id) {
                    $key_sort = self::special_category_data_sort_key.$sc_id.":".$data_id;
                    $cd = RedisIO::get($key_sort);
                    $model = Data::getDataRedis($data_id, $channel_id);
                    array_push($special_category_data , array('cd'=>json_decode($cd), 'model'=>$model));
                }
            $special_category_data = json_encode($special_category_data);
            return  json_decode($special_category_data);
        }
    }

    public static function findSpecialCategoryPublishInfo() {
        $channel_id = Session::get('user')->channel_id;
        $sc_id = Request::get('special_category_id');
        $key_all = self::special_category_data_publish_num_key.":all:".$channel_id.":".$sc_id;
        $key_approve = self::special_category_data_publish_num_key.":approve:".$channel_id.":".$sc_id;
        if(!RedisIO::exists($key_all)||!RedisIO::exists($key_approve)) {
            $result_all = self::query()
                ->columns(['SpecialCategoryData.*'])
                ->leftJoin("Data", "Data.id = SpecialCategoryData.data_id")
                ->andWhere("Data.channel_id = {$channel_id} and SpecialCategoryData.special_category_id = " . intval($sc_id))
                ->execute();
            RedisIO::set($key_all, $result_all->count(), 600);
            $result_approve = self::query()
                ->columns(['SpecialCategoryData.*'])
                ->leftJoin("Data", "Data.id = SpecialCategoryData.data_id")
                ->andWhere("Data.channel_id = {$channel_id} and Data.status=1 and SpecialCategoryData.special_category_id = " . intval($sc_id))
                ->execute();
            RedisIO::set($key_approve, $result_approve->count(), 600);
        }
        $all_number =RedisIO::get($key_all);
        $approve_number = RedisIO::get($key_approve);
        return [$all_number, $approve_number];
    }

    public static function getIdByData($id) {
        $data = self::query()->andCondition('data_id', $id)->execute()->toArray();
        return !empty($data) ? array_values(array_refine($data, 'id', 'special_category_id')) : [];
    }

    /**
     * 发布
     * @param int $id
     * @param array $category_id
     * @return mix
     */
    public static function publish($id, array $categories) {
        DB::begin();
        try {
            $data = self::query()->andCondition('data_id', $id)->execute();
            if (!empty($data)) {
                $data->delete();
            }
            foreach ($categories as $category) {
                $model = new self;
                $model->save([
                    'data_id' => $id,
                    'special_category_id' => $category,
                    'partition_by' => date("Y")
                ]);
                self::top($category,$id);
            }

            DB::commit();
            return self::getDiffCategory($data, $categories);
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            return [];
        }
    }

    private static function getDiffCategory($data, $categories) {
        if (empty($data)) {
            return $categories;
        }
        $old = array_refine($data->toArray(), 'id', 'category_id');
        return array_diff($old, $categories);
    }


    public static function sortBySorts(array $ids,array $sorts){

        foreach($ids as $key=>$cat_data_id){
            $cateogrydata = self::findFirst($cat_data_id);
            $cateogrydata->sort = $sorts[$key];

            if($cateogrydata->weight) {
                self::updateRedisRecommend($cateogrydata->special_category_id, $cateogrydata->data_id, $cateogrydata->sort);
            }
            else {
                self::updateRedisList($cateogrydata->special_category_id, $cateogrydata->data_id, $cateogrydata->sort);
            }
            if(!$cateogrydata->update())
                return false;
        }
        return true;
    }

    /**
     * 单条置顶
     * @param int $category_id
     * @param int $id
     * @return bool
     */
    public static function top($category_id, $id) {
        $data = self::query()
            ->andCondition('special_category_id', $category_id)
            ->andCondition('data_id', $id)
            ->first();
        if ($data) {
            $oldTop = self::query()
                ->andCondition('special_category_id', $category_id)
                ->orderBy("sort desc")
                ->first();
            $data->sort = $oldTop ? $oldTop->sort + 1 : 1;
            return $data->save();
        }
        return false;
    }



    //取消推荐位
    private static function updateRedisList($category_id, $data_id, $sort) {
        $channel_id = Session::get('user')->channel_id;
        $redis_key = Data::category_data_list_key ."::special:". $channel_id .":". $category_id;
        RedisIO::zAdd($redis_key, $sort, $data_id);

        $key_sort = self::special_category_data_sort_key.$category_id.":".$data_id;
        $cd = json_decode(RedisIO::get($key_sort));
        $cd->weight = 0;
        RedisIO::set($key_sort, json_encode($cd));
    }

    private static function deleteRedisList($category_id, $data_id) {
        $channel_id = Session::get('user')->channel_id;
        $redis_key = Data::category_data_list_key ."::special:". $channel_id .":". $category_id;
        RedisIO::zRem($redis_key, $data_id);
    }


    //设置推荐位
    private static function updateRedisRecommend($category_id, $data_id, $sort) {
        $channel_id = Session::get('user')->channel_id;
        $redis_key = Data::category_data_list_key ."::special::recommend:". $channel_id .":". $category_id;
        RedisIO::zAdd($redis_key, $sort, $data_id);
        $key_sort = self::special_category_data_sort_key.$category_id.":".$data_id;
        $cd = json_decode(RedisIO::get($key_sort));
        $cd->weight = 1;
        RedisIO::set($key_sort, json_encode($cd));
    }

    private static function deleteRedisRecommend($category_id, $data_id) {
        $channel_id = Session::get('user')->channel_id;
        $redis_key = Data::category_data_list_key ."::special::recommend:". $channel_id .":". $category_id;
        RedisIO::zRem($redis_key, $data_id);
    }


    /**
     * 专题栏目推荐
     * @param int $special_category_id
     * @param int $id
     * @return bool
     */
    public static function recommend($special_category_id, $id) {
        $data = self::query()
            ->andCondition('special_category_id', $special_category_id)
            ->andCondition('data_id', $id)
            ->first();
        if ($data) {
            $oldTop = self::query()
                ->andCondition('special_category_id', $special_category_id)
                ->orderBy("sort desc")
                ->first();
            $data->sort = $oldTop ? $oldTop->sort + 1 : 1;
            $data->weight = 1;
            self::updateRedisRecommend($special_category_id, $id, $data->sort);
            self::deleteRedisList($special_category_id, $id);
            return $data->save();
        }
        return false;
    }

    /**
     * 取消专题栏目推荐
     * @param int $special_category_id
     * @param int $id
     * @return bool
     */
    public static function cancelRecommend($special_category_id, $id) {
        $data = self::query()
            ->andCondition('special_category_id', $special_category_id)
            ->andCondition('data_id', $id)
            ->first();
        if ($data) {
            $key_sort = self::special_category_data_sort_key.$special_category_id.":".$id;
            $sortval = json_decode(RedisIO::get($key_sort));
            self::updateRedisList($special_category_id, $id, $sortval->sort);
            self::deleteRedisRecommend($special_category_id, $id);
            $data->weight = 0;
            return $data->save();
        }
        return false;
    }




    public static function listInfo($id,$direct,$offset,$pagesize){
        $criterial = self::query()
            ->Columns(["SpecialCategory.*","SpecialCategoryData.*","Data.*","DataStatistics.*"])
            ->leftJoin("SpecialCategory","SpecialCategory.id = SpecialCategoryData.special_category_id")
            ->leftJoin("Data","Data.id = SpecialCategoryData.data_id")
            ->leftJoin("DataStatistics","DataStatistics.data_id = SpecialCategoryData.data_id")
            ->andwhere("SpecialCategory.id = $id")
            ->orderBy($direct)
            ->limit($pagesize,$offset);
        $datas =  $criterial->execute();
        $returns = [];
        foreach($datas as $model){
            $statistics = $model->dataStatistics->toArray();
            $data = array(
                'data_id'=>$model->data->id,
                'title'=>$model->data->title,
                'type'=>$model->data->type,
                'weight'=>$model->specialCategoryData->weight,
                'sort'=>$model->specialCategoryData->sort,
                'intro'=>$model->data->intro,
                'count'=>self::formatStatistic($statistics),
                'create_at'=>$model->data->created_at,
                'thumb' => (false===stripos($model->data->thumb, "image.xianghunet.com"))?cdn_url('image',$model->data->thumb):$model->data->thumb,
                'url'=>'',
                'wapurl'=>'',
                'thumbs'=>$model->data->type == 'album'?AlbumImage::getAlbumImage($model->data->source_id):[],
                'video_info'=>$model->data->type == 'video'?Videos::getWithFiles($model->data->source_id):[],                
            );
            array_push($returns,$data);
        }
        return $returns;
    }

    private static function formatStatistic($statistics){

        if(array_key_exists('data_id',$statistics))
            unset($statistics['data_id']);
        if(array_key_exists('formulas',$statistics))
            unset($statistics['$statistics']);
        foreach($statistics as $key=>$item)
            $statistics[$key] = empty($item)?0:intval($item);
        return $statistics;
    }


    public static function listIds($id,$direct,$offset,$pagesize){
        $criterial = self::query()
            ->Columns(["Data.id"])
            ->leftJoin("SpecialCategory","SpecialCategory.id = SpecialCategoryData.special_category_id")
            ->leftJoin("Data","Data.id = SpecialCategoryData.data_id")
            ->leftJoin("DataStatistics","DataStatistics.data_id = SpecialCategoryData.data_id")
            ->andwhere("SpecialCategory.id = $id")
            ->orderBy($direct)
            ->limit($pagesize,$offset);
        $datas = $criterial->execute();
        $returns = [];
        foreach($datas as $model){
            $returns[] = $model->id;
        }
        return $returns;
    }

    public static function columnCount($special_cat_id){
        return self::query()
            ->where("special_category_id=:special_cat_id:")
            ->bind(array('special_cat_id'=>$special_cat_id))
            ->execute()
            ->count();
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

    private static function initSortCat($cat_id){
        $datas = self::query()->where("special_category_id = :cid:")->bind(array("cid"=>$cat_id))->orderBy("id DESC")->execute();
        $sort_value = $datas->count();
        foreach($datas as $data){
            $data->sort = $sort_value--;
            $data->save();
        }
    }

    private static function listCategoryList(){
        $category_datas = self::query()->columns("DISTINCT special_category_id")->execute();
        foreach($category_datas as $category_data)
            $category_ids[] = $category_data->special_category_id;
        return $category_ids;
    }

    /**
     * 数据更新后的操做
     */
    public function afterSave(){
        //修改缓存时间
        $channel_id = Session::get('user')->channel_id;
        $last_modified_key = "media/latest:" . $this->special_category_id;
        F::_clearCache($last_modified_key, $channel_id);
        SmartyData::delSpecialCategoryDataRedis($this->special_category_id);
    }

}