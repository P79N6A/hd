<?php

use GenialCloud\Helper\IWC;
/**
 * @RoutePrefix("/pushcountymessage")
 */
class PushCountyMsgController extends ApiBaseController {

    /**
     * 个推/全推 所有推送记录
     * @Get('/all')
     */
    public function pushmsgListAction() {
        $rs = Queues::apiFindAll($this->page, $this->per_page);
        $return = [];
        if(!empty($rs)) {
            foreach($rs as $v) {
                $return[] = [
                    'id' => $v['id'],
                    'data_id' => empty(json_decode($v['task_data']))?:json_decode($v['task_data'])->data_id,
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
    		$rs = Queues::getOne($id);
    		$return = [];
    		if(!empty($rs)) {
   				$return[] = [
   				'id' => $rs->id,
   				'data_id' => empty(json_decode($rs->task_data))?:json_decode($rs->task_data)->data_id,
   				'data_title' => empty(json_decode($rs->task_data))?:json_decode($rs->task_data)->data_title,
   				'single' => $rs->single,
   				'singleClient' => $rs->push_single_client,
   				'remark' => $rs->remark,
   				'createdAt' => $rs->created_at,
   				'status' => $rs->status,
   				'updatedAt' => $rs->updated_at,
   				'content' => $rs->push_content,
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