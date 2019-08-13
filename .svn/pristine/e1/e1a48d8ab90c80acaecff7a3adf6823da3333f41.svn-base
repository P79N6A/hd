<?php

/**
 * @class:   MediaController
 * @author:  汤荷
 * @version: 1.0
 * @date:    2017/2/11
 */
/**
 * @RoutePrefix("/media")
 */
class MediaController extends  ApiBaseController
{


    public function initialize() {
        parent::initialize();
        $this->checkToken();
        $this->channel_id = Request::get("channel_id");
    }

    /**
     * @Post("/gps/{id:[0-9]+}")
     * @param int $id
     * @return json
     */
    public function gpsAction($id) {
        $this->channel_id = Request::get("channel_id");

        // id 为0 啥也不做
        if($id== 0 ){
            $this->_json([]);
        }
        $key = __FUNCTION__ . "data:gps:{$id}";
        $gps = RedisIO::get($key);

        $gpsStr =  Request::getPost("longitude").",". Request::getPost("latitude");
        $gpsKey = Admin::adminGpsRedisKey($this->user->id);
        RedisIO::set($gpsKey,$gpsStr);

        $needUpdate = false;
        if (!$gps) {
            $needUpdate = true;
        } else {
            $gpsArr = json_decode($gps, true);
            if ($gpsArr["longitude"] != Request::getPost("longitude") || $gpsArr["latitude"] != Request::getPost("latitude")) {
                $needUpdate = true;
            }
        }
        if ($needUpdate) {
            $channel_id = $this->channel_id;
            $data = Data::getByDataId($channel_id, $id);
            if ($data) {
                $data->longitude = Request::getPost("longitude");
                $data->latitude = Request::getPost("latitude");
                $data->save();
                RedisIO::set($key, json_encode(["longitude" => Request::getPost("longitude"), "latitude" => Request::getPost("latitude")]));

                $this->_json([]);
            } else {
                $this->_json([], 404, "failed");
            }
        } else {
            $this->_json([]);
        }
    }

    /**
     * @Post("/add")
     * @return json
     */
    public function addAction() {

        $input = Request::getPost();
        $validator = self::makeValidator($input);
        if($validator->fails()){
            $this->_json([], 404, "参数错误");
        }

        $images = json_decode(Request::getPost("images"),true);
        if(!$images){
            $this->_json([], 404, "图片参数错误");
        }
        $categoryId = Request::getPost("category_id");
        unset($input["images"]);
        unset($input["category_id"]);
        unset($input["channel_id"]);

        $gpsStr = $input["longitude"].",".$input["latitude"];
        $gpsKey = Admin::adminGpsRedisKey($this->user->id);
        RedisIO::set($gpsKey,$gpsStr);

        try {
            DB::begin();

            $input["created_at"] = time();
            $input["updated_at"] = time();
            $input["channel_id"] = $this->channel_id;
            $input["author_id"] = $this->user->id;
            $input["author_name"] = $this->user->name;
            $input["referer_author"] = $this->user->name;
            $input["comment_type"] = 2;
            $input["partition_by"] = date("Y");
            if(empty($input["thumb"]) || $input["thumb"] == '""'){
                $input["thumb"] = $images[0];
            }
            $input["content"] = "";
            $input["status"] = 1; //审核

            //1、新建新闻
            $new_data_id = Data::createData($input, 'news');
            if (!$new_data_id) {
                $this->throwDbE('News save failed');
            }
            //2、新建相册
            $album = new Album();
            $album->created_at= $input["created_at"];
            $album->updated_at= $input["updated_at"];
            $album->channel_id = $this->channel_id;

            if (!$album->save()) {
                $this->throwDbE('Album_model');
            }
            //新上传的图片
            if (count($images) > 0) {
                $album_id = $album->id;
                $imgData = [
                    'path' => '',
                    'intro' => $input['intro'],
                    'author_id' => $this->user->id,
                    'author_name' => $this->user->name,
                    'sort' => 0
                ];
                $imgIds = [];
                foreach ($images as $idx => $path) {
                    $imgData["path"] = $path;
                    $imgData["sort"] = $idx;
                    $m = new AlbumImage();
                    $albumImgId = $m->saveImg($album_id, $imgData, date('Y'));
                    if (!$albumImgId) {
                        $this->throwDbE('Album image model');
                    }
                    $imgIds[] = $albumImgId;
                }
            }

            $album_data = new Data();
            foreach ($input as $key => $val) {
                $album_data->$key = $val;
            }
            $album_data->source_id = $album->id;
            $album_data->hits = 0;
            $album_data->type = "album";
            $album_data->data_data = "[]";
            if (!$album_data->save()) {
                $this->throwDbE('data album model');
            }

            $album_data_id = intval($album_data->id);

            //3、新建复合媒资
            $input["data_data"] = "[{$album_data_id}, {$new_data_id}]";
            $data_data_ext = new stdClass();
            $data_data_ext->news = [
                ["data_id"=>$new_data_id,"template"=>"default"]
            ];
            $data_data_ext->album = [
                ["data_id"=>$album_data_id, "template"=>"default"]
            ];
            $input["data_data_ext"] = json_encode($data_data_ext);
            $multimedia_data_id = Data::createData($input, 'multimedia');

            $media_publish = explode(',', $categoryId);
            if (CategoryData::publish($multimedia_data_id, $media_publish) === false) {
                $this->throwDbE('Category Publish Error');
            }

            CategoryData::deleteListRedis($categoryId,$this->channel_id);
            if(CategoryData::PAGE_CACHE_NUMBER > 0 ){
                for ($i = 0;$i<CategoryData::PAGE_CACHE_NUMBER;$i++){
                    $page = $i + 1;
                    $key_cache_json_key = "Backend:cache_json:".$this->channel_id .":".$categoryId.":".$page;
                    if(RedisIO::exists($key_cache_json_key)){
                        RedisIO::delete($key_cache_json_key);
                    }
                }
            }
			SmartyData::delCategoryDataRedisChannel($categoryId, $this->channel_id);

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
//            $this->throwDbE($exception);
            $this->_json([],404,$exception->getMessage());
        }
        $this->_json([]);
    }


