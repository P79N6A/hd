<?php

class SmartyData {

    protected static $channelId = 0;
    protected static $domainId = 0;
    protected static $templates = [];

    const EXPIRES = 60;
    
    const CACHE_TIME = 86400;			// 缓存时间 30天
    
    public static function init($channel_id, $domain_id) {
        self::$channelId = $channel_id;
        self::$domainId = $domain_id;
    }

    public static function initTemplates($templates) {
        self::$templates = $templates;
    }

    /**
     * 获取列表
     *
     * @param string $order
     * @param string $type
     * @param int $page
     * @param int $size
     */
    public static function getDataList($order, $type, $page = 1, $size = 20) {
        return self::parcelToArray(Data::tplList(self::$channelId, $order, $type, $page, $size));
    }

    /**
     * 获取媒资类型值
     *
     * @param $type
     * @return int
     */
    public static function getMediaTypeValue($type) {
        return Templates::getMediaTypeValue($type);
    }

    /**
     * 生成模板系统路径
     *
     * @param array $params
     * @param $type
     * @param string $group
     * @return string
     */
    public static function url(array $params, $type, $group = '') {

        if ($type == Templates::TPL_INDEX) {
            return '/';
        }

        if (empty($params) || in_array($type, [Templates::TPL_STATIC, Templates::TPL_LAYOUT, Templates::TPL_PAGE])) {
            return '';
        }
		
		

        list($r, $ids) = TemplateFriends::tplUrlByParams($params);
        /**
         * FIXME 非 custom, static, layout 类的模板应该全局共享
         * 2015-12-08 共用是越发的重要了
         */
        $url = '';
        if ($r) {
            $url = $r->url;
        } else {
            $templates = self::$templates;
            if ($group !== '' && isset($templates['groups'][$group])) {
                $templates = $templates['groups'][$group];
            } elseif (isset($templates['main'])) {
                $templates = $templates['main'];
            }
            if (!empty($templates)) {
                $get_url_by_type = function ($type) use ($templates, $ids) {
                    $url = '';
                    if (isset($templates[$type])) {
                        $r = $templates[$type];
                        $url = $r['url_rules'];
                        foreach ($ids as $key => $id) {
                            if ($id) {
                                $url = str_replace('{' . $key . '}', $id, $url);
                            }
                        }
                    }
                    return $url;
                };
                $url = $get_url_by_type($type);
                //分类可以通过默认方式读取
                if (!$url) {
                    $ranges = Templates::getTypeRanges();
                    $default_type = 0;
                    foreach (['category', 'region_category'] as $key) {
                        $type_ranges = range($ranges[$key][0], $ranges[$key][1]);
                        if ($type != $type_ranges[0] && in_array($type, $type_ranges)) {
                            $default_type = $type_ranges[0];
                            break;
                        }
                    }
                    if ($default_type) {
                        $url = $get_url_by_type($default_type);
                    }
                }
            }
        }

        return $url;
    }

    /**
     * 同过替换实现新闻内容读取
     *
     * @param $content
     * @param $resources
     * @return string
     */
    public static function getNewsContent($content, $resources) {
        $pattern = '!(<input class="quote-item" data-id="(\d+)" data-type="\w+" disabled=""/>)!';
        preg_match_all($pattern, $content, $matches);
        list(, $searches, $ids) = $matches;
        if(!count($ids)) {
            $pattern = '!(<input disabled="" class="quote-item" data-type="\w+" data-id="(\d+)"/>)!';
            preg_match_all($pattern, $content, $matches);
            list(, $searches, $ids) = $matches;
        }
        if(!count($ids)) {
            $pattern = '!(<input class="quote-item" value="(.+)" data-id="(\d+)" data-type="\w+" disabled=""/>)!';
            preg_match_all($pattern, $content, $matches);
            list(, $searches,, $ids) = $matches;
        }
        $html = $content;
        //替换资源
        foreach ($resources as $id => $str) {
            if (!is_string($str)) {
                continue;
            }
            $idx = array_search($id, $ids);
            if ($idx !== false) {
                $search = $searches[$idx];
                $html = str_replace($search, $str, $html);
            }
        }
        //删除无用引入
        $html = str_replace($searches, '', $html);
        return $html;
    }

    public static function assignData(&$r, &$model) {
        $r['data_id'] = $model['id'];
        foreach (['type', 'title','sub_title', 'thumb', 'intro', 'comments', 'hits', 'referer_id', 'referer_url', 'referer_author', 'redirect_url', 'author_name','timelimit_begin', 'timelimit_end'] as $k) {
            $r[$k] = $model[$k];
        }
        $r['referer'] = '';
        if (in_array($model['type'], ['news', 'album','multimedia']) && (int)$model['referer_id']) {
            $ref = Referer::tplById(self::$channelId, $model['referer_id']);
            if ($ref) {
                $r['referer'] = $ref->name;
            }
        }
    }

