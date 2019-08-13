<?php
/**
 * Created by PhpStorm.
 * User: zhangyichi
 * Date: 2016/3/3
 * Time: 10:39
 */
/**
 * @RoutePrefix("/yao")
 */
class YaoController extends ApiBaseController {
    protected function checkSignature() {
        $key = Request::getPost('key');
        if($key!='0acd3f2a-10df-49d1-af62-30cdc46b5051') {
            $this->_json([], 404, D::apiError(4002));
        }
    }

    /**
     * 获取获奖名单
     * 通过会场ID(lottery_group_id)或者活动ID(lottery_id)
     */
    public function listAction() {
        $return = [];
        $tvid = Request::getPost('tvid');
        $rownum = Request::getPost('rows');
        switch($tvid){//频道ID暂时固定
            case '4bd60e73-f935-11e4-b367-d5fd5fcda93b': $lottery_channel_id = 1;break;//民生频道直播间
            case 'c6c6ace5-c4d7-11e5-a841-d9f8ce18a183': $lottery_channel_id = 2;break;//教育频道直播间
            case 'e0a0b2c5-a3a4-11e5-8645-ecddfc9cb604': $lottery_channel_id = 3;break;//影视频道直播间
            default : $lottery_channel_id = 0;
        }
        $lottery = Lotteries::getLotteryByChannel($lottery_channel_id);
        if(!empty($lottery)){
            $lottery_id = $lottery[0]['id'];
            $list = LotteryWinnings::findlistByLottery($lottery_id);
            $return['total'] = count($list);
            $rows = array();
            if(!$rownum||$rownum>count($list)){
                $rownum = count($list);
            }
            for($i=0;$i<$rownum;$i++){
                $row = array(
                    'id'=>($i+1),
                    'headimgurl'=>'http://o.cztvcloud.com/static/xcgd/images/abcavtar.jpg',
                    'nickname'=>$list[$i]['name'],
                    'openid'=>($list[$i]['mobile'])?$list[$i]['mobile']:$list[$i]['client_id'],
                    'time'=>date('Y-m-d H:i:s',$list[$i]['created_at']),
                    'total_amount'=>$list[$i]['prize_name']
                );
                array_push($rows,$row);
            }
            $return['rows'] = $rows;
            $this->_json($return);
        }else{
            $this->_json($return);
        }
    }

    /**
     * 获取获奖人数和活动人数
     */
    public function numberAction() {
        $return = [];
        $tvid = Request::getPost('tvid');
        switch($tvid){//频道ID暂时固定
            case '4bd60e73-f935-11e4-b367-d5fd5fcda93b': $lottery_channel_id = 1;break;//民生频道直播间
            case 'c6c6ace5-c4d7-11e5-a841-d9f8ce18a183': $lottery_channel_id = 2;break;//教育频道直播间
            case 'e0a0b2c5-a3a4-11e5-8645-ecddfc9cb604': $lottery_channel_id = 3;break;//影视频道直播间
            default : $lottery_channel_id = 0;
        }
        $lottery = Lotteries::getLotteryByChannel($lottery_channel_id);
        if(!empty($lottery)) {
            $return['ySum'] = $lottery[0]['estimated_people'];
            $return['cSum'] = count(LotteryWinnings::findNubmer($lottery[0]['id']));
            $return['zSum'] = $return['cSum'];
            $this->_json($return);
        }else{
            $this->_json($return);
        }
    }

    protected function _json($data, $code = 200, $msg = "success"){
        header('Content-type: application/json');
        echo json_encode($data);
        exit;
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }

}