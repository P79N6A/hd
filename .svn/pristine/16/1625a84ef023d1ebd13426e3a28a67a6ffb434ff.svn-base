<?php
/**
 * Created by PhpStorm.
 * User: zhangyichi
 * Date: 2016/8/29
 * Time: 10:00
 */
class VoteController extends InteractionBaseController{

    public function initialize()
    {
        parent::initialize();
    }

    const VOTE_DATA_MODEL = 'cztv::vote::data::model::';
    const VOTE_IP = 'cztv::vote::ip::';
    const BULE_TV_USER_SECRET = '4PkZBxWgBHH7sthBDHo8QYRXtZLmTcGj';

    private function getVote($vote_id){
        $vote = RedisIO::get(self::VOTE_DATA_MODEL.$vote_id);
        if($vote){
            $return = json_decode($vote,true);
        }else {
            $data_vote = Data::getMediaByDataId($vote_id);
            $data = $data_vote[0];//媒资
            $vote = $data_vote[1];//投票
            if($data->type !='vote'){
                return false;
            }
            $vote_option_id = explode(',', $vote->option_id);
            $vote_option = [];//选项
            if ($vote_option_id == null || empty($vote_option_id)) {

            } else {
                foreach ($vote_option_id as $k => $option_id) {
                    $option = VoteOption::findOptionById($option_id);
                    if($option->video_url){
                        $option->video_url=cdn_url('image',$option->video_url);
                    }
                    $vote_option[] = VoteOption::findOptionById($option_id);
                }
            }
            $return = array('data' => $data, 'vote' => $vote, 'vote_option' => $vote_option);
            $return_json = json_encode($return);
            RedisIO::set(self::VOTE_DATA_MODEL . $vote_id, $return_json);
            $return = json_decode($return_json,true);
        }
        return $return;
    }

