<?php
use Phalcon\Mvc\Model\Query\Status;
error_reporting(1);

/**
 * @RoutePrefix("/kaige")
 */
class KaiGelotteryController extends LotteryController {
	
	private $uid_cache = "kg::index::status:";//登入状态
	private $uid_get = "kg::lottery::status:";//中奖状态
	private $uid_num = "kg::kaige::number:";//接住凯哥的数量
    
    /**
     * 初始化数据
     *
     */
    public function initialize(){
        //签名验证
        $this->checkSignatureTv() ;
        //获取客户端client_id
        $this->client_id = Request::getQuery('client_id', 'string');
    }

    /**
     * @Post('/lottery')
     */
    public function lotteryAction() {  
    	$client_id = $this->client_id;
        
        $lottery_id = Request::getQuery('lottery_id', 'int');
        $num = Request::getPost('num', 'int'); 
        
        $status = RedisIO::get($this->uid_cache.$client_id); //10分钟内有效成绩为一次

        if($client_id && $num && $lottery_id && !$status){//成功玩游戏     

            $resp = $this->getDraw($lottery_id);  //进入抽奖                    
            
            $arr = json_decode($resp,true);          
            if($arr['code']==1||intval($arr['code'])===0) {
                RedisIO::set($this->uid_num . $client_id, $num, 600);//保存接住数量10分钟
                if ($arr['code'] == 1) {//获得奖品
                    RedisIO::set($this->uid_get . $client_id, 1, 600);
                }               
                RedisIO::set($this->uid_cache . $client_id, 1, 600);
            }
            echo $resp;
        }else{
            echo 0;
        }
        exit;
    }

    /**
     * 抽奖
     * @Get("/draw/{id}")
     * @param $id
     */
    protected function getDraw($id) {
        //code -2 抽奖结束, -1 抽奖次数耗尽, 0 未中奖, 1 中奖了
        $code = 0;
        $token = '';
        $is_real = 0;
        $prize = '';
        $msg = '';
        $rest = 0;
        $thumb = '';
        try {
            $id = (int)$id;
            //校验抽奖数据
            $this->checkLottery($id);
            //校验抽奖次数
            $rest = $this->checkLotteryHistory();
            //是否获取资格
            $this->getQualification();
           
            //进入抽奖环节
            list($token, $is_real, $prize, $thumb) = $this->draw($id);          
            if($token) {
                $code = 1;
            }
        } catch(LotteryException $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
        }
        
        //$this->jsonp(compact('code', 'token', 'prize', 'is_real', 'rest', 'msg', 'thumb'));
        return json_encode(compact('code', 'token', 'prize', 'is_real', 'rest', 'msg', 'thumb'));

    }

    /**
     * 校验抽奖次数
     * @var int $c
     * @throws LotteryException
     */
    protected function checkLotteryHistory() {
        $user = $this->client_id;
        $channel_id = $this->lottery->lottery_channel_id;
        $lottery_id = $this->lottery->id;
        $c = $this->lottery->times_limit;
        $key = 'too:'.$lottery_id.':'.$user;
        $count = RedisIO::incr($key);
        $time = strtotime(date("Y-m-d 00:00:00",strtotime("+1 day")))-time();
        if($count == 1) {
            //设置过期时间
            RedisIO::expire($key, $time);           
            Lotteries::incrLotteryCount($lottery_id);
        }
        if($count > $c) {
            $this->throwE('draw count has been drained', -1);
        }
        return $c - $count;
    }
    
    protected function _json($data, $code = 200, $msg = "success") {
        header('Content-type: application/json');
        echo json_encode([
                'code' => $code,
                'msg' => $msg,
                'data' => $data,
                ]);
        exit;
    }



    /**
     * 获得奖品
     *
     * @param $id
     * @return string
     * @throws LotteryException
     */
    protected function win($id) {
        $client_id = $this->client_id;
        $lottery_channel_id = $this->lottery->lottery_channel_id;
        $this->channel_id = 3;
        $group_id = $this->lottery->group_id;
        DB::begin();
        //一个终端只能中奖一次, 查询中奖也不应进行下接下来的操作
        if(LotteryWinnings::ifClientHasWin($group_id, $client_id)) {
            DB::rollback();
            $this->throwE('client has win');
        }
        $prize = LotteryPrizes::findFirst( array("id = ".$id, "for_update" => true));
        if(!$prize) {
            DB::rollback();
            $this->throwE('prize not found');
        }
        if($prize->rest_number <= 0) {
            DB::rollback();
            $this->throwE('count has been drained');
        }
        $goods = LotteryGoods::findFirst($prize->goods_id);
        if(!$goods) {
            DB::rollback();
            $this->throwE('goods not found');
        }
        $prize->rest_number -= 1;
        if(!$prize->save()) {
            DB::rollback();
            $this->throwE('prize save failed');
        }
        $winning = new LotteryWinnings;
        $data = [
            'client_id' => $client_id,
            'prize_id' => $prize->id,
            'prize_name' => $prize->name,
            'prize_level' => $prize->level,
            'prize_is_real' => $prize->is_real,
            'lottery_id' => $this->lottery->id,
            'lottery_group_id' => $this->lottery->group_id,
            'lottery_channel_id' => $lottery_channel_id,
            'channel_id' => $this->channel_id,
            'extra_value' => 0,
            'created_at' => time(),
        ];
        if(!$id = $winning->saveGetId($data)) {
            
            DB::rollback();
            $this->throwE('winning save failed');
        }

        $token = md5(uniqid(str_random()));
        if(!LotteryContacts::dataInit($id, $token, $prize->is_real)) {
            DB::rollback();
            $this->throwE('contact save failed');
        }
       
        DB::commit();
        
        return [$token, $prize->is_real, $prize->name, $prize->level];
    }

}