    public static function getNewid($old_id) {
        $sync = new Sync();
        $result = $sync->query()->andCondition('old_id', $old_id)->andCondition('channel_id', self::$channelId)->andCondition('old_table', "cms_news")->first();
        if($result) {
            return $result->new_id;
        }
        else {
            return 0;
        }
    }


    /**
     * 获取新闻
     *
     * @param $data_id
     * @param $terminal
     * @param string $group
     * @return array|\Phalcon\Mvc\Model
     */
    public static function getNews($data_id, $terminal, $group = '') {

    	$type = "news";
    	$key = "DataCache:".self::$channelId.":".$data_id;
    	$newsData = RedisIO::get($key);
    	if (!$newsData) {
	        $model = Data::getReadDataByType($data_id, 'news', self::$channelId);

            if(!$model) {
                $model = Data::getReadDataByType($data_id, 'multimedia', self::$channelId);
                $data_data_ext = json_decode($model['data_data_ext']);
                if(isset($data_data_ext->news)) {
                    $queue_news = Data::getReadDataByType($data_data_ext->news[0]->data_id, 'news', self::$channelId);
                    $source_id = $queue_news['source_id'];
                }
            }
            else {
                $source_id = $model['source_id'];
            }
	        if (!$model) {
	            return [];
	        }
	        $r = News::findFirst($source_id);
	        if (!$r) {
	            return [];
	        }
	        $r = $r->toArray();
	        self::assignData($r, $model);
	        $r['data_data'] = Data::getDataData($model['data_data']);
	        $r['breadcrumb'] = Category::tplBreadcrumbs($data_id, $terminal, Templates::TPL_CATEGORY, $group);
	        if(array_key_exists("data_id", $r)) {
				$hits = RedisIO::get("hits:" . $r['data_id']);
                $baseHitsCounts = RedisIO::get("baseHitsCounts:" . $r['data_id']);
				$r['hits'] = $hits?:0;
                $r['hits_fake'] =(($baseHitsCounts)?intval($baseHitsCounts):0)+(($hits)?intval($hits):0);
				$r['likes'] = RedisIO::get("meiZiLikes:" . $r['data_id']);
				$r['likes_fake'] = RedisIO::get("meiZiLikes:" . $r['data_id']) + RedisIO::get("baseLikesCounts:" . $r['data_id']);
	        }
	        RedisIO::set($key, json_encode($r), self::CACHE_TIME);
	        return $r;
    	} 
       	return json_decode($newsData, true);
       	
    }

    /**
     * 获取相册
     *
     * @param $data_id
     * @param $terminal
     * @return array|bool
     */
    public static function getAlbum($data_id, $terminal, $group = '') {
    	$type = "album";
    	$key = "DataCache:".self::$channelId.":".$data_id;
    	$albumData = RedisIO::get($key);
    	if (!$albumData) {
	        $model = Data::getReadDataByType($data_id, 'album', self::$channelId);
	        if (!$model) {
	            return [];
	        }
	        $r = Album::getWithImages($model['source_id']);
	        self::assignData($r, $model);
	        $r['breadcrumb'] = Category::tplBreadcrumbs($data_id, $terminal, Templates::TPL_CATEGORY_ALBUM, $group);
	        if(array_key_exists("data_id", $r)) {
				$hits = RedisIO::get("hits:" . $r['data_id']);
                $baseHitsCounts = RedisIO::get("baseHitsCounts:" . $r['data_id']);
				$r['hits'] = $hits?:0;
                $r['hits_fake'] =(($baseHitsCounts)?intval($baseHitsCounts):0)+(($hits)?intval($hits):0);
				$r['likes'] = RedisIO::get("meiZiLikes:" . $r['data_id']);
				$r['likes_fake'] = RedisIO::get("meiZiLikes:" . $r['data_id']) + RedisIO::get("baseLikesCounts:" . $r['data_id']);
	        }
	        RedisIO::set($key, json_encode($r), self::CACHE_TIME);
	        return $r;
    	}
    	return json_decode($albumData, true);
    }