    //投票接口
    public function upWorkAction() {//需要获取ip
        $vote_id = Request::getQuery('vote_id','int');
        $option_number = Request::getQuery('option_number','string');
        if($vote_id && $option_number){
            //首先验证验证码
            $verify_back = self::beforeCheckGetCode();
            if($verify_back==false){
                $this->jsonp(array('code'=>2004,'msg'=>'验证码不正确或已失效'));
            }

            $return = self::getVote($vote_id);
            if(!$return){
                $this->jsonp(array('code'=>2001,'msg'=>'参数不为投票id'));
            }
            $vote = $return['vote'];

            $option_arr = array_unique(explode('|',$option_number));

            if(count($option_arr)>$vote['option_max']||count($option_arr)<$vote['option_min']){//判断选项数
                $this->jsonp(array('code'=>2001,'msg'=>'选项数量不正确'));
            }
            if($vote['end_time']<time()||$vote['start_time']>time()){
                $this->jsonp(array('code'=>2001,'msg'=>'不在投票时间内'));
            }
            foreach ($option_arr as $k=>$value){//投票数增加
                if(is_numeric($value)&&isset($return['vote_option'][$value-1])){
                    $return['vote_option'][$value-1]['sum']++;
                    $return['vote_option'][$value-1]['actual_sum']++;
                }
            }

            if($return['vote']['type']=='ip') {//限制ip的投票
                $ip = F::getRealIp();
                $up_times = RedisIO::get(self::VOTE_IP . $vote_id . '::' . $ip);
                if (!$up_times) {
                    if ($return['vote']['rate'] > 0) {//每日更新缓存
                        $time = strtotime(date('Y-m-d',strtotime("+1 day")))-time();
                        RedisIO::set(self::VOTE_IP . $vote_id . '::' . $ip, 1, $time);
                    } else {
                        RedisIO::set(self::VOTE_IP . $vote_id . '::' . $ip, 1);
                    }
                } elseif ($up_times >= $return['vote']['times']) {
                    $this->jsonp(array('code' => 2002, 'msg' => '此ip已经投过票了'));
                } else {
                    RedisIO::incr(self::VOTE_IP . $vote_id . '::' . $ip);
                }
            }else{//用户登入的投票
                $user_token = Request::getQuery('open_id');
                $user = RedisIO::get('interaction::vote::upwork::' . $user_token);
                if($user){
//                    $user = json_decode($user,true);
//                    $user_id = $user['id'];//还未确定
                    $up_times = RedisIO::get(self::VOTE_IP . $vote_id . '::' . $user_token);
                    if (!$up_times) {
                        if ($return['vote']['rate'] > 0) {//每日更新缓存
                            $time = strtotime(date('Y-m-d',strtotime("+1 day")))-time();
                            RedisIO::set(self::VOTE_IP . $vote_id . '::' . $user_token, 1, $time);
                        } else {
                            RedisIO::set(self::VOTE_IP . $vote_id . '::' . $user_token, 1);
                        }
                    } elseif ($up_times >= $return['vote']['times']) {
                        $this->jsonp(array('code' => 2002, 'msg' => '此ip已经投过票了'));
                    } else {
                        RedisIO::incr(self::VOTE_IP . $vote_id . '::' . $user_token);
                    }
                }else{
                    $this->jsonp(array('code' => 2003, 'msg' => '此用户不存在'));
                }
            }

            RedisIO::set(self::VOTE_DATA_MODEL . $vote_id, json_encode($return));

            $this->jsonp(array('code'=>200,'msg'=>'ok'));
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'参数为空'));
        }
    }

    //新投票接口
    public function upWorkNewAction() {//需要获取ip
        $vote_id = Request::getQuery('vote_id','int');
        $option_number = Request::getQuery('option_number','string');
        if($vote_id && $option_number){
            //首先验证验证码
            $verify_back = self::beforeCheckGetCode();
            if($verify_back==false){
                $this->jsonp(array('code'=>2004,'msg'=>'验证码不正确或已失效'));
            }

            $return = self::getVoteNew($vote_id);
            if(!$return){
                $this->jsonp(array('code'=>2001,'msg'=>'参数不为投票id'));
            }
            $vote = $return['vote'];

            $option_arr = array_unique(explode('|',$option_number));

            if(count($option_arr)>$vote->option_max||count($option_arr)<$vote->option_min){//判断选项数
                $this->jsonp(array('code'=>2001,'msg'=>'选项数量不正确'));
            }
            if($vote->end_time<time()||$vote->start_time>time()){
                $this->jsonp(array('code'=>2001,'msg'=>'不在投票时间内'));
            }

            if($vote->type=='ip') {//限制ip的投票
                $ip = F::getRealIp();
                $up_times = RedisIO::get(self::VOTE_IP . $vote_id . '::' . $ip);
                if (!$up_times) {
                    if ($vote->rate > 0) {//每日更新缓存
                        $time = strtotime(date('Y-m-d',strtotime("+1 day")))-time();
                        RedisIO::set(self::VOTE_IP . $vote_id . '::' . $ip, 1, $time);
                    } else {
                        RedisIO::set(self::VOTE_IP . $vote_id . '::' . $ip, 1);
                    }
                } elseif ($up_times >= $vote->times) {
                    $this->jsonp(array('code' => 2002, 'msg' => '此ip已经投过票了'));
                } else {
                    RedisIO::incr(self::VOTE_IP . $vote_id . '::' . $ip);
                }
            }else{//用户登入的投票
                $user_token = Request::getQuery('open_id');
                $user = RedisIO::get('interaction::vote::upwork::' . $user_token);
                if($user){
//                    $user = json_decode($user,true);
//                    $user_id = $user['id'];//还未确定
                    $up_times = RedisIO::get(self::VOTE_IP . $vote_id . '::' . $user_token);
                    if (!$up_times) {
                        if ($vote->rate > 0) {//每日更新缓存
                            $time = strtotime(date('Y-m-d',strtotime("+1 day")))-time();
                            RedisIO::set(self::VOTE_IP . $vote_id . '::' . $user_token, 1, $time);
                        } else {
                            RedisIO::set(self::VOTE_IP . $vote_id . '::' . $user_token, 1);
                        }
                    } elseif ($up_times >= $vote->times) {
                        $this->jsonp(array('code' => 2002, 'msg' => '此ip已经投过票了'));
                    } else {
                        RedisIO::incr(self::VOTE_IP . $vote_id . '::' . $user_token);
                    }
                }else{
                    $this->jsonp(array('code' => 2003, 'msg' => '此用户不存在'));
                }
            }
            foreach ($option_arr as $k=>$value){//投票数增加
                if(is_numeric($value)&&RedisIO::hGet(self::VOTE_DATA_MODEL. $vote_id . '::hash', $value)){
                    RedisIO::zIncrBy(self::VOTE_DATA_MODEL . $vote_id . '::zset', 1, $value);
                }
            }

            $this->jsonp(array('code'=>200,'msg'=>'ok'));
        }else{
            $this->jsonp(array('code'=>2001,'msg'=>'参数为空'));
        }
    }


    private function getVoteNew($vote_id) {
        $data_vote = Data::getMediaByDataId($vote_id);
        $data['vote'] = $data_vote[1];
        return $data;
    }

    const VOTE_VERIFY = 'cztv::vote::data::verify::';

    private function beforeCheckGetCode() {
        $vote_id = Request::get('vote_id');
        $captcha_verify = RedisIO::get(self::VOTE_VERIFY . $vote_id );
        if(!$captcha_verify) {
            return false;
        }

        switch ($captcha_verify) {
            case Vote::VERIFY_OFF : return true ;break;
            case Vote::VERIFY_ENGLISH : return $this->checkGetCode(Vote::VERIFY_ENGLISH) ;break;
            case Vote::VERIFY_CHINESE: return $this->checkGetCode(Vote::VERIFY_CHINESE) ;break;
            default : return false;
        }

    }

    private function checkGetCode($captcha_verify) {
        $regcode = Request::getPost('regcode');//图片验证码
        if(isset($regcode)){

        }else {
            return false;
        }
        session_start();
        if(isset($_COOKIE['captchaId'])){
            $captchaId = $_COOKIE['captchaId'];
        }else {
            return false;
        }
        if(isset($_SESSION["captchaValue"])){
            $captchaValue = $_SESSION["captchaValue"];
            if ($captcha_verify == Vote::VERIFY_CHINESE) {
                if (preg_match("/[\x7f-\xff]/", $captchaValue)) {

                }else{
                    return false;
                }
            }
        }else {
            return false;
        }
        if(!$_SESSION['captchaTime']){
            return false;
        }
        if($_SESSION['captchaTime']>time()-2) {//获取验证码2秒内请求，直接返回,并作废其验证码
            $_SESSION['captchaValue'] = rand(1000,9999);
            return false;
        }

        if(session_id()==$captchaId && $regcode && strcasecmp($regcode,$captchaValue)==0){
            return true;
        }else{
            return false;
        }
    }

    /**
     * 检查蓝tv用户是否正式存在
     */
    public function checkTvUserAction() {
        $uid = Request::getPost('uid');
        $client_id = Request::get('client_id');
        if ($uid) {
            if(RedisIO::get('interaction::vote::upwork::' . $uid)) {
                $this->jsonp(array('code' => 200 , 'msg' => '验证通过'));
            }else {
                $userid = Userid::findFirstByUid($uid);
                if ($userid){
                    RedisIO::set('interaction::vote::upwork::' . $uid, 1);
                    $this->jsonp(array('code' => 200 , 'msg' => '验证通过'));
                }else{
                    $this->jsonp(array('code' => 2002 , 'msg' => '用户不存在'));
                }
            }
        }elseif ($client_id) {
            if(RedisIO::get('interaction::vote::upwork::' . $client_id)) {
                $this->jsonp(array('code' => 200 , 'msg' => '验证通过'));
            }else {
                $exist_status = RedisIO::exists($client_id);
                if ($exist_status){
                    RedisIO::set('interaction::vote::upwork::' . $client_id, 1);
                    $this->jsonp(array('code' => 200 , 'msg' => '验证通过'));
                }else{
                    $origin_id = Client::apiFindOneByOriginId($client_id , 1);
                    if (!empty($origin_id)) {
                        RedisIO::set('interaction::vote::upwork::' . $client_id, 1);
                        $this->jsonp(array('code' => 200 , 'msg' => '验证通过'));
                    }else{
                        $this->jsonp(array('code' => 2003 , 'msg' => '机器码不存在'));
                    }
                }
            }
        }else{
            $this->jsonp(array('code' => 2001 , 'msg' => '验证参数不能全为空'));
        }

    }

    /**
     * 检查蓝tv用户签名是否正确，如果正确存机器码一天可以投票
     */
    public function checkTvUserSignatureAction() {
        $signature = Request::get('signature');
        $mac_id = Request::get('mac_id');
        if ($signature == null || $mac_id == null) {
            $this->jsonp(array('code' => 2001 , 'msg' => '关键参数不能为空'));
        }

        //已验证通过的用户直接返回
        if(RedisIO::get('interaction::vote::upwork::' . $mac_id)) {
            $this->jsonp(array('code' => 200 , 'msg' => '验证通过'));
        }else{
            $timestamp = Request::get('timestamp');
            if ($timestamp == null || time()-$timestamp >= 24*60*60) {
                $this->jsonp(array('code' => 2002 , 'msg' => '时间戳已失效'));
            }
            $input_arr = [];
            $input_arr['name'] = Request::get('name');
            $input_arr['gender'] = Request::get('gender');
            $input_arr['email'] = Request::get('email');
            $input_arr['uid'] = Request::get('uid');
            $input_arr['client_id'] = Request::get('client_id');
            $input_arr['mobile'] = Request::get('mobile');
            $input_arr['timestamp'] = Request::get('timestamp');
            $input_arr['type'] = Request::get('type');
            $input_arr['is_login'] = Request::get('is_login');
            $input_arr['terminal_id'] = 'cztv_activity';
            $lastValue = substr($input_arr['timestamp'], -1);
            $input_arr['key'] = substr(self::BULE_TV_USER_SECRET, $lastValue);
            if ($input_arr['type'] == 'ios') {
                $input_arr['id'] = '';
                if ($input_arr['is_login'] == 'n') {
                    unset($input_arr['uid']);
                }
            }

            ksort($input_arr);
            $parm = http_build_query($input_arr);
            if ($input_arr['type'] == 'ios') {
                $parm = str_replace('client_id=%E6%9A%82%E6%97%A0','client_id=暂无',$parm);
            }
            $input_md5 = md5(base64_encode($parm));

            if ($input_md5 == Request::get('signature')) {
                RedisIO::set('interaction::vote::upwork::' . $mac_id , 1, 24*60*60);
                $this->jsonp(array('code' => 200 , 'msg' => '验证通过'));
            }else {
                $this->jsonp(array('code' => 2003 , 'msg' => '签名错误'));
            }

        }

    }

    /**
     * 获取验证码接口，时效1分钟，保存在客户端cookie中
     * @param string $num
     * @param string $w
     * @param string $h
     */
    public function getCodeAction() {
        session_start();
//        error_reporting(3);
        $code = "";
//        for ($i = 0; $i < $num; $i++) {
//            $code .= rand(0, 9);
//        }
        //4位验证码也可以用rand(1000,9999)直接生成
        //将生成的验证码写入session，备验证时用

        Header("Content-type: image/PNG");
        $str = "的一是在了不和有大这主中人上为们地个用工时要动国产以我到他会作来分生对于学下级就年阶义发成部民可出能方进同行面说种过命度革而多子后自社加小机也经力线本电高量长党得实家定深法表着水理化争现所二起政三好十战无农使性前等反体合斗路图把结第里正新开论之物从当两些还天资事队批如应形想制心样干都向变关点育重其思与间内去因件日利相由压员气业代全组数果期导平各基或月毛然问比展那它最及外没看治提五解系林者米群头意只明四道马认次文通但条较克又公孔领军流入接席位情运器并飞原油放立题质指建区验活众很教决特此常石强极土少已根共直团统式转别造切九你取西持总料连任志观调七么山程百报更见必真保热委手改管处己将修支识病象几先老光专什六型具示复安带每东增则完风回南广劳轮科北打积车计给节做务被整联步类集号列温装即毫知轴研单色坚据速防史拉世设达尔场织历花受求传口断况采精金界品判参层止边清至万确究书术状厂须离再目海交权且儿青才证低越际八试规斯近注办布门铁需走议县兵固除般引齿千胜细影济白格效置推空配刀叶率述今选养德话查差半敌始片施响收华觉备名红续均药标记难存测士身紧液派准斤角降维板许破述技消底床田势端感往神便贺村构照容非搞亚磨族火段算适讲按值美态黄易彪服早班麦削信排台声该击素张密害侯草何树肥继右属市严径螺检左页抗苏显苦英快称坏移约巴材省黑武培著河帝仅针怎植京助升王眼她抓含苗副杂普谈围食射源例致酸旧却充足短划剂宣环落首尺波承粉践府鱼随考刻靠够满夫失包住促枝局菌杆周护岩师举曲春元超负砂封换太模贫减阳扬江析亩木言球朝医校古呢稻宋听唯输滑站另卫字鼓刚写刘微略范供阿块某功套友限项余倒卷创律雨让骨远帮初皮播优占死毒圈伟季训控激找叫云互跟裂粮粒母练塞钢顶策双留误础吸阻故寸盾晚丝女散焊功株亲院冷彻弹错散商视艺灭版烈零室轻血倍缺厘泵察绝富城冲喷壤简否柱李望盘磁雄似困巩益洲脱投送奴侧润盖挥距触星松送获兴独官混纪依未突架宽冬章湿偏纹吃执阀矿寨责熟稳夺硬价努翻奇甲预职评读背协损棉侵灰虽矛厚罗泥辟告卵箱掌氧恩爱停曾溶营终纲孟钱待尽俄缩沙退陈讨奋械载胞幼哪剥迫旋征槽倒握担仍呀鲜吧卡粗介钻逐弱脚怕盐末阴丰编印蜂急拿扩伤飞露核缘游振操央伍域甚迅辉异序免纸夜乡久隶缸夹念兰映沟乙吗儒杀汽磷艰晶插埃燃欢铁补咱芽永瓦倾阵碳演威附牙芽永瓦斜灌欧献顺猪洋腐请透司危括脉宜笑若尾束壮暴企菜穗楚汉愈绿拖牛份染既秋遍锻玉夏疗尖殖井费州访吹荣铜沿替滚客召旱悟刺脑措贯藏敢令隙炉壳硫煤迎铸粘探临薄旬善福纵择礼愿伏残雷延烟句纯渐耕跑泽慢栽鲁赤繁境潮横掉锥希池败船假亮谓托伙哲怀割摆贡呈劲财仪沉炼麻罪祖息车穿货销齐鼠抽画饲龙库守筑房歌寒喜哥洗蚀废纳腹乎录镜妇恶脂庄擦险赞钟摇典柄辩竹谷卖乱虚桥奥伯赶垂途额壁网截野遗静谋弄挂课镇妄盛耐援扎虑键归符庆聚绕摩忙舞遇索顾胶羊湖钉仁音迹碎伸灯避泛亡答勇频皇柳哈揭甘诺概宪浓岛袭谁洪谢炮浇斑讯懂灵蛋闭孩释乳巨徒私银伊景坦累匀霉杜乐勒隔弯绩招绍胡呼痛峰零柴簧午跳居尚丁秦稍追梁折耗碱殊岗挖氏刃剧堆赫荷胸衡勤膜篇登驻案刊秧缓凸役剪川雪链渔啦脸户洛孢勃盟买杨宗焦赛旗滤硅炭股坐蒸凝竟陷枪黎救冒暗洞犯筒您宋弧爆谬涂味津臂障褐陆啊健尊豆拔莫抵桑坡缝警挑污冰柬嘴啥饭塑寄赵喊垫康遵牧遭幅园腔订香肉弟屋敏恢忘衣孙龄岭骗休借丹渡耳刨虎笔稀昆浪萨茶滴浅拥穴覆伦娘吨浸袖珠雌妈紫戏塔锤震岁貌洁剖牢锋疑霸闪埔猛诉刷狠忽灾闹乔唐漏闻沈熔氯荒茎男凡抢像浆旁玻亦忠唱蒙予纷捕锁尤乘乌智淡允叛畜俘摸锈扫毕璃宝芯爷鉴秘净蒋钙肩腾枯抛轨堂拌爸循诱祝励肯酒绳穷塘燥泡袋朗喂铝软渠颗惯贸粪综墙趋彼届墨碍启逆卸航雾冠丙街莱贝辐肠付吉渗瑞惊顿挤秒悬姆烂森糖圣凹陶词迟蚕亿矩";
        $imgWidth = 140;
        $imgHeight = 40;
        $authimg = imagecreate($imgWidth,$imgHeight);
        $bgColor = ImageColorAllocate($authimg,255,255,255);
        if(rand(0,1)) {
            $fontfile = BASE_PATH . 'static/fonts/hyflj.ttf';
        }else {
            $fontfile = BASE_PATH . 'static/fonts/hyfd.ttf';
        }
        $white=imagecolorallocate($authimg,234,185,95);
        imagearc($authimg, 150, 8, 20, 20, 75, 170, $white);
        imagearc($authimg, 180, 7,50, 30, 75, 175, $white);
        imageline($authimg,20,20,180,30,$white);
        imageline($authimg,20,18,170,50,$white);
        imageline($authimg,25,50,80,50,$white);
        $noise_num = 800;
        $line_num = 20;
        imagecolorallocate($authimg,0xff,0xff,0xff);
        $rectangle_color=imagecolorallocate($authimg,0xAA,0xAA,0xAA);
        $noise_color=imagecolorallocate($authimg,0x00,0x00,0x00);
        $font_color=imagecolorallocate($authimg,0x00,0x00,0x00);
        $line_color=imagecolorallocate($authimg,0x00,0x00,0x00);
        for($i=0;$i<$noise_num;$i++){
            imagesetpixel($authimg,mt_rand(0,$imgWidth),mt_rand(0,$imgHeight),$noise_color);
        }
        for($i=0;$i<$line_num;$i++){
            imageline($authimg,mt_rand(0,$imgWidth),mt_rand(0,$imgHeight),mt_rand(0,$imgWidth),mt_rand(0,$imgHeight),$line_color);
        }
        $randnum=rand(0,strlen($str)/3-4);
        $str = mb_substr($str,$randnum,4,'utf-8');

        ImageTTFText($authimg, 20, 0, 16, 30, $font_color, $fontfile, $str);
        ImagePNG($authimg);
        ImageDestroy($authimg);
        $code = $str;

        setcookie('captchaId', session_id(),time()+60,'/','.cztvcloud.com');
        setcookie('captchaId', session_id(),time()+60,'/','.cztv.com');

        $_SESSION['captchaValue'] = $code;
        $_SESSION['captchaTime'] = time();

    }

    public function getStringAction($num = '4', $w = '60', $h = '20') {
        session_start();
        
        $pool = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code =  substr(str_shuffle(str_repeat($pool, 5)), 0, $num);

        setcookie('captchaId', session_id(),time()+60,'/','.cztvcloud.com');
        setcookie('captchaId', session_id(),time()+60,'/','.cztv.com');

        $_SESSION['captchaValue'] = $code;
        $_SESSION['captchaTime'] = time();

        //创建图片，定义颜色值
        header("Content-type: image/PNG");
        $im = imagecreate($w, $h);
        $black = imagecolorallocate($im, 0, 0, 0);
        $gray = imagecolorallocate($im, 200, 200, 200);
        $bgcolor = imagecolorallocate($im, 255, 255, 255);
        //填充背景
        imagefill($im, 0, 0, $gray);

        //画边框
        imagerectangle($im, 0, 0, $w - 1, $h - 1, $black);

        //随机绘制两条虚线，起干扰作用
        $style = array($black, $black, $black, $black, $black,
            $gray, $gray, $gray, $gray, $gray
        );
        imagesetstyle($im, $style);
        $y1 = rand(0, $h);
        $y2 = rand(0, $h);
        $y3 = rand(0, $h);
        $y4 = rand(0, $h);
        imageline($im, 0, $y1, $w, $y3, IMG_COLOR_STYLED);
        imageline($im, 0, $y2, $w, $y4, IMG_COLOR_STYLED);

        //在画布上随机生成大量黑点，起干扰作用;
        for ($i = 0; $i < 80; $i++) {
            imagesetpixel($im, rand(0, $w), rand(0, $h), $black);
        }
        //将数字随机显示在画布上,字符的水平间距和位置都按一定波动范围随机生成
        $strx = rand(3, 8);
        for ($i = 0; $i < $num; $i++) {
            $strpos = rand(1, 6);
            imagestring($im, 5, $strx, $strpos, substr($code, $i, 1), $black);
            $strx += rand(8, 12);
        }
        ob_clean();
        imagepng($im);//输出图片
        imagedestroy($im);//释放图片所占内存



    }

    /**
     * 统计次数
     * 李红刚
     * 2019-07-10
     */
    public function censusCountAction()
    {
        $vote_id = Request::getQuery('vote_id','int');

        $return = self::getVoteNew($vote_id);
        if(!$return){
            $this->jsonp(array('code'=>2001,'msg'=>'参数不为投票id'));
        }
        $vote = $return['vote'];

        if($vote->end_time<time()||$vote->start_time>time()){
            $this->jsonp(array('code'=>2001,'msg'=>'不在投票时间内'));
        }

        RedisIO::incr(self::VOTE_DATA_MODEL . $vote_id . '::count');

        $this->jsonp(array('code'=>200,'msg'=>'ok'));
    }
}