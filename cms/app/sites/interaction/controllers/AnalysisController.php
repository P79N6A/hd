<?php


/**
 * @RoutePrefix("/visit")
 */
class AnalysisController extends InteractionBaseController {

    private function IsNewClick() {
        $ip = Request::getClientAddress();
        $item_id = Request::get("item_id", "int");
        if($item_id == 0) {
            return;
        }
        $items = MemcacheIO::get($ip);
        $new_click = false;
        if(empty($items)) {
            $items = array();
        }
        if(!in_array($item_id, $items)) {
            array_push($items, $item_id);
            MemcacheIO::set($ip, $items, 86400);
            $new_click = true;
        }
        return $new_click;
    }

    private function createDefaultItemLog() {
        $item_log = new ItemLog();
        $item_log->item_id = intval(Request::get('item_id', 'int'));;
        $item_log->editor_id = intval(Request::get('editor_id', 'int'));
        $item_log->channel_id = intval(Request::get('channel_id', 'int'));
        $item_log->title = Request::get('title');
        $item_log->type = intval(Request::get('type', 'int'));
        $item_log->total_hits = 0;
        $item_log->total_valid_hits = 0;
        $item_log->total_app_hits = 0;
        $item_log->total_web_hits = 0;
        $item_log->total_wap_hits = 0;
        $item_log->create_day = date('Y-m-d');
        return $item_log;
    }

    private function checkSignature() {
        $signature = Request::getQuery('signature');
        if(!isset($signature)) {
            return false;
        }
        $requestRealTime = Request::getServer('REQUEST_TIME');
        $requestSignatureTime = Request::getQuery('timestamp');
        if($requestRealTime - $requestSignatureTime > 5) {
            return false;
        }
        $params = array();
        $params['timestamp'] = Request::get('timestamp');
        $params['key'] = KEY;
        ksort($params);
        $str = http_build_query($params);
        $params['signature'] = md5(base64_encode($str));
        return $params['signature'] == Request::get('signature');
    }

    private function getReport($type = null) {
        if($type == null) {
            $type = 'lastmonth';
        }
        switch($type) {
            case 'thisweek':
                $start = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1, date("Y")));
                $end = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7, date("Y")));
                break;
            case 'lastweek':
                $start = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m"), date("d") - date("w") + 1 - 7, date("Y")));
                $end = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), date("d") - date("w") + 7 - 7, date("Y")));
                break;
            case 'lastmonth':
                $start = date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") - 1, 1, date("Y")));
                $end = date("Y-m-d H:i:s", mktime(23, 59, 59, date("m"), 0, date("Y")));
                break;
        }
        $items = ItemLog::find(array(
            "create_day BETWEEN  '$start' AND '$end'",
            'columns' => "sum(total_hits) as total_hits, sum(total_valid_hits) as total_valid_hits,
            sum(total_web_hits) as total_web_hits, sum(total_app_hits) as total_app_hits,
            sum(total_wap_hits) as total_wap_hits, channel_id, item_id, editor_id, title, type",
            'group' => 'item_id'
        ));
        return $items->toArray();
    }

    public function reportAction() {
        $this->response->setContentType('application/json', 'utf-8');
        if(!$this->checkSignature()) {
            echo json_encode(array(
                'status' => 'ERROR',
                'code' => 403,
                'message' => '无效的签名'
            ));
            return;
        }
        $rows = $this->getReport(Request::get("type"));
        echo json_encode($rows);
    }

    /**
     * @Get("/hits/{id:-?[0-9]+}")
     * @param int $id
     * @return json
     */
    public function getHitsAction($id) {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $hits = RedisIO::get("hits:" . $id);
        $baseHitsCounts = RedisIO::get("baseHitsCounts:" . $id);
        $this->jsonp(array('code' => 200, 'success' => 1, 'data' => array('hits'=>(($hits)?intval($hits):0) ,'hits_fake'=>(($baseHitsCounts)?intval($baseHitsCounts):0)+ (($hits)?intval($hits):0)  )  ));
    }

    /**
     * @Post('/')
     */
    public function visitAction() {
        $this->response->setHeader('Access-Control-Allow-Origin', '*');
        $item_id = intval(Request::get("item_id", "int"));
        $create_day = date('Y-m-d');
        $item_log = ItemLog::findFirst(array("item_id = $item_id and create_day = \"$create_day\""));
        if(empty($item_log)) {
            $item_log = $this->createDefaultItemLog();
        }
        if($this->IsNewClick()) {
            $item_log->total_valid_hits = $item_log->total_valid_hits + 1;
        }
        $item_log->total_hits = $item_log->total_hits + 1;
        $terminal = intval(Request::get("terminal", "int", 1));
        if($terminal == 1) {
            $item_log->total_web_hits = $item_log->total_web_hits + 1;
        } else if($terminal == 2) {
            $item_log->total_wap_hits = $item_log->total_wap_hits + 1;
        } else {
            $item_log->total_app_hits = $item_log->total_app_hits + 1;
        }

        if($item_log->save()){
            RedisIO::incr("hits:" . $item_id);
            RedisIO::zAdd(DataStatistics::QUEUEDATAID, 0, $item_id);
        }
        exit;
    }
}