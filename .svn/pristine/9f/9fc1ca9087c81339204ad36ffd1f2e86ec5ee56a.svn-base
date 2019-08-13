<?php
/**
 *  直播管理 码率配置表
 *  data table signal_rates
 *  @author     cjh
 *  @created    2016-12-26
 *  方法命名规则， find开头：直接操作数据库，进行查询； get开头：对一个或多个find方法操作；
 *  		   add开头：  直接操作数据库，进行增加； create开头：对一个或多个add方法操作；
 *             edit开头：直接操作数据库，进行修改； modify开头：对一个或多个edit方法操作；
 *             delelte开头：直接进行删除操作；
 *  @param id,rate_type,rate_name,rate_kpbs,rate_weight
 *
 */
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SignalRates extends RedisModel {

	const PAGE_SIZE = 50;
	
	const SET_SORTED_KEY = "SIGNAL_RATES_SCORE_ID:redis_cache_by_model";
	const SET_HASH_MAP_KEY = "SIGNAL_RATES_HASH_MAP:redis_cache_by_model";
	
    public function getSource() {
        return 'signal_rates';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'rate_type', 'rate_name', 'rate_kpbs', 'rate_weight', 'rate_unit',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['rate_type', 'rate_name', 'rate_kpbs', 'rate_weight', 'rate_unit',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'rate_type' => Column::TYPE_INTEGER,
                'rate_name' => Column::TYPE_VARCHAR,
                'rate_kpbs' => Column::TYPE_INTEGER,
                'rate_weight' => Column::TYPE_INTEGER,
                'rate_unit' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'rate_type', 'rate_kpbs', 'rate_weight',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'rate_type' => Column::BIND_PARAM_INT,
                'rate_name' => Column::BIND_PARAM_STR,
                'rate_kpbs' => Column::BIND_PARAM_INT,
                'rate_weight' => Column::BIND_PARAM_INT,
                'rate_unit' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'rate_weight' => '0'
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
    			'rate_name' => 'required|max:50',
    			'rate_kpbs' => 'required|max:10',
    			], [
    			'rate_name.required' => '请填写码率名称',
    			'rate_name.max'=>'码率名称过长',
    			'rate_kpbs.required'=>'请填写码率值',
    			'rate_kpbs.max'=>'码率值过长',
    			]
    	);
    	return $validator;
    }
    
    /**
     * 组装paylist数据, 提供给对外的json接口
     */
    public function getPayList($ids) {
    	$resData = array();
    	$data = $this->findDataByIds($ids);
    	if(isset($data) && !empty($data)) {
			foreach ($data as $v) {
				array_push($resData, $v['rate_name']);
			}
    	}
    	return $resData;
    }
    
    /**
     * 查询多个id 数据
     * @param unknown $ids
     * @return multitype:
     */
    public function findDataByIds($ids) {    	
    	$data = array();
    	if($ids != '') {
    		$ids = str_replace("，",",",$ids);
    		$idArr = explode(',', $ids);
    		if(isset($idArr) && !empty($idArr)) {
    			foreach ($idArr as $id) {
    				$datas = $this->findOne($id);
    				array_push($data, $datas);
    			}
    		}
    		if(empty($data)) {
    			$data = self::query()
    			->where("SignalRates.id in ({$ids})")
    			->orderBy("SignalRates.rate_kpbs desc")
    			->execute()->toarray();
    		}
    	}
    	return $data;
    }
    
    /**
     * 获取默认码率名称
     * @param unknown $id
     * @return multitype:unknown
     */
    public function getDefaulteRateName($id) {
    	$resValue = "";
    	$defaultData = $this->findOne($id);
    	if (isset($defaultData) && !empty($defaultData)) {
    		$resValue = $defaultData['rate_name'];
    	}
    	return $resValue;
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
    			$value =  $this->findRedisHashMapCache($dataList[0]);
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
    		->where("SignalRates.id = {$id}")
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
    	if($data['rate_weight'] == "") {
    		$max = self::maximum(array("column"=>"rate_weight"));
    		$data['rate_weight'] = $max + 1;
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
    		$delCount = RedisIO::hDel(self::SET_HASH_MAP_KEY.$id, 'id', 'rate_type', 'rate_name', 'rate_kpbs', 'rate_weight', 'rate_unit');
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
    	$ratesUnit = $data['unit'];
    	 
    	$query = self::query();
    	if ($ratesName) {
    		$query = $query->orWhere("SignalRates.rate_name like '%{$ratesName}%'");
    	}
    
    	if ($ratesValue) {
    		$query = $query->orWhere("SignalRates.rate_kpbs like '%{$ratesValue}%'");
    	}
    	
    	if ($ratesUnit) {
    		$query = $query->orWhere("SignalRates.rate_unit = '{$ratesUnit}'");
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