    /**
     * 获取视频
     *
     * @param $data_id
     * @param $terminal
     * @param string $group
     * @return array|bool
     */
    public static function getVideo($data_id, $terminal, $group = '') {
    	$type = "video";
    	$key = "DataCache:".self::$channelId.":".$data_id;
    	$videoData = RedisIO::get($key);
    	if (!$videoData) {
	        $model = Data::getReadDataByType($data_id, 'video', self::$channelId);
	        if (!$model) {
	            return [];
	        }
	        $r = Videos::getWithFiles($model['source_id']);
	        self::assignData($r, $model);
	        $r['breadcrumb'] = Category::tplBreadcrumbs($data_id, $terminal, Templates::TPL_CATEGORY_VIDEO, $group);
	        $r['collection'] = VideoCollections::getWithData($r['collection_id']);
	        if(array_key_exists("data_id", $r)) {
				$hits = RedisIO::get("hits:" . $r['data_id']);
                $baseHitsCounts = RedisIO::get("baseHitsCounts:" . $r['data_id']);
				$r['hits'] = $hits?:0;
                $r['hits_fake'] =(($baseHitsCounts)?intval($baseHitsCounts):0)+(($hits)?intval($hits):0);
				$r['likes'] = RedisIO::get("meiZiLikes:" . $r['data_id']);
				$r['likes_fake'] = RedisIO::get("meiZiLikes:" . $r['data_id']) + RedisIO::get("baseLikesCounts:" . $r['data_id']);
				/*
                $param_values = DataExt::getExtValues($r['data_id']);
                $r = array_merge($r, $param_values);
                    $government = self::DepartmentData($r['data_id']);
                    if($government) {
                        $r['government_id'] =$government->id;
                        $r['government'] =$government->name;
                    }
				*/
	        }
	        RedisIO::set($key, json_encode($r), self::CACHE_TIME);
	        return $r;
    	}
    	return json_decode($videoData, true);
    }

    /**
     * 获取视频集
     * @param $data_id
     * @param $terminal
     * @param string $group
     * @return array|bool
     */
    public static function getVideoCollection($data_id, $terminal, $group = '') {
    	$type = "video_collection";
    	$key = "DataCache:".self::$channelId.":".$data_id;
    	$videoCollectionData = RedisIO::get($key);
    	if (!$videoCollectionData) {
	        $model = Data::getReadDataByType($data_id, 'video_collection', self::$channelId);
	        if (!$model) {
	            return [];
	        }
	        $r = VideoCollections::getWithVideos($model['source_id']);
	        self::assignData($r, $model);
	        $r['breadcrumb'] = Category::tplBreadcrumbs($data_id, $terminal, Templates::TPL_CATEGORY_VIDEO_COLLECTION, $group);
	        if(array_key_exists("data_id", $r)) {
	        	$r['hits'] = RedisIO::get("hits:" . $r['data_id'])?:0;
	        }
	        RedisIO::set($key, json_encode($r), self::CACHE_TIME);
	        return $r;
    	}
    	return json_decode($videoCollectionData, true);
    }

    /**
     * 获取专题
     *
     * @param $data_id
     * @param $terminal
     * @return array
     */
    public static function getSpecial($data_id, $terminal, $group = '') {
    	$type = "special";
    	$key = "DataCache:".self::$channelId.":".$data_id;
    	$specialData = RedisIO::get($key);
    	if (!$specialData) {
	        $model = Data::getReadDataByType($data_id, 'special', self::$channelId);
	        if (!$model) {
	            return [];
	        }
	        $r = Specials::findFirst($model['source_id']);
	        if (!$r) {
	            return [];
	        }
	        $r = $r->toArray();
	        self::assignData($r, $model);
	        $r['extras'] = SpecialExtras::tplBySpecial($r['id']);
	        $r['breadcrumb'] = Category::tplBreadcrumbs($data_id, $terminal, Templates::TPL_CATEGORY, $group);
	        $r['categories'] = SpecialCategory::tplBySpecial($r['id']);
	     	if(array_key_exists("data_id", $r)) {
	        	$r['hits'] = RedisIO::get("hits:" . $r['data_id'])?:0;
	        }
	        RedisIO::set($key, json_encode($r), self::CACHE_TIME);
	        return $r;
    	}
    	return json_decode($specialData, true);
    }

