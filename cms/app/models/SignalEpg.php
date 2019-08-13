<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SignalEpg extends Model {
	const PAGE_SIZE = 50;
    public function getSource() {
        return 'signal_epg';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'lives_id', 'livesource_id', 'vender_id', 'remarks', 'isp2p', 'isdrm', 'drm_id', 'type', 'rate_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['lives_id', 'livesource_id', 'vender_id', 'remarks', 'isp2p', 'isdrm', 'drm_id', 'type', 'rate_id',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'lives_id' => Column::TYPE_INTEGER,
                'livesource_id' => Column::TYPE_INTEGER,
                'vender_id' => Column::TYPE_INTEGER,
                'remarks' => Column::TYPE_TEXT,
                'isp2p' => Column::TYPE_INTEGER,
                'isdrm' => Column::TYPE_INTEGER,
                'drm_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'rate_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'lives_id', 'livesource_id', 'vender_id', 'isp2p', 'isdrm', 'drm_id', 'type', 'rate_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'lives_id' => Column::BIND_PARAM_INT,
                'livesource_id' => Column::BIND_PARAM_INT,
                'vender_id' => Column::BIND_PARAM_INT,
                'remarks' => Column::BIND_PARAM_STR,
                'isp2p' => Column::BIND_PARAM_INT,
                'isdrm' => Column::BIND_PARAM_INT,
                'drm_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'rate_id' => Column::BIND_PARAM_INT,
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

    public function createEpgData($cdnV, $sourceId, $liveId) {
    	$playArr = $cdnV->playArr;
    	$textArr = $cdnV->textArr;
    	$venderArr = $cdnV->vendor_select;
    	$drmArr = $cdnV->passwordArr;
    	$typeArr = $cdnV->type_select;
    	$isDrmArr = $cdnV->encryptArr;
    	$defaultRateArr = $cdnV->default_rate;
    	if(!empty($playArr)) {
	    	foreach ($playArr as $playK => $playV) { 
	    		$playData['lives_id'] = $liveId;
	    		$playData['livesource_id'] = $sourceId;
	    		$playData['vender_id'] = $venderArr[$playK];	// cdn厂家id    		
	    		$playData['remarks'] = $textArr[$playK];		// 备注
	    		$playData['isp2p'] = 1;
	    		$playData['isdrm'] = empty($isDrmArr[$playK]) ? 1 : $isDrmArr[$playK];			// 是否进行加密
	    		$playData['drm_id'] = $drmArr[$playK];			// 防盗链 id
	    		$playData['type'] = $typeArr[$playK];			// 码流类型 m3u8、flv、rtmp
				$playData['rate_id'] = $defaultRateArr[$playK]; // 默认码率
				
				$signalEpg = new SignalEpg();
	    		$id = $signalEpg->doSaveData($playData);
	    		if($id > 0) {
	    			foreach ($playV as $pkay=> $pValue) {
	     				$pData['epg_id'] = $id;
	     				$pData['play_url'] = $pValue->play_url;
	     				$pData['rate_id'] = $pValue->play_rate;
	     				$signalPlayUrl = new SignalPlayurl();
	     				$bRes = $signalPlayUrl->saveData($pData);
	     				if(!$bRes) return false;
	     			}
	    		}
	    	
	    	} // foreach end
	    	return true;
    	}
    	else {
    		return false;
    	}
    }
    
    public function saveData($data) {
    	$this->assign($data);
    	return $this->save() ? true : false;
    }
    
    public function doSaveData($data, $whiteList=null){
    	return $this->saveGetId($data, $whiteList);
    }
    
    /**
     * 删除数据
     * @param unknown $id
     * @param unknown $channel_id
     * @return boolean
     */
	public static function deleteData($id) {
    	$data = self::query()->andwhere('lives_id='. $id)->execute();
    	if(isset($data) && !empty($data)) {
    		$data = $data->toArray();
    		$res = true;
    		foreach ($data as $v) {
    			SignalPlayurl::deleteData($v['id']);
    			$res = self::delData($v['id']);
    			if(!$res) return $res;
    		}
    		return $res;
    	}
    	else {
    		return false;
    	}
    }
    
    public static function delData($id) {
    	$data = self::query()->andwhere('SignalEpg.id='. $id)->first();
    	return ($data->delete()) ? true : false;
    }
    
    /**
     * 获取数据
     * @param unknown $liveSourceId live_source表id
     * @param unknown $singalId 直播id
     */
    public static function getData($liveSourceId) {
    	$query = self::query()
	    	->columns(array('SignalEpg.*'))
	    	->andWhere("SignalEpg.livesource_id={$liveSourceId}")
	    	->execute()->toArray();
    	
    	foreach ($query as $k => $v) {
    		$query[$k]['playurl_data'] = SignalPlayurl::findPlayUrlById($v['id']);
    	}
    	return $query;
    }
    
    /**
     * 
     * @param unknown $liveId
     * @return unknown
     */
    public static function findDataByLivesId($liveId) {
    	$query = self::query()
    	->columns(array('SignalEpg.*'))
    	->andWhere("SignalEpg.lives_id={$liveId}")
    	->groupby("vender_id")
    	->orderBy("id desc")
    	->execute()->toArray();
    	return $query;
    }
    
    public static function findEpgAllData($liveId, $venderId) {
    	$data = self::query()
		    ->columns(array('SignalEpg.*', 'SignalPlayurl.*'))
		    ->leftJoin("SignalPlayurl", "SignalPlayurl.epg_id=SignalEpg.id")
		    ->where("SignalEpg.lives_id={$liveId}")
		    ->andWhere("SignalEpg.vender_id={$venderId}");
		return $data->execute()->toArray();
    }
    
    /**
     * 根据 直播 id获取播放地址
     * @param unknown $liveId
     */
    public static function findEpgAllDatas($liveId) {
    	$data = self::query()
    	->columns(array('SignalPlayurl.play_url','SignalPlayurl.is_pushing'))
    	->leftJoin("SignalPlayurl", "SignalPlayurl.epg_id=SignalEpg.id")
    	->where("SignalEpg.lives_id={$liveId}");
    	return $data->execute()->toArray();
    }
    
    
    public static function getEpgInfo($dataId, $liveId, &$weightData, &$epgDatas) {
    	$venderIdArr = self::findDataByLivesId($liveId);
    	if(isset($venderIdArr) && !empty($venderIdArr)) {
    		$venderIds = "";
    		foreach ($venderIdArr as $epgKey => $epgArr){
    			if(isset($epgArr['vender_id']) && $epgArr['vender_id'] != "" && $epgArr['vender_id'] != null) {
	    			if($epgKey == count($venderIdArr)-1) {
	    				$venderIds .= $epgArr['vender_id'];
	    			} else {
	    				$venderIds .= $epgArr['vender_id'].",";
	    			}
    			}
    			$urlData = array();
    			if (isset($epgArr['id']) && !empty($epgArr['id'])) {
    				$urlData = SignalPlayurl::getUrlData($epgArr['id']);
    			}
    			
    			$typeArr = array(
    				"type" => $epgArr['type']
    			);
    			$signalRates = new SignalRates();
    			$defaultRateArr = array(
    				"defaultrate" => isset($epgArr['rate_id'])&&!empty($epgArr['rate_id']) ? $signalRates->getDefaulteRateName($epgArr['rate_id']) :""
    			); 
    			$p2pArr = array(
    				"isp2p" => intval($epgArr['isp2p'])
    			);
    			$drmArr = array(
    				"drm" => intval($epgArr['isdrm'])	
    			);
    			$channelNameArr = array(
    				"channel_name" => strval($dataId)
    			);
    			$epgData = array();
    			array_push($epgData, $urlData);
    			array_push($epgData, $typeArr);
    			array_push($epgData, $defaultRateArr);
    			array_push($epgData, $p2pArr);
    			array_push($epgData, $drmArr);
    			array_push($epgData, $channelNameArr);
    			$signalVender = new SignalProducer();
    			$venderData = isset($epgArr['vender_id'])&&!empty($epgArr['vender_id']) ? $signalVender->findOne($epgArr['vender_id']) : "";
    			if(isset($venderData) && count($venderData) > 0){
	    			$key = $venderData->vender_code;
	    			$resData = array(
	    				$key => $epgData,
	    			);
	    			array_push($epgDatas, $resData);
    			}
    		}
    		// 厂家权重
    		if($venderIds != "") {
    			$signalVender = new SignalProducer();
	    		$datas = $signalVender->findDataByIds($venderIds);
	    		$weightData = array();
	    		foreach ($datas as $k => $v) {
	    			$key = $v["vender_code"];
	    			$weightData[$key] = intval($v["weight"]);
	    		}
    		}
    	}
    }

    /**
     * @param $live_id
     */
    public function findOneData($live_id) {
        $epgData = self::query()
            ->andCondition("lives_id", $live_id)
            ->first();
        return $epgData;
    }
    
}