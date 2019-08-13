<?php
/**
 *  电视节目单站点管理
 *  model stationsSet
 * @author     
 * @created    2016-08-31
 *
 * @param 
 * 
 */
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class StationsSet extends Model {
	const PAGE_SIZE = 50;
	
    public function getSource() {
        return 'stations_set';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'vms_siteId', 'station_name', 'station_file', 'update_time' , 'pinyin' , 'bitrate' , 'format' ,'station_guid','live_stream'
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['vms_siteId', 'station_name','station_file', 'update_time', 'pinyin' ,'bitrate' , 'format' , 'station_guid' , 'live_stream' ,],
            MetaData::MODELS_NOT_NULL => ['id', 'vms_siteId', 'station_name', 'station_file', 'update_time', 'pinyin' ,],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'vms_siteId' => Column::TYPE_INTEGER,
                'station_name' => Column::TYPE_VARCHAR,
                'station_file' => Column::TYPE_VARCHAR,
                'update_time'  => Column::TYPE_INTEGER,
                'pinyin' => Column::TYPE_VARCHAR,
                'bitrate' => Column::TYPE_INTEGER, 
                'format' => Column::TYPE_CHAR,
                'station_guid' => Column::TYPE_VARCHAR,
                'live_stream' => Column::TYPE_VARCHAR,
                
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'stationId', 'bitrate',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'vms_siteId' => Column::BIND_PARAM_INT,
                'station_name' => Column::BIND_PARAM_STR,
                'station_file' => Column::BIND_PARAM_STR,
                'update_time' => Column::BIND_PARAM_INT,
                'pinyin' => Column::BIND_PARAM_STR,
                'bitrate' => Column::BIND_PARAM_INT,
                'format' => Column::BIND_PARAM_STR,
                'station_guid' => Column::BIND_PARAM_STR,
                'live_stream' => Column::BIND_PARAM_STR,
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
    public static function checkForm($inputs) {
        $validator = Validator::make(
            $inputs, [
            'vms_siteId' => 'required',
            'station_name' => 'required',
            'station_file' => 'required',
        ], [
                'station_id.required' => '请填写站点ID',
                'station_name.required' => '请填写站点名称',
                'station_file.required' => '请填写站点存放文件夹名称',
            ]
        );
        return $validator;
    }

  	/**
     * 查询所有数据
     * @return unknown
     */
	public static function findAll() {
		$result = self::query()
			->columns(array('StationsSet.*'))
			->orderBy('StationsSet.vms_siteId asc')
			->paginate(self::PAGE_SIZE, 'Pagination');
		return $result;
	}
	
	/**
	 * 增加操作
	 * @param $data 需要保持数据 
	 * @return boolean
	 */
    public function saveSet($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }
	
    /**
     * 保持当前时间，界面填写数据
     * @param $data 需要保持数据
     * @return boolean
     */
    public function saveData(array $inputs) {
    	$messages = [];
    	$validator = StationsSet::checkForm($inputs);
    	if(!$validator->fails()) {
    		$inputs['update_time'] = time();
    		$inputs['pinyin'] = Cutf8py::encode($inputs['station_name']);
    		if($this->saveSet($inputs)) {
    			$messages[] = Lang::_('success');
    		}else {
    			$messages[] = Lang::_('error');
    		}
    	}else {
    		foreach($validator->messages()->all() as $msg){
    			$messages[]=$msg;
    		}
    	}
    	return $messages;
    }
    
    
    /**
     * 获取一条数据
     * @param unknown $id
     * @return 
     */
    public static function getOne($id) {
		return self::findFirst($id);
    }
    
    /**
     * 根据节目但站点ID删除
     * @param unknown $id
     * @return boolean
     */
    public static function deleteData($id) {
    	$firstData = self::findFirst($id);
    	if ($firstData) {
    		return $firstData->delete();
    	} else {
    		return false;
    	}
    }
    
    /**
     * 获取站点
     * @return unknown
     */
    public static function getStationsSet() {
    	$data = self::query()
    	->execute()->toarray();
    	return $data;
    }
    
}