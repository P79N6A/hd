<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Signals extends Model {
	const PAGE_SIZE = 50;
	
	const SET_LIST_KEY = "SIGNALS_JSONDATA_LIST_VALUE:redis_cache_by_model";
	const REDIS_SVAETIME_OUT = 2592000;   // 30天
	 
	const LIVE_TYPE_TV = 1;					// 传统电视
	const LIVE_TYPE_BROADCAST = 2;			// 传统电台
	const LIVE_TYPE_SUPPOSITIONAL = 3;		// 虚拟直播
	const LIVE_TYPE_ACTIVITY = 4;			// 活动直播
	const LIVE_TYPE_UGC = 5;				// 手机直播
	
	const LIVE_TYPE_TV_VALUES = "电视直播";
	const LIVE_TYPE_BROADCAST_VALUES = "广播直播";
	const LIVE_TYPE_SUPPOSITIONAL_VALUES = "虚拟直播";
	const LIVE_TYPE_ACTIVITY_VALUES = "活动直播";
	const LIVE_TYPE_UGC_VALUES = "手机直播";
	
	const LIVE_DATA_STATUS_NO_REVIEWED = 9;		// 未审核
	const LIVE_DATA_STATUS_REVIEWED = 1;		// 已发布
	const LIVE_DATA_STATUS_FORBIDDEN = 0;		// 撤销
	
	const LIVE_DATA_STATUS_NO_REVIEWED_VALUES = "未审核";
	const LIVE_DATA_STATUS_REVIEWED_VALUES = 	"已发布"; 
	const LIVE_DATA_STATUS_FORBIDDEN_VALUES =	"撤    销"; 
	
	const LIVE_STATUS_NOUSE = 1;			// 未使用
	const LIVE_STATUS_NOTSTART = 1;			// 未启用
	const LIVE_STATUS_FORBIDDEN = 2;		// 禁用
	const LIVE_STATUS_FINISH = 3;			// 已结束
	const LIVE_STATUS_PLAYING = 4;			// 播放中
	
	const LIVE_STATUS_NOTSTART_VALUES = "未启用";
	const LIVE_STATUS_FORBIDDEN_VALUES = "禁用";
	const LIVE_STATUS_FINISH_VALUES = "已结束";
	const LIVE_STATUS_PLAYING_VALUES = "播放中";
	
	const IS_BINDING_FEATURES = 1;			// 推荐位显示
	const IS_NOT_BINDING_FEATURES = 0;		// 列表显示
	
	// redis 键值
	const REDIS_HASH_SIGNALS_STATUS_KEY = "hash_signals_find_datas_by_dataId_json";					// 状态
	const REDIS_HASH_SIGNALS_STATUS_HASHKEY = "signals_find_datas_by_dataId_json: data_id :"; 
	const REDIS_HASH_SIGNALS_TO_PLAYER_KEY = "signals_to_play_json";
	const REDIS_HASH_SIGNALS_TO_PLAYER_HASHKEY = "signals_play_json:data_id:";
	const setBeginTimeKey = "begin_limit_time:reids_cache_by_task";
	const setEndTimeKey = "end_limit_time:reids_cache_by_task";
	use HasChannel;
    public function getSource() {
        return 'signals';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'keywords', 'content', 'live_type', 'live_status', 'sort', 'isfeatured', 'notstarted_img', 'unauthorized_img', 'complete_img', 'buffering_img', 'danmu', 'firstlook', 'paylist', 'trylook', 'comment_type',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'keywords', 'content', 'live_type', 'live_status', 'sort', 'isfeatured', 'notstarted_img', 'unauthorized_img', 'complete_img', 'buffering_img', 'danmu', 'firstlook', 'paylist', 'trylook', 'comment_type',],
            MetaData::MODELS_NOT_NULL => ['id', 'live_type',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'keywords' => Column::TYPE_VARCHAR,
                'content' => Column::TYPE_TEXT,
                'live_type' => Column::TYPE_INTEGER,
                'live_status' => Column::TYPE_INTEGER,
                'sort' => Column::TYPE_INTEGER,
                'isfeatured' => Column::TYPE_INTEGER,
                'notstarted_img' => Column::TYPE_VARCHAR,
                'unauthorized_img' => Column::TYPE_VARCHAR,
                'complete_img' => Column::TYPE_VARCHAR,
                'buffering_img' => Column::TYPE_VARCHAR,
                'danmu' => Column::TYPE_INTEGER,
                'firstlook' => Column::TYPE_INTEGER,
                'paylist' => Column::TYPE_VARCHAR,
                'trylook' => Column::TYPE_INTEGER,
                'comment_type' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'live_type', 'live_status', 'sort', 'isfeatured', 'danmu', 'firstlook', 'trylook', 'comment_type',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'keywords' => Column::BIND_PARAM_STR,
                'content' => Column::BIND_PARAM_STR,
                'live_type' => Column::BIND_PARAM_INT,
                'live_status' => Column::BIND_PARAM_INT,
                'sort' => Column::BIND_PARAM_INT,
                'isfeatured' => Column::BIND_PARAM_INT,
                'notstarted_img' => Column::BIND_PARAM_STR,
                'unauthorized_img' => Column::BIND_PARAM_STR,
                'complete_img' => Column::BIND_PARAM_STR,
                'buffering_img' => Column::BIND_PARAM_STR,
                'danmu' => Column::BIND_PARAM_INT,
                'firstlook' => Column::BIND_PARAM_INT,
                'paylist' => Column::BIND_PARAM_STR,
                'trylook' => Column::BIND_PARAM_INT,
                'comment_type' => Column::BIND_PARAM_INT,
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
     * 判断空值
     * @param unknown $input
     */
    public static function makeValidator($input) {
    	return Validator::make(
    			$input,
    			[
    			'keywords' => 'max:255',
    			],
    			[
    			'title.required' => '请填写标题',
    			'keywords.max' => '关键词不得多于255个字',
    			]
    	);
    }
    
    /**
     * 与媒资表了联合查询
     * 列表页显示
     */
    public static function findMediaData($channel_id, $type) {
    	//     	$memkey = "in_data_left_join_media_signals:"."channel_id:".$channel_id."type:".$type;
    	//     	$redisData = RedisIO::get($memkey);
    	//     	if(!$redisData) {
    	$query = self::query()
    	->columns(array('Data.*', 'Signals.*'))
    	->leftJoin("Data", "Signals.id=Data.source_id")
    	->andWhere("Data.channel_id={$channel_id}")
    	->andWhere("Data.type='{$type}'")
    	->andwhere("Signals.isfeatured ='0'")
    	->orderBy('Signals.sort desc, Data.created_at');
    	$query = $query->paginate(self::PAGE_SIZE, 'Pagination');
    	return $query;
    	//     		$rs =  self::parcelToArray($query);
    	//     		$rs1 = json_encode($rs);
    	//     		$rs2 = json_decode($rs1);
    	//    		RedisIO::set($memkey, $rs1, 60);
    	//     	}
    	//     	else {
    	//     		$rs2 = json_decode($redisData);
    	//     	}
    	//     	return $rs2;
    }
    
    public static function searchMediaData($data, $channel_id) {
    
    	$query = self::query()
    	->columns(array('Data.*', 'Signals.*'))
    	->leftJoin("Data", "Signals.id=Data.source_id")
    	->where("Signals.isfeatured ='0'")
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
    	return $query->paginate(self::PAGE_SIZE, 'Pagination');
    }
    
    public static function findDataById($signal_id) {
	    $data = self::query()
		   	->columns(array('Signals.*'))
		   	->andWhere("Signals.id={$signal_id}")
		   	->first();
    	return $data;
    }
    
    /**
     * 查询所有数据
     * @return unknown
     */
    public static function findAll() {
    	$result = self::query()
    	->columns(array('Signals.*'))
    	->orderBy('Signals.created_at desc')
    	->paginate(self::PAGE_SIZE, 'Pagination');
    	return $result;
    }
    
    /**
     * 获取一条数据
     * @param unknown $id
     * @return \Phalcon\Mvc\ModelInterface
     */
    public static function getOne($id) {
    	return self::query()
    	->andCondition('id', $id)
    	->first();
    }
    
    /**
     * 更改推荐位
     * @param unknown $signalId 直播 id
     * @param unknown $isFeature 是否进行推荐 ，0： 否， 1： 是
     * @return boolean
     */
    public static function updateFeatureStatus($signalId, $isFeature) {
    	$data = self::getOne($signalId);
    	$data->isfeatured = $isFeature;
    	return $data->save() ? true : false;
    }
    
    /**
     * 新增数据
     * @param unknown $data
     */
    public function createSignalsData($data) {
    	$top_sort = self::maximum(array("column"=>"sort"));
    	$data['sort'] = $top_sort + 1;
    	$this->assign($data);
    	return $this->save() ? true : false;
    }
    
    /**
	 *  根据时效 修改状态
     */
    public static function checkTimeLimit($dataId, $channelId, &$status, &$statusName) {
    	$data = Data::getById($dataId, $channelId);
    	$beginTime = $data->timelimit_begin;
    	$endTime = $data->timelimit_end;
    	
    	self::checkInputTime($beginTime, $endTime, $status, $statusName);
    }
    
    public static function checkInputTime($beginTime, $endTime, &$status, &$statusName) {
    	if($endTime != 0 && $endTime < time() && $beginTime <= $endTime) {
    		$status = self::LIVE_STATUS_FINISH;
    		$statusName = self::LIVE_STATUS_FINISH_VALUES;
    	}
    	else if(($beginTime > time() && $endTime == 0) || ($beginTime > time() && $beginTime <= $endTime)) {
    		$status = self::LIVE_STATUS_NOTSTART;
    		$statusName = self::LIVE_STATUS_NOTSTART_VALUES;
    	}
    	else {
    		$status = self::LIVE_STATUS_PLAYING;
    		$statusName = self::LIVE_STATUS_PLAYING_VALUES;
    	}
    }
    
    /**
     * 保存状态值
     * @param unknown $live_id
     * @param unknown $live_status
     */
    public function updateLiveStatus($data_id, $live_status, $channel_id) {
    	$datas = Data::getById($data_id, $channel_id);
    	$data = self::getOne($datas->source_id);
    	$data->live_status = $live_status;
    	$res = $data->save() ? true : false;
    	if($res) {
    		$this->delRedisStatus($data_id);
    		self::delJsonByRedis($data_id);
    		$this->setJsonByRedis($data_id);
    	}
    	return $res;
    }
    
    public function delRedisStatus($data_id) {
    	$memKey = self::REDIS_HASH_SIGNALS_STATUS_KEY;
    	$hashKey = self::REDIS_HASH_SIGNALS_STATUS_HASHKEY.$data_id;
    	if(RedisIO::hExists($memKey, $hashKey)){
    		RedisIO::hDel($memKey, $hashKey);
    	}
    }
    
    /**
     * 连表查询 data表，signals表
     * @param unknown $dataId
     * @param unknown $channelId
     */
    public function findData($dataId) {
    	$memKey = self::REDIS_HASH_SIGNALS_STATUS_KEY;
    	$hashKey = self::REDIS_HASH_SIGNALS_STATUS_HASHKEY.$dataId;
    	$resData = array();
    	if(RedisIO::hExists($memKey, $hashKey)){
    		$jsonData = RedisIO::hGet($memKey, $hashKey);
    		$resData = json_decode($jsonData);
    	}
    	if(!RedisIO::hExists($memKey, $hashKey) || empty($resData)) {
    		$data = Data::query()
    		->columns(array('Data.*', 'Signals.*'))
    		->leftJoin("Signals", "Signals.id=Data.source_id")
    		->where("Data.id ={$dataId}")
    		->andwhere("Data.type = 'live'");
    		$resData = $data->execute()->toArray();
    		RedisIO::hSet($memKey, $hashKey, json_encode($resData));
    	}
    	return $resData;
    }
    
    /**
     * 删除数据
     * @param unknown $fatherId
     * @param unknown $signalsId
     * @return boolean
     */
    public static function deleteOneData($signalsId) {
    	$data = self::query()->andwhere('id='. $signalsId)->first();
    	return ($data->delete()) ? true : false;
    }
    
    
    public static function deleteData($id, $channel_id) {
    	$bRes = false;
    	$data = self::query()->andwhere('id=' . $id)->execute();
    	if (!empty($data) && count($data) > 0) {
    		DB::begin();
    		foreach ($data as $v) {
    		    $bRes =	$v->delete() ? true : false;
    		}
    		$bRes ? DB::commit() : DB::rollback();
    	}
    	return $bRes;
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
    
    /**
     * 单条置顶
     * @param int $category_id
     * @param int $id
     * @return bool
     */
    public static function top($sort, $id) {
    	$top_sort = self::maximum(array("column"=>"sort"));
    	if($top_sort != $sort){
    		$data = self::findFirst($id);
    		$data->sort = $top_sort+1;
    		return $data->update() ? true : false;
    	}
    }
    
    

     /**
     * 返回错误的json字符串
     * @param unknown $flag
     * @param unknown $status
     * @param unknown $statuscode
     */
    public static function fialJson($flag, $status, $statuscode) {
    	$json = array(
    		"playstatus" => array(
    			"flag" => $flag,
    			"status" => $status
    		),
    		"statuscode" => $statuscode
    	);
		return json_encode($json);
    }

    /**
     * 播控平台直播播放接口 json格式
     * @param $dataId
     * @return string
     */
    public function JsonByRedis($dataId) {
    	$resJson = self::getJsonByRedis($dataId);
    	if($resJson == "") {
    		$resJson = $this->setJsonByRedis($dataId);
    	}
    	return $resJson;
    }
    
    public static function delJsonByRedis($dataId) {
    	$memKey = self::REDIS_HASH_SIGNALS_TO_PLAYER_KEY;
    	$hashKey = self::REDIS_HASH_SIGNALS_TO_PLAYER_HASHKEY.$dataId;
    	if(RedisIO::hExists($memKey, $hashKey)) {
    		RedisIO::hDel($memKey, $hashKey);
    	}
    }

    /** 获取播放接口数据（播放接口数据缓存在redis中)
     * @param $dataId
     * @return string
     */
    public static function getJsonByRedis($dataId) {
    	$memKey = self::REDIS_HASH_SIGNALS_TO_PLAYER_KEY;
    	$hashKey = self::REDIS_HASH_SIGNALS_TO_PLAYER_HASHKEY.$dataId;
    	if(RedisIO::hExists($memKey, $hashKey)) {
    		$jsonData = RedisIO::hGet($memKey, $hashKey);
    		return $jsonData;
    	}
    	else {
    		return "";	
    	}
    }
    
    public function setJsonByRedis($dataId) {
    	$memKey = self::REDIS_HASH_SIGNALS_TO_PLAYER_KEY;
    	$hashKey = self::REDIS_HASH_SIGNALS_TO_PLAYER_HASHKEY.$dataId;
    	$signal = new Signals();
    	$data = $signal->packageJsonToPlayer($dataId);
    	if($data == 0) {
    		RedisIO::hSet($memKey, $hashKey, Signals::fialJson(1, 0, 1001));		// 禁用， 不能播放
    		return Signals::fialJson(1, 0, 1001);
    	}
    	else if($data == 2) {
    		RedisIO::hSet($memKey, $hashKey, Signals::fialJson(0, 1, 1001));		// 下线或未开始
    		return Signals::fialJson(0, 1, 1001);
    	}
    	else {
    		if(isset($data) && !empty($data)) {
    			RedisIO::hSet($memKey, $hashKey, json_encode($data));
    			return json_encode($data);
    		}
    		else {
    			RedisIO::hSet($memKey, $hashKey, Signals::fialJson(1, 0, 1003));		// 参数错误
    			return Signals::fialJson(1, 0, 1003);
    		}
    	}
    }
 
    /**
     * 设置有序队列 , 用于缓存定时扫描
     * @param unknown $data_id data表id
     * @param unknown $channel_id 通道号
     * @param unknown $begin_time 时效的开始时间
     */
    public static function setBeginTimeCache($data_id, $channel_id, $begin_time) {
    	RedisIO::zAdd(self::setBeginTimeKey, $begin_time, $data_id.",".$channel_id);
    }
    /**
     * 获取有序队列 数据
     * @param unknown $timeTemp 需要查询的时间点
     */
    public static function getBeginTimeCache($timeTemp) {
    	$arrScore = RedisIO::zRangeByScore(self::setBeginTimeKey, 0, $timeTemp);
    	return $arrScore;
    }
    /**
     * 删除有序队列 数据
     * @param unknown $value 为 data_id.",".channel_id
     */
    public static function delBeginTimeCache($value) {
    	RedisIO::zRem(self::setBeginTimeKey, $value);
    }
    
    /**
     * 设置有序队列 , 用于缓存定时扫描
     * @param unknown $data_id data表id
     * @param unknown $channel_id 通道号
     * @param unknown $end_time 时效的结束时间
     */
    public static function setEndTimeCache($data_id, $channel_id, $end_time) {
    	RedisIO::zAdd(self::setEndTimeKey, $end_time, $data_id.",".$channel_id);
    }
    /**
     * 获取有序队列 数据
     * @param unknown $timeTemp 需要查询的时间点
     */
    public static function getEndTimeCache($timeTemp) {
    	$arrScore = RedisIO::zRangeByScore(self::setEndTimeKey, 0, $timeTemp);
    	return $arrScore;
    }
   	/**
     * 删除有序队列 数据
     * @param unknown $value 为 data_id.",".channel_id
     */
    public static function delEndTimeCache($value) {
    	RedisIO::zRem(self::setEndTimeKey, $value);
    }
    
    /**
     * 组装成json格式提供给播放器
     * @param unknown $data_id
     * @param unknown $channel_id
     */
    protected function packageJsonToPlayer($dataId){
    	$statuscode = "1001";
    	$jsonData = array();
    	$data = self::findData($dataId);
		if(isset($data) && !empty($data)) {
    		foreach ($data as $value) {
    			$playSatus = $value->signals->live_status;
    			$status = 0;	// 0 不能播放， 1 可以播放, 2 下线或未上线
    			switch ($playSatus) {
    				case self::LIVE_STATUS_NOUSE:			// 未使用
    				case self::LIVE_STATUS_FORBIDDEN:		// 禁用
    					$status = 0;
    					break;
    				case self::LIVE_STATUS_NOTSTART: 		// 未开启
    				case self::LIVE_STATUS_FINISH: 			// 播放结束
    					$status = 2;		
    					break;
    				case self::LIVE_STATUS_PLAYING:			// 播放中
    					$status = 1;
    				break;
    			}
    			if($status != 1) {
    				return $status;
    			}
    	 		$jsonData['danmu'] = $value->signals->danmu;
    	 		$jsonData['firstlook'] = $value->signals->firstlook;
    	 		$signalRates = new SignalRates();
    	 		$jsonData['paylist'] = $signalRates->getPayList($value->signals->paylist);
    	 		$jsonData['playstatus'] =array(
    	 				"status" => strval($status),  
    	 		);
    	 		$venderIdArr = array();
    	 		$epgData = array();
    	 		SignalEpg::getEpgInfo($dataId, $value->data->source_id, $venderIdArr, $epgData);
    	 		$picArr = array(
    	 			"thumb" => $value->data->thumb,
    	 			"thumb1" => $value->data->thumb1,
    	 			"thumb2" => $value->data->thumb2,
    	 			"thumb3" => $value->data->thumb3,
    	 		);
    	 		
    	 		$jsonData['playurl'] = array(
    	 				"dispatch" => array(array(
    	 						"weight" => $venderIdArr,
    	 						"url" => $epgData
    	 				),),
    	 				"title" => $value->data->title,
    	 				"pic" => $picArr,
    	 				"domain" => array(),
    	 		);
    	 		$jsonData['statuscode'] = $statuscode;
    	 		$jsonData['trylook'] = $value->signals->trylook;
    	 	}
    	 }
    	 return $jsonData;
    }
    
    public static function deleteTVJsonToRedis($signalId) {
    	if(RedisIO::exists(self::SET_LIST_KEY.$signalId)) {
    		RedisIO::delete(self::SET_LIST_KEY.$signalId);
    	}
    }
    
    /**
	 * 修改页面显示json 存到redis
     */
    public static function saveTVJsonToRedis($signalId) {
    	$signalJsons = Signals::editShowPackage($signalId);
    	self::deleteTVJsonToRedis($signalId);
    	RedisIO::set(self::SET_LIST_KEY.$signalId, $signalJsons, self::REDIS_SVAETIME_OUT);
    	return $signalJsons;
    }
    
   /**
	* 获取 redis中json数据，显示到修改页面
    */
    public static function getTVJsonByRedis($signalId) {
    	$data = "";
    	if(RedisIO::exists(self::SET_LIST_KEY.$signalId)) {
    		$data =	RedisIO::get(self::SET_LIST_KEY.$signalId);
    	}
    	else {
    		$data = Signals::saveTVJsonToRedis($signalId);
    	}
    	return $data;
    }
    
    
    /**
     * 拆分保存到数据表中
     * @param unknown $traditionTV
     * @param unknown $live_id
     * @return boolean
     */
    public function saveSignalsTV($traditionTV, $live_id) {
    	$temp = true;
    	if(!empty($traditionTV) && count($traditionTV) > 0) {
    		foreach ($traditionTV as $k => $v) {
    			// 源地址
    			$fatherSource = new SignalSource();
    			if($fatherId = $fatherSource->createFatherData($v, $live_id)) {
    				$datas = $v->data;
    				foreach ($datas as $cdnK => $cdnV) {
    					// cdn回源地址
    					$signalSource = new SignalSource();
    					if($childId = $signalSource->createChildData($cdnV, $fatherId, $live_id)) {
    						// cdn播放地址
    						$signalEpg = new SignalEpg();
    						if($signalEpg->createEpgData($cdnV, $childId, $live_id) == false) {
    							var_dump("1");
    							$temp = false;
    							break;
    						}
    					}else {
    						var_dump("2");
    						$temp = false;
    						break;
    					}
    				} // foreach end
    			}else {
    				var_dump("3");
    				$temp = false;
    				break;
    			}
    		}
    	}
    	return $temp;
    }

    
    /**
     * 新增操作
     * @param unknown $data 
     * @return number
     */
    public function createLivesData($data) {
    	$livesData['channel_id'] = Auth::user()->channel_id;
    	if(!isset($livesData['channel_id'])){
    	    $livesData['channel_id'] = Request::get("channel_id");
        }
    	$top_sort = self::maximum(array("column"=>"sort"));
    	$livesData['keywords'] = $data['keywords'];
    	$livesData['content'] = $data['content'];
    	$livesData['live_type'] = $data['types'];				// 直播流类型(传统电视，传统广播，虚拟直播，活动直播等)
    	$livesData['live_status'] = $data['live_status'];		// 直播状态（开启，禁用，播放结束，直播中）
    	$livesData['sort'] = $top_sort+1;
    	$livesData['isfeatured'] = Signals::IS_NOT_BINDING_FEATURES;
    	$livesData['notstarted_img'] = $data['input_file2'];	
    	$livesData['unauthorized_img'] = $data['input_file3']; 
    	$livesData['complete_img'] = $data['input_file4'];
    	$livesData['buffering_img'] = $data['input_file5'];
    	$livesData['danmu'] = $data['types']== 5 ? 0 :$data['danmu'];					// 弹幕
    	$livesData['firstlook'] = $data['types']== 5 ? 0 :$data['firstlook'];			// 首次观看
    	$livesData['comment_type'] = $data['comment_type'];			// 评论类型
    	$payList = "";
    	if(!empty($data['paylist'])) {
	    	foreach ($data['paylist'] as $k => $payV) {
	    		if($k == count($data['paylist'])-1) {
	    			$payList .= $payV;
	    		} else {
	    			$payList .= $payV.",";
	    		}
	    	}
    	}
    	$livesData['paylist'] = $data['types']== 5 ? "" :$payList;						// 付费码率
    	$livesData['trylook'] = $data['types']== 5 ? 0 :$data['trylook'];				// 试看
    	$signal = new Signals();
    	$id = $signal->doSaveData($livesData);
    	return $id;
    }
	
	public function doSaveData($data, $whiteList=null){
    	return $this->saveGetId($data, $whiteList);
    }
    
    public static function deleteSignals($id) {
    	$res = true;
    	$livesData = self::getOne($id);
    	if(isset($livesData) && !empty($livesData)) {
    		$res = $livesData->delete();
    	}
    	return $res;
    }
    
    /**
     * 保存修改值
     * @param unknown $live_id
     * @param unknown $live_status
     */
    public static function updateLiveData($live_id, $data) {
    	$livesData = self::getOne($live_id);
    	$livesData->channel_id = isset($data['channel_id']) ? $data['channel_id'] : Auth::user()->channel_id;
    	$livesData->keywords = $data['keywords'];
    	$livesData->content = $data['content'];
    	$livesData->live_type = $data['types'];				// 直播流类型(传统电视，传统广播，虚拟直播，活动直播等)
    	$livesData->live_status = $data['live_status'];
    	$livesData->notstarted_img = $data['input_file2'];
    	$livesData->unauthorized_img = $data['input_file3'];
    	$livesData->complete_img = $data['input_file4'];
    	$livesData->buffering_img = $data['input_file5'];
    	$livesData->danmu = $data['types']== 5 ? 0 : $data['danmu'];					// 弹幕
    	$livesData->firstlook = $data['types']== 5 ? 0 : $data['firstlook'];			// 首次观看
    	$livesData->comment_type = $data['comment_type'];			// 评论类型
    	
    	$payList = "";
    	if(!empty($data['paylist'])) {
	    	foreach ($data['paylist'] as $k => $payV) {
	    		if($k == count($data['paylist'])-1) {
	    			$payList .= $payV;
	    		} else {
	    			$payList .= $payV.",";
	    		}
	    	}
    	}
    	$livesData->paylist = $data['types']== 5 ? "" :$payList;					// 付费码率
    	$livesData->trylook = $data['types']== 5 ? 0 :$data['trylook'];				// 试看
    	return $livesData->save() ? true : false;
    }
    
    /**
     * 组装数据
     * 作用： 打包相关数据，嵌套显示
     */
    public static function editShowPackage($signalsId) {
    	
    	$sourceFatherData = SignalSource::getFatherData($signalsId);
    	if(isset($sourceFatherData) && !empty($sourceFatherData)) {
    		$resData = array();
    		foreach ($sourceFatherData as $sVal) {
    			$sourceData = array();
    			$sourceData["sUrl"] = $sVal["url"];
    			$sourceData["sRateId"] = $sVal["rate_id"];
    			$sourceChildData = SignalSource::getChildData($sVal["id"], $signalsId);
    			$childData = array();
    			if(isset($sourceChildData) && !empty($sourceChildData)) {
	    			foreach ($sourceChildData as $cVal) {
	    				$cData = array();    				
	    				$epgData = SignalEpg::getData($cVal["id"]);
	    				$cData["cdnUrl"] = $cVal["url"];
	    				$cData["cdnRateId"] = $cVal["rate_id"];
	    				$cData["epgData"] = $epgData;
	    				array_push($childData, $cData);
	    			}
    			}
    			$sourceData["data"] = $childData;
    			array_push($resData, $sourceData);
    		}
    		return json_encode($resData);
    	} else{
    		return json_encode([]);
    	}
    	
    }
    
    /**
     * 刷新CDN
     */
    public static function refreshCDN($data_id, $channel_id) {
    	$configArr = F::getConfig('domain_config');
    	$url = $configArr['interaction']."/config/signals/".$data_id.".json";
        
        $data = self::sendData($data_id, $url);
        $re = F::cdnProxy("直播流".(string)$data_id, $data_id, $data, $channel_id);
    }
    
    /**
     * 刷新CDN的具体数据
     * @param unknown $staticFiles
     * @return multitype:multitype:number string unknown
     */
    private static function sendData($data_id, $url) {
    	$filepath = $url;
    	$fileType = 4;
    	$data = array(array(
    		"item_id"	   => $data_id,
    		"operation"    => 1,
    		"file_type"    => $fileType,
    		"source_path"  => $filepath,
    		"publish_path" => $filepath,
    		"md5"          => md5($filepath),
    		"file_size"    => "0"
    	),);
    	
    	return $data;
    }
    
    /**
     * 处理图片路径
     * @param unknown $imgPath
     */
    public static function readyThumb(&$imgPath){
    	$thumb_path = self::uploadBase64StreamImg($imgPath);
    	if (empty($thumb_path) && strpos($imgPath,cdn_url("image","")) !== false)
    	{
    		$thumb_path = str_replace(cdn_url("image", ""), "", $imgPath);
    	}
    	$imgPath = $thumb_path;
    }
    
    private static function uploadBase64StreamImg($thumb)
    {
    	$url ="";
    	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $thumb, $files))
    	{
    		$url = Auth::user()->channel_id.'/thumb/'.date('Y/m/d/').md5(uniqid(str_random())).".{$files[2]}";
    		Oss::uploadContent($url,base64_decode(str_replace($files[1], '', $thumb)));
    	}
    	return $url;
    }

    public static function liveSignalRedisKey($rtmp){
        return __FUNCTION__.md5($rtmp);
    }
}