    /**
     * 获取活动
     *
     * @param $data_id
     * @param $terminal
     * @return array|bool
     */
    public static function getActivity($data_id, $terminal, $group = '') {
    	$type = "activity";
    	$key = "DataCache:".self::$channelId.":".$data_id;
    	$activityData = RedisIO::get($key);
    	if (!$activityData) {
	        $model = Data::getReadDataByType($data_id, 'activity', self::$channelId);
	        if (!$model) {
	            return [];
	        }
	        $r = Activity::getWithSignup($model['source_id']);
	        self::assignData($r, $model);
	        $r['breadcrumb'] = Category::tplBreadcrumbs($data_id, $terminal, Templates::TPL_CATEGORY_ALBUM, $group);
	        if(array_key_exists("data_id", $r)) {
	        	$r['hits'] = RedisIO::get("hits:" . $r['data_id'])?:0;
	        }
	        RedisIO::set($key, json_encode($r), self::CACHE_TIME);
	        return $r;
    	}
    	return json_decode($activityData, true);
    }

    public static function getFeature($position, $category_id, $region_id = 0, $count = 10) {
        return Features::tplFeatures(self::$channelId, $position, $category_id, $region_id, $count);
    }

    protected static function processSpecialData($is_code, $mains, $size, $page, $is_sort = false) {
        if (!is_array($mains)) {
            $mains = [$mains];
        }
        $fun = function ($id) use ($size, $page, $is_sort) {
            $id = (int)$id;
            if (!$id) {
                return [];
            }
            $order = $is_sort ? 'weight DESC, sort DESC, created_at DESC, scd.id DESC' : 'scd.id DESC';
            $rs = Data::channelQuery(self::$channelId)
                ->andWhere('scd.special_category_id = :special_category_id: AND Data.status = :status:', ['special_category_id' => $id, 'status' => 1])
                ->leftJoin('SpecialCategoryData', 'scd.data_id = Data.id', 'scd')
                ->orderBy($order)
                ->paginate($size, 'SmartyPagination', $page);
            return self::parcelToArray($rs);
        };
        $rs = [];
        
        $memkey = "DataCache:Special:".self::$channelId.":".md5(json_encode($mains))."is_code:".$is_code."size:".$size."page:".$page."is_code:".$is_code;
        $sepcialdata = RedisIO::get($memkey);
        if(!$sepcialdata) {   
	        if ($is_code) {
	            $mains = SpecialCategory::tplFindInCodes($mains, self::$channelId);
	        }
	        if (count($mains) == 1) {
	            $main = current($mains);
	            $rs = $fun($main);
	        } else {
	            foreach ($mains as $key => $id) {
	                $rs[$is_code ? $key : $id] = $fun($id);
	            }
	        }
    		RedisIO::set($memkey, json_encode($rs), 60);
    	}
    	else {
    		$rs = json_decode($sepcialdata, true);
    	}
        
        
        if(is_array($mains)) {
	        foreach($mains as $sepcial_category_id) {
	            $key = "DataCache_special_keylist_".self::$channelId."_".$sepcial_category_id;
	            RedisIO::zAdd($key, 0, $memkey);
	        }
        }
        else {
        	 $key = "DataCache_special_keylist_".self::$channelId."_".$mains;
	            RedisIO::zAdd($key, 0, $memkey);
        }
        return $rs;
    }

    public static function getSpecialDataByCode($codes, $size = 50, $page = null) {
        return self::processSpecialData(true, $codes, $size, $page);
    }

    public static function getSpecialDataById($ids, $size = 50, $page = null) {
        return self::processSpecialData(false, $ids, $size, $page);
    }
    
    public static function getSpecialDataWithSortById($ids, $size = 50, $page = null) {
    	return self::processSpecialData(false, $ids, $size, $page, true);
    }
    
    public static function getSpecialDataByIds($ids, $size = 50, $page = null) {
    	return self::processGetSpecialCategoryData($ids, $size, $page, false);
    }
    
    public static function getSpecialDataWithSortByIds($ids, $size = 50, $page = null) {
    	return self::processGetSpecialCategoryData($ids, $size, $page, true);
    }

    protected static function getLatestDataQuery($category_id) {
        return Data::channelQuery(self::$channelId)
            ->andWhere('cd.category_id = :category_id: AND Data.status = :status: AND cd.publish_status = :publish_status:', ['category_id' => $category_id, 'status' => 1, 'publish_status'=>1])
            ->leftJoin('CategoryData', 'cd.data_id = Data.id', 'cd');
    }
    
	protected static function getLatestDataQueryByCode($code) {
	    $category_id = self::getCategoryId($code);
        return Data::query()
            ->andWhere('Data.channel_id = :channel_id: AND cd.category_id = :category_id: AND Data.status = :status: AND cd.publish_status = :publish_status:', ['channel_id' => self::$channelId, 'category_id' => $category_id, 'status' => 1, 'publish_status'=>1])
            ->rightJoin('CategoryData', 'cd.data_id = Data.id', 'cd');
    }

