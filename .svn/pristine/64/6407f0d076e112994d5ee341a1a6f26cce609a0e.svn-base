<?php

date_default_timezone_set('Asia/Shanghai');
class ProgramTask extends Task {

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

    /**
     * 从集团接口获取节目单定时任务
     * @param $date
     */
    public function getvideoAction() {
        $codes = array('1'=>101,'2'=>102,'3'=>103,'4'=>104,'5'=>105,'6'=>106,
            '7'=>107,'8'=>108,'9'=>109,'A'=>110,'C'=>111,'B'=>112);
        foreach ($codes as $key => $value) {
            $codeid = strval($key);
            $strdate = date('Y-m-d',strtotime("+1 day"));

            $url = 'http://10.30.137.17/epg.php?playDate='.date('Ymd', strtotime($strdate)).'&channelCode='.$codeid;

            $xmldata = file_get_contents($url);

            $stations = Stations::getStationsByCode($value);
            foreach ($stations as $station){
                $this->dealWithXml($xmldata, $station->id);
                sleep(5);
            }
        }
		$this->getradioAction();
    }

    /**
     * 从集团接口获取广播节目单定时任务
     * @param $date
     */
    public function getradioAction() {
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
        foreach ($codes as $key => $value) {
            $codeid = strval($key);
            $strdate = date('Y-m-d',strtotime("+1 day"));			
			$url = 'http://10.1.101.27:8080/zjbd/pb/pbradio.do?channelId='.$codeid.'&playDate='.date('Ymd', strtotime($strdate));
            $xmldata = file_get_contents($url);
            $stations = Stations::getStationsByCode($value);
            foreach ($stations as $station){
                $this->dealWithRadioXml($xmldata, $station->id, $strdate, $value);
                sleep(5);
            }
        }
    }

    private function dealWithXml($xmldata, $stations_id = 0) {
        $xml = simplexml_load_string($xmldata);
        foreach ($xml->programinfotype->programitemtype as $programrow) {
            $title = (string)$programrow->maininfotype->programname;//标题
            $date = (string)$programrow->maininfotype->date;//日期
            $time = substr((string)$programrow->maininfotype->time, 0, -3);
            $start_time = strtotime($date.' '.$time);//开始时间
            $this->programInsert($title, $date, $start_time, $stations_id); 
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

    protected function deleteMemcache($stations_id, $date){
        $key = D::memKey('apiGetProgramById', ['id' => $stations_id, 'date' => $date]);
        MemcacheIO::delete($key);
        $key = D::memKey('apiGetNowProgramById', ['id' => $stations_id]);
        MemcacheIO::delete($key);
    }

}

?>