<?php
use Illuminate\Support\Facades\Redis;
/**
 *  电视节目单管理
 *  controller stationsProgram
 *  @author     Zhangyichi
 *  @created    2015-9-14
 *
 *  @param id,stations_id,title,start,duration,partition_by
 */


class StationsProgramController extends \BackendBaseController {

    private $program_1604 = array(
        array("programtitle"=>"私家车 夜精彩", "playtime"=>"00:00:00"),
        array("programtitle"=>"私家车 夜精彩", "playtime"=>"01:00:00"),
        array("programtitle"=>"私家车 夜精彩", "playtime"=>"02:00:00"),
        array("programtitle"=>"私家车 夜精彩", "playtime"=>"04:00:00"),
        array("programtitle"=>"107健康会所 晓凡", "playtime"=>"05:00:00"),
        array("programtitle"=>"报刊选读 铁男", "playtime"=>"06:00:00"),
        array("programtitle"=>"私家车上班路上 袁逸", "playtime"=>"07:00:00"),
        array("programtitle"=>"私家车上班路上 袁逸", "playtime"=>"08:00:00"),
        array("programtitle"=>"私家车上班路上 袁逸", "playtime"=>"09:00:00"),
        array("programtitle"=>"车房时代 常红", "playtime"=>"10:00:00"),
        array("programtitle"=>"车房时代 常红", "playtime"=>"11:00:00"),
        array("programtitle"=>"车房时代 常红", "playtime"=>"12:00:00"),
        array("programtitle"=>"心花路放  高雯", "playtime"=>"13:00:00"),
        array("programtitle"=>"心花路放  高雯", "playtime"=>"14:00:00"),
        array("programtitle"=>"超能娱乐圈  徐伟", "playtime"=>"15:00:00"),
        array("programtitle"=>"超能娱乐圈  徐伟", "playtime"=>"16:00:00"),
        array("programtitle"=>"私家车下班路上  田雪", "playtime"=>"17:00:00"),
        array("programtitle"=>"私家车下班路上  田雪", "playtime"=>"18:00:00"),
        array("programtitle"=>"跑神计划  刘少聪 阳阳", "playtime"=>"19:00:00"),
        array("programtitle"=>"跑神计划  刘少聪 阳阳", "playtime"=>"20:00:00"),
        array("programtitle"=>"私家车新闻夜高峰", "playtime"=>"20:30:00"),
        array("programtitle"=>"火星人音乐  刘少聪 阳阳", "playtime"=>"21:00:00"),
        array("programtitle"=>"超级火星人 雪峰", "playtime"=>"22:00:00"),
        array("programtitle"=>"清华听演讲", "playtime"=>"23:00:00"),
    );

    private $program_1608 = array(
        array("programtitle"=>"night app2", "playtime"=>"00:00:00"),
        array("programtitle"=>"白天节目精编1", "playtime"=>"02:00:00"),
        array("programtitle"=>"白天节目精编2", "playtime"=>"04:00:00"),
        array("programtitle"=>"城市morning call", "playtime"=>"06:00:00"),
        array("programtitle"=>"刷新早高峰", "playtime"=>"07:00:00"),
        array("programtitle"=>"八点到十点(六、日)", "playtime"=>"08:00:00"),
        array("programtitle"=>"财经app", "playtime"=>"09:00:00"),
        array("programtitle"=>"十点到十二点(六、日)", "playtime"=>"10:00:00"),
        array("programtitle"=>"贵妃格格驾到", "playtime"=>"11:00:00"),
        array("programtitle"=>"十二点到十四点(六、日)", "playtime"=>"12:00:00"),
        array("programtitle"=>"i 互联", "playtime"=>"13:00:00"),
        array("programtitle"=>"@超链接", "playtime"=>"14:00:00"),
        array("programtitle"=>"齐心协力晚高峰", "playtime"=>"16:00:00"),
        array("programtitle"=>"十八点到二十点(六、日)", "playtime"=>"18:00:00"),
        array("programtitle"=>"i news", "playtime"=>"19:00:00"),
        array("programtitle"=>"二十点到二十三点(六、日)", "playtime"=>"20:00:00"),
        array("programtitle"=>"贾如时段", "playtime"=>"21:00:00"),
        array("programtitle"=>"崔麒时段", "playtime"=>"22:00:00"),
        array("programtitle"=>"night app1", "playtime"=>"23:00:00"),
    );