    public static function getLatest($category_id, $size = 50, $page = null,$author_id = null) {
        return self::processGetLatest(false, $category_id, 0, $size, $page, false,$author_id);
    }

    public static function getLatestWithSort($category_id, $size = 50, $page = null,$author_id = null) {
        return self::processGetLatest(false, $category_id, 0, $size, $page, true,$author_id);
    }

    public static function getLatestByCode($code, $size = 50, $page = null) {
        return self::processGetLatest(true, $code, 0, $size, $page, false);
    }

    public static function getLatestByCodeWithSort($code, $size = 50, $page = null) {
        return self::processGetLatest(true, $code, 0, $size, $page, true);
    }

    public static function getRegionLatest($region_id, $category_id, $size = 50, $page = null) {
        return self::processGetLatest(false, $category_id, $region_id, $size, $page, false);
    }

    public static function getRegionLatestWithSort($region_id, $category_id, $size = 50, $page = null) {
        return self::processGetLatest(false, $category_id, $region_id, $size, $page, true);
    }

    public static function getRegionLatestByCode($region_id, $code, $size = 50, $page = null) {
        return self::processGetLatest(true, $code, $region_id, $size, $page, false);
    }

    public static function getRegionLatestByCodeWithSort($region_id, $code, $size = 50, $page = null) {
        return self::processGetLatest(true, $code, $region_id, $size, $page, true);
    }

    protected static function processGetLatest($is_code, $main, $region_id, $size, $page, $is_sort,$author_id=null) {
        $weight = Request::getQuery('weight', 'int', 0);
        $fun = function ($main) use ($region_id, $is_code, $size, $page, $is_sort, $weight,$author_id) {
            $fun = $is_code ? 'getLatestDataQueryByCode' : 'getLatestDataQuery';
            $order = $is_sort ? 'weight DESC, cd.sort DESC, created_at DESC' : 'created_at DESC';
            /**
             * @var \Phalcon\Mvc\Model\Criteria $query
             */
            $query = self::$fun($main);
            if ($region_id) {
                $r = Regions::fetchById($region_id);
                if (!$r) {
                    return [];
                }
                $key = $r->level . '_id';
                $query->rightJoin('RegionData', 'rd.data_id = Data.id', 'rd')
                    ->andWhere("rd.{$key} = :{$key}:", [$key => $region_id]);
            }
            if($author_id){
                $query->andWhere("Data.author_id={$author_id}");
            }
            if($weight) {
                $weight = ($weight==2)?0:1;
                return $query->andWhere("cd.weight=".$weight)
                    ->orderBy($order)
                    ->paginate($size, 'SmartyPagination', $page);

            }
            else {
            return $query
                ->orderBy($order)
                ->paginate($size, 'SmartyPagination', $page);
            }

        };

        $memkey = "DataCache:Latest:".self::$channelId.":".md5(json_encode($main)).":is_code:".$is_code.":region_id:".$region_id."size:".$size."page:".$page."is_sort:".$is_sort."weight:".$weight."author".(isset($author_id)? $author_id:-1);
        $latestmemdata = RedisIO::get($memkey);
        if(!$latestmemdata||strlen($latestmemdata)<400) {
            $rs = [];
            if (!is_array($main)) {
                $rs = $fun($main);
                $rs = self::parcelToArray($rs);
            } else {
                $main = array_unique($main);
                foreach ($main as $m) {
                    $r = $fun($m);
                    $rs[$m] = self::parcelToArray($r);
                }
            }
            RedisIO::set($memkey, json_encode($rs), 86400);
        }
        else {
            $rs = json_decode($latestmemdata, true);
        }
        foreach ($rs['models'] as $key => $value) {
            $data_id = $value['id'];
            $hits = RedisIO::get("hits:" . $data_id);
            $baseHitsCounts = RedisIO::get("baseHitsCounts:" . $data_id);
            $rs['models'][$key]['hits'] = $hits?:0;
            $rs['models'][$key]['hits_fake'] =(($baseHitsCounts)?intval($baseHitsCounts):0)+(($hits)?intval($hits):0);
            $rs['models'][$key]['likes'] = RedisIO::get("meiZiLikes:" . $data_id);
            $rs['models'][$key]['likes_fake'] = RedisIO::get("meiZiLikes:" . $data_id) + RedisIO::get("baseLikesCounts:" . $data_id);
            if($value['type']=="multimedia") $rs['models'][$key]['type'] = "news";
        }
        if(is_array($main)) {
            foreach($main as $category_id) {
                $category_id = $is_code?(self::getCategoryId($category_id)):$category_id;
                $key = "DataCache_keylist_".self::$channelId."_".$category_id;
                RedisIO::zAdd($key, 0, $memkey);
            }
        }
        else {
            $category_id = $is_code?(self::getCategoryId($main)):$main;
            $key = "DataCache_keylist_".self::$channelId."_".$category_id;
            RedisIO::zAdd($key, 0, $memkey);
        }
        return $rs;
    }

