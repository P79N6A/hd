<?php

/**
 * @RoutePrefix("/stations")
 */
class StationsController extends ApiBaseController {

    /**
     * 电台/广播列表
     * @Get('/')
     */
    public function indexAction() {
        $station_type = Request::getQuery('type') == 'tv'? 1: 2;
        $data = Stations::apiGetStationsByType($this->id, $this->stations, $station_type);
        $return = [];
        if(!empty($data)) {
		    for($i=0; $i<2; $i++)
            foreach($data as $v) {
			    if(0==$i&&$v['id']<=20) continue;
			    if(1==$i&&$v['id']>20) continue;
                $program = StationsProgram::apiGetNowProgramById($v['id']);
                $return[] = [
                    'id' => $v['id'],
                    'name' => $v['name'],
                    'logo' => strpos($v['logo'],'http') === false ? cdn_url('image',$v['logo']) : $v['logo'],
                    'epg' => $this->getEpg($v['id']),
                    "station_id" => $v['id'],
                    "station_code" => $v['code'],
                    "station_name" => $v['name'],
                    "station_icon" => strpos($v['logo'],'http') === false ? cdn_url('image',$v['logo']) : $v['logo'],
                    "station_playing"=> isset($program['title'])?$program['title']:'',
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