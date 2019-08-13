<?php
error_reporting(1);
use GenialCloud\Auth\Signature;

/**
 * @RoutePrefix("/lottery")
 */
class LotteryController extends ApiBaseController {
    const KEY = '4PkZBxWgBHH7sthBDHo8QYRXtZLmTcGj';

    public function initialize()
    {
        $this->checkSignatureTv() ;
        $this->crossDomain();  //跨域支持
        View::disable();
    }

    /**
     * 允许跨域请求
     */
    private function crossDomain()
    {
        $host = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';
        $root_domain = "";
        if (!empty($host)) {
            $root_domain = $this->getUrlToDomain($host);
        }
        //跨域白名单
        $domains = array(
            "cztv.com",
            "cztvcloud.com",
            "xianghunet.com",
            "szttkk.com",
            "zjbtv.com",
            "sybtv.com",
            "txnews.com.cn",
            "qz123.com",
            "zjxcw.com",
            "yysee.net"
        );
        if (in_array($root_domain, $domains)) {
            header('content-type:application:json;charset=utf8');
            header('Access-Control-Allow-Origin:' . $host);
            header('Access-Control-Allow-Methods:POST,GET,PUT');
            header("Access-Control-Allow-Credentials: true");
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }

    }

    /**
     * 取得根域名
     * @param type $domain 域名
     * @return string 返回根域名
     */
    protected function getUrlToDomain($domain)
    {
        $re_domain = '';
        $domain_postfix_cn_array = array("com", "net", "org", "gov", "edu", "com.cn", "cn");
        $array_domain = explode(".", $domain);
        $array_num = count($array_domain) - 1;
        if(!$array_num){
            return "";
        }
        if ($array_domain[$array_num] == 'cn') {
            if (in_array($array_domain[$array_num - 1], $domain_postfix_cn_array)) {
                $re_domain = $array_domain[$array_num - 2] . "." . $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
            } else {
                $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
            }
        } else {
            $re_domain = $array_domain[$array_num - 1] . "." . $array_domain[$array_num];
        }
        return $re_domain;
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
     * @param $data
     * @param int $code
     * @param string $msg
     */
    protected function _json($data, $code = 200, $msg = 'success') {
        $rs = [
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ];
        $this->jsonp($rs);
        exit;
    }

    protected function jsonp(array $rs) {
        $resp = json_encode($rs);
        if($callback = Request::get('callback')) {
            echo htmlspecialchars($callback)."({$resp});";
        } else {
            echo $resp;
        }
    }

    /**
     * 签名校验
     */
    protected function checkSignature() {
        $input = Request::getQuery();
        $keeps = ['app_id', 'client_id', 'timestamp', 'token', 'signature'];
        if(!issets($input, $keeps)) {
            $this->_json([], 404, D::apiError(4001));
        }
        // 站点信息读取
        $data = Site::getByAppId($input['app_id']);
        if(empty($data)) {
            $this->_json([], 404, D::apiError(4002));
        }
        foreach($data as $k => $v) {
            $this->$k = $v;
        }
        $beSigned = [];
        foreach($keeps as $k) {
            $beSigned[$k] = $input[$k];
        }
        // 签名匹配
        if(!Signature::MD5SimpleCheck($beSigned, $data)) {
            $this->_json([], 404, D::apiError(4003));
        }
        $this->client_id = $input['client_id'];
    }
	
	/**
     * 校验签名
     * @throws LotteryException
     */
    protected function checkSignatureTv() {
        error_reporting(1);
        $params = array();
        $params['timestamp'] = (string)$_GET['timestamp'];
        $params['key'] = substr(self::KEY, $params['timestamp'][strlen($params['timestamp']) - 1]);
        if(isset($_GET['terminal_id'])) {
            $params['terminal_id'] = $_GET['terminal_id'];
        }
        else {
            $params['terminal_id'] = "cztv_activity";
        }
        $params['id'] = $_GET['id'];
        $params['type'] = $_GET['type'];
        $params['is_login'] = $_GET['is_login'];
        $params['uid'] = $_GET['uid'];
        $params['gender'] = $_GET['gender'];
        $params['mobile'] = $_GET['mobile'];
        $params['email'] = urldecode($_GET['email']);
        $params['name'] = urldecode($_GET['name']);
        $params['client_id'] = $_GET['client_id'];
        ksort($params);
        $str = http_build_query($params);
        $params['signature'] = md5(base64_encode($str));
        $this->client_id = 'a:'.$params['client_id'];
        $this->channel_id = $_GET['channel_id'];
        //签名失败
        if($params['signature'] != Request::get('signature'))
            $this->throwE('signature validate failed.');
    }


    /**
     * 频道列表
     * @Get('/lotteries/{id:0-9+}')
     */
    public function lotteriesAction($group_id) {
        $group_id = (int) $group_id;
        $rs = ['code' => 404, 'msg' => 'not found', 'data' => []];
        if($group_id) {
            $rs = Lotteries::openedLotteries($group_id);
            foreach($rs as $k => $v) {
                $rs[$k]['background'] = cdn_url('yao', $v['background']);
                $rs[$k]['style'] = cdn_url('yao', $v['style']);
            }
        }
        $this->jsonp($rs);
    }

    /**
     * 中奖用户列表
     * @Get('/winners/{id:0-9+}')
     */
    public function winnersAction($group_id) {
        $rs = LotteryContacts::latestWinners($group_id);
        $msg = 'ok';
        $this->jsonp(compact('rs', 'msg'));
    }

    /**
     * 根据手机号搜索用户中奖
     * @Get('/search_winners/{id:0-9+}')
     */
    public function searchWinnersAction($group_id) {
        $mobile = Request::get('mobile');
        $msg = 'ok';
        $rs = [];
        try {
            if(!$mobile || !preg_match('/^1[0-9]{10}$/', $mobile)) {
                $this->throwE('请输入正确的手机号.');
            }
            $rs = LotteryContacts::searchWinners($group_id, $mobile);
        } catch(LotteryException $e) {
            $msg = $e->getMessage();
        }
        $this->jsonp(compact('rs', 'msg'));
    }

    /**
     * 短信验证
     *
     * @Get('/verifycode')
     */
    public function verifycodeAction() {
        //暂时取消验证码
        exit;
        $token = Request::get('_token');
        $mobile = Request::get('mobile');
        if(!$token || !LotteryContacts::getByToken($token)) {
            $code = -2;
        } else {
            // -1 手机号码不正确, 0 发送失败, 1 发送成功
            if(!$mobile || !preg_match('/^1[0-9]{10}$/', $mobile)) {
                $code = -1;
            } elseif(VerifyCode::send($mobile)) {
                $code = 1;
            } else {
                $code = 0;
            }
        }
        $this->jsonp(compact('code'));
    }

    /**
     * 中奖联系人信息
     *
     * @Get('/contact')
     */
    public function contactAction() {
        try {
            $inputs = Request::getPost();
            foreach(['real_name', 'province', 'city', 'area', 'address'] as $key) {
                $inputs[$key] = urldecode($inputs[$key]);
            }
            /**
             * @var \Illuminate\Validation\Validator $validator
             */
            $validator = Validator::make($inputs, [
                '_token' => 'required',
//                'code' => 'required',
                'mobile' => 'required',
            ]);
            if($validator->fails()) {
                $this->throwE('params required',2000);
            }
            //校验手机验证码
            $mobile = $inputs['mobile'];
            if (!preg_match("/^1[34578]\d{9}$/i", $mobile)) {
                $this->throwE('invalid mobile',2001);
            }
//            $code = $inputs['code'];
//            if(!VerifyCode::validate($mobile, $code)) {
//                $this->throwE('verify code error');
//            }
            DB::begin();
            $contact = LotteryContacts::getByToken($inputs['_token']);
            //非法的数据
            if(!$contact) {
                DB::rollback();
                $this->throwE('invalid info',2001);
            }
            //校验中奖联系人数据
            if($contact->mobile) {
                DB::rollback();
                $this->throwE('info has been set',2002);
            }
            //如果真实奖品需要填写更具体的联系信息
            if($contact->prize_is_real) {
                /**
                 * @var \Illuminate\Validation\Validator $validator
                 */
                $validator = Validator::make($inputs, [
                    'real_name' => 'required',
                    'province' => 'required',
                    'city' => 'required',
                    'area' => 'required',
                    'address' => 'required',
                ]);
                if($validator->fails()) {
                    DB::rollback();
                    $this->throwE('params required',2003);
                }
                $inputs['name'] = $inputs['real_name'];
            } else {
                $inputs['name'] = '';
                $inputs['province'] = '';
                $inputs['city'] = '';
                $inputs['area'] = '';
                $inputs['address'] = '';
            }
            unset($inputs['real_name']);
            foreach(['mobile', 'name', 'province', 'city', 'area', 'address'] as $key) {
                $contact->$key = $inputs[$key];
            }
            $contact->updated_at = time();
            $contact->status = 1;
            if(!$contact->save()) {
                DB::rollback();
                $this->throwE('retry',2004);
            }
            DB::commit();
            //优化中奖搜索查询
            LotteryContacts::saveRedisWinners(
                $contact->id,
                $contact->lottery_group_id,
                $mobile,
                $inputs['name'],
                $contact->prize_name,
                $contact->prize_level,
                $contact->status
            );
            $msg = 'ok';
            $code = 200;
        } catch(LotteryException $e) {
            $msg = $e->getMessage();
            $code = $e->getCode();
        }
        $this->jsonp(compact('msg','code'));
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
        $this->jsonp(compact('code', 'token', 'prize', 'is_real', 'rest', 'msg', 'thumb'));
    }

    /**
     * 检查抽奖数据
     * @param $id
     * @throws LotteryException
     */
    protected function checkLottery($id) {
        $now = time();
        $lottery = Lotteries::getById($id);
        if(!$lottery)
            $this->throwE('lottery not found');
//        if($lottery->channel_id != $this->channel_id)
        if($lottery->open_time > $now)
            $this->throwE('lottery not start', -3);
        if($lottery->close_time < $now)
            $this->throwE('lottery has been closed', -2);
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
            RedisIO::expire($key, 86400);
            Lotteries::incrLotteryCount($lottery_id);
        }
        if($count > $c) {
            $this->throwE('draw count has been drained', -1);
        }
        return $c - $count;
    }

