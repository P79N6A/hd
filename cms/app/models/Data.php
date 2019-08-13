<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Data extends Model {

    use HasChannel;

    const PAGE_SIZE             = 50;
    const STATE_PENDING         = 9;   //待审核
    const STATE_APPROVE         = 1;   //审核
    const STATE_REJECT          = 0;   //撤销
    const STATE_RECYCLE         = 3;   //下架


    const STATUS_PENDING_DISPLAY = "未审核";
    const STATUS_APPROVE_DISPLAY = 	"已发布";

    const category_data_list_key = 'backend::categorydata::list:';//
    const data_list_key = 'backend::data::list:';//
    const data_detail_key = 'backend::data::detail:';//


    /**
     * 评论类型
     *
     * @var array
     */

    protected static $commentTypes = [
        1 => '禁用评论',
        2 => '先审后发',
        3 => '先发后审',
    ];

    public static $type2model = [
        'news' => 'News',
        'album' => 'Album',
        'video' => 'Videos',
        'special' => 'Specials',
        'multimedia' => 'Multimedia',
        'live' => 'Live',
        'news_collection' => 'news_collection',
        'album_collection' => 'album_collection',
        'video_collection' => 'VideoCollections',
        'activity' => 'Activity',
        'vote' => 'Vote',
        'lottery' => 'LotteryGroup',
    ];

    protected static $typeNames = [
        'news' => '文章',
        'album' => '相册',
        'video' => '视频',
        'special' => '专题',
        'live' => '直播',
        'news_collection' => '文集',
//        'album_collection'=>'相册集',
        'video_collection' => '视频专辑',
        'activity' => '活动',
        'vote' => '投票',
        'lottery' => '摇奖',
    ];

    /**
     * @param $channel_id
     * @param $id
     * @return mixed
     */
    public static function apiGetDataById($channel_id, $id) {
        $key = D::memKey('apiGetDataById', ['id' => $id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $news = self::query()
                ->andCondition('id', $id)
                ->andCondition('channel_id', $channel_id)
                ->andCondition('status', 1)
                ->first();
            if ($news) {
                $data = $news->toArray();
                if($data['type']=="multimedia") {
                    $data_data_ext = json_decode($data['data_data_ext']);
                    if(isset($data_data_ext->news)) {
                        $model = self::$type2model['news'];
                        $queue_news = self::query()
                            ->andCondition('id', $data_data_ext->news[0]->data_id)
                            ->andCondition('channel_id', $channel_id)
                            ->andCondition('status', 1)
                            ->first();
                        $source_id = $queue_news->source_id;
                    }
                }
                else {
                    $model = self::$type2model[$data['type']];
                    $source_id = $data['source_id'];
                }
                $extend = $model::findFirst($source_id);

                if ($extend) {
                    $data = array_merge($extend->toArray(), $data);
                }
                if ($data['type'] == 'video') {
                    $tempvideoarr = VideoFiles::apiGetFileByVideo($data['source_id'])->toArray();
                    foreach ($tempvideoarr as $f) {
                        $data['videodomain'] =  (false===stripos($f['path'], "video.xianghunet.com"))?"":"http://video.xianghunet.com/";
                        $f['path'] = str_ireplace('http://video.xianghunet.com/', "", $f['path']);
                        $f['path'] = str_ireplace('http://cloudvideo.cztv.com/', "", $f['path']);
                        $f['path'] = str_ireplace('http://v1.cztvcloud.com/', "", $f['path']);
                        $data['videos'][] = $f;
                    }
                }
                $data['quote'] = self::getDataData($data['data_data']);
                MemcacheIO::set($key, $data, 1800);
            }
        }
        return $data;
    }

    public static function getDataRedis($data_id, $channel_id) {
        $key = Data::data_list_key.":".$channel_id .":". $data_id;
        if(!RedisIO::exists($key)) {
            $r = Data::findFirstOrFail($data_id);
            $result = json_encode($r);
            RedisIO::set($key, $result, 86400);
        }
        else {
            $result = RedisIO::get($key);
        }
        $model = json_decode($result);
        return $model;
    }

    public static function apiFindBySourceId($channel_id, $source_id, $type) {
        $data = self::query()
            ->andCondition('source_id', $source_id)
            ->andCondition('channel_id', $channel_id)
            ->andCondition('type', $type)
            ->andCondition('status', 1)
            ->first();
        return $data;
    }

    /**
     * @param array $data
     * @param array $whiteList
     * @param int $source_type
     * @param int $source_id
     * @param int $data_data
     * @return bool
     */
    public function doSave($data, $whiteList, $source_type, $source_id, $data_data = null) {
        $data['hits'] = 0;
        $data['status'] = array_key_exists('status',$data)?$data['status']:1;
        $data['sort'] = 0;
        $data['weight'] = 0;
        $data['type'] = $source_type;
        $data['source_id'] = $source_id;
        if (is_null($data_data)) {
            $data_data = '[]';
        }
        $data['data_data'] = $data_data;
        return $this->saveGetId($data, $whiteList);
    }


    /*
     * @更新状态
     * */
    public static function updateStatus($id,$status){
        return (self::query()->first($id)->assign(['status'=>$status])->save())?true:false;
    }

    public function modifyStatus($id, $status) {
    	$data = self::query()
    	->andWhere("Data.id={$id}")
    	->first();
    	$data->status = $status;
    	return $data->save() ? true : false;
    }

    public function getSource() {
        return 'data';
    }


    /*
     *@desc 返回专题的sourceid
     *@return source_id
     * */
    public function getSpecialSourceId()
    {
        $special_id = 0;
        $data = json_decode($this->data_data,true);
        if(isset($data) && !empty($data)) {
	        foreach($data as $data_id){
	            if(intval($data_id)>0){
	                $model = self::query()->where("id = $data_id")->first();
	                if($model && $model->type == 'special'){
	                    $special_id = $model->source_id;
	                    break;
	                }
	            }
	        }
        }
        return $special_id;
    }





    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'type', 'source_id', 'title', 'sub_title', 'intro', 'thumb', 'data_template_id', 'created_at', 'updated_at', 'timelimit_begin', 'timelimit_end', 'author_id', 'author_name', 'hits', 'comments', 'data_data', 'data_data_ext', 'status', 'partition_by', 'referer_id', 'referer_author', 'redirect_url', 'referer_url',
                'secret_key','secret_url','thumb1','thumb2','thumb3','longitude','latitude',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'type', 'source_id', 'title', 'sub_title', 'intro', 'thumb', 'data_template_id', 'created_at', 'updated_at', 'timelimit_begin', 'timelimit_end', 'author_id', 'author_name', 'hits', 'comments', 'data_data', 'data_data_ext', 'status', 'referer_id', 'referer_url', 'redirect_url', 'referer_author',
                'secret_key','secret_url','thumb1','thumb2','thumb3','longitude','latitude',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'type', 'source_id', 'title', 'sub_title', 'intro', 'created_at', 'updated_at', 'author_id', 'author_name', 'hits', 'comments', 'data_data', 'status', 'partition_by', 'referer_id'],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'source_id' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'sub_title' => Column::TYPE_VARCHAR,
                'intro' => Column::TYPE_TEXT,
                'thumb' => Column::TYPE_VARCHAR,
                'data_template_id' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
                'updated_at' => Column::TYPE_INTEGER,
                'timelimit_begin' => Column::TYPE_INTEGER,
                'timelimit_end' => Column::TYPE_INTEGER,
                'author_id' => Column::TYPE_INTEGER,
                'author_name' => Column::TYPE_VARCHAR,
                'hits' => Column::TYPE_INTEGER,
                'comments' => Column::TYPE_INTEGER,
                'data_data' => Column::TYPE_TEXT,
                'data_data_ext' => Column::TYPE_TEXT,
                'status' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
                'referer_id' => Column::TYPE_INTEGER,
                'referer_url' => Column::TYPE_VARCHAR,
                'referer_auther' => Column::TYPE_VARCHAR,
                'redirect_url' => Column::TYPE_VARCHAR,
                'secret_key' => Column::TYPE_VARCHAR,
                'secret_url' => Column::TYPE_VARCHAR,
                'thumb1' => Column::TYPE_VARCHAR,
                'thumb2' => Column::TYPE_VARCHAR,
                'thumb3' => Column::TYPE_VARCHAR,
                'longitude'=> Column::TYPE_VARCHAR,
                'latitude'=> Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'type', 'source_id', 'data_template_id', 'created_at', 'updated_at', 'timelimit_begin', 'timelimit_end', 'author_id', 'hits', 'comments', 'status', 'partition_by', 'referer_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'source_id' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'sub_title' => Column::BIND_PARAM_STR,
                'intro' => Column::BIND_PARAM_STR,
                'thumb' => Column::BIND_PARAM_STR,
                'data_template_id' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
                'updated_at' => Column::BIND_PARAM_INT,
                'timelimit_begin' => Column::BIND_PARAM_INT,
                'timelimit_end' => Column::BIND_PARAM_INT,
                'author_id' => Column::BIND_PARAM_INT,
                'author_name' => Column::BIND_PARAM_STR,
                'hits' => Column::BIND_PARAM_INT,
                'comments' => Column::BIND_PARAM_INT,
                'data_data' => Column::BIND_PARAM_STR,
                'data_data_ext' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
                'referer_id' => Column::BIND_PARAM_INT,
                'referer_url' => Column::BIND_PARAM_STR,
                'referer_author' => Column::BIND_PARAM_STR,
                'redirect_url' => Column::BIND_PARAM_STR,
                'secret_key' => Column::BIND_PARAM_STR,
                'secret_url' => Column::BIND_PARAM_STR,
                'thumb1' => Column::BIND_PARAM_STR,
                'thumb2' => Column::BIND_PARAM_STR,
                'thumb3' => Column::BIND_PARAM_STR,
                'longitude' => Column::BIND_PARAM_STR,
                'latitude' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'type' => 'news',
                'sub_title' => '',
                'comments' => '0',
                'status' => '0',
                'referer_id' => '0',
                'data_template_id' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * @param $inputs
     * @param int $excluded_id
     * @return \Illuminate\Validation\Validator
     */
    public static function makeValidator($inputs, $excluded_id = 0) {
        Validator::extend('referer', function ($attribute, $value, $parameters) {
            return MediaValidator::refererId($attribute, $value, $parameters);
        });
        if ($inputs['referer_self']) {
            $inputs['referer_id'] = 0;
        }
        return Validator::make(
            $inputs,
            [
                'title' => 'required',
                'keywords' => 'max:255',
                'referer_url' => 'max:255|url',
                'secret_key' => 'max:20', //口令
                'secret_url' => 'max:255|url',
                'redirect_url'=>'url'
            ],
            [
                'title.required' => '请填写标题',
                'keywords.max' => '关键词不得多于255个字',
                'referer_url.max' => '来源地址不得多于255个字',
                'referer_url.url' => '来源地址必须是一个有效的HTTP网址',
                'secret_key.max' => '口令不得多于20个英文或中文',
                'secret_url.max' => '口令地址不得多于255个字',
                'redirect_url.url' => '外链地址必须是一个有效的HTTP网址'
            ]
        );
    }

    /**
     * @param $input
     * @return mixed
     */
    public static function makePublishValidator($input, $type) {
        $input['type'] = 1;
        if ($type == 'special') {
            if ($input['special_publish']) {
                $input['type'] = 0;
            }
        }
        return Validator::make($input, [
            'media_publish' => 'required_without:special_publish',
            'type' => 'accepted',
        ], [
                'media_publish.required_without' => '至少选择一个栏目或者一个专题栏目',
                'type.accepted' => '专题无法发布在专题栏目下',
            ]
        );
    }

    public static function getCommentTypes() {
        return self::$commentTypes;
    }

    /**
     * 安全插入字段
     * @return array
     */
    public static function getAllowed() {
        return [
            'type', 'channel_id', 'source_id', 'title', 'sub_title', 'intro', 'thumb', 'data_template_id', 'created_at', 'updated_at', 'sort', 'weight', 'author_id', 'author_name', 'hits', 'data_data', 'data_data_ext', 'status', 'partition_by','referer_id','referer_author','redirect_url','referer_url','timelimit_begin', 'timelimit_end',
            'secret_key','secret_url','thumb1','thumb2','thumb3','longitude','latitude',
        ];
    }

    /**
     * 安全更新的字段
     * @return array
     */
    public static function safeUpdateFields() {
        return ['title','type', 'source_id', 'intro', 'thumb', 'sub_title', 'referer_id', 'data_template_id', 'referer_url','created_at', 'referer_author', 'redirect_url', 'data_data', 'data_data_ext', 'updated_at','timelimit_begin', 'timelimit_end',
            'secret_key','secret_url','thumb1','thumb2','thumb3','longitude','latitude','status'];
    }

    public static function getDatasByIds($ids) {
        if (count($ids) == 0) return null;
        return Data::find(
            array(
                'id IN ({ids:array})',
                'bind' => array('ids' => $ids),
                'columns' => 'id, type, source_id, title, intro, thumb, data_template_id, created_at, updated_at, author_id, author_name, hits, status',
                'order' => 'created_at'
            )
        );
    }

    /**
     * 判断 ids 是否存在
     *
     * @param $channel_id
     * @param $ids
     * @return array
     */
    public static function queryByIds($channel_id, $ids) {
        if (count($ids)) {
            return Data::channelQuery($channel_id)
                ->inWhere('id', $ids)
                ->execute();
        } else {
            return [];
        }
    }

    /**
     * 通过媒资找Data
     *
     * @param $id
     * @param $type
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function getByMedia($id, $type) {
        return Data::query()
            ->andCondition('source_id', $id)
            ->andCondition('type', $type)
            ->first();
    }

    /**
     * 通过媒资找终端,数据做了memcache缓存
     *
     * @param $id
     * @return array
     */
    public static function getTerminal($id) {
        $key = "data_terminal:" . $id;
        $data = MemcacheIO::get($key);
        if (!$data) {
            $data = CategoryData::query()->andCondition('data_id', $id)->execute()->toArray();
            MemcacheIO::set($key, $data, 1800);
        }
        $category = Category::listCategory("", true);
        $terminal = [];
        if (!empty($data)) {
            foreach ($data as $v) {
                $terminal[] = [$v['category_id'], $category[$v['category_id']]];
            }
        }
        return $terminal;
    }

    /**
     * 根据媒资ID，查询完整数据
     */
    public static function getMediaByData($id) {
        $channelId = Session::get('user')->channel_id;
        $data = self::channelQuery($channelId)->andCondition('id', $id)->first();
        if (!$data) {
            return false;
        }
        $type = $data->type;
        if($type=='multimedia') {
            $obj = "News";
            $data_data_ext = json_decode($data->data_data_ext);
            if(isset($data_data_ext->news)) {
                $queue_news = Data::getReadDataByType($data_data_ext->news[0]->data_id, 'news', $channelId);
                $source_id = $queue_news['source_id'];
            }
        }
        else {
            $source_id = $data->source_id;
            $obj = self::$type2model[$type];
        }
        $source = $obj::query()->andCondition('id', $source_id)->first();
        return [$data, $source];
    }

    public static function getMediaByDataId($id) {
        $data = self::query()->andCondition('id', $id)->first();
        if (!$data) {
            return false;
        }
        $type = $data->type;
        $obj = self::$type2model[$type];
        $source = $obj::query()->andCondition('id', $data->source_id)->first();
        return [$data, $source];
    }

    public static function searchMedia($data, $channel_id) {
        $data_id = $data['id'] ?: '';
        $column_id = $data['column_id'] ?: '';
        $title = $data['title'] ?: '';
        $type = $data['type'];
        $query = Data::query();
        if ($column_id) {
            $query = Data::query()->columns(array('Data.*'))
                ->leftjoin("PrivateCategoryData", "Data.id=PrivateCategoryData.data_id")
                ->andWhere("PrivateCategoryData.category_id='{$column_id}'");
        } else {
            $query = Data::query();
        }
        $query = $query->andWhere("Data.channel_id='{$channel_id}'");
        if ($data_id) {
            $query = $query->andWhere("Data.id='{$data_id}'");
        }
        if ($title) {
            $query = $query->andWhere("Data.title like '%$title%'");
        }
        return $query->orderBy('id Desc')->andCondition("type", $type)->paginate(50, 'Pagination');
    }

    public static function tplList($channel_id, $order, $type, $page, $size = 20) {
        $page = (int)$page;
        $size = (int)$size;
        if ($page < 1 || $size < 1) {
            return [];
        }
        switch ($order) {
            case 'new':
                $orderBy = 'id DESC';
                break;
            case 'hot':
                $orderBy = 'hits DESC';
                break;
            default:
                return [];
        }
        return self::channelQuery($channel_id)
            ->andCondition('status', 1)
            ->andCondition('type', $type)
            ->orderBy($orderBy)
            ->paginate($size, 'SmartyPagination', $page);
    }

    public static function getReadQuery($data_id, $channel_id = null) {
        if ($channel_id) {
            $query = self::channelQuery($channel_id, true);
        } else {
            $query = self::query();
        }
        return $query->andCondition('status', 1)
            ->andWhere('Data.id = :id:', ['id' => $data_id]);
    }

    public static function getReadDataByType($data_id, $type, $channel_id = null) {
        $r = Data::getReadQuery($data_id, $channel_id)
            ->andCondition('type', $type)
            ->first();
        if ($r) {
            $r = $r->toArray();
        }
        return $r;
    }

    public static function getDataData($data_data) {
        $data_data = json_decode($data_data, true);
        $rs = [];
        if (!empty($data_data)) {
            foreach ($data_data as $dd) {
                $r = null;
                $d = self::getReadQuery((int)$dd)->first();

                if ($d) {
                    if($d->type=='news') continue;
                    $d = $d->toArray();
                    switch ($d['type']) {
                        case 'video':
                            $r = Videos::getWithFiles($d['source_id']);
                            break;
                        case 'album':
                            $r = Album::getWithImages($d['source_id']);
                            break;
                        case 'video_collection':
                            $r = VideoCollections::getWithVideos($d['source_id']);
                            break;
                        case 'signals':
                            $r = json_decode(Signals::getTVJsonByRedis($d['source_id']));
                            break;
                        case 'vote':
                            $vote = Vote::findVoteById($d['source_id']);
                            if($vote){
                                $r = $vote->toArray();
                            }else{
                                $r = array();
                            }
                            break;
                        default:
                            $r = array();
                    }
                    if ($r) {
                        $r['type'] = $d['type'];
                        $r['intro'] = $d['intro'];
                    }
                }
                $rs[$dd] = $r;
            }
        }
        return $rs;
    }

    /**
     * 返回一个data中绑定的指定类型的data的id
     * @param int $data_id
     * @param string $type
     * @return array
     */
    public static function getDataDataIdByType($data_data, $type = '') {
        $data_data = json_decode($data_data, true);
        $rs = [];
        if (!empty($data_data)) {
            foreach ($data_data as $dd) {
                $d = self::getReadQuery((int)$dd)->first();
                if ($d) {
                    $d = $d->toArray();
                    if ($d['type'] == $type) {
                        $rs[] = $d['id'];
                    }

                }
            }
        }
        return $rs;
    }

    public static function getById($data_id, $channel_id) {
        return self::channelQuery($channel_id)
            ->andCondition('id', $data_id)
            ->first();
    }




    public static function getBySourceId($source_id,$channel_id,$type){
        return self::channelQuery($channel_id)
            ->andCondition('source_id', $source_id)
            ->andCondition('type',$type)
            ->first();
    }


    public static function search($data, $channel_id) {
        $keyword = $data['keyword'];
        $author_name = $data['author_name'] ?: '';
        $created_at_from = $data['created_at_from'] ?: '';
        $created_at_to = $data['created_at_to'] ?: '';
        $source_id = $data['source_id'] ?: '';
        $type = $data['type'];
        $status = $data['status'];
        $query = Data::query()->andWhere("Data.channel_id='{$channel_id}'");
        if ($source_id) {
            $query = $query->andWhere("Data.referer_id='{$source_id}'");
        }
        if ($keyword) {
            $query = $query->andWhere("Data.title like '%$keyword%'");
        }
        if ($author_name) {
            $query = $query->andWhere("Data.author_name= '{$author_name}' ");
        }
        if ($created_at_from) {
            $query = $query->andWhere("Data.updated_at> '{$created_at_from}' ");
        }
        if ($created_at_to) {
            $query = $query->andWhere("Data.updated_at< '{$created_at_to}'");
        }
        if ($status != '') {
            $query = $query->andWhere("Data.status =  '{$status}'");
        }
        return $query->andCondition('type', $type)->paginate(50, 'Pagination');
    }

    private function __getDataDetailMemCacheKey($xid, $pid, $type, $sort = array(), $ctype = 0) {
        global $config;
        $_cacheConf = $this->__loadCacheConf();
        $_cmtTypeMath = $config['_cmtTypeMath'];

        $cacheConfIndex = 'data_list';

        return sprintf($_cacheConf['memCacheKeys'][$cacheConfIndex], $type, 'xid', $xid);
    }

    /**
     * 编辑的时候将值赋到具体媒资上, 因为 join 的结果无法直接调用 update 方法
     *
     * @param $media
     */
    public function assignToMedia(&$media) {
        $media->title = $this->title;
        $media->intro = $this->intro;
        $media->thumb = $this->thumb;
        $media->data_template_id = $this->data_template_id;
        $media->sub_title = $this->sub_title;
        $media->referer_id = $this->referer_id;
        $media->referer_url = $this->referer_url;
        $media->referer_author = $this->referer_author;
        $reff_name = $this->referer_id > 0 ? Referer::findOne($this->referer_id)->name : "";
        $media->referer_name = $reff_name;
        $media->redirect_url = $this->redirect_url;
        $media->private_category_id = PrivateCategoryData::getCategoryId($this->id);
    }

    public static function getTypeNames() {
        return self::$typeNames;
    }

    public function doSaveAc($data, $whiteList, $source_type, $source_id, $data_data = null) {
        $data['hits'] = 0;
        $data['status'] = 1;
        $data['sort'] = 0;
        $data['weight'] = 0;
        $data['type'] = $source_type;
        $data['source_id'] = $source_id;
        if (is_null($data_data)) {
            $data_data = '[]';
        }
        $data['data_data'] = $data_data;
        return $this->saveGetId($data, $whiteList);
    }
    
    /**
    * 根据data_id获取相关值
    * @param unknown $channelId  对应channel_id字段值
    * @param unknown $dataId	对应data_id字段值
    */
    public static function getByDataId($channelId,$dataId) {
    	$query = Data::query();
    	$query = $query->andWhere("Data.channel_id='{$channelId}'");
    	if ($dataId) {
    		$query = $query->andWhere("Data.id='{$dataId}'");
    	}
    	return $query->first();
    }

    /**
     * 根据$channelId $secretKey获取对应媒资的url
     * @param unknown $channelId
     * @param unknown $secretKey 注意这个key要先md5
     * @return string $url
     */
    public static function getSecretUrlAndStatus($channelId, $secretKey) {
        $key = D::redisKey("secret_key",md5($channelId.":".$secretKey));
        $info = RedisIO::get($key);
        if ( !$info ) {
           return ["url"=>"","status"=>0];
        }
        return json_decode($info,true);
    }

    /**
     * @function 检查口令是否重复 返回true 不重复 false重复
     * @author 汤荷
     * @version 1.0
     * @param $channelId
     * @param $newKey 新口令
     * @param $oldKey 旧口令
     * @return bool
     */
    public static function checkSecretKey($channelId, $newKey, $oldKey="") {
        if ( $newKey == ""  || $newKey == $oldKey ){
            return true;
        }else {
            $secretInfo = self::getSecretUrlAndStatus($channelId,md5($newKey));
            if(  $secretInfo["url"] == "" ){
                return true;
            }else{
                return false;
            }
        }
    }

    /**
     * @function 设置口令
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $channelId
     * @param $dataId
     * @param $secretKey
     * @param $status //媒资状态
     */
    public static function setRedisSecretKey($channelId, $dataId, $secretKey, $status=1){
        if( $secretKey != "" ){
            $config = F::getConfig('domain_config');
            $url = $config["interaction"]."secret/".$dataId."?channel_id=".$channelId;

            $secretKey = md5($secretKey);
            $key = D::redisKey("secret_key",md5($channelId.":".$secretKey));
            RedisIO::set($key, json_encode(["url"=>$url, "status"=>$status]) );
        }
    }
    //删除口令
    public static function deleteRedisSecretKey($channelId, $secretKey){
        $secretKey = md5($secretKey);
        $key = D::redisKey("secret_key",md5($channelId.":".$secretKey));
        if(Redisio::exists($key))
            RedisIO::del($key);
    }

    /**
     * @function 设置输入口令url
     * @author 汤荷
     * @version 1.0
     * @param $dataId
     * @param $secretUrl
     */
    public static function setRedisSecretInputUrl($dataId, $secretUrl){
        $key = D::redisKey("secret_url","id:".$dataId);
        RedisIO::set($key,$secretUrl);
    }
    //获取输入口令url
    public static function getRedisSecretInputUrl($dataId){
        $key = D::redisKey("secret_url","id:".$dataId);
        $url = RedisIO::get($key);
        if (!$url){
            $url ="";
        }
        return $url;
    }
    //删除输入口令url
    public static function deleteRedisSecretInputUrl($dataId){
        $key = D::redisKey("secret_url","id:".$dataId);
        if(RedisIO::exists($key))
            RedisIO::del($key);
    }

    public static function deleteRedisContent($channel_id,$data_id,$type)
    {
        if( !empty($channel_id) &&
            !empty($data_id) &&
            !empty($type) &&
            RedisIO::exists("SmartyData:{$channel_id}:{$type}:{$data_id}")){
            $key = "SmartyData:{$channel_id}:{$type}:{$data_id}";
            RedisIO::del($key);
        }
    }
    
    
    public static function compareTime(&$getData) {
    	if($getData['limittime_choose']== 1) {
    		// 永久
    		$getData['live_status'] = Signals::LIVE_STATUS_PLAYING;
    		$getData['timelimit_begin'] = 0;
    		$getData['timelimit_end'] = 0;
    		return true;
    	} else if($getData['limittime_choose']== 0) {
    		//时效设置
    		$start_time = $getData['timelimit_begin'] !='' ? strtotime($getData['timelimit_begin']) : 0;
    		$end_time = $getData['timelimit_end'] != '' ? strtotime($getData['timelimit_end']) : 0 ;
    		
    		
    		if($start_time == 0 ||($end_time != 0 && $start_time > $end_time)) {
    			return false;
    		}
    		if($start_time > 0 && $end_time > 0) {
    			if (time() < $start_time) {
    				// 未开始
    				$getData['live_status'] = Signals::LIVE_STATUS_NOTSTART;
    			}
    			else {
    				// 播放中
    				$getData['live_status'] = Signals::LIVE_STATUS_PLAYING;
    			}
    			if($end_time > 0&&time() > $end_time) {
    				// 已结束
    				$getData['live_status'] = Signals::LIVE_STATUS_FINISH;
    			}
    		}else {
    			$end_time = 0;
    		}
    		$getData['timelimit_begin'] = $start_time;
    		$getData['timelimit_end'] = $end_time;
    		
    		return true;
    	}
    }

    private static function setRedisDataInfo($data_id)
    {
        $key = "DATA:DATAID:{$data_id}";
        $metaData = new Phalcon\Mvc\Model\MetaData\Memory();
        $attributes = $metaData->getAttributes(new self());
        $data = self::findFirst($data_id);
        if($data)
        {
            $row = $data->toarray();
            foreach($attributes as $attribute)
            {
                RedisIO::hset($key,$attribute,$row[$attribute]);
            }
            RedisIO::hset($key,'special_id',$data->getSpecialSourceId());
        }
        return $row;
    }


    public function afterSave(){
        $key = "DATA:DATAID:{$this->id}";
        $metaData = new Phalcon\Mvc\Model\MetaData\Memory();
        $attributes = $metaData->getAttributes(new self());
        $row = $this->toarray();
        foreach($attributes as $attribute)
        {
            RedisIO::hset($key,$attribute,$row[$attribute]);
            RedisIO::expire($key,parent::MAX_REDIS_TTL_DAY);
        }
        //RedisIO::hset($key,'special_id',$this->getSpecialSourceId());
        RedisIO::expire($key,parent::MAX_REDIS_TTL_DAY);
        if($this->type == 'news')
        {
            RedisIO::hset("NEWS:DATA:NEWSID:{$this->source_id}","data_id",$this->id);
            RedisIO::expire("NEWS:DATA:NEWSID:{$this->source_id}",parent::MAX_REDIS_TTL_DAY);
        }
        //Jason
        $channel_id = Session::get('user')->channel_id;
        if(!isset($channel_id)&&isset($_GET['channel_id'])){
            $channel_id = intval($_GET['channel_id']);
        }
        $last_modified_key = "media/" . $this->type . ":" . $this->id;
        F::_clearCache($last_modified_key, $channel_id);

    }


    public function unitTestAction()
    {
        CategoryData::getRedistList();
    }

    public function unitTest2Action()
    {

    }



    /** 图片缩放
     * @function scaleImg
     * @author 汤荷
     * @version 1.0
     * @date 2016-12-07
     * @param $imgData 图片原始数据
     * @param $imgType 图片类型，如png,jpeg
     * @param $targetWidth
     * @param $targetHeight
     * @return string
     */
    public static function scaleImg($imgData, $imgType, $targetWidth, $targetHeight){
        $srcImg=@imagecreatefromstring ($imgData);
        if ($srcImg == false){
            return "";
        }
        $srcWidth=imagesx($srcImg);
        $srcHeight=imagesy($srcImg);
        $newImg = imagecreatetruecolor($targetWidth, $targetHeight);
        imagecopyresampled($newImg, $srcImg, 0, 0, 0, 0, $targetWidth, $targetHeight, $srcWidth, $srcHeight);

        ob_start();
        switch ($imgType){
            case "jpeg":
                imagejpeg($newImg);
                break;
            case "png":
                imagepng($newImg);
                break;
        }
        $imageStr = ob_get_contents();
        ob_end_flush();
        imagedestroy($newImg);
        ob_clean();
        return $imageStr;
    }

    public static function createData($data, $type) {
        $model = new Data();
        $data['type'] = $type;
        $func = 'create'.ucfirst($type);
        if(!isset($data['data_data'])) $data['data_data'] = "[]";
        $data['source_id'] = Data::$func($data);
        $data['data_template_id'] = 0;
        $data['hits'] = 0;
        $data['partition_by'] = date("Y", $data['created_at']);
        return $model->saveGetId($data);
    }
    
    public static function createSignals($data) {

    	Signals::readyThumb($data['input_file2']);
    	Signals::readyThumb($data['input_file3']);
    	Signals::readyThumb($data['input_file4']);
    	Signals::readyThumb($data['input_file5']);
    	$data['status'] = Signals::LIVE_DATA_STATUS_REVIEWED;
    	$signal = new Signals();
    	$signal_id = $signal -> createLivesData($data);

    	$traditionTV = json_decode($data['json']);
    	if(!$signal->saveSignalsTV($traditionTV, $signal_id)) {
            $signal->throwDbE('Signal save fail');
    	}
    	return $signal_id;
    }

    public static function createMultimedia($data) {
        $model = new Multimedia();
        return $model->saveGetId([
            'channel_id' => $data['channel_id'],
            'partition_by' => date('Y', $data['created_at']),
        ]);
    }

    public static function createNews($data) {

        $model = new News();
        return $model->saveGetId([
            'channel_id' => $data['channel_id'],
            'keywords' => "",
            'content' => $data['content'] != null? $data['content'] : '',
            'created_at' => $data['created_at'],
            'updated_at' => $data['updated_at'],
            'partition_by' => date('Y', $data['created_at']),
            'comment_type' => isset($data["comment_type"]) ? $data["comment_type"] : 1
        ]);
    }



    public static function createSpecial($data) {
        $model = new Specials();
        $model->thumb = $data['thumb'];
        $model->banner = '';
        return $model->saveGetId($data);
    }


    /**
     * 修改状态
     */
    public function changeLiveStatus($id, $status, $channel_id) {
        $dataStatus =$status;
        $dataId = $id;
        $type = Request::getPost('type','string');
        if($dataStatus < 0){
            exit;
        }
        $res_status = "";

        DB::begin();
        $bRes = false;
        $channelId = $channel_id;
        $signals = new Signals();
        $data = new Data();
        switch ($dataStatus) {
            case Signals::LIVE_DATA_STATUS_NO_REVIEWED:
                $bRes = $data->modifyStatus($dataId, $dataStatus);
                if($type == 'live') {
                    $bRes = $signals->updateLiveStatus($dataId, Signals::LIVE_STATUS_NOTSTART, $channelId);
                    $res_status = Signals::LIVE_DATA_STATUS_NO_REVIEWED_VALUES;
                }
                break;
            case Signals::LIVE_DATA_STATUS_FORBIDDEN:
                $bRes = $data->modifyStatus($dataId, $dataStatus);
                if($type == 'live') {
                    $res_status = Signals::LIVE_DATA_STATUS_FORBIDDEN_VALUES;
                    $bRes = $signals->updateLiveStatus($dataId, Signals::LIVE_STATUS_FORBIDDEN, $channelId);
                }
                break;
            case Signals::LIVE_DATA_STATUS_REVIEWED:
                $dataV = Data::getByDataId($channelId, $dataId);
                if($dataV->status != Signals::LIVE_DATA_STATUS_REVIEWED || $dataV->status == Signals::LIVE_DATA_STATUS_FORBIDDEN) {
                    $bRes = $data->modifyStatus($dataId, $dataStatus);
                    if($type == 'live') {
                        $signalsStatus = Signals::LIVE_STATUS_NOTSTART;
                        $res_status = Signals::LIVE_STATUS_NOTSTART_VALUES;
                        Signals::checkTimeLimit($dataId, $channelId, $signalsStatus, $res_status);
                        $bRes = $signals->updateLiveStatus($dataId, $signalsStatus, $channelId);
                        Signals::refreshCDN($dataId, $channelId);
                    }
                }else {
                    $res_status = "";
                }
                break;
            default:;
        }

        if ($bRes) {
            DB::commit();
            echo json_encode($res_status);
            exit;
        } else {
            DB::rollback();
            exit;
        }
    }

    public static function getVideoUrl($dataId,$type="video") {
        $url = "";
        if($type == "video"){
            $data=self::query()
                ->columns("VideoFiles.path")
                ->andWhere("Data.id = $dataId")
                ->leftJoin("VideoFiles","VideoFiles.video_id = Data.source_id ")
                ->orderBy("VideoFiles.rate DESC")
                ->limit(1)
                ->execute()
                ->toArray();
            if(count($data)){
                $path = reset($data)["path"];
                $url= cdn_url("video",$path);
            }
        }elseif ($type == "signals"){
            $data=self::query()
                ->columns("SignalPlayurl.play_url")
                ->andWhere("Data.id = $dataId")
                ->leftJoin("SignalEpg","SignalEpg.id = Data.source_id ")
                ->leftJoin("SignalPlayurl","SignalEpg.lives_id = SignalPlayurl.epg_id")
                ->limit(1)
                ->execute()
                ->toArray();
            if(count($data)){
                $path = reset($data)["play_url"];
                $url= $path;
            }
        }
        return $url;
    }

    public static function getLatestDataInCategory($author_id,$category_id) {
        $data = self::query()
            ->columns(array('Data.*'))
            ->andWhere("Data.author_id = {$author_id} ")
            ->andWhere("CategoryData.id = {$category_id}")
            ->leftJoin("CategoryData", "CategoryData.data_id=Data.id")
            ->orderBy("Data.updated_at DESC")
            ->limit(1)
            ->execute()
            ->toArray();
        return $data;
    }

    /**
     * 删除复合媒资，新闻媒资，回收站媒资
     * @param $id
     * @return bool
     */
    public static function delMultimeAndNewsData($id) {
        $channel_id = Auth::user()->channel_id;

        $rs = true;
        $newsData = self::findDataById($id, "news");
        if(isset($newsData) && !empty($newsData)) {
            DB::begin();
            foreach ($newsData as $news) {
                $data_id = $news['id'];
                $rs = Data::findFirst($data_id)->delete();   // 删除引用媒资
                if (!$rs) break;
            }
            if ($rs) {
                $rs = Data::findFirst($id)->delete();       // 删除复合媒资
                if (!$rs) {
                    DB::rollback();
                    return $rs;
                }
                $rs = Recycle::delRecycleData($id);         // 删除回收站
                $rs ? DB::commit() : DB::rollback();
            } else {
                DB::rollback();
            }
        }
        else {
            DB::begin();
            $rs = Data::findFirst($id)->delete();       // 删除复合媒资
            if (!$rs) {
                DB::rollback();
                return $rs;
            }
            $rs = Recycle::delRecycleData($id);         // 删除回收站
            $rs ? DB::commit() : DB::rollback();
        }
        return $rs;
    }

    public static function findDataById($id, $type) {
        $multimeData = self::query()
            ->andCondition('id', $id)
            ->first();
        $newsData = array();
        if(!empty($multimeData)) {
            $ids = $multimeData->data_data;

            $ids = str_replace(array("[", "]"), "", $ids);
            if ($ids != "") {
                $newsData = self::query()
                    ->andWhere("Data.id in({$ids})")
                    ->andWhere("Data.type = '{$type}'")
                    ->execute()
                    ->toArray();
            }
        }
        return $newsData;
    }


    /**获取媒资的播放url
     * @function getSignalPlayUrl
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $data_id
     * @return array
     */
    public static function getSignalPlayUrl($data_id) {

        $signalId = null;
        $model = self::getReadQuery($data_id)->execute()->getFirst();
        if($model->type == "signals"){
            $signalId = $model->id;
        }else if($model->type = "multimedia"){
            $data_data_ext = json_decode($model->data_data_ext,true);
            if($data_data_ext && isset($data_data_ext["signal"])){
                if (count($data_data_ext["signal"]) == 1){
                    $signalId = reset($data_data_ext["signal"])["data_id"];
                }else{
                    $signalId = $data_data_ext["signal"];
                }

            }
        }
        if (!$signalId){
            return [];
        }
        $query = self::query()
            ->columns(["SignalPlayurl.play_url"])
            ->leftJoin("SignalEpg","SignalEpg.lives_id = Data.source_id")
            ->leftJoin("SignalPlayurl","SignalPlayurl.epg_id = SignalEpg.id");
        if(is_array($signalId)){
            $ids = [];
            foreach ($signalId as $value){
                $ids[] = $value["data_id"];
            }
            $query= $query->inWhere("Data.id",$ids)
                ->execute()
                ->toArray();
        }else{
            $query= $query->where("Data.id = {$signalId}")
                ->execute()
                ->toArray();
        }

        return $query;
    }

    public static function getAlbumImageFiles($data_id) {
        $albumId = null;
        $model = self::getReadQuery($data_id)->execute()->getFirst();
        if($model->type == "album"){
            $albumId = $model->id;
        }else if($model->type = "multimedia"){
            $data_data_ext = json_decode($model->data_data_ext,true);
            if($data_data_ext && isset($data_data_ext["album"])){
                if (count($data_data_ext["album"]) == 1){
                    $albumId = reset($data_data_ext["album"])["data_id"];
                }else{
                    $albumId = $data_data_ext["album"];
                }

            }
        }
        if (!$albumId){
            return [];
        }
        //
        $query = self::query()
            ->columns(["AlbumImage.*"])
            ->leftJoin("AlbumImage","AlbumImage.album_id = Data.source_id");
        if(is_array($albumId)){
            $ids = [];
            foreach ($albumId as $value){
                $ids[] = $value["data_id"];
            }
            $query= $query->inWhere("Data.id",$ids)
                ->execute()
                ->toArray();
        }else{
            $query= $query->where("Data.id = {$albumId}")
                ->execute()
                ->toArray();
        }
        foreach ($query as & $path){
            $path["path"] = cdn_url("image",$path["path"]);
        }
        return $query;
    }

}