    protected static function makeValidator($input) {
        return Validator::make(
            $input,
            [
                'title' => 'required|max:255',
                'thumb' => 'required',
                'intro' => 'required',
                'images'=> 'required',
                'category_id'=>'required',
                'longitude' => 'required',
                'latitude' => 'required',
                'channel_id' => 'required',
            ],
            []
        );
    }


    /**
     * @Get("/latestDetail")
     * @return json
     */
    public function latestDetailAction() {

        $categoryId = Request::getQuery('category_id', 'string');

        $channelId = Request::getQuery('channel_id', 'string');
        $domainId = Request::getQuery('domain_id', 'int', 0);
        if($channelId != '' && $this->isNum($channelId)) {
            SmartyData::init((int)$channelId, $domainId);
            $this->findCategoryData($categoryId,true);
        }

    }


    private function isNum($id) {
        if (preg_match("/^[0-9]*$/i", $id)) {
            return true;
        }
        else {
            return false;
        }
    }

    /**
     * 媒资发布库
     */
    public function findCategoryData($id, $detailFlag = false) {
        $sort = Request::getQuery('sort', 'int', 0);
        $size = 0;
        $page = null;
        $this->returnPageAndSize($size, $page);
        $author_id = $this->user->id;
        $data = array();
        if($sort > 0) {
            $data = SmartyData::getLatestWithSort($id, $size, $page,$author_id);
        }else {
            $data = SmartyData::getLatest($id, $size, $page,$author_id);
        }
        $this->returnLatestData($data, $size, $page, $sort, $detailFlag);
    }

    /**
     * 获取 行数和页号
     * @param unknown $size
     * @param unknown $page
     */
    private function returnPageAndSize(&$size, &$page) {
        $getSize = Request::getQuery('size', 'string');
        if($getSize != '' && $this->isNum($getSize)) {
            $size = (int)$getSize;
            if($size > 1000) {
                $size = 1000;
            }
        }
        $page = Request::getQuery('page', 'string');
        if($page != '' && $this->isNum($page)) {
            $page =(int)$page;
        }else {
            $page = 1;
        }
    }


    /**
     * 返回 总记录， 总页数， 请求行数， 请求页数
     * @param unknown $data
     * @param unknown $size
     * @param unknown $page
     * @para bool $detailFlag
     */
    private function returnLatestData($data, $size, $page, $sort, $detailFlag = false) {
        $pages = ceil($data["count"] / $size);
        $resData = array(
            "list" => $data["models"],		// 数据
            "count" => $data["count"],			// 总记录
            "pages" => $pages,					// 总页数
            "size"  => $size,					// 一页行数
            "page"  => $page,					// 当前页
            "channel_id" => $this->channel_id,  // 频道号
            "sort"  => $sort,					// 是否排序，0：不排序， 1：排序
        );
        if($detailFlag){
            //todo::获取详情
            $this->getDetail($resData,$data);
        }
        $this->_json($resData);
    }

