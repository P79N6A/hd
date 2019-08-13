<?php

class SysmsgController extends ApiBaseController {

    //重写initialize方法, 获取系统消息不需要验证
    public function initialize() {

    }

    /**
     * @desc  获取系统消息
     * @param
     * start_time     查询范围的开始时间戳, 如果不传默认取三天之内的
     */
    public function listAction() {
        $start_time = intval($this->request->get('start_time', null, 0));
        $sysMsgModel = new VcmSysMsg();
        $result = $sysMsgModel->listSysMsg($start_time);
        exit(json_encode(array('code' => 200, 'data' => $result)));
    }

}

?>