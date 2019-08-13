<?php

use GenialCloud\Helper\IWC;
/**
 * @RoutePrefix("/pushtvmessage")
 */
class PushTvMsgController extends ApiBaseController {

    /**
     * 个推/全推 所有推送记录
     * @Get('/all')
     */
    public function pushmsgListAction() {
        $rs = PushMsg::apiFindAll($this->page, $this->per_page);
        $return = [];
        if(!empty($rs)) {
            foreach($rs as $v) {
                $return[] = [
                    'id' => $v['id'],
                    'title' => $v['push_content'],
                    'enterType' => $v['push_mode'],
                    'image' => $v['push_image'],
                    'timestamp' => $v['push_timestamp'],
                ];
            }
           	return $this->_json($return);
        } else {
            return $this->_json([], 404, 'No Data');
        }
    }

    /**
     * 详细信息
     * @Get("/{id:[0-9]+}")
     */
    public function pushmsgInfoAction($id) {
    
    	if(isset($id)) {
    		$rs = PushMsg::getOne($id);
    		$return = [];
    		if(!empty($rs)) {
   				$return[] = [
   				'id' => $rs->id,
   				'title' => $rs->push_content,
   				'enterType' => $rs->push_mode,
   				'videoId' => $rs->push_id,
   				'image' => $rs->push_image,
   				'url' => $rs->push_url,
   				'single' => $rs->push_single,
   				'singleClient' => $rs->push_single_client,
   				'pushType' => $rs->push_type,
   				'createdAt' => $rs->created_at,
   				'status' => $rs->status,
   				'channelId' => $rs->push_channelid,
   				'ablumId' => $rs->push_ablumid,
   				'terminalId' => $rs->push_terminal
    			];
    			
    			return $this->_json($return);
    		} else {
    			return $this->_json([], 404, 'Not Found');
    		}
    	}else {
    		return $this->_json([], 404, 'Not Found');
    	}
    }
   
}