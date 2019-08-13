<?php
/**
 * redis 基础类
 * @author cjh
 *
 */

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;
use Illuminate\Support\Facades\Redis;

class RedisModel extends Model  {

	private $set_redis_sorted_key;
	private $page_size;
	private $set_hash_map_key;
	
	public function setSortedKey($key) {
		$this->set_redis_sorted_key = $key;
	}
	public function setPageSize($size) {
		$this->page_size = $size;
	}
	public function setHashKey($key) {
		$this->set_hash_map_key = $key;
	}
	
	public function getRedisSortedCache($page, &$count) {
		//$count = RedisIO::zCard(self::SET_SORTED_KEY);
		$redisModel = new RedisModel();
		$count = RedisIO::zSize($this->set_redis_sorted_key);
		$pageSize = ceil($count / $this->page_size);
		
		if($count < $pageSize) {
			$count = $pageSize;
		}
		if($page == 1) {
			$beginPage = 0;
			$endPage = $this->page_size-1;
		}
		else if($page > 1) {
			$beginPage = ($page-1) * $this->page_size;
			$endPage = $page * $this->page_size-1;
		}
	
		$arrScore = self::findRedisSortedData($beginPage, $endPage);
		return $arrScore;
	}
	
	public function addRedisSortedCache($id, $data) {
		$saveTime = 60 * 60 * 24 * 30;			// 保存30天		
		RedisIO::zAdd($this->set_redis_sorted_key, $id, $id);
		RedisIO::hMset($this->set_hash_map_key.$id, $data);
		RedisIO::expire($this->set_redis_sorted_key, $saveTime);
		RedisIO::expire($this->set_hash_map_key.$id, $saveTime);
	}
	
	public function findRedisSortedData($start, $end) {
		$arrScore = RedisIO::zRevRange($this->set_redis_sorted_key, $start, $end);
		return $arrScore;
	}
	
	public function findRedisHashMapCache($id) {
		$data = RedisIO::hGetAll($this->set_hash_map_key.$id);
		return $data;
	}

	public function updateData($data) {
		$this->assign($data);
		return ($this->update()) ? true : false;
	}
	
	public function addData($data, $whiteList=null){
		return $this->saveGetId($data, $whiteList);
	}
	
	public static function findAll() {
		$data = self::query()
		->execute()->toarray();
		return $data;
	}
	
	
}