    public static function getCategoryBreadcrumbs($id, $type = Templates::TPL_CATEGORY, $group = '') {
        return Category::tplBreadcrumbsById($id, self::$channelId, $type, $group);
    }

    /**
     * 获取分类的单个或多个 id 的获取的最新新闻合集
     *
     * @param $id
     * @param int $size
     * @param null $page
     * @return array
     */
    public static function getLatestInIds($id, $size = 50, $page = null) {
        return self::processGetLatestInCodes(false, $id, $size, $page, false);
    }

    /**
     * 获取分类的单个或多个 id 的按权重排序获取的最新新闻合集
     *
     * @param $id
     * @param int $size
     * @param null $page
     * @return array
     */
    public static function getLatestWithSortInIds($id, $size = 50, $page = null) {
        return self::processGetLatestInCodes(false, $id, $size, $page, true);
    }

    /**
     * 获取分类的单个或多个 code 的获取的最新新闻合集
     *
     * @param $code
     * @param int $size
     * @param null $page
     * @return array
     */
    public static function getLatestInCodes($code, $size = 50, $page = null) {
        return self::processGetLatestInCodes(true, $code, $size, $page, false);
    }

    /**
     * 获取分类的单个或多个 code 的按权重排序获取的最新新闻合集
     *
     * @param $code
     * @param int $size
     * @param null $page
     * @return array
     */
    public static function getLatestWithSortInCodes($code, $size = 50, $page = null) {
        return self::processGetLatestInCodes(true, $code, $size, $page, true);
    }

    /**
     * 获取多 codes 分类新闻, 实际处理
     *
     * @param $is_code
     * @param $main
     * @param $size
     * @param $page
     * @param $is_sort
     * @return array
     */
    protected static function processGetLatestInCodes($is_code, $main, $size, $page, $is_sort) {
        if (!is_array($main) && $main) {
            $main = [$main];
        }
        if (!$main || empty($main)) {
            $x = false;
            return self::parcelToArray($x);
        }
        if ($is_code) {

            $rs = Category::getAllByCodes($main, self::$channelId);
            $main = [];
            if ($rs) {
                foreach ($rs as $r) {
                    $main[] = $r->id;
                }
            }

        }
        if (!$main || empty($main)) {
            $x = false;
            return self::parcelToArray($x);
        }
        $order = $is_sort ? 'weight DESC, sort DESC, created_at DESC' : 'created_at DESC';
        /**
         * @var \GenialCloud\Database\Criteria $query
         */
        $memkey = "DataCache:LatestInCodes:".self::$channelId.":".md5(json_encode($main).":is_code:".$is_code."size:".$size."page:".$page."is_sort:".$is_sort);

        $latestmemdata2 = RedisIO::get($memkey);
        if(!$latestmemdata2) {
            $rs = Data::channelQuery(self::$channelId)
                ->columns('Data.id, category_id, type, source_id, title, intro, thumb, created_at, updated_at, author_id, author_name, hits, status, cd.sort, weight')
                ->andWhere('Data.status = :status: AND cd.publish_status = :publish_status: AND cd.category_id IN (' . implode(',', $main) . ')', ['status' => 1, 'publish_status' => 1])
                ->rightJoin('CategoryData', 'cd.data_id = Data.id', 'cd')
                ->orderBy($order)
                ->paginate($size, 'SmartyPagination', $page);
            $rs2 = self::parcelToArray($rs);
            RedisIO::set($memkey, json_encode($rs2), 600);
        }
        else {
            $rs2 = json_decode($latestmemdata2, true);
        }

        foreach($main as $category_id) {
            $key = "DataCache_keylist_".self::$channelId."_".$category_id;
            RedisIO::zAdd($key, 0, $memkey);
        }
        return $rs2;
    }
    