    /**
     * 从配置读取入围资格参数
     *
     * @return int
     * @throws Exception
     */
    protected function getConfigQualify() {
        $lottery_qualify = app_site()->lottery_qualify;
        $lottery_qualify = $lottery_qualify > 0? $lottery_qualify: 1;
        return $lottery_qualify;
    }

    /**
     * 获取进入抽奖环节的资格
     * @throws LotteryException
     */
    protected function getQualification() {
        if(mt_rand(1, $this->getConfigQualify()) !== 1)
            throw new LotteryException('not winning');
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
        $factor = $this->lottery->estimated_people / $this->getConfigQualify();
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
        //一个终端只能中奖一次, 查询中奖也不应进行下接下来的操作
        if(LotteryWinnings::ifClientHasWin($group_id, $client_id)) {
            DB::rollback();
            $this->throwE('client has win');
        }
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
        $prize->rest_number -= 1;
        if(!$prize->save()) {
            DB::rollback();
            $this->throwE('prize save failed');
        }
        if(!$client_id) {
            DB::rollback();
            $this->throwE('client_id dont exist');
        }
        $winning = new LotteryWinnings;
        $data = [
            'client_id' => $client_id?:str_random(10),
            'prize_id' => $prize->id,
            'prize_name' => $prize->name,
            'prize_level' => $prize->level,
            'prize_is_real' => $prize->is_real,
            'lottery_id' => $this->lottery->id,
            'lottery_group_id' => $this->lottery->group_id,
            'lottery_channel_id' => $channel_id,
            'channel_id' => $this->channel_id,
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
        return [$token, $prize->is_real, $prize->name, cdn_url('yao', $goods->thumb)];
    }

    /**
     * 抽奖
     * @Get("/channelsjs")
     */
    public function channelJsAction() {
        $rs = [];
        foreach(LotteryChannels::getLotteryChannel() as $channel => $url) {
            $rs[$channel] = Oss::url($url);
        }
        $this->jsonp($rs);
    }

}