    public function initialize() {
        parent::initialize();
        require_once APP_PATH . 'libraries/Excel/PHPExcel.php';
    }
	

	/**
	 * 根据上级ID获取下级队列
	 */
	public function listAction() {
		$id = Request::getPost('id','int');
		if(!$id){
			$data=Stations::getStations();
			echo json_encode($data);
			exit;
		}
		
	}

	/**
	 * 页面加载
	 */
    public function indexAction() {
    	$stations_id = Request::getQuery('id');
        $date = Request::getQuery('date');
        if(!$date){
            $date = date('Y-m-d',time());
        }
        $data= StationsProgram::getStationsProgramByStations($stations_id , $date);
        View::setVars(compact('data','date'));
    }

    /**
     * 新增
     */
    public function createAction() {
        $messages = [];
        if (Request::isPost()) {
        	$msg = true;				// 存数据表返回值，true，false
        	$temp = false;				// 标示符，判断页面是否选择了星期
            $inputs=Request::getPost();

            if(isset($inputs['stations_id'])) {
                if($inputs['start_time']) {
                    $inputs['title'] = $inputs['title'] ?: '';
                    $inputs['start_date'] = strtotime(date('Y-m-d', strtotime($inputs['start_time'])));
                    $inputs['start_time'] = strtotime($inputs['start_time']) * 1000 ?: 0;// 时间格式转换
                    $inputs['status'] = 1;
                    $inputs['replay'] = $inputs['replay'] ?: 1;
                    $inputs['allow_order'] = $inputs['allow_order'] ?: 1;
                    $inputs['rate'] = '';
                    $inputs['format'] = '';
                    $inputs['type'] = 0;
                    $inputs['partition_by'] = date('Y');
                    $inputs['rate_status'] = 0;
                    $inputs['end_time'] = StationsProgram::getProgramEndTime($inputs['stations_id'], $inputs['start_time']) ?: 0;
                    $inputs['duration'] = $inputs['end_time'] - $inputs['start_time'];

                    DB::begin();
                    StationsProgram::beforeSaveProgram($inputs['stations_id'], $inputs['start_time']);
                    $program = new StationsProgram();
                    $return = $program->createProgram($inputs);
                    if (!$return) {
                        DB::rollback();
                        $messages[] = Lang::_('保存不成功');
                    }
                    self::deleteMemcache($inputs['stations_id'], date('Y-m-d', $inputs['start_date']));
                    DB::commit();
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('开始时间必须填写');
                }
            }else{
                $messages[] = Lang::_('电台id不存在');
            }


        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }
    
    /**
     *  保存到数据表
     * @param unknown $inputs
     * @return boolean
     */
    private  function createStationProgramValue($inputs) {
    	
    	$validator=StationsProgram::checkForm($inputs);
    	if($validator->passes()) {
    		$inputs['partition_by']=date_format(date_create(),"Y");//加入年分区
    		$stationsprogram=new StationsProgram();
	    	if($stationsprogram->createStationsProgram($inputs)) {
	    		return true;
	    	}else{
	    		return false;
	    	}
    	}else {
    		foreach($validator->messages()->all() as $msg) {
    			$messages[]=$msg;
    		}
    	}
    }

    /**
     * 回放
     */
    public function replayAction(){
        $id = Request::get('id');

        if (empty($id) || null == $id) {
            $id=$this->request->getQuery("id","int");
            if(empty($id) ){
                $this->_json([], 404, D::apiError(4001));
            }
        }

        $data = StationsProgram::getStationsProgramById($id);
        if($data->replay==1){
            $data->replay=2;
        }else{
            $data->replay=1;
        }
        $return = $data->update();
        if($return){
            $arr=array('code'=>200);
            self::deleteMemcache($data->stations_id,date('Y-m-d',$data->start_date));
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 预约
     */
    public function orderAction(){
        $id = Request::get('id');

        if (empty($id) || null == $id) {
            $id=$this->request->getQuery("id","int");
            if(empty($id) ){
                $this->_json([], 404, D::apiError(4001));
            }
        }

        $data = StationsProgram::getStationsProgramById($id);
        if($data->allow_order==1){
            $data->allow_order=2;
        }else{
            $data->allow_order=1;
        }
        $return = $data->update();
        if($return){
            $arr=array('code'=>200);
            self::deleteMemcache($data->stations_id,date('Y-m-d',$data->start_date));
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 删除
     */
    public function deleteAction(){
        
    	$id = Request::get('id');
        if (empty($id) || null == $id) {
        	$id=$this->request->getQuery("id","int");
        	if(empty($id) ){
        		$this->_json([], 404, D::apiError(4001));
        	}
        }
      	
        if(is_array($id)){	
        	// 批量删除
        	$stationsprogram = StationsProgram::findFirst($id[0]);
        	self::deleteMemcache($stationsprogram->stations_id,date('Y-m-d',$stationsprogram->start_date));
        	foreach ($id as $v) {
        		$return=StationsProgram::deleteStationsProgram($v);
        	}
        	header('Content-type: application/json');
        	$arr=array('msg'=>'success');
        	echo json_encode($arr);
        	exit;
        }else {
        	// 单个删除
        	// $id=$this->request->getQuery("id","int");
            $stationsprogram = StationsProgram::findFirst($id);
        	$return=StationsProgram::deleteStationsProgram($id);//此方法有修改,多一次查询后期优化
        	if($return){
        		$arr=array('code'=>200);
                self::deleteMemcache($stationsprogram->stations_id,date('Y-m-d',$stationsprogram->start_date));
        	}else{
        		$arr=array('msg'=>Lang::_('failed'));
        	}
        	echo json_encode($arr);
        	exit;
        }
    }

    /**
     * 隐藏节目单
     */
    public function hideprogramAction(){

        $id = Request::get('id');
        if (empty($id) || null == $id) {
            $id=$this->request->getQuery("id","int");
            if(empty($id) ){
                $this->_json([], 404, D::apiError(4001));
            }
        }

        if(is_array($id)){
            // 批量处理
            $stationsprogram = StationsProgram::findFirst($id[0]);
            self::deleteMemcache($stationsprogram->stations_id,date('Y-m-d',$stationsprogram->start_date));
            foreach ($id as $v) {
                $return=StationsProgram::deleteStationsProgram($v);
            }
            header('Content-type: application/json');
            $arr=array('msg'=>'success');
            echo json_encode($arr);
            exit;
        }else {
            // 单个处理
            $return = false;
            $stationsprogram = StationsProgram::findFirst($id);
            if ($stationsprogram) {
                $stationsprogram->status = $stationsprogram->status==StationsProgram::PROGRAM_STATUS_NORMAL?StationsProgram::PROGRAM_STATUS_HIDE:StationsProgram::PROGRAM_STATUS_NORMAL;
                $return = $stationsprogram->save();
            }
            if($return){
                $arr=array('code'=>200);
                self::deleteMemcache($stationsprogram->stations_id,date('Y-m-d',$stationsprogram->start_date));
            }else{
                $arr=array('msg'=>Lang::_('failed'));
            }
            echo json_encode($arr);
            exit;
        }
    }
    
    /**
     * 录制入库操作
     */
    public function  rateVideoAction() {
    	$id = $this->request->getQuery("id","int");
    	$sendData = StationsProgram::findSendVmsDataById($id);
    	$arr=array('msg'=>Lang::_('failed'));
    	if($sendData['rate_status'] == 0) {
	    	$result = false;
	    	if(isset($sendData) && count($sendData) > 0) {
	    		$sendData['rate_status'] = VmsVideo::RATE_STATUS_NEW;
	    		$vmsVideo = new VmsVideo();
	    		$result = $vmsVideo->setRedisData(VmsVideo::REDIS_KEY_SET, $id, $sendData);
	    	}
	    	$stationsprogram = new StationsProgram();
	    	$data = $stationsprogram->getStationsProgramById($id);
	    	$data->rate_status = VmsVideo::RATE_STATUS_NEW;
	    	if($result == true && $stationsprogram->modifyStationsProgram($data->toarray())) {
		   		$arr=array('code'=>200);
	        }
    	}
        echo json_encode($arr);
        exit;
    }

    /**
     * 修改
     */
    public function modifyAction(){
        $messages = [];
        $msg = true;				// 存数据表返回值，true，false
        if (Request::isPost()) {
            $inputs=Request::getPost();

            if(isset($inputs['stations_id'])&&isset($inputs['program_id'])) {
                if($inputs['start_time']) {
                    $inputs['title'] = $inputs['title'] ?: '';
                    $inputs['start_date'] = strtotime(date('Y-m-d', strtotime($inputs['start_time'])));
                    $inputs['start_time'] = strtotime($inputs['start_time']) * 1000 ?: 0;// 时间格式转换
                    $inputs['status'] = 1;
                    $inputs['replay'] = $inputs['replay'] ?: 1;
                    $inputs['allow_order'] = $inputs['allow_order'] ?: 1;
                    $inputs['rate'] = '';
                    $inputs['format'] = '';
                    $inputs['type'] = 0;
                    $inputs['partition_by'] = date('Y');
                    $inputs['rate_status'] = 0;

                    $data = StationsProgram::getStationsProgramById($inputs['program_id']);

                    if ($data) {
                        $inputs['end_time'] = StationsProgram::getProgramEndTime($inputs['stations_id'], $inputs['start_time'],$data->id) ?: 0;
                        $inputs['duration'] = $inputs['end_time'] - $inputs['start_time'];

                        DB::begin();
                        StationsProgram::beforeModifyProgram($inputs['stations_id'], $data->start_time, $data->end_time);
                        StationsProgram::beforeSaveProgram($inputs['stations_id'], $inputs['start_time'],$data->id);
                        $return = $data->createProgram($inputs);
                        if (!$return) {
                            DB::rollback();
                            $messages[] = Lang::_('保存不成功');
                        }
                        self::deleteMemcache($inputs['stations_id'], date('Y-m-d', $inputs['start_date']));
                        DB::commit();
                        $messages[] = Lang::_('success');
                    } else {
                        $messages[] = Lang::_('节目单不存在');
                    }
                }else{
                    $messages[] = Lang::_('开始时间必须填写');
                }
            }else{
                $messages[] = Lang::_('电台id不存在');
            }
        }

        $program_id = Request::getQuery('id');
        $data= StationsProgram::getStationsProgramById($program_id);

        View::setMainView('layouts/add');
        View::setVars(compact('messages','data'));
    }

    public function importexcelAction(){
        $messages = [];
        if(Request::hasFiles()){
            $stations_id = Request::get('id');
            if(!$stations_id){
                $messages[] = '电台ID不存在';
                echo json_encode(array('code' => 2004 ,'msg'=>$messages));
                exit;
            }
            if($excel = $this->validateExcel($messages)) {

                $objReader = PHPExcel_IOFactory::createReader('CSV')
                    ->setDelimiter(',')
                    ->setInputEncoding('GBK')
                    ->setEnclosure('"')
                    ->setLineEnding("\r\n")
                    ->setSheetIndex(0);
                $objPHPExcel = $objReader->load($excel->getTempName());

                $objWorksheet = $objPHPExcel->getActiveSheet();
                $highestRow = $objWorksheet->getHighestRow();
                $highestColumn = $objWorksheet->getHighestColumn();
                $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);
                $excelData = array();
                for ($row = 1; $row <= $highestRow; $row++) {
                    for ($col = 0; $col < $highestColumnIndex; $col++) {
                        $excelData[$row][] = (string)$objWorksheet->getCellByColumnAndRow($col, $row)->getValue();
                    }
                }

                foreach ($excelData as $row => $row_arr){
                    if($row==1){continue;}
                    if(count($row_arr)!=5){
                        $messages[] = '文件内格式不正确';
                        echo json_encode(array('code' => 2003 ,'msg'=>$messages));
                        break;
                    }
                    if($row>500){
                        $messages[] = '已保存到第'.($row-1).'行';
                        echo json_encode(array('code' => 2004 ,'msg'=>$messages));
                        exit;
                    }

                    $program_arr = array();
                    $date_str = $row_arr[0].' '.$row_arr[1];
                    if($start_time = strtotime($date_str)){

                        $program_arr['start_time'] = $start_time*1000;
                        $program_arr['title'] = $row_arr[2];

                        $program_arr['stations_id'] = $stations_id;
                        $program_arr['start_date'] = strtotime(date('Y-m-d', $start_time));
                        $program_arr['status'] = 1;
                        $program_arr['replay'] = $row_arr[3]==2 ? 2 : 1;
                        $program_arr['allow_order'] = $row_arr[4]==1 ? 1 : 2;
                        $program_arr['rate'] = '';
                        $program_arr['format'] = '';
                        $program_arr['type'] = 0;
                        $program_arr['partition_by'] = date('Y');
                        $program_arr['rate_status'] = 0;
                        $program_arr['end_time'] = StationsProgram::getProgramEndTime($program_arr['stations_id'], $program_arr['start_time']) ?: 0;
                        $program_arr['duration'] = $program_arr['end_time'] - $program_arr['start_time'];

                        DB::begin();
                        StationsProgram::beforeSaveProgram($program_arr['stations_id'], $program_arr['start_time']);
                        $program = new StationsProgram();
                        $return = $program->createProgram($program_arr);
                        if (!$return) {
                            DB::rollback();
                            $messages[] = Lang::_('保存不成功');
                        }
                        self::deleteMemcache($program_arr['stations_id'], date('Y-m-d', $program_arr['start_date']));
                        DB::commit();

                    }else{
                        $messages[] = '已保存到第'.($row-1).'行，第'.$row.'行日期格式不正确';
                        echo json_encode(array('code' => 2004 ,'msg'=>$messages));
                        exit;
                    }
                }

                echo json_encode(array('code' => 200,'msg'=>Lang::_('success')));

            }else{
                echo json_encode(array('code' => 2002 ,'msg'=>$messages));
            }
        }else {
            $messages[] = '文件不存在';
            echo json_encode(array('code' => 2001,'msg'=>$messages));
        }
        exit;
    }

    public function filterwordAction() {
        View::disable();
        $input = Request::getPost();
        $date = Request::get('date');
        $stations_id = Request::get('id');
        if (!isset($input['filtertype']) || !$input['filtertype']) {
            echo json_encode(array('code' => 2001 ,'msg'=>'必须选择过滤类型'));exit;
        }
        if ($input['filtertype']==1) {
            if ($input['filtertime']) {
                $duration = $input['filtertime']*60*1000;
                $return = StationsProgram::getProgramByDuration($stations_id , $duration , $date);
                foreach ($return as $key => $value){
                    StationsProgram::beforeModifyProgram($value->stations_id,$value->start_time, $value->end_time);
                    $value->delete();
                }
                echo json_encode(array('code' => 200 ,'msg'=>'成功'));exit;
            }else{
                echo json_encode(array('code' => 2003 ,'msg'=>'过滤时间未填写'));exit;
            }
        }elseif ($input['filtertype']==2) {
            if ($input['filterkeywords']) {
                $titles = explode(',',$input['filterkeywords']);
                foreach ($titles as $title) {
                    $return = StationsProgram::getProgramByTitle($stations_id , $title , $date);
                    foreach ($return as $key =>$value){
                        $value->title = str_replace($title , '' , $value->title);
                        $value->update();
                    }
                }
                echo json_encode(array('code' => 200 ,'msg'=>'成功'));exit;
            }else{
                echo json_encode(array('code' => 2003 ,'msg'=>'过滤词未填写'));exit;
            }
        }else{
            echo json_encode(array('code' => 2002 ,'msg'=>'错误的过滤类型'));exit;
        }
    }

    public function interfaceAction() {
        $id = Request::get('id');

        if (empty($id) || null == $id) {
            $id=$this->request->getQuery("id","int");
            if(empty($id) ){
                $this->_json([], 404, D::apiError(4001));
            }
        }

        $station = Stations::getStationsById($id);
        if (!$station) {
            $this->_json([], 404, D::apiError(4001));
        }

        $date = Request::get('date');
        if(!$date){
            $date = date('Y-m-d' , time());
        }
        if($station->code < 1000) {
            $codes = array('1'=>101,'2'=>102,'3'=>103,'4'=>104,'5'=>105,'6'=>106,
                '7'=>107,'8'=>108,'9'=>109,'A'=>110,'C'=>111,'B'=>112);
            $codeid = array_search($station->code,$codes);
            if($codeid){
                $url = 'http://10.30.137.17/epg.php?playDate='.date('Ymd', strtotime($date)).'&channelCode='.$codeid;
                $xmldata = file_get_contents($url);
                $this->dealWithXml($xmldata, $station->id ,$date);
                $url = 'http://10.30.137.17/epg.php?playDate='.date('Ymd', strtotime($date)-24*60*60).'&channelCode='.$codeid;
                $xmldata = file_get_contents($url);
                $this->dealWithXml($xmldata, $station->id ,$date);
            }
        }
        else {
            $codes = array(
                '15'=>1606, //动听968
                '14'=>1605, //交通之声
                '10'=>1601, //浙江之声
                '16'=>1607, //旅游之声
                '28'=>1608, //浙江新闻广播
                '11'=>1602, //财富广播
                '13'=>1604, //城市之声
                '12'=>1603, //民生996
            );
            $codeid = array_search($station->code,$codes);
            $url = 'http://10.1.101.27:8080/zjbd/pb/pbradio.do?channelId='.$codeid.'&playDate='.date('Ymd', strtotime($date));
            $xmldata = file_get_contents($url);
            $this->dealWithRadioXml($xmldata, $station->id, $date, $station->code);
        }

        $arr=array('code'=>200);
        echo json_encode($arr);
        exit;
    }

    protected function deleteMemcache($stations_id, $date){
        $key = D::memKey('apiGetProgramById', ['id' => $stations_id, 'date' => $date]);
        MemcacheIO::delete($key);
        $key = D::memKey('apiGetNowProgramById', ['id' => $stations_id]);
        MemcacheIO::delete($key);
        $key = D::memKey('apiGetNextProgramById', ['id' => $stations_id]);
        MemcacheIO::delete($key);
    }
    private function dealWithXml($xmldata, $stations_id = 0,$date_need = '') {
        $xml = simplexml_load_string($xmldata);
        foreach ($xml->programinfotype->programitemtype as $programrow) {
            $title = (string)$programrow->maininfotype->programname;//标题
            $date = (string)$programrow->maininfotype->date;//日期
            $time = substr((string)$programrow->maininfotype->time, 0, -3);
            $start_time = strtotime($date.' '.$time);//开始时间
            if($title && $date_need==$date) {
                $this->programInsert($title, $date, $start_time, $stations_id);
            }
        }
    }

    private function programInsert($title, $date, $start_time, $stations_id) {
        if($title) {
            $program_arr = array();
            $program_arr['stations_id'] = $stations_id;
            $program_arr['title'] = $title;
            $program_arr['start_time'] = $start_time*1000;
            $program_arr['end_time'] = StationsProgram::getProgramEndTime($program_arr['stations_id'], $program_arr['start_time']) ?: 0;
            $program_arr['start_date'] = strtotime($date);
            $program_arr['duration'] = $program_arr['end_time'] - $program_arr['start_time'];
            $program_arr['status'] = 1;
            $program_arr['replay'] = 1;
            $program_arr['allow_order'] = 1;
            $program_arr['rate'] = '';
            $program_arr['format'] = '';
            $program_arr['type'] = 0;
            $program_arr['partition_by'] = date('Y');
            $program_arr['rate_status'] = 0;
            DB::begin();
            StationsProgram::beforeSaveProgram($program_arr['stations_id'], $program_arr['start_time']);
            $program = new StationsProgram();
            $return = $program->createProgram($program_arr);
            if (!$return) {
                DB::rollback();
                $messages[] = Lang::_('保存不成功');
            }
            self::deleteMemcache($program_arr['stations_id'], date('Y-m-d', $program_arr['start_date']));
            DB::commit();
        }
    }

    private function dealWithRadioXml($xmldata, $stations_id = 0, $strdate, $station_code) {
        $xml = simplexml_load_string($xmldata);
        $list = $xml->response->list;
        if (!empty($list)) {
            foreach ($list->radioItem as $programrow) {
                $title = (string)$programrow->programtitle;//标题
                $date = (string)$strdate;//日期
                $time = substr((string)$programrow->playtime, 0, -3);
                $start_time = strtotime($date.' '.$time);//开始时间
                $this->programInsert($title, $date, $start_time, $stations_id);
            }
        }
        else if($station_code=='1604'||$station_code=='1608') {
            switch($station_code) {
                case "1604": $programlist = $this->program_1604; break;
                case "1608": $programlist = $this->program_1608; break;
            }
            $db_program = StationsProgram::getStationsProgramByStations($stations_id , date('Y-m-d',strtotime($strdate)-604800));
            if($db_program->count) {
                foreach ($db_program->models as $row) {
                    $program_arr = array();
                    $program_arr['stations_id'] = $stations_id;
                    $program_arr['title'] = $row->title;
                    $program_arr['start_time'] = $row->start_time + 604800*1000;
                    $program_arr['end_time'] = $row->end_time + 604800*1000;;
                    $program_arr['start_date'] = $row->start_date + 604800;
                    $program_arr['duration'] = $row->duration;
                    $program_arr['status'] = $row->status;
                    $program_arr['replay'] = $row->replay;;
                    $program_arr['allow_order'] = $row->allow_order;
                    $program_arr['rate'] = $row->rate;
                    $program_arr['format'] = $row->format;
                    $program_arr['type'] = $row->type;
                    $program_arr['partition_by'] = date('Y');
                    $program_arr['rate_status'] = $row->rate_status;
                    DB::begin();
                    StationsProgram::beforeSaveProgram($program_arr['stations_id'], $program_arr['start_time']);
                    $program = new StationsProgram();
                    $return = $program->createProgram($program_arr);
                    if (!$return) {
                        DB::rollback();
                        $messages[] = Lang::_('保存不成功');
                    }
                    self::deleteMemcache($program_arr['stations_id'], date('Y-m-d', $program_arr['start_date']));
                    DB::commit();
                }
            }
            else {
                foreach ($programlist as $row) {
                    $title = (string)$row['programtitle'];//标题
                    $date = (string)$strdate;//日期
                    $time = substr((string)$row['playtime'], 0, -3);
                    $start_time = strtotime($date.' '.$time);//开始时间
                    $this->programInsert($title, $date, $start_time, $stations_id);
                }
            }
        }
    }

    protected function findById($id) {
    	return StationsProgram::getStationsProgramById($id);
    }
    
    /**
     * 按条件查询
     * @param unknown $id
     */
    public function searchAction() {
    	$data = array();
        if(true == ($mess = Request::getPost())){        
            $data = StationsProgram::search($mess);
            $date = "";
    		View::pick('stations_program/index');
    		View::setVars(compact('mess','data','date'));
    	}
    }

    protected function validateExcel(&$messages) {
        $excel = '';
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if (!$error) {
                $ext = $file->getExtension();
                if (in_array($ext, ['csv'])) {
                    $excel = $file;
                } else {
                    $messages[] = Lang::_('please upload valid excel image');
                }
            } elseif ($error == 4) {
                $excel = '';
                if (!$excel) {
                    $messages[] = Lang::_('please choose upload csv');
                }
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload excel image');
        }
        return $excel;
    }
    
}