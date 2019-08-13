<?php

/**
 * 
 * @author cjh
 * 
 */
class VmsVideo {
    const SET_VIDEO = 0;							// 发送收录请求
    const GET_VIDEO = 1;							// 获取收录状态
    
    const RATE_STATUS_NEW = 1;						// 新建录像
    const RATE_STATUS_COLLECTION_SUCCESS = 2;		// 收录请求发送成功
    const RATE_STATUS_COLLECTION_FAILED = 3;		// 收录请求发送失败
    const RATE_STATUS_RECORD_SUCCESS = 4;			// 录像请求发送成功
    const RATE_STATUS_RECORD_FAILED = 5;			// 录像请求发送失败
	
    const REDIS_KEY_SET = 'redis_station_program_vms_rough_set';			// 收录请求redis_key
    const REDIS_KEY_SEARCH = 'redis_station_program_vms_rough_search';      // 查询请求redis_key
//    const REDIS_KEY_RECORD_FAILED = 'redis_station_program_vms_rough_failed';      // 收录请求失败redis_key
    
	private $vms_guid;
	private $vms_codec;
	private $vms_bitrate;
	private $vms_seekType;
	private $vms_outfile;
	
	public function initVmsVideo($guid,$bitrate,$outfile,$codec="h264",$seekType="rough") {
		$this->vms_guid = $guid;
		$this->vms_codec = $codec;
		$this->vms_bitrate = $bitrate;
		$this->vms_seekType = $seekType;
		$this->vms_outfile = $outfile;
	}
	
	/**
	 * 收录拆条
	 * @param unknown $uuid
	 * @param unknown $type 请求类型
	 */
	public function sendVmsRough($id,$type,$data) {
		if(!isset($data) && empty($data)){
			$data =	StationsProgram::findSendVmsDataById($id);
		}
		switch ($type) {
			case self::SET_VIDEO:
				$result = $this->setVideoData($data);
				break;
			case self::GET_VIDEO:
				$result = $this->getVideoData($id, $data);
				break;
			default:;
		}
		return $result;
	}
	
	/**
	 * 组装查找状态数据
	 * @param unknown $id
	 * @param unknown $data
	 * @return string
	 */
	private function getVideoData($id, $data) {
		if(is_array($data)) {
			$slicesDataArr = $this->setData($data);					// 数据库数据
			$result = $this->searchStatic($data['id'],$slicesDataArr);
		}else {
			$result = $this->searchStatic($id, $data);				// redis数据
		}
		return $result;
	}
	
	/**
	 * 组装收录数据
	 * @param unknown $data
	 * @return string
	 */
	private function setVideoData($data) {
		if(!is_array($data)) {
			$data = json_decode($data,true);
		}
		$slicesDataArr = $this->setData($data);
		$result = $this->setFileSobeyVideo($data['id'],$slicesDataArr);
		return $result;
	}
	
	/**
	 * 组装json格式
	 * @param unknown $data
	 * @return Ambigous <string, multitype:unknown, multitype:unknown array NULL string >
	 */
	private function setData($data) {
		$format = !empty($data['format']) || $data['format'] != null ? $data['format'] : "flv";
		$uuid = $data['id'];
		$guid = $data['station_guid'];
		$outfile = "/webtv/srcvms/".$data['station_file']."/source/storage/".date("Y/m/d",time())."/".$data['pinyin']."_".$data['id'].".$format";
		$rate = ($data['bitrate'] > 0) ? $data['bitrate']."k" : "1000k";
		$startTime = $this->tzdelta(($data['start_time']) / 1000) * 1000;
		$endTime = $this->tzdelta(($data['end_time']) / 1000) * 1000;
		$this->initVmsVideo($guid, $rate, $outfile);
		$slicesDataArr = array();
		for($i=0; $i < 1; $i++) {
			$slicesData = array(
					"index" => 0,
					"type" => 0,
					"starttime" => 0,//pts
					"endtime" => 0,//pts
					"startGMT" => $startTime,
					"endGMT" => $endTime,
					"audioChannel" => "left",
					"filepath" => "",
			);
			array_push($slicesDataArr, $slicesData);
		}
		$setValue = $this->setDataSobeyVideo($uuid, $slicesDataArr);
		if(isset($setValue) && count($setValue) > 0) {
			$setValue = json_encode($setValue, JSON_UNESCAPED_SLASHES);
		}
		return $setValue;
	}

