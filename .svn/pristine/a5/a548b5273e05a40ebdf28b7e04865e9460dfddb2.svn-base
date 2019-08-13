<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class SignalPlayurl extends Model {

    const  IS_PUSHINIG = 1;     // 正在推送
    const  IS_NOT_PUSHING = 0;  // 没有推送

    public function getSource() {
        return 'signal_playurl';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'epg_id', 'play_url', 'rate_id', 'is_pushing',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['epg_id', 'play_url', 'rate_id', 'is_pushing',],
            MetaData::MODELS_NOT_NULL => ['id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'epg_id' => Column::TYPE_INTEGER,
                'play_url' => Column::TYPE_VARCHAR,
                'rate_id' => Column::TYPE_INTEGER,
                'is_pushing' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'epg_id', 'rate_id', 'is_pushing',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'epg_id' => Column::BIND_PARAM_INT,
                'play_url' => Column::BIND_PARAM_STR,
                'rate_id' => Column::BIND_PARAM_INT,
                'is_pushing' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'is_pushing' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    
    public function saveData($data) {
        $data['is_pushing'] = self::IS_NOT_PUSHING;
    	$this->assign($data);
    	return $this->save() ? true : false;
    }
    
    public static function deleteData($id) {
    	$res = true;
    	$datas = self::findPlayUrlById($id);
    	if(isset($datas) && !empty($datas)) {
    		foreach ($datas as $v) {
    			$signalPlayUrl = new SignalPlayurl();
    			$res = $signalPlayUrl->delData($v['id']);
    			if($res == false) return $res;
    		}
    	}
    	return $res;
    }
    
    public function delData($id) {
    	$data = self::query()->andwhere("SignalPlayurl.id = {$id}")->first();
    	$res = ($data->delete()) ? true : false;
    	return $res;
    }
    
    public static function findPlayUrlById($epgId){
    	$query = self::query()
    	->columns(array('SignalPlayurl.*'))
    	->andWhere("SignalPlayurl.epg_id={$epgId}")
    	->execute()->toArray();
    	return $query;
    }
    
    public static function findPlayUrlData($epgId) {
    	$data = self::query()
    	->columns(array('SignalPlayurl.*, SignalRates.*'))
    	->leftJoin("SignalRates", "SignalPlayurl.rate_id=SignalRates.id")
    	->where("SignalPlayurl.epg_id={$epgId}")
    	->orderBy("SignalRates.rate_kpbs desc");
    	return $data->execute()->toArray();
    }
    
    /**
     * 组装 url
     * @param unknown $epgId
     * @return multitype:NULL
     */
    public static function getUrlData($epgId) {
    	$resData = array();
    	$data = self::findPlayUrlData($epgId);
    	if(isset($data) && !empty($data)) {
    		foreach ($data as $v) {
    			$key = $v->signalRates->rate_name;
    			$resData[$key] = $v->signalPlayurl->play_url;
    		}
    	}
    	return $resData;
    }

    public function updateData($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }


    public static function findPlayUrlByIdAndUrl($epgId, $playUrl, $static){
        $query = self::query()
            ->andwhere("SignalPlayurl.epg_id={$epgId}")
            ->andwhere("SignalPlayurl.play_url='{$playUrl}'")
            ->andwhere("is_pushing={$static}")
            ->execute()->toArray();
        return $query;
    }

    public static function findPlayUrlByStatic($epg_id, $static) {
        $data = self::query()
            ->andwhere("epg_id={$epg_id}")
            ->andwhere("is_pushing={$static}")
            ->execute()->toArray();
        return $data;
    }

    /**
     * 更新 是否推送 状态
     * @param $epg_id
     * @param $clickStatic
     * @return bool
     */
    public function updateIsPushing($epg_id, $playUrl) {
        $res = false;
        $data = self::findPlayUrlByIdAndUrl($epg_id, $playUrl, self::IS_NOT_PUSHING);
        if(isset($data) && !empty($data)) {
            DB::begin();
            foreach ($data as $v) {
                $v["is_pushing"] = self::IS_PUSHINIG;
                $res = $this->updateData($v);
                if($res == false) {
                    DB::rollback();
                    return $res;
                }
            }
            DB::commit();
        }
        return $res;
    }


    public function updateAllDataStatic($epg_id) {
        $res = false;
        $data = $this->findPlayUrlByStatic($epg_id, self::IS_PUSHINIG);
        if (isset($data) && !empty($data)) {
            DB::begin();
            foreach ($data as $v) {
                $v['is_pushing'] = self::IS_NOT_PUSHING;
                $res = $this->updateData($v);
                if($res == false) {
                    DB::rollback();
                    return $res;
                }
            }
            DB::commit();
        }
        return $res;
    }
}