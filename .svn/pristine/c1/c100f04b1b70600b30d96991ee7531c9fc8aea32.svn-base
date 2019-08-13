<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SignalProducer extends RedisModel {
	const PAGE_SIZE = 50;
	
	const SET_SORTED_KEY = "SIGNAL_PRODUCER_SCORE_ID:redis_cache_by_model";
	const SET_HASH_MAP_KEY = "SIGNAL_PRODUCER_HASH_MAP:redis_cache_by_model";
	
    public function getSource() {
        return 'signal_producer';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'vender_name', 'vender_code', 'weight', 'remarks',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['vender_name', 'vender_code', 'weight', 'remarks',],
            MetaData::MODELS_NOT_NULL => ['id', 'vender_name', 'vender_code',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'vender_name' => Column::TYPE_VARCHAR,
                'vender_code' => Column::TYPE_VARCHAR,
                'weight' => Column::TYPE_INTEGER,
                'remarks' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'weight',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'vender_name' => Column::BIND_PARAM_STR,
                'vender_code' => Column::BIND_PARAM_STR,
                'weight' => Column::BIND_PARAM_INT,
                'remarks' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'weight' => '0'
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
    			'vender_code' => 'required|max:50',
    			'vender_code' => 'required|max:10',
    			], [
    			'vender_code.required' => '请填写厂家名称',
    			'vender_code.max'=>'厂家名称过长',
    			'vender_code.required'=>'请填写厂家编码值',
    			'vender_code.max'=>'厂家编码值过长',
    			]
    	);
    	return $validator;
    }
    
    public static function findAll() {
    	$data = self::query()
    	->execute()->toarray();
    	return $data;
    }
    
    /**
     * 根据多个id 查询数据
     * @param unknown $venderIds
     * @return multitype:
     */
    public function findDataByIds($venderIds) {
    	$data = array();
    	if($venderIds != '') {
    		$ids = str_replace("，",",",$venderIds);
    		$idArr = explode(',', $ids);
    		if(isset($idArr) && !empty($idArr)) {
    			foreach ($idArr as $id) {
    				$datas = $this->findOne($id);
    				array_push($data, $datas);
    			}
    		}
    		if(empty($data)) {
		    	$data = self::query()
		    		->where("SignalProducer.id in ({$venderIds})")
		    		->orderBy("SignalProducer.weight desc")
		    		->execute()->toarray();
    		}
    	}
    	return $data;
    }
    
    /**
     * 获取所有的数据
     * @return multitype:
     */
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
    	$this->initRedisKey();
    	$this->setPageSize(self::PAGE_SIZE);
    	 
    	$count = 0;
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
     * 根据id，查询单条数据
     * @param unknown $id
     * @return unknown
     */
    public function findOne($id) {
    	$this->setHashKey(self::SET_HASH_MAP_KEY);
    	$data = $this->findRedisHashMapCache($id);
    	if(empty($data)) {
    		$data = self::query()
    		->where("SignalProducer.id = {$id}")
    		->first();
    		if (isset($data) && !empty($data)) {
    			$data = $data->toArray();
    		}
    	}
    	return $data;
    }
    
    /**
     * 创建数据
     * @param unknown $data 输入的数据
     * @return boolean 是否保存成功
     */
    public function createData($data) {
    	if($data['weight'] == "") {
    		$max = self::maximum(array("column"=>"weight"));
    		$data['weight'] = $max + 1;
    	}
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
    
    /**
     * 删除数据
     * @param unknown $id 唯一id
     * @return boolean
     */
    public function deleteData($id) {
    
    	$res = true;
    	$data = $this->findOne($id);
    	if(isset($data) && !empty($data)) {
    		$delCount = RedisIO::hDel(self::SET_HASH_MAP_KEY.$id, 'id', 'vender_name', 'vender_code', 'weight', 'remarks');
    		$res = $delCount > 0 ? RedisIO::zRem(self::SET_SORTED_KEY, $id) : 0;
    		$res = $res > 0 ? self::findFirst($id)->delete() : false;
    	}
    	return $res;
    }
    
    /**
     * 修改数据
     * @param unknown $data 输入的数据
     * @return boolean
     */
    public function modifyData($data) {
    	$res = true;
    	$getData = $this->findOne($data['id']);
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
    
    /**
     * 搜索数据操作
     * @param unknown $data
     * @return unknown
     */
    public static function searchData($data) {
    	$ratesName = $data['title'];
    	$ratesValue = $data['value'];
    	
    	$query = self::query();
    	if ($ratesName) {
    		$query = $query->orWhere("SignalProducer.vender_name like '%{$ratesName}%'");
    	}
    
    	if ($ratesValue) {
    		$query = $query->orWhere("SignalProducer.vender_code like '%{$ratesValue}%'");
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
    
 	/**
     * 初始化基类redis 键值
     */
    private function initRedisKey() {
    	$this->setSortedKey(self::SET_SORTED_KEY);
    	$this->setHashKey(self::SET_HASH_MAP_KEY);
    }
    
}