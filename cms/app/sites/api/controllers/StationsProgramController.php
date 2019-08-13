<?php

/**
 * @RoutePrefix("/stations_program")
 */
class StationsProgramController extends ApiBaseController {

    /**
     * 电台/广播列表
     * @Get('/')
     */
    public function indexAction() {

    }

    /**
     * 获取电台的节目单
     * @Get("/{id:[0-9]+}")
     * @param int $id 电台ID
     */
    public function infoAction($id) {
        $id = intval($id);
        $stations = explode(",", $this->stations);
        if(in_array($id, $stations)) {
            $date = Request::getQuery('date','string',date("Y-m-d"));
            $user_id = Request::getQuery('user_id');
            $stations = Stations::apiGetStationsById($id);
            $stations_program = StationsProgram::apiGetProgramById($id, $date);
            $program_arr = array();
            foreach ($stations_program as $key => $value) {
                $status = 2;
                if(floor($value['end_time']/1000)<time()){//已播
                    $status = 1;
                }elseif(floor($value['start_time']/1000)>time()){//未播
                    $status = 3;
                }
                $program_order = array();
                if($user_id!=0) {
                    $program_order = StationsProgramOrder::apiFindStationsProgramOrder($value['id'], $user_id);
                }
                $program_arr[] = array(
                    'program_id' => $value['id'],
                    'program_title' => $value['title'],
                    'play_time' => $value['start_time'],
                    'duration' => $value['duration'],
                    'program_status' => $status,
                    'program_replay' => $value['replay'],
                    'program_order' => $value['allow_order'],
                    "user_is_order" => empty($program_order)?2:1 //1已预约，2未预约
                );
            }
            $arr = array(
                'station_id'=>$stations['id'],
                'station_type'=>$stations['type'],
                'station_name'=>$stations['name'],
                'station_date'=>$date,
                'station_icon'=>strpos($stations['logo'],'http') === false ? cdn_url('image',$stations['logo']) : $stations['logo'],
                'list'=>$program_arr
            );
            $data = array();
            $data[] =$arr;
            $this->_jsonzgltv($this->channel_id,$data , 200 , 'success',true);
        } else {
            $this->_jsonzgltv($this->channel_id, [] , 404, 'Not Found' );
        }
    }

    /**
     * 获取电台的节目单,通过code
     * @Get("/{id:[0-9]+}")
     * @param int $id 电台ID
     */
    public function infoByCodeAction($id) {
        $id = intval($id);
        if($id>100 && $id<113) {
            $date = Request::getQuery('date','string',date("Y-m-d"));
            $stations = Stations::apiGetStationsByCode($id, 1);
            $stations_program = StationsProgram::apiGetProgramById($stations['id'], $date);
            $program_arr = array();
            foreach ($stations_program as $key => $value) {
                $status = 2;
                if(floor($value['end_time']/1000)<time()){//已播
                    $status = 1;
                }elseif(floor($value['start_time']/1000)>time()){//未播
                    $status = 3;
                }
                $program_arr[] = array(
                    'program_id' => $value['id'],
					'station_code'=>$id,
                    'program_title' => $value['title'],
                    'play_time' => $value['start_time'],
                    'duration' => $value['duration'],
                    'program_status' => $status,
                    'program_replay' => $value['replay'],
                    'program_order' => $value['allow_order']
                );
            }
            $arr = array(
                'station_id'=>$stations['id'],
                'station_type'=>$stations['type'],
                'station_name'=>$stations['name'],
                'station_date'=>$date,
                'station_icon'=>strpos($stations['logo'],'http') === false ? cdn_url('image',$stations['logo']) : $stations['logo'],
                'list'=>$program_arr
            );
            $data = array();
            $data[] =$arr;
            $this->_jsonzgltv($this->channel_id,$data , 200 , 'success',true);
        } else {
            $this->_jsonzgltv($this->channel_id, [] , 404, 'Not Found' );
        }
    }

    /**
     * 节目单预约功能
     * @Post('/order')
     */
    public function orderAction() {
        $program_id = Request::getPost('program_id');
        $user_id = Request::getPost('user_id');
        $user_is_order = Request::getPost('user_is_order');
        if($program_id&&$user_id){
            $program = StationsProgram::apiGetProgramByProgramId($program_id);
            if ($program){
                $key1 = 'api::stations::program::order::program::'.floor($program['start_time']/1000);
                $key2 = 'api::stations::program::order::user::'.$program['id'];
                if($user_is_order == 1) {//预约操作
                    try{
                        $order = StationsProgramOrder::apiFindStationsProgramOrder($program_id,$user_id);
                        if(!$order) {
                            $data = array();
                            $data['channel_id'] = $this->channel_id;
                            $data['program_id'] = $program_id;
                            $data['user_id'] = $user_id;
                            $data['partition_by'] = date('Y', time());
                            $program_order = new StationsProgramOrder();
                            $program_order->createStationsProgramOrder($data);

                            RedisIO::zAdd($key1, 0, $program_id);
                            RedisIO::zAdd($key2, 0, $user_id);
                        }else{
                            $this->_jsonzgltv($this->channel_id, [] , 4003, 'order is exist' );
                        }

                    }catch (Exception $e){
                        $this->_jsonzgltv($this->channel_id, [] , 4003, 'order create failed' );
                    }
                }elseif ($user_is_order ==2){//取消预约
                    try{
                        $order = StationsProgramOrder::apiFindStationsProgramOrder($program_id,$user_id);
                        if($order){
                            StationsProgramOrder::deleteStationsProgramOrder($order['id']);
                            $key = D::memKey('apiFindStationsProgramOrder', ['program_id' => $program_id, 'user_id' => $user_id]);
                            MemcacheIO::delete($key);
                            RedisIO::zDelete($key2, $user_id);
                        }else{
                            $this->_jsonzgltv($this->channel_id, [] , 4004, 'order is not find' );
                        }

                    }catch (Exception $e){
                        $this->_jsonzgltv($this->channel_id, [] , 4003, 'order delete failed' );
                    }
                }else{
                    $this->_jsonzgltv($this->channel_id, [] , 4004, 'order is error' );
                }
                $this->_jsonzgltv($this->channel_id, [] , 200, 'success' );
            } else {
                $this->_jsonzgltv($this->channel_id, [] , 4004, 'Program is not exist or overdue or can not order' );
            }
        }else{
            $this->_jsonzgltv($this->channel_id, [] , 4004, 'Program_id or User_id is null' );
        }

    }

    /**
     * @param $channel_id
     * @param $data
     * @param int $code
     * @param string $msg
     * @param bool $aleradyarray
     */
    protected function _jsonzgltv($channel_id, $data, $code = 200, $msg = "success", $aleradyarray=false) {
        if($channel_id==LETV_CHANNEL_ID) {
            header('Content-type: application/json');
            $listdata = [];
            if($data!=[]) $listdata[] = $data;
            if($aleradyarray) $listdata = $data;
            echo json_encode([
                'alertMessage' => "数据获取成功",
                'state' => ($code==200)?0:$code,
                'message' => $msg,
                'content' => ['list'=>$listdata],
            ]);
            exit;
        }
        else {
            $this->_json($data, $code, $msg);
        }
    }
}