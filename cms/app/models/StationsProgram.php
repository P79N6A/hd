<?php
/**
 *  电视节目单管理
 *  model stationsProgram
 * @author     Zhangyichi
 * @created    2015-9-14
 *
 * @param id ,stations_id,title,start,duration,partition_by
 * 
 */
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class StationsProgram extends Model {
	const PAGE_SIZE = 50;
    const PROGRAM_STATUS_NORMAL = 1;
	const PROGRAM_STATUS_HIDE = 4;
	
    public function getSource() {
        return 'stations_program';
    }

    /**
     * 获取某个电台某个日期的节目单
     * @param $stations_id
     * @param $date
     * @return array|mixed
     */
    public static function apiGetProgramById($stations_id, $date) {
        $key = D::memKey('apiGetProgramById', ['id' => $stations_id, 'date' => ($date?:date("Y-m-d"))]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $start = $date ? strtotime($date) : strtotime(date("Y-m-d"));
            $data = self::query()
                ->andCondition('status', self::PROGRAM_STATUS_NORMAL)
                ->andCondition('stations_id', $stations_id)
                ->andCondition('start_date', $start)
                ->orderBy('start_time asc')
                ->execute();
            $data_arr = array();
            if($data){
                $data_arr = $data->toArray();
            }
            $data = $data_arr;
            $time = 1800;
            if($date==date('Y-m-d')){
                $program = self::apiGetNowProgramById($stations_id);
                $time = floor(($program['end_time']-time()*1000)/1000);
            }
            if($time>0) {
                MemcacheIO::set($key, $data, $time);
            }else{
                MemcacheIO::set($key, $data, 1800);
            }
        }
        return $data;
    }

    /**
     * 获取某个电台当前的节目
     * @param $stations_id
     * @return array|mixed
     */
    public static function apiGetNowProgramById($stations_id) {
        $key = D::memKey('apiGetNowProgramById', ['id' => $stations_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('status', self::PROGRAM_STATUS_NORMAL)
                ->andCondition('stations_id', $stations_id)
                ->andCondition('start_time', '<=', time()*1000)
                ->andCondition('end_time', '>=', time()*1000)
                ->first();
            $data_arr = array();
            $time = 300;
            if($data){
                $data_arr = $data->toArray();
                $time = floor(($data_arr['duration']-(time()*1000-$data_arr['start_time']))/1000);
            }
            $data = $data_arr;
            MemcacheIO::set($key, $data, $time);
        }
        return $data;
    }

    /**
     * 获取某个电台接下来的节目
     * @param $stations_id
     * @return array|mixed
     */
    public static function apiGetNextProgramById($stations_id , $number=5) {
        $key = D::memKey('apiGetNextProgramById', ['id' => $stations_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('status', self::PROGRAM_STATUS_NORMAL)
                ->andCondition('stations_id', $stations_id)
                ->andCondition('start_time', '>', time()*1000)
                ->limit($number)
                ->execute();
            $data_arr = array();
            $time = 300;
            if($data){
                $data_arr = $data->toArray();
                $time = floor(($data_arr[0]['start_time']-time()*1000)/1000);
            }
            if ($time<=0) $time = 300;
            $data = $data_arr;
            MemcacheIO::set($key, $data, $time);
        }
        return $data;
    }

    /**
     * 获取某个节目
     * @param $stations_id
     * @return array|mixed
     */
    public static function apiGetProgramByProgramId($program_id) {
        $key = D::memKey('apiGetProgramByProgramId', ['id' => $program_id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $data = self::query()
                ->andCondition('id', $program_id)
                ->andCondition('start_time', '>', time()*1000)
                ->andCondition('allow_order',1)
                ->first();
            $data_arr = array();
            $time = 300;
            if($data){
                $data_arr = $data->toArray();
                $time = floor(($data_arr['start_time']-time()*1000)/1000);
            }
            $data = $data_arr;
            MemcacheIO::set($key, $data, $time);
        }
        return $data;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'stations_id', 'title', 'start_time', 'end_time', 'start_date', 'duration', 'status', 'replay', 'allow_order', 'rate', 'format', 'type', 'partition_by', 'rate_status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['stations_id', 'title', 'start_time', 'end_time', 'start_date', 'duration', 'status', 'replay', 'allow_order', 'rate', 'format', 'type', 'rate_status',],
            MetaData::MODELS_NOT_NULL => ['id', 'stations_id', 'title', 'start_time', 'end_time', 'start_date', 'duration', 'status', 'replay', 'allow_order', 'rate', 'format', 'type', 'partition_by', 'rate_status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'stations_id' => Column::TYPE_INTEGER,
                'title' => Column::TYPE_VARCHAR,
                'start_time' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'start_date' => Column::TYPE_INTEGER,
                'duration' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'replay' => Column::TYPE_INTEGER,
                'allow_order' => Column::TYPE_INTEGER,
                'rate' => Column::TYPE_VARCHAR,
                'format' => Column::TYPE_VARCHAR,
                'type' => Column::TYPE_INTEGER,
                'partition_by' => Column::TYPE_INTEGER,
                'rate_status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'stations_id', 'start_time', 'end_time', 'start_date', 'duration', 'status', 'replay', 'allow_order', 'type', 'partition_by', 'rate_status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'stations_id' => Column::BIND_PARAM_INT,
                'title' => Column::BIND_PARAM_STR,
                'start_time' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'start_date' => Column::BIND_PARAM_INT,
                'duration' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'replay' => Column::BIND_PARAM_INT,
                'allow_order' => Column::BIND_PARAM_INT,
                'rate' => Column::BIND_PARAM_STR,
                'format' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_INT,
                'partition_by' => Column::BIND_PARAM_INT,
                'rate_status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'title' => '',
                'start_time' => '0',
                'end_time' => '0',
                'start_date' => '0',
                'duration' => '0',
                'replay' => '1',
                'allow_order' => '2',
                'rate' => '',
                'format' => '',
                'type' => '0',
                'rate_status' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    
    //查找操作
    //获取所有节目单
    public static function getStationsProgram() {
    	$date = date('y-m-d ',time());

    	$start = $date? strtotime($date): strtotime(date("Y-m-d"));
    	$end = $start + 24 * 60 * 60;


        $data = Stations::query()
        	->columns(array('Stations.name','StationsProgram.id','StationsProgram.stations_id','StationsProgram.title','StationsProgram.duration'
        			,'StationsProgram.start_time','StationsProgram.start_date','StationsProgram.end_time','StationsProgram.entire'
        			,'StationsProgram.tear','StationsProgram.records','StationsProgram.partition_by','StationsProgram.is_rate'))
            ->leftjoin("StationsProgram","Stations.id=StationsProgram.stations_id")
//     		$data = StationsProgram::query()
//     		->columns(array('StationsProgram.id','StationsProgram.stations_id','StationsProgram.title','StationsProgram.duration'
//          			,'StationsProgram.start_time','StationsProgram.start_date','StationsProgram.end_time','StationsProgram.entire'
//          			,'StationsProgram.tear','StationsProgram.records','StationsProgram.partition_by'))
//            ->andwhere("StationsProgram.stations_id !='null'")
            ->andwhere("StationsProgram.start_date ='{$start}'")
            ->order('StationsProgram.start_date desc')->paginate(self::PAGE_SIZE, 'Pagination');
        return $data;
    }

    //获取指定电台节目单，默认当天
    public static function getStationsProgramByStations($stations_id , $date = null) {
        $timestamp = strtotime($date);
        if (!$timestamp){
            $timestamp = strtotime(date('Y-m-d',time()));
        }else{
            $timestamp = strtotime(date('Y-m-d',$timestamp));
        }
        $data = self::query()
            ->andCondition('stations_id',$stations_id)
            ->andCondition('start_date',$timestamp)
            ->orderBy('start_time asc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
        return $data;
    }

    public static function getProgramByDuration($stations_id , $duration , $date = null) {
        $timestamp = strtotime($date);
        if (!$timestamp){
            $timestamp = strtotime(date('Y-m-d',time()));
        }else{
            $timestamp = strtotime(date('Y-m-d',$timestamp));
        }
        $data = self::query()
            ->andCondition('stations_id',$stations_id)
            ->andCondition('start_date',$timestamp)
            ->andwhere('duration <= '.$duration)
            ->orderBy('start_time asc')
            ->execute();
        if($data){
            return $data;
        }
        return false;
    }

    public static function getProgramByTitle($stations_id , $title , $date = null) {
        $timestamp = strtotime($date);
        if (!$timestamp){
            $timestamp = strtotime(date('Y-m-d',time()));
        }else{
            $timestamp = strtotime(date('Y-m-d',$timestamp));
        }
        $data = self::query()
            ->andCondition('stations_id',$stations_id)
            ->andCondition('start_date',$timestamp)
            ->andwhere("title like '%{$title}%'")
            ->orderBy('start_time asc')
            ->execute();
        if($data){
            return $data;
        }
        return false;
    }

    /**
     * 根据开始时间返回下一个节目的开始时间(毫秒数)
     * @param $start_time
     * @return mixed
     * @auth zhangyichi
     */
    public static function getProgramEndTime($stations_id,$start_time,$program_id=0){
        $query = self::query()
            ->andCondition('stations_id',$stations_id)
            ->andCondition('start_date','<=',strtotime(date('Y-m-d',floor($start_time/1000)))+7*24*60*60)
            ->andCondition('start_time','>',$start_time);
        if($program_id) {
            $query = $query->andwhere('id != ' . $program_id);
        }
        $data = $query->orderBy('start_time asc')
            ->first();
        if($data){
            return $data->start_time;
        }else{
            return strtotime(date('Y-m-d 24:00:00',floor($start_time/1000)))*1000;//当天24点
        }
    }

    /**
     * 插入新节目单时，将前一个节目时间变短
     * @param $start_time
     * @return bool
     */
    public static function beforeSaveProgram($stations_id,$start_time,$program_id=0){
        $query = self::query()
            ->andCondition('stations_id',$stations_id)
            ->andCondition('start_date','>=',strtotime(date('Y-m-d',floor($start_time/1000)))-7*24*60*60)
            ->andCondition('start_time','<',$start_time);
        if($program_id) {
            $query = $query->andCondition('id', '<>' , $program_id);
        }
        $data = $query->orderBy('start_time desc')
            ->first();
        if($data) {
            $data->duration = $start_time - $data->start_time;
            $data->end_time = $start_time;
            $data->update();
        }
        return true;
    }

    /**
     * 修改之前将前一个节目单的时间延长到此节目的结束时间
     * @param $start_time
     * @param $end_time
     * @return bool
     */
    public static function beforeModifyProgram($stations_id,$start_time,$end_time){
        $data = self::query()
            ->andCondition('stations_id',$stations_id)
            ->andCondition('end_time',$start_time)
            ->first();
        if($data) {
            $data->duration = $end_time - $data->start_time;
            $data->end_time = $end_time;
            $data->update();
        }
        return true;
    }

    /**
     * 当开始时间相同时，修改原有节目
     * @param $data
     * @return bool
     * @auther zhangyichi
     */
    public function createProgram($data){
        $return = $this->findProgramByStartTime($data);
        if ($return) {
            $return->assign($data);
            return ($return->update()) ? true : false;
        }else {
            $this->assign($data);
            return ($this->save()) ? true : false;
        }
    }

    public static function findProgramByStartTime($data){
        $program = self::query()
            ->andCondition('stations_id',$data['stations_id'])
            ->andCondition('start_time',$data['start_time'])
            ->first();
        return $program;
    }
    
    /**
     * 搜索
     * @param unknown $data
     * @return unknown
     */
    public static function search($data) {
    	$date = date('y-m-d ',time());
    	$start = $date? strtotime($date): strtotime(date("Y-m-d"));
    	$end = $start + 24 * 60 * 60;
    
    	if( $data['created_at_from'] != '' && array_key_exists('created_at_from',$data) ) {
    		$start = strtotime($data['created_at_from']);
    	}
    	
    	if($data['created_at_to'] != '' && array_key_exists('created_at_to',$data) ) {
    		$end = strtotime($data['created_at_to']);
    	}
    	
    	$queryData = self::query();
    		
//     	$queryData = StationsProgram::query()
//     		->columns(array('StationsProgram.id','StationsProgram.stations_id','StationsProgram.title','StationsProgram.duration'
// 			,'StationsProgram.start_time','StationsProgram.start_date','StationsProgram.end_time','StationsProgram.entire'
//     	    ,'StationsProgram.tear','StationsProgram.records','StationsProgram.partition_by'));
			
	    	if($data['id'] != '' ) {
	    		$stationId = $data['id'];
	    		$queryData = $queryData->andwhere("StationsProgram.stations_id ='{$stationId}'");
	    	}
	    	
	    	if($data['keyword'] != '' ) {
	    		$titleValue = $data['keyword'];
    			$queryData = $queryData->andwhere("StationsProgram.title like '%{$titleValue}%'");
	    	}
	    	
	    	if($start < $end) {
    			$queryData = $queryData->andwhere("StationsProgram.start_date >='{$start}'");
    			$queryData = $queryData->andwhere("StationsProgram.start_date <='{$end}'");
	    	}
	    	
    		
    		$queryData = $queryData->order('StationsProgram.start_time asc')->paginate(self::PAGE_SIZE, 'Pagination');

    	return $queryData;
    		
    	
    }
    
    //获取指定电视台的所有节目单
    public static function getStationsProgramByChannelId($stations_id, $partition_by) {
        $data = self::query()
            ->where("stations_id = {$stations_id} AND partition_by={$partition_by}")
            ->execute();
        return $data;
    }

    //指定节目单ID查找
    public static function getStationsProgramById($id) {
        return self::findFirst($id);
    }

    //增加操作
    //节目单对象创建信息
    public function createStationsProgram($data) {
        $this->assign($data);
        return ($this->save()) ? true : false;
    }

    //删除操作
    //根据节目单ID删除
    public static function deleteStationsProgram($id) {
        $stationsprogram = StationsProgram::findFirst($id);
        if ($stationsprogram) {
            self::beforeModifyProgram($stationsprogram->stations_id,$stationsprogram->start_time, $stationsprogram->end_time);
            return $stationsprogram->delete();
        } else {
            return false;
        }
    }

    //修改操作
    //节目单对象修改信息
    public function modifyStationsProgram($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    //检验表单信息
    public static function checkForm($inputs) {
        $validator = Validator::make(
            $inputs, [
            'stations_id' => 'required',
            'title' => 'required',
            'start_time' => 'required',
        	'end_time' => 'required',
            'duration' => 'required',
        	'rate' => 'required',
        	'format' => 'required',

        ], [
                'stations_id.required' => '请填写频道ID',
                'title.required' => '请填写标题',
                'start_time.required' => '请填写开始时间',
        		'end_time.required' => '请填写结束时间',
        		'duration.required' => '请填写时长',
        		'rate.required' => '请选择码率',
        		'format.required' => '请选择格式',
            ]
        );
        return $validator;
    }

    /**
     * 通过频道代码获取节目单
     *
     * @param $channel_id
     * @param $main
     * @param string $type
     * @return bool
     */
    public static function tplByStationCode($channel_id, $main, $type = 'code') {
        if (!in_array($type, ['code', 'id'])) {
            throw new \Phalcon\Mvc\Model\Exception('Invalid Epg main type ' . $type);
        }
        $key = D::memKey('tplPrograms', ['c' => $channel_id, 'm' => $main, 't' => $type]);
        return MemcacheIO::snippet($key, 1800, function () use ($channel_id, $main, $type) {
            $station = Stations::tplOne($channel_id, $main, $type);
            if ($station) {
                $rs = self::query()
                    ->andCondition('stations_id', $station['id'])
                    ->execute()
                    ->toArray();
                $station['programs'] = $rs;
            }
            return $station;
        });
    }
    
    public static function findSendData($status) {
    	$time = time()*1000;
    	$query = self::query()
    	->columns(array('StationsProgram.id','StationsSet.station_guid','StationsSet.bitrate','StationsSet.pinyin','StationsSet.station_file'
    			,'StationsSet.format','StationsProgram.start_date','StationsProgram.start_time','StationsProgram.end_time'
    			,'StationsProgram.rate_status'))
    			->leftjoin("Stations","StationsProgram.stations_id = Stations.id")
    			->leftjoin("StationsSet","Stations.vms_id = StationsSet.id")
    	->where("StationsProgram.rate_status = {$status}")
    	->andWhere("StationsProgram.end_time < {$time}");
    	return $query->execute()->toArray();
    }
    
    /**
     * 查询需要发送到索贝vms的数据
     * @param unknown $id
     */
    public static function findSendVmsDataById($id) {
    	$dateValue = strtotime(date('Y-m-d',time()));
    	$timeValue = strtotime(date('H:i:s',time()));
    	$query = self::query()
    	->columns(array('StationsProgram.id','StationsSet.station_guid','StationsSet.bitrate','StationsSet.pinyin','StationsSet.station_file'
    			,'StationsSet.format','StationsProgram.start_date','StationsProgram.start_time','StationsProgram.end_time'
    			,'StationsProgram.rate_status'))
    	->leftjoin("Stations","StationsProgram.stations_id = Stations.id")
    	->leftjoin("StationsSet","Stations.vms_id = StationsSet.id")
    	->where("StationsProgram.id = {$id}");
    	return $query->first()->toArray();
    }
    
    /**
     * 更新发送后状态
     * @param unknown $id
     * @param unknown $rateStatus
     * @return boolean
     */
    public function updateData($uuid, $rateStatus) {
    	$queryData = self::query()
    	->where("StationsProgram.id = {$uuid}")
    	->first()
    	->toarray();
    	if(isset($queryData) && count($queryData)) {
    		$queryData['rate_status'] = $rateStatus;
    		return $this->modifyStationsProgram($queryData);
    	}
    }
    
    /**
     * 根据id获取节目单
     * @param unknown $id
     */
    public static function findDataById($id) {
    	$query = self::query()
    	->columns(array('StationsProgram.id','StationsProgram.start_date','StationsProgram.title','Stations.code','Stations.share_url'))
    	->leftjoin("Stations","StationsProgram.stations_id = Stations.id")
    	->where("StationsProgram.id = {$id}");
    	return $query->first();
    }
    
    //属性获取
    public function getId() {
    	return $this->id;
    }
    
    public function getChannelId() {
    	return $this->channel_id;
    }
    
    public function getTitle() {
    	return $this->title;
    }
    
    public function getStartTime() {
    	return $this->start_time;
    }
    
    public function getStartDate() {
    	return $this->start_date;
    }
    
    public function getDuration() {
    	return $this->duration;
    }
    
    public function getEntire() {
    	return $this->entire;
    }
    
    public function getTear() {
    	return $this->tear;
    }
    
    public function getRecords() {
    	return $this->records;
    }
    
    public function getPartitionBy() {
    	return $this->partition_by;
    }
    
}