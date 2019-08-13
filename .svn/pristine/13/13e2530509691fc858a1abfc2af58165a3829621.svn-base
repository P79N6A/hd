<?php

/**
 * @RoutePrefix("/stations")
 */
class StationsController extends InteractionBaseController {

    /**
     * 电台/广播列表
     */
    public function indexAction() {
        $site_id = Request::get('site_id');
        $channel_id = Request::get('channel_id');
        $station_type = Request::get('type') == 'tv'? 1: 2;
        $program_number = Request::get('number');
        if (!$program_number || !is_numeric($program_number)){
            $program_number = 5;
        }

        if (!is_numeric($site_id) || !is_numeric($channel_id) || !$site_id || !$channel_id) {
            $this->_jsonzgltv($this->channel_id, [] , 404, 'Not Found' );
        }
        $this->channel_id = $channel_id;
        $stations = Site::getOne($site_id,$channel_id);
        if (!$stations){
            $this->_jsonzgltv($this->channel_id, [] , 404, 'Not Found' );
        }

        $data = Stations::apiGetStationsByType($site_id, $stations->stations, $station_type);

        $return = [];
        if(!empty($data)) {
		    for($i=0; $i<2; $i++)
            foreach($data as $v) {
			    if(0==$i&&$v['id']<=20) continue;
			    if(1==$i&&$v['id']>20) continue;
                $stations_program = StationsProgram::apiGetNextProgramById($v['id'], $program_number);
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
                        'program_title' => $value['title'],
                        'play_time' => $value['start_time'],
                        'duration' => $value['duration'],
                        'program_status' => $status,
                        'program_replay' => $value['replay'],
                        'program_order' => $value['allow_order']
                    );
                }
                $return[] = [
                    'id' => $v['id'],
                    'name' => $v['name'],
                    'logo' => strpos($v['logo'],'http') === false ? cdn_url('image',$v['logo']) : $v['logo'],
                    'epg' => $this->getEpg($v['id']),
                    'station_id' => $v['id'],
                    'station_code' => $v['code'],
                    'station_name' => $v['name'],
                    'station_icon' => strpos($v['logo'],'http') === false ? cdn_url('image',$v['logo']) : $v['logo'],
                    'station_playing' => isset($program['title']) ? $program['title'] : '',
                    'list'=>$program_arr,
                    'share_url' => $v['share_url']
                ];
            }
            $this->_jsonzgltv($this->channel_id,$return , 200 , 'success' ,true);
        }else{
            $this->_jsonzgltv($this->channel_id,[], 404, 'Not Found');
        }
    }

    private function getEpg($id) {
        $station = Stations::apiGetStationsById($id);
        $data = StationsEpg::apiGetEpgById($id);
        $return = [];
        if(!empty($data)) {
            foreach($data as $v) {
                $return[] = $this->joinEpg($station, $v);
            }
        }
        return $return;
    }

    /**
     * 电台/广播流媒体列表
     * @Get("/{id:[0-9]+}")
     * @param int $id
     * @return json
     */
    public function infoAction($id) {
        $id = (int)$id;
        $stations = explode(",", $this->stations);
        if(in_array($id, $stations)) {
            $station = Stations::apiGetStationsById($id);
            $data = StationsEpg::apiGetEpgById($id);
            $return = [];
            if(!empty($data)) {
                foreach($data as $v) {
                    $return[] = $this->joinEpg($station, $v);
                }
            }
            $this->_json($return);
        } else {
            $this->_json([], 404, 'Not Found');
        }
    }

    /**
     * 拼接直播流
     * @param $station
     * @param $epg
     * @return string
     */
    private function joinEpg($station, $epg) {
        return StationsEpg::joinUrl($station, $epg);
    }

    /**
     * 获取电台的节目单
     * @param int $station_id 电台ID
     * @param int $site_id 站点id
     * @param int $channel_id 频道id
     */
    public function programInfoAction() {
        $station_id = Request::get('station_id');
        $site_id = Request::get('site_id');
        $channel_id = Request::get('channel_id');
        if (!is_numeric($site_id) || !is_numeric($channel_id) || !$site_id || !$channel_id) {
            $this->_jsonzgltv($this->channel_id, [] , 404, 'Not Found' );
        }
        $this->channel_id = $channel_id;
        $stations = Site::getOne($site_id,$channel_id);
        $stations = explode(",", $stations->stations);
        if (!$stations){
            $this->_jsonzgltv($this->channel_id, [] , 404, 'Not Found' );
        }
        if($station_id && in_array($station_id, $stations)) {
            $date = Request::getQuery('date','string',date("Y-m-d"));
            $user_id = Request::getQuery('user_id');
            $stations = Stations::apiGetStationsById($station_id);
            $stations_program = StationsProgram::apiGetProgramById($station_id, $date);
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
            echo $this->jsonp([
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

    protected function jsonp(array $rs) {
        $resp = json_encode($rs);
        if ($callback = Request::get('callback')) {
            echo htmlspecialchars($callback) . "({$resp});";
        } else {
            echo $resp;
        }
        exit;
    }
}