	/**
	 * 组装发送数据格式
	 * @param unknown $uuid
	 * @param array $data
	 * @return multitype:unknown NULL string
	 */
	private function setDataSobeyVideo($uuid,array $data) {
		$arrData = array(
				'id' => $uuid,
				'guid' => $this->vms_guid,
				'codec' => $this->vms_codec,
				'bitrate' => $this->vms_bitrate,
				'seekType' => $this->vms_seekType,
				'outfile' => $this->vms_outfile,
				'slices' => $data,
		);
		return $arrData;
	}
	
	/**
	 * 收录录像
	 * @param unknown $id	唯一id
	 * @param unknown $data
	 */
	private function setFileSobeyVideo($uuid, $data) {
		// var_dump(json_encode($setValue, JSON_UNESCAPED_SLASHES));
		$resUpdate = "";
		$stationsProgram = new StationsProgram();
		$url = SOBEY_URL_SET_VIDEO;
		$state = VmsVideo::RATE_STATUS_COLLECTION_FAILED;
		$return = F::curlInfoRequestCode($url,'post',$data);
		var_dump($data);
		if($return == '200') {
			// 发送成功
			$state = VmsVideo::RATE_STATUS_COLLECTION_SUCCESS;
			$this->setRedisData(VmsVideo::REDIS_KEY_SEARCH, $uuid, $data);
		}
		$resUpdate = $stationsProgram->updateData($uuid, $state);
		$this->delRedisData(VmsVideo::REDIS_KEY_SET, $uuid);
		return "curl return: ".$return." update send vms video result data ".$resUpdate;
	}
	
	/**
	 * 查询收录状态
	 * @param unknown $id	唯一id
	 * @param unknown $data 
	 */
	private function searchStatic($uuid, $data) {
		$url = SOBEY_URL_GET_VIDEO;
		$return = F::curlRequest($url,'post',$data);
		$resValue = json_decode($return);
		if($resValue->id == $uuid) {
			$resUpdate = 0;
			if($resValue->state == "sussessful") {
				$state = VmsVideo::RATE_STATUS_RECORD_SUCCESS;
				$this->delRedisData(VmsVideo::REDIS_KEY_SEARCH, $uuid);
				$stationsProgram = new StationsProgram();
				$resUpdate = $stationsProgram->updateData($uuid, $state);
			}else if($resValue->state == "failed") {
				$state = VmsVideo::RATE_STATUS_RECORD_FAILED;
				$this->delRedisData(VmsVideo::REDIS_KEY_SEARCH, $uuid);
				$stationsProgram = new StationsProgram();
				$resUpdate = $stationsProgram->updateData($uuid, $state);
			}
			return $return." update search vms video result data ".$resUpdate;
		}else {
			return $return." vms video result data deffient";
		}
	}
	
	/**
	 * 保存数据到redis
	 * @param unknown $key 标识关键字
	 * @param unknown $id  uuid，发送的唯一标识
	 */
	public function setRedisData($key = VmsVideo::REDIS_KEY_SET, $id, $data) {
		// 存redis
		$isSetRedis = RedisIO::hExists($key, $id);
		if($isSetRedis) {
			RedisIO::hDel($key, $id);
		}
		if(is_array($data) && count($data) > 0) {
			$data = json_encode($data, JSON_UNESCAPED_SLASHES);
		}
	 	return RedisIO::hSet($key, $id, $data) ? true : false;
	}
	
	/**
	 * 根据id，删除redis数据
	 * @param unknown $key redis标识
	 * @param unknown $id  hashkey(uuid,唯一标识)
	 */
	public function delRedisData($key = VmsVideo::REDIS_KEY_SET, $id) {
		// 删redis
		$isSetRedis = RedisIO::hExists($key, $id);
		if($isSetRedis) {
			RedisIO::hDel($key, $id);
		}
	}
	
	/**
	 * 根据id, 获取redis数据
	 * @param unknown $key redis标识
	 * @param unknown $id hashkey(uuid,唯一标识)
	 * @return json array 返回数组
	 */
	public function getRedisData($key = VmsVideo::REDIS_KEY_SET, $id) {
		$isSetRedis = RedisIO::hExists($key, $id);
		$data = RedisIO::hGet($key, $id);
		return json_encode($data);
	}
	
	/**
	 * 转换为格兰仕时间戳
	 * @param number $iTime
	 * @return number
	 */
	function tzdelta ( $iTime = 0 ) {
		if ( 0 == $iTime ) {
			$iTime = time();
		}
		$iTime = $iTime-(8*3600);
		return $iTime;
	}
	
}