    protected static function processGetSpecialCategoryData($main, $size, $page, $is_sort) {
    	if (!is_array($main) && $main) {
    		$main = [$main];
    	}
    	if (!$main || empty($main)) {
    		$x = false;
    		return self::parcelToArray($x);
    	}
    	if (!$main || empty($main)) {
    		$x = false;
    		return self::parcelToArray($x);
    	}
    
    	/**
    	 * @var \GenialCloud\Database\Criteria $query
    	 */
    	$order = $is_sort ? 'weight DESC, sort DESC, created_at DESC, scd.id DESC' : 'scd.id DESC';
    	
    	$memkey = "DataCache:SpecialCategoryData:".md5(json_encode($main)."size:".$size."page:".$page."is_sort:".$is_sort);
    	
    	$latestmemdataSpecial = RedisIO::get($memkey);
    	if(!$latestmemdataSpecial) {
	    	$rs = Data::channelQuery(self::$channelId)
		    	->andWhere('Data.status = :status: AND scd.special_category_id IN ('.implode(',', $main).')', ['status' => 1])
		    	->leftJoin('SpecialCategoryData', 'scd.data_id = Data.id', 'scd')
		    	->orderBy($order)
		    	->paginate($size, 'SmartyPagination', $page);
	    	$rs2 = self::parcelToArray($rs);
    		RedisIO::set($memkey, json_encode($rs2), 60);
    	}
    	else {
    		$rs2 = json_decode($latestmemdataSpecial, true);
    	}
    	return $rs2;
    }

    protected static function getCategoryWithSeo($main, $type) {
        $query = Category::channelQuery(self::$channelId, 'Category')
            ->columns(['Category.*', 'CategorySeo.*'])
            ->leftJoin('CategorySeo', 'category_id = Category.id');

        $r = $query->andCondition($type, $main)->first();
        if ($r) {
            $r = array_merge($r->categorySeo->toArray(), $r->category->toArray());
        } else {
            $r = [];
        }
        return $r;
    }

    public static function getCategory($category_id) {
        return self::getCategoryWithSeo($category_id, 'id');
    }

    /**
     * 通过 Code 获取分类
     *
     * @param $code
     * @return array
     */
    public static function getCategoryByCode($code) {
	    $category_id = self::getCategoryId($code);
        return self::getCategoryWithSeo($category_id, 'id');
    }

    /**
     * 获取子一级全部分类
     *
     * @param $id
     * @return array
     */
    public static function getSubCategory($id) {
        return Category::tplSub($id, self::$channelId);
    }

    /**
     * 获取子一级全部分类,根据权重排序
     *
     * @param $id
     * @return array
     */
    public static function getSubCategorySort($id) {
        return Category::tplSub($id, self::$channelId ,true);
    }

    /**
     * 获取地区
     *
     * @param $id
     * @return array
     */
    public static function getRegion($id) {
        return Regions::tplFirst($id);
    }

    /**
     * 获取子一级全部地区
     *
     * @param $id
     * @return array
     */
    public static function getSubRegion($id) {
        return Regions::tplSub($id);
    }

    /**
     * @return array|mixed
     */
    public static function listStations() {
        return Stations::tplStations(self::$channelId);
    }

    /**
     * @param $main
     * @param string $type
     * @return array|mixed
     * @throws \Phalcon\Mvc\Model\Exception
     */
    public static function getEpgs($main, $type = 'code') {
        return StationsEpg::tplByStationCode(self::$channelId, $main, $type);
    }

    /**
     * @param $main
     * @param string $type
     * @return bool
     * @throws \Phalcon\Mvc\Model\Exception
     */
    public static function getPrograms($main, $type = 'code') {
        return StationsProgram::tplByStationCode(self::$channelId, $main, $type);
    }

    public static function getBlockByCode($code) {
        $query = function ($code) {
            $rs = [];
            $block = Blocks::channelQuery(self::$channelId)
                ->andCondition('code', $code)
                ->first();
            if ($block) {
                $rs = $block->toArray();
                $values = BlockValues::query()->andCondition('block_id', $block->id)->execute();
                $rs['values'] = [];
                if ($values) {
                    foreach ($values as $v) {
                        $rs['values'][$v->name] = $v->value;
                    }
                }
            }
            return $rs;
        };
        if (is_array($code)) {
            foreach ($code as $c) {
                $rs[$c] = $query($c);
            }
        } else {
            $rs = $query($code);
        }
        return $rs;
    }
    private static function DepartmentData($data_id) {
        $governmentData = GovernmentDepartmentData::fetchGovernmentDepartmentId($data_id);
        if(isset($governmentData) && !empty($governmentData)){
            foreach ($governmentData as $v){
                $government_id = $v['government_department_id'];
            }
            return GovernmentDepartment::fetchById($government_id);
        }
        return "";
    }

