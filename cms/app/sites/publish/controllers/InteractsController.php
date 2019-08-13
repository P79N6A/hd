<?php

use \GenialCloud\Helper\XmasEncrypt;

class InteractsController extends \YearBaseController {

    static $key = 'tuanbaihuijiami';

    public $ignore = [
        'vote','reward','rewardinfo','show','getinfo','showsort'
    ];

    public function initialize()
    {
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
     * 投票接口
     * @throws Exception
     */
    public function voteAction() {

        // 揭秘方法,传递过来的id为sdfsf12312等加密字符
        $hash = Request::getQuery('id');
//        $hash = urldecode($hash);
        $encrypt = new XmasEncrypt();
        // 解密出ID，即1，2，3，4等等
        $id = $encrypt->decode($hash,self::$key);

        // 加密算法，放在生成二维码的地方
//        $id = 123;
//        $hash = $encrypt->encode($id,self::$key);
//        $id = Request::getQuery('id');//后期加密
        $vote = VoteYear::findOneById($id);
        if(!$vote){
            $this->jsonp(array(414));//情况4：错误id号
        }
        if(Request::getQuery('show_id')){
            $input = Request::getQuery();
            $show_arr = explode(',',$input['show_id']);
            if($vote->show_id){
                $this->jsonp(array(411));//情况1：已投票过
            }
            if(count($show_arr)!=3){
                $this->jsonp(array(412));//'情况2：投票数不足5票';
            }else{
                $show = ShowYear::findOneById($show_arr[0]);
                if(!$show||$show->status==0) {
                    $this->jsonp(array(413));//'情况3：已关闭投票';
                }
            }
//            foreach($show_arr as $id){
//                $show = ShowYear::findOneById($id);
//                if(!$show) continue;
//                $show->vote++;
//                $show->update();
//            }
            $vote->show_id = $input['show_id'];
            $vote->update();
            $this->updateShowVote();
        }
        $this->jsonp(explode(',',$vote->show_id));
        exit;
    }

    protected function updateShowVote() {
        $votes = VoteYear::findVoteHas();
        $show_num = array();
        foreach ($votes as $k => $vote) {
            foreach (explode(',',$vote->show_id) as $key => $value){
                isset($show_num[$value])?$show_num[$value]++:$show_num[$value]=1;
            }
        }
        foreach ($show_num as $show_id => $vote_num){
            $show = ShowYear::findOneById($show_id);
            if($show){
                $show->vote = $vote_num + $show->extra;
                $show->update();
            }
        }
    }

    /**
     * 抽奖接口
     * @throws Exception
     */
    public function rewardAction() {
        $id = Request::getQuery('id');
        $reward = RewardYear::findOneById($id);
        if(!$reward){
            $this->jsonp(array());
        }
        $ticket = TicketYear::findAllByRewardId($id);
        if(count($ticket)){//已抽奖过的情况
            $return = array();
            foreach($ticket as $t){
                array_push($return,sprintf("%03d", $t->number));
            }
            $this->jsonp($return);
        }else{
            $ticket = TicketYear::findAllNoReward();
            $return = array();
            if($reward->sum>=count($ticket)){//抽奖数量大于剩余人数
                foreach($ticket as $t){
                    $t->reward_id = $id;
                    $t->update();
                    array_push($return,sprintf("%03d", $t->number));
                }
                $this->jsonp($return);
            }else {
                $id_arr = array();
                foreach ($ticket as $t) {
                    array_push($id_arr, $t->id);
                }
                $array = array_rand($id_arr, $reward->sum);//随机产生获奖ID
                if($reward->sum==1){
                    $array = array($array);
                }
                foreach($array as $index){
                    $ticket[$index]->reward_id = $id;
                    $ticket[$index]->update();
                    array_push($return,sprintf("%03d", $ticket[$index]->number));
                }
                $this->jsonp($return);
            }
        }
    }

    /**
     * 获取奖品信息
     */
    public function rewardinfoAction() {
        $data = RewardYear::findList();
        $this->jsonp($data);
        exit;
    }

    /**
     * 获取节目列表
     */
    public function showAction() {
        $data = ShowYear::findList();
        $this->jsonp($data);
        exit;
    }

    public function showsortAction() {
        $data = ShowYear::findListVote();
        $this->jsonp($data);
        exit;
    }

    public function getVoteInfoAction() {
        $ticket_num = count(VoteYear::findVoteHas()->toArray());
        $extra_sum = ShowYear::findListExtra();
        $this->jsonp(array('ticket_num' => $ticket_num , 'extra_sum' => (int)$extra_sum[0]['extra_sum']));
    }

    /**
     * 通过奖品ID获得奖品信息,next为左右调节默认为0
     */
    public function getinfoAction() {
        $reward_id = Request::getQuery('id');
        $next = Request::getQuery('next')?:0;
        $reward_arr = RewardYear::findList();
        $index = 0;
        foreach($reward_arr as $k=>$reward){
            if($reward['id']==$reward_id){
                $index = $k;break;
            }
        }
        $fianl = $index+$next;
        if($fianl<0) $fianl=0;
        if($fianl>=count($reward_arr)) $fianl=count($reward_arr)-1;
        $this->jsonp($reward_arr[$fianl]);
    }

    public function errorAction() {
    }

}