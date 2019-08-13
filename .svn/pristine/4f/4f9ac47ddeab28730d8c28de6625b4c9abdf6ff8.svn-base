<?php

/**
 *  直播管理 防盗链配置表
 *  data table signal_drm
 *  @author     cjh
 *  @created    2016-12-22
 *  方法命名规则， find开头：直接操作数据库，进行查询； get开头：对一个或多个find方法操作；
 *  		   add开头：  直接操作数据库，进行增加； create开头：对一个或多个add方法操作；
 *             edit开头：直接操作数据库，进行修改； modify开头：对一个或多个edit方法操作；
 *             delelte开头：直接进行删除操作；
 *  @param id,drm_name,drm_value
 *    
 */

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;
class SignalDrm extends RedisModel {
	const PAGE_SIZE = 50;
	
	const SET_SORTED_KEY = "SIGNAL_DRM_SCORE_ID:redis_cache_by_model";
	const SET_HASH_MAP_KEY = "SIGNAL_DRM_HASH_MAP:redis_cache_by_model";
	
    public function getSource() {
        return 'signal_drm';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'drm_name', 'drm_value',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['drm_name', 'drm_value',],
            MetaData::MODELS_NOT_NULL => ['id', 'drm_name', 'drm_value',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'drm_name' => Column::TYPE_VARCHAR,
                'drm_value' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'drm_name' => Column::BIND_PARAM_STR,
                'drm_value' => Column::BIND_PARAM_STR,
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

    //检验表单信息
    public static function makeValidator($inputs){
    	$validator = Validator::make(
    			$inputs, [
    			'drm_name' => 'required|max:50',
    			'drm_value' => 'required|max:50',
    			], [
    			'drm_name.required' => '请填写防盗链名称',
    			'drm_name.max'=>'防盗链名称过长',
    			'drm_value.required'=>'请填写防盗链',
    			'drm_value.max'=>'防盗链过长',
    			]
    	);
    	return $validator;
    }
    
    public static function searchData($data) {
    	$drmName = $data['title'];
    	$drmValue = $data['value'];
    	
    	$query = self::query();
    	if ($drmName) {
    		$query = $query->orWhere("SignalDrm.drm_name like '%{$drmName}%'");
    	}
    	 
    	if ($drmValue) {
    		$query = $query->orWhere("SignalDrm.drm_value like '%{$drmValue}%'");
    	}
    	$dataValue = $query->execute()->toarray();
    	if(isset($dataValue) && !empty($dataValue)) {
    		$count = count($dataValue);
    		$data = $query->redisPaginate(self::PAGE_SIZE, $count, $dataValue, "Pagination");
    	}
	    else {
	    	$data = $query->redisPaginate(self::PAGE_SIZE, 0, [], "Pagination");
	    }
    	return $data;
    }
    
    public function deleteData($id) {
    	
    	$res = true;
    	$data = $this->getOne($id);
    	if(isset($data) && !empty($data)) {
    		$delCount = RedisIO::hDel(self::SET_HASH_MAP_KEY.$id, 'id', 'drm_name', 'drm_value');
    		$res = $delCount > 0 ? RedisIO::zRem(self::SET_SORTED_KEY, $id) : 0; 
			$res = $res > 0 ? SignalDrm::findFirst($id)->delete() : false;
    	}
    	return $res;
    }
    
    public function modifyData($data) {
    	$res = true;
    	$getData = $this->getOne($data['id']);
    	if(isset($getData) && !empty($getData)) {
    		 $res = $this->updateData($data);
    		 $this->initRedisKey();
    		 $this->addRedisSortedCache($data['id'], $data);
    	}
    	else {
    		$res = $this->createData($data);
    	}
    	return $res;
    }

    public function createData($data) {
    	$id = $this->addData($data);
    	if($id > 0) {
    		$data["id"] = $id;
    		$this->initRedisKey();
    		$this->addRedisSortedCache($id, $data);
    		return true;
    	}
    	else {
    		return false;
    	} 
    }
    
    public function getOne($id) {
    	$this->setHashKey(self::SET_HASH_MAP_KEY);
    	$data = $this->findRedisHashMapCache($id);
    	if(empty($data)) {
    		$data = self::query()
    		->where("SignalDrm.id = {$id}")
    		->first();
    		if(isset($data) && !empty($data)) {
    			$data = $data->toArray();
    		}
    	}
    	return $data;
    }

    public function getAll() {
    	$data = array();
    	$this->initRedisKey();
    	$dataList = $this->findRedisSortedData(0, -1);
    	
    	if(isset($dataList) && !empty($dataList)) {
    		foreach ($dataList as $v) {
    			$value =  $this->findRedisHashMapCache($v);
    			array_push($data, $value);
    		}
    	}
    	else {
	    	$data = self::findAll();
	    	if(isset($data) && !empty($data)) {
	    		foreach ($data as $v) {
	    			$this->addRedisSortedCache($v['id'], $v);
	    		}
	    	}
    	}
    	return $data;
    }
    
    /**
     * 查询所有数据，供类表页展示
     */
    public function findAllData($page) {
    	$count = 0;
    	$this->initRedisKey();
    	$this->setPageSize(self::PAGE_SIZE);
    	
    	$dataList = $this->getRedisSortedCache($page, $count); 
    	$dataValue = array();
    	if(isset($dataList) && !empty($dataList)) {
	    	foreach ($dataList as $list) {
	    		$value =  $this->findRedisHashMapCache($list);
	    		if(empty($value)) {
	    			$this->deleteData($list);
	    		}
	    		else {
	    			array_push($dataValue, $value);
	    		}
	    	}
	    	//$dataValue = $this->getAllDatas($count);
    	}
    	else {
    		$dataValue = $this->getAllDatas($count);
    	}
    	$data = self::query()->redisPaginate(self::PAGE_SIZE, $count, $dataValue, "Pagination");
    	return $data;
    }

    public function getAllDatas(&$count) {
    	$dataValue = self::findAll();
    	if(isset($dataValue) && !empty($dataValue)) {
    		$count = count($dataValue);
    		foreach ($dataValue as $v) {
    			$this->addRedisSortedCache($v['id'], $v);
    		}
    	}
    	return $dataValue;
    }
    
    /**
     * 初始化基类redis 键值
     */
    private function initRedisKey() {
    	$this->setSortedKey(self::SET_SORTED_KEY);
    	$this->setHashKey(self::SET_HASH_MAP_KEY);
    }
}