    protected static function parcelToArray(&$parcel) {
        $models = [];
        $count = 0;
        $pagination = '';
        if ($parcel) {
            if ($parcel->models) {
                $models = $parcel->models->toArray();
                foreach ($models as $key => $modelsvalue) {
				    $data_id = $models[$key]['id'];
                    $hits = RedisIO::get("hits:" . $data_id);
                    $baseHitsCounts = RedisIO::get("baseHitsCounts:" . $data_id);
					$models[$key]['hits'] = $hits?:0;
                    $models[$key]['hits_fake'] =(($baseHitsCounts)?intval($baseHitsCounts):0)+(($hits)?intval($hits):0);
					$models[$key]['likes'] = RedisIO::get("meiZiLikes:" . $data_id);
					$models[$key]['likes_fake'] = RedisIO::get("meiZiLikes:" . $data_id) + RedisIO::get("baseLikesCounts:" . $data_id);
					if($models[$key]['type']=="multimedia") $models[$key]['type'] = "news";
                    /*
                    $param_values = DataExt::getExtValues($models[$key]['id']);
                    $models[$key] = array_merge($models[$key], $param_values);
                    $government = self::DepartmentData($models[$key]['id']);
                    if($government){
                      $models[$key]['government_id'] =$government->id;
                      $models[$key]['government'] =$government->name;
                    }
                    */
                }
            }
            $count = $parcel->count;
            $pagination = $parcel->pagination->render();
        }
        return compact('models', 'count', 'pagination');
    }

    public static function getDataByDeptId($dept_id,$channel_id,$page=1,$page_size=12){
        $key = "getdatabydeptid" . $dept_id . $channel_id . $page . $page_size;
        $res = RedisIO::get($key);
        if(!$res) {
            $res = Data::query()
                ->andWhere("channel_id=" . $channel_id . " and gd.government_department_id=" . $dept_id)
                ->rightJoin("GovernmentDepartmentData", "gd.data_id=Data.id", "gd")
                ->orderBy("created_at desc")
                ->limit($page_size, ($page - 1) * $page_size)
                ->execute()->toArray();
            foreach ($res as $key => $resvalue) {
                $param_values = DataExt::getExtValues($res[$key]['id']);

                $res[$key] = array_merge($res[$key], $param_values);
            }

            $res = json_encode($res);
            RedisIO::set($key, $res, self::EXPIRES);
        }
        return json_decode($res,true);
    }

    public static function getOneChannel() {
        $key = "channelinfo:" . self::$channelId;
        $res = RedisIO::get($key);
        if(!$res) {
            $res = Channel::getOneChannel(self::$channelId)->toArray();
            $res = json_encode($res);
            RedisIO::set($key, $res, self::EXPIRES);
        }
        return json_decode($res,true);
    }

    public static function delCategoryDataRedis($category_id) {

        $channel_id = Session::get('user')->channel_id;

        $key = "DataCache_keylist_".$channel_id."_".$category_id;

        $latestlist = RedisIO::zRange($key, 0, -1);

        foreach($latestlist as $v) {

            RedisIO::delete($v);
            RedisIO::zRem($key, $v);
        }
    }

    public static function delSpecialCategoryDataRedis($special_category_id) {

        $channel_id = Session::get('user')->channel_id;

        $key = "DataCache_special_keylist_".$channel_id."_".$special_category_id;

        $latestlist = RedisIO::zRange($key, 0, -1);

        foreach($latestlist as $v) {

            RedisIO::delete($v);
            RedisIO::zRem($key, $v);
        }
    }

    public static function delDataRedis($data_id) {

        $channel_id = Session::get('user')->channel_id;

        $key = "DataCache:".$channel_id.":".$data_id;
        RedisIO::delete($key);
    }

    public static function delCategoryDataRedisChannel($category_id, $channel_id) {
        $key = "DataCache_keylist_".$channel_id."_".$category_id;
        $latestlist = RedisIO::zRange($key, 0, -1);
        foreach($latestlist as $v) {
            RedisIO::delete($v);
            RedisIO::zRem($key, $v);
        }
    }

    public static function delDataRedisChannel($data_id, $channel_id) {
        $key = "DataCache:".$channel_id.":".$data_id;
        RedisIO::delete($key);
    }	

    protected static function getCategoryId($code) {
        $key_code_to_id = "SmartyData:channel_id:".self::$channelId.":code:".md5($code);
        $category_id = RedisIO::get($key_code_to_id);
        $category_id = 0 ;
        if(!$category_id) {
            $parameters = array();
            $parameters['conditions'] = "channel_id=".self::$channelId." and code='".$code."'";
            $category =  Category::findFirst($parameters);
            $category_id  = $category->id;
            RedisIO::set($key_code_to_id, $category_id);
        }
	return $category_id;
    }
}