    /**获取详情
     * @function getDetail
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $resData
     * @param $data
     */
    private function getDetail(&$resData,&$data){
        $detailData = [];

        foreach ($data["models"] as  $model){
            $tmpData = $model;
            $tmpData["detail"] = [];
            $tmpData["thumb"] = cdn_url("image",$tmpData["thumb"]);
            // 直播或点播
            $playUrls = Data::getSignalPlayUrl($model["id"]);
            if(strpos($model["redirect_url"],"m3u8") !== false){ //点播
                $tmpData["detail"]["video"]=[
                    "m3u8"=>  $model["redirect_url"]
                ];

            }else if (count($playUrls) > 0 ){ //直播
                $tmpData["detail"]["live"]=[];
                foreach ($playUrls as $urls){
                    if(strpos($urls["play_url"],"m3u8") !== false){
                        $tmpData["detail"]["live"]["m3u8"] = $urls["play_url"];
                    }else if (strpos($urls["play_url"],"rtmp") !== false){
                        $tmpData["detail"]["live"]["rtmp"] = $urls["play_url"];
                    }
                }

            }else{ //图集
                $tmpData["detail"]["image"] = Data::getAlbumImageFiles($model["id"]);
            }
            $detailData[] = $tmpData;
        }

        $resData["list"] = $detailData;
    }



    /**
     * @Post("/attach")
     * @return json
     */
    public function attachAction() {

        $input = Request::getPost();

        $files = json_decode(Request::getPost("files"),true);
        if(!$files){
            $this->_json([], 404, "文件参数错误");
        }
        $categoryId = Request::getPost("category_id");
        unset($input["files"]);
        unset($input["category_id"]);
        unset($input["channel_id"]);

        $gpsStr = $input["longitude"].",".$input["latitude"];
        $gpsKey = Admin::adminGpsRedisKey($this->user->id);
        RedisIO::set($gpsKey,$gpsStr);


        try {
            DB::begin();

            $input["created_at"] = time();
            $input["updated_at"] = time();
            $input["channel_id"] = $this->channel_id;
            $input["author_id"] = $this->user->id;
            $input["author_name"] = $this->user->name;
            $input["referer_author"] = $this->user->name;
            $input["comment_type"] = 2;
            $input["partition_by"] = date("Y");
            if(empty($input["thumb"]) || $input["thumb"] == '""'){
                $input["thumb"] = $files[0];
            }
            $input["content"] = "";
            $input["status"] = 1; //审核

            //1、新建新闻
            $new_data_id = Data::createData($input, 'news');
            if (!$new_data_id) {
                $this->throwDbE('News save failed');
            }
            //2、新建附件
            $attach_id = Attachs::createAttach($this->channel_id,$files);
            if(!$attach_id){
                $this->throwDbE('attach  save failed');
            }
            $attach_data = new Data();
            foreach ($input as $key => $val) {
                $attach_data->$key = $val;
            }
            $attach_data->source_id = $attach_id;
            $attach_data->hits = 0;
            $attach_data->type = "attach";
            $attach_data->data_data = "[]";
            if (!$attach_data->save()) {
                $this->throwDbE('data album model');
            }

            $album_data_id = intval($attach_data->id);

            //3、新建复合媒资
            $input["data_data"] = "[{$album_data_id}, {$new_data_id}]";
            $data_data_ext = new stdClass();
            $data_data_ext->news = [
                ["data_id"=>$new_data_id,"template"=>"default"]
            ];
            $data_data_ext->attach = [
                ["data_id"=>$album_data_id, "template"=>"default"]
            ];
            $input["data_data_ext"] = json_encode($data_data_ext);
            $multimedia_data_id = Data::createData($input, 'multimedia');

            $media_publish = explode(',', $categoryId);
            if (CategoryData::publish($multimedia_data_id, $media_publish) === false) {
                $this->throwDbE('Category Publish Error');
            }

            if(CategoryData::changePublishStatus($categoryId,$multimedia_data_id,0) == false ){
                $this->throwDbE('Change Category Publish  Status Error');
            }

            CategoryData::deleteListRedis($categoryId,$this->channel_id);
            if(CategoryData::PAGE_CACHE_NUMBER > 0 ){
                for ($i = 0;$i<CategoryData::PAGE_CACHE_NUMBER;$i++){
                    $page = $i + 1;
                    $key_cache_json_key = "Backend:cache_json:".$this->channel_id .":".$categoryId.":".$page;
                    if(RedisIO::exists($key_cache_json_key)){
                        RedisIO::delete($key_cache_json_key);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $exception) {
            DB::rollback();
//            $this->throwDbE($exception);
            $this->_json([],404,$exception->getMessage());
        }
        $this->_json([]);
    }
}