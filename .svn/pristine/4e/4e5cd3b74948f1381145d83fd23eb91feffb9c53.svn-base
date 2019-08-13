<?php

/**
 * Created by PhpStorm.
 * User: zhangyichi
 * Date: 2016/8/29
 * Time: 10:00
 */
class LotteryNormalController extends InteractionBaseController
{
    const LOTTERY_VERIFY = 'cztv::lottery::data::verify::';

    public function initialize()
    {
        parent::initialize();
        header('Cache-Control: max-age=1');
    }

    /**
     * @param $message
     * @param int $code
     * @throws LotteryException
     */
    protected function throwE($message, $code = 0) {
        throw new LotteryException($message, $code);
    }

    /**
     * 抽奖
     * @Get("/draw/{id}")
     * @param $id
     */
    public function drawAction($id) {
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
//            $this->getQualification();
            //进入抽奖环节
            list($token, $is_real, $prize, $thumb) = $this->draw($id);
            if($token) {
                $code = 1;
            }
        } catch(LotteryException $e) {
            $code = $e->getCode();
            $msg = $e->getMessage();
        }
        $this->jsonp(compact('code', 'token', 'prize', 'is_real', 'rest', 'msg', 'thumb'));
    }

    /**
     * 检查抽奖数据
     * @param $id
     * @throws LotteryException
     */
    protected function checkLottery($id) {
        $now = time();
        $client_id = Request::get('client_id');
        $this->client_id = $client_id;
        if (!$client_id || !(strlen($client_id) == 28 || strlen($client_id) == 32)) {
            $this->throwE('client_id not exist or effective', 2001);
        }

        $captcha_verify = RedisIO::get(self::LOTTERY_VERIFY . $id);
        if ($captcha_verify == 1) {
            $user = RedisIO::get('interaction::vote::upwork::' . $client_id);
            if (!$user) {
                $this->throwE('the lottery need weixin verify', 2005);
            }
        }

        $lottery = Lotteries::getById($id);
        if(!$lottery)
            $this->throwE('lottery not found', 2002);
        if($lottery->open_time > $now)
            $this->throwE('lottery not start', 2003);
        if($lottery->close_time < $now)
            $this->throwE('lottery has been closed', 2004);
        $this->lottery = $lottery;
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
        $key = 'lottery:'.$lottery_id.':'.$user;
        $count = RedisIO::incr($key);
        if($count == 1) {
            //设置过期时间
            $timeToNextDay = strtotime(date('Y-m-d',strtotime("+1 day")))-time();
            RedisIO::expire($key, $timeToNextDay);
            //统计参加人数
//            Lotteries::incrLotteryCount($lottery_id);
        }
        if($count > $c) {
            $this->throwE('draw count has been drained', -1);
        }
        return $c - $count;
    }

    /**
     * 抽奖环节
     *
     * @param int $id
     * @return string
     * @throws LotteryException
     */
    protected function draw($id) {
        $token = '';
        $is_real = 0;
        $prize = '';
        $thumb = '';
        list($sum, $rs) = LotteryPrizes::getByLottery($id);
        $factor = $this->lottery->estimated_people ;
        if($factor < $sum)
            $factor = $sum;
        $hit = mt_rand(1, $factor);
        if($hit > $sum)
            throw new LotteryException('not winning.');
        $total = 0;
        foreach($rs as $r) {
            $total += $r->number;
            if($hit <= $total) {
                list($token, $is_real, $prize, $thumb) = $this->win($r->id);
                break;
            }
        }
        return [$token, $is_real, $prize, $thumb];
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
        $channel_id = $this->lottery->lottery_channel_id;
        $group_id = $this->lottery->group_id;
        DB::begin();
        $prize = LotteryPrizes::findFirst($id);
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
        //一个终端只能中奖一次, 查询中奖也不应进行下接下来的操作
        //修改为判断奖品属性中是否允许重复中奖
        if((!$goods->is_rewin) && LotteryWinnings::ifClientHasWinPrize($group_id, $client_id, $id)) {
            DB::rollback();
            $this->throwE('client has win');
        }

        $prize->rest_number -= 1;
        if(!$prize->save()) {
            DB::rollback();
            $this->throwE('prize save failed');
        }
        if(!$client_id) {
            DB::rollback();
            $this->throwE('client_id dont exist');
        }
        $token = md5(uniqid(str_random()));
        $winning = new LotteryWinnings;
        $data = [
            'client_id' => $client_id?:str_random(10),
            'prize_id' => $prize->id,
            'contacts_token' => $token,
            'prize_name' => $prize->name,
            'prize_level' => $prize->level,
            'prize_is_real' => $prize->is_real,
            'lottery_id' => $this->lottery->id,
            'lottery_group_id' => $this->lottery->group_id,
            'lottery_channel_id' => $channel_id,
            'channel_id' => $this->channel_id?:1,
            'created_at' => time(),
        ];
        if(!$winnings_id = $winning->saveGetId($data)) {
            DB::rollback();
            $this->throwE('winning save failed');
        }
        if(!LotteryContacts::dataInit($winnings_id, $token, $prize->is_real)) {
            DB::rollback();
            $this->throwE('contact save failed');
        }
        DB::commit();
        return [$token, $prize->is_real, $prize->goods_id, cdn_url('image', $goods->thumb)];
    }

    /**
     * 通过openID获取用户的中奖列表
     */
    public function userWinningsAction() {
        $lottery_id = Request::get('lottery_id');
        $client_id = Request::get('client_id');
        if (!$lottery_id || !$client_id) {
            $code = 201;
            $data = array();
            $msg = '请求参数不全';
            $this->jsonp(compact('code', 'data', 'msg'));
        }
        $lottery_winnings = LotteryWinnings::getAllForClientAndLottery($lottery_id, $client_id);
        $winnings_array = $lottery_winnings->toArray();
        $data = array();
        $data_winnings = array();
        foreach ($winnings_array as $array) {
            if ($array['sum'] == 1) {
                $prize = LotteryPrizes::findById($array['prize_id']);
                $array['prize_id'] = $prize->goods_id;
                $data_winnings[] = $array;
            }
        }

        $lottery = Lotteries::getById($lottery_id);
        $key = 'lottery:'.$lottery_id.':'.$client_id;
        $count = RedisIO::get($key);
        $count = $lottery->times_limit - $count;

        $data['winnings'] = $data_winnings;
        $data['client'] = array('count' => $count);

        $code = 200;
        $msg = '获取成功';
        $this->jsonp(compact('code', 'data', 'msg'));
    }

    /*
     * 摇奖页面分享回调接口
     */
    public function lotteryShareAction() {
        $lottery_id = Request::get('lottery_id');
        $client_id = Request::get('client_id');
        if (!$lottery_id || !$client_id) {
            $code = 201;
            $data = array();
            $msg = '请求参数不全';
            $this->jsonp(compact('code', 'data', 'msg'));
        }

        $key1 = 'lottery:'.$lottery_id.':'.$client_id.':first:share';
        $key2 = 'lottery:'.$lottery_id.':'.$client_id;
        $first_share = RedisIO::get($key1);
        if ($first_share == null){
            RedisIO::set($key1, 1, strtotime(date('Y-m-d',strtotime("+1 day")))-time());
            $count = RedisIO::get($key2);
            if ($count == null){
                RedisIO::set($key2, -3, strtotime(date('Y-m-d',strtotime("+1 day")))-time());
            }else{
                RedisIO::set($key2, $count-3, strtotime(date('Y-m-d',strtotime("+1 day")))-time());
            }
            $code = 200;
            $data = array();
            $msg = '分享成功';
            $this->jsonp(compact('code', 'data', 'msg'));
        }else{
            $code = 202;
            $data = array();
            $msg = '今天已分享过';
            $this->jsonp(compact('code', 'data', 'msg'));
        }
    }

    /*
     * 提交联系方式接口,post提交
     */
    public function submitContactAction() {
        $post = Request::getPost();
        if ($this->checkUpSubmitContactForm($post)){
            $winnings = LotteryWinnings::getOneByClientAndToken($post['client_id'], $post['contacts_token']);
            if (!$winnings) {
                $code = 202;
                $data = array();
                $msg = '奖品不存在';
                $this->jsonp(compact('code', 'data', 'msg'));
            }
            DB::begin();
            try {
                if ($winnings->sum < 1) {
                    $this->throwE('奖品数量不足');
                } else {
                    $winnings->sum--;
                }
                if (!$winnings->update()) {
                    DB::rollback();
                    $this->throwE('winnings update fail');
                }
                $contact = LotteryContacts::getOneByToken($post['contacts_token']);
                if (!$contact || $contact->status == 2) {
                    DB::rollback();
                    $this->throwE('奖品已被领取');
                } else {
                    $contact->mobile = $post['mobile'];
                    $contact->name = $post['name'];
                    $contact->address = $post['address'];
                    $contact->updated_at = time();
                    $contact->status = 1;

                    if (!$contact->update()) {
                        DB::rollback();
                        $this->throwE('contact update fail');
                    }
                    $code = 200;
                    $msg = '填写成功';
                }
            }catch (LotteryException $e) {
                $code = $e->getCode();
                $msg = $e->getMessage();
            }
            DB::commit();

            $data = array();
            $this->jsonp(compact('code', 'data', 'msg'));
        }else{
            $code = 201;
            $data = array();
            $msg = '请求参数不全';
            $this->jsonp(compact('code', 'data', 'msg'));
        }
    }

    /**
     * @param $input
     */
    protected function checkUpSubmitContactForm($input) {
        if (!$input['contacts_token'] || !$input['client_id']) {
            return false;
        }
        if (!$input['mobile'] || !$input['name'] || !$input['address']) {
            return false;
        }
        return true;
    }

}