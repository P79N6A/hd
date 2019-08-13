<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SignalSource extends Model {
	const PAGE_SIZE = 50;
    public function getSource() {
        return 'signal_source';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'lives_id', 'father_id', 'url', 'rate_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['lives_id', 'father_id', 'url', 'rate_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'lives_id', 'father_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'lives_id' => Column::TYPE_INTEGER,
                'father_id' => Column::TYPE_INTEGER,
                'url' => Column::TYPE_VARCHAR,
                'rate_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'lives_id', 'father_id', 'rate_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'lives_id' => Column::BIND_PARAM_INT,
                'father_id' => Column::BIND_PARAM_INT,
                'url' => Column::BIND_PARAM_STR,
                'rate_id' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'father_id' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    
    /**
     * 存子节点数据
     * @param unknown $cdnV
     * @param unknown $fatherId
     * @param unknown $signalsId
     */
    public function createChildData($cdnV, $fatherId, $signalsId) {
    	$cdnUrl = $cdnV->cdnUrl;
    	$cdnRate = $cdnV->cdn_rate;
    	$liveCDN = new SignalSource();
    	$cdnId = $liveCDN->createSourceData($cdnUrl, $cdnRate, $fatherId, $signalsId);
    	return $cdnId;
    }

    /**
     * 存父节点数据
     * @param unknown $v
     * @param unknown $signalsId
     */
    public function createFatherData($v, $signalsId) {
    	$cdnUrl = $v->url_sourse;
    	$cdnRate = $v->rate;
    	$liveSource = new SignalSource();
    	$sourceId = $liveSource->createSourceData($cdnUrl, $cdnRate, 0, $signalsId);
    	return $sourceId;
    }
    
    /**
     * 存数据
     * @param unknown $cdnUrl
     * @param unknown $cdnRate
     * @param unknown $fatherId
     * @param unknown $signalsId
     */
    public function createSourceData($cdnUrl, $cdnRate, $fatherId, $signalsId) {
    	$cdnData['lives_id'] = $signalsId;				// signals表 id
    	$cdnData['father_id'] = $fatherId;				// 父节点 id
    	$cdnData['url'] = $cdnUrl;						// 流源地址
    	$cdnData['rate_id'] = $cdnRate;					// 码率id
    	$signalSource = new SignalSource();
    	$id = $signalSource->doSaveData($cdnData);
    	return $id;
    }
    
    public function doSaveData($data, $whiteList=null){
    	return $this->saveGetId($data, $whiteList);
    }
    
    /**
     * 获取子节点数据
     * @param unknown $fatherId
     * @param unknown $signalsId
     */
    public static function getChildData($fatherId, $signalsId) {
    	return self::getData($fatherId, $signalsId)->toArray();
    }
    
    /**
     * 获取父节点数据
     * @param unknown $signalsId
     */
    public static function getFatherData($signalsId) {
       return self::getData(0, $signalsId)->toArray();
    }
    
    /**
     * 获取数据
     * @param unknown $fatherId  父节点id
     * @param unknown $signalsId 直播id
     */
    public static function getData($fatherId, $signalsId) {
    	$query = self::query()
    		->columns(array('SignalSource.*'))
		   	->andWhere("SignalSource.father_id={$fatherId}")
		   	->andWhere("SignalSource.lives_id={$signalsId}")
		   	->execute();
    	return $query;
    }

    /**
     * 获取源流地址， cdn回源地址
     * @param $liveId
     * @param $type
     */
    public static function findSignalSourceUrl($liveId, $type) {
        $data = self::query()
            ->columns(array('SignalSource.url'))
            ->where("SignalSource.lives_id={$liveId}");
        if($type == "source") {
            $data = $data->andWhere("SignalSource.father_id = 0");
        }
        else if($type == "rollback") {
            $data = $data->andWhere("SignalSource.father_id <> 0");
        }
        return $data->execute()->toArray();
    }
    
    public static function deleteAllData($signalsId) {
    	$bRes = false;
    	$fatherId = 0;
    	$fatherIdArr = self::getData($fatherId, $signalsId);
    	if(!empty($fatherIdArr) && count($fatherIdArr) > 0) {
    		//DB::begin();
    		foreach ($fatherIdArr as $v) {
    			$childData = self::getData($v->id, $signalsId);
    			if(!empty($childData) && count($childData) > 0) {
    				// foreach ($childData as $cV) {
    					$bRes = SignalEpg::deleteData($signalsId);
    					var_dump("1: ".$bRes);
    					if(!$bRes) {
    						//DB::rollback();
    						return $bRes;
    					}
    				//}
    			}
    			$bRes = self::deleteData($v->id, $signalsId);
    			var_dump("2: ".$bRes);
    			if(!$bRes) {
    				//DB::rollback();
    				return $bRes;
    			}
    			$bRes = ($v->delete()) ? true : false;
    			var_dump("3: ".$bRes);
    			if(!$bRes) {
    				//DB::rollback();
    				return $bRes;
    			}
    		}
    		//DB::commit();
    	} else {
    		$bRes = true;
    	}
    	return $bRes;
    }
    
    /**
     * 删除数据
     * @param unknown $fatherId
     * @param unknown $signalsId
     * @return boolean
     */
	public static function deleteData($fatherId, $signalsId) {
		$data = self::query()->andwhere('father_id='. $fatherId)->andwhere('lives_id='. $signalsId)->first();
		return ($data->delete()) ? true : false;
	}    
	
}