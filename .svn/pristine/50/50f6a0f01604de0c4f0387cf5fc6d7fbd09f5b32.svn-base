<?php

/**
 * @RoutePrefix("/secret")
 */
class SecretController extends ApiBaseController{


    /**
     * 初始化，校验令牌
     */
    public function initialize() {
        parent::initialize();

    }

    /**
     * @Post('/verify')
     */
    public function verifyAction(){
        $secretKey = Request::getPost("key");
        if ( $secretKey == md5("") || empty($secretKey) ) {
            $this->_jsonzgltv($this->channel_id,[], \SecretVerify::SECRET_KEY_INPUT_EMPTY, \SecretVerify::getErrMsg(\SecretVerify::SECRET_KEY_INPUT_EMPTY),true);
        }else {
            $secretInfo = Data::getSecretUrlAndStatus($this->channel_id, $secretKey);
            if ( empty( $secretInfo["url"] ) || $secretInfo["status"] != 1 ) {
                $this->_jsonzgltv($this->channel_id,[],\SecretVerify::SECRET_KEY_ERROR,\SecretVerify::getErrMsg(\SecretVerify::SECRET_KEY_ERROR),true);
            } else {
                $this->_jsonzgltv($this->channel_id,["url" => $secretInfo["url"] ],200,"success",true);
            }
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
                'alertMessage' => $msg,
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