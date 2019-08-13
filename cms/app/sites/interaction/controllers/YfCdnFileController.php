<?php
class YfCdnFileController extends BaseController{
    /*
     * @记录回调反馈信息
     * */
    public function indexAction(){
        header('Content-type: application/json');
        $input = file_get_contents("php://input");
        file_put_contents("yf.log",date('Y-m-d H:i:s').">>".$input."\n",FILE_APPEND);
        echo json_encode(array('result'=>'success')); //返回处理结果
        die();
    }

}