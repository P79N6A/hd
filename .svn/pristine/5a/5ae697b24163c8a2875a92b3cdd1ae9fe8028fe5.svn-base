<?php

/**
 * @RoutePrefix("/config")
 */
class VideoConfigController extends InteractionBaseController {


    public function initialize() {
        parent::initialize();
        $this->crossDomain();
    }

    /**
     * 允许跨域请求
     */
    private function crossDomain() {
        $host = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';
        if(false !== strpos($host,'cztv')) {
            header('content-type:application:json;charset=utf8');
            header('Access-Control-Allow-Origin:' . $host);
            header('Access-Control-Allow-Methods:POST,GET,PUT');
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }
    }

    /**
     * @Get('/signals/{id:[0-9]+}\.json')
     */
    public function signalsAction($id) {
    	$signal = new Signals();
    	echo $signal->JsonByRedis($id);
    	exit;
    }
    
    /**
     * @Get('/live/{id:[0-9]+}\.json')
     */
    public function liveAction($id) {
        $stations = Stations::getStationsByCode($id);
        if(count($stations)) {
            $station_epg = StationsEpg::tplByStationCode($stations[0]->channel_id, $stations[0]->code);
            $playlist = array();
            $yf_m3u8_urls = array();
            foreach($station_epg['epgs'] as $epg) {
                array_push($playlist, $epg['name']);
                $epgname_zh = "标清";
                switch($epg['name']) {
                case '360p': $epgname_zh = "标清"; break;
                case '540p': $epgname_zh = "高清"; break;
                case '720p': $epgname_zh = "超清"; break;
                case '1080p': $epgname_zh = "原画"; break;
                }
                $yf_m3u8_urls[$epgname_zh] = $epg['url'];
            }
            $yf_m3u8 = array(
                'type'=>"m3u8",
                'defaultrate'=>$epgname_zh,
                'isp2p'=>"0",
                'drm'=>"0",
                'channel_name'=>"0",
            );
            $livejson = array(
                'danmu' => 0,
                'firstlook' => 0,
                'paylist' => $playlist,
                'playstatus' => array(
                    'status'=>1
                ),
                'playurl' => array(
                    'dispatch'=>array(
                        array(
                            'weight' => array('yf_m3u8'=>1, 'yf2_m3u8'=>1),
                            'url' => array(
                                array(
                                    'yf_m3u8'=>array(
                                        $yf_m3u8_urls,
                                        array('type' => $yf_m3u8['type']),
                                        array('defaultrate' => $yf_m3u8['defaultrate']),
                                        array('isp2p' => $yf_m3u8['isp2p']),
                                        array('drm' => $yf_m3u8['drm']),
                                        array('channel_name' => $yf_m3u8['channel_name']),
                                    )
                                ),
                                array(
                                    'yf2_m3u8'=>array(
                                        $yf_m3u8_urls,
                                        array('type' => $yf_m3u8['type']),
                                        array('defaultrate' => $yf_m3u8['defaultrate']),
                                        array('isp2p' => $yf_m3u8['isp2p']),
                                        array('drm' => $yf_m3u8['drm']),
                                        array('channel_name' => $yf_m3u8['channel_name']),
                                    )
                                )
                            )

                        )
                    ),
                    'title' => $station_epg['name'],
                    'pic' => "",
                    'domain' => array(),
                ),
                'statuscode' => $station_code,
                'trylook'=>0

            );
            ob_end_clean();
            echo json_encode($livejson);
            exit;
        }
        else {
            $this->_json([], 404, "not found");
        }
    }
    

}

