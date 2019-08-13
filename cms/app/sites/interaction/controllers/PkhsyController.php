<?php

/**
 * 《PK好声音接口》
 * Created by PhpStorm.
 * User: Jason Fang
 * Date: 2016/7/6
 * Time: 15:45
 */
class PkhsyController extends BaseController
{
    public $id;

    public function initialize()
    {
        parent::initialize();
        $this->id = Request::getQuery('vid','int');  //根据Vid获取ID
        $this->crossDomain();
    }

    /**
     * 允许跨域请求
     */
    private function crossDomain()
    {
        $host = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';

        if(false !== strpos($host,'cztv')) {
            header('content-type:application:json;charset=utf8');
            header('Access-Control-Allow-Origin:' . $host);
            header('Access-Control-Allow-Methods:POST,GET,PUT');
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }

    }
    /**
     * 刷点赞数
     */
    public function addYearGoodAction(){
        $vid = Request::getQuery('vid','int');
        $val = Request::getQuery('val','int');
        $pwd = Request::getQuery('pwd','string');
        if($pwd == "pkhsy2345") {
            $key = 'pkhsy_user_good:' . $vid;
            $res = RedisIO::set($key, $val);   //设置点赞数
            if ($res) {
                echo RedisIO::get($key);
            } else {
                echo "刷新出错！";
            }
        } else {
            echo "密码错误";
        }

    }

    /**
     * 刷点赞数周
     */
    public function addWeekGoodAction(){
        $vid = Request::getQuery('vid','int');
        $val = Request::getQuery('val','int');
        $pwd = Request::getQuery('pwd','string');
        if($pwd == "pkhsy2345") {
            $key = 'pkhsy_user_good:' . date('W') . ':' . $vid;
            $res = RedisIO::set($key, $val);   //设置点赞数
            if ($res) {
                echo RedisIO::get($key);
            } else {
                echo "刷新出错！";
            }
        } else {
            echo "密码错误";
        }

    }

    /**
     * 点赞接口
     * @return int 返回点赞数
     */
    public function goodAction()
    {
        $vid = Request::getQuery('vid', 'int');

        $key = 'videoId:' . $vid;
        if(RedisIO::exists($key) && $data = RedisIO::get($key)){
            $ip = $this->getClientIp();    //获取客户端IP
            $userGood = RedisIO::incr(md5("pkhsy_" . $ip . $vid . date("Y-m-d")));
            //每天只能点赞10次
            if ($userGood > 10) {
                $y_good = RedisIO::get('pkhsy_user_good:' . $vid);   //获取当前点赞次数
                $this->_json($y_good, 4100, '对不起！你今天的点赞次数超出。');
                exit;
            }
            $y_good = RedisIO::incr('pkhsy_user_good:' . $vid);   //递增点赞次数
            $w_good = RedisIO::incr('pkhsy_user_w_good:' . date('W') . ':' . $vid);   //递增周点赞次数

            $data = json_decode($data, true);
            $data['y_good'] = $y_good;
            $data['w_good'] = $w_good;
            $data['what_gui'] = 'gui';
            $type = $data['type'];      //节目类型
            $data = json_encode($data);
            $res = RedisIO::set('videoId:' . $vid, $data);

            //如果不是原创内容，就存入有序集合，进行排序
            if (!isset($type['yc'])) {
                RedisIO::zAdd('pkHsySort', $y_good, $vid);                      //总排序有序集合
                RedisIO::zAdd('pkHsySort:' . date('W'), $w_good, $vid);         //周排序有序集合
            }

        } else {
            //$content = date('Y-m-d H:i:s') . json_encode(Request::getQuery()) . "\n";
            //file_put_contents('videoid.log', $content, FILE_APPEND);
            $this->_json([], 404, '非法操做');
        }


        //返回数据
        if (false !== $res) {
            $this->_json($y_good);
        } else {
            $this->_json([], 404, 'error');
        }

    }

    /**
     * 获取点赞数
     */
    public function getGoodAction()
    {
        $vid = Request::getQuery('vid','int');
        $res = json_decode(RedisIO::get("videoId:" . $vid), true);
        if (false !== $res) {
            $this->_json($res['y_good']);
        } else {
            $this->_json([], 4200, '获取点赞数失败！');
        }
    }

    /**
     * 获取点赞排名
     */
    public function getGoodSortAction()
    {
        $page = Request::getQuery('page','int');  //分页
        $page_size = 100;                          //每几显示数
        $type = Request::getQuery('type');
        if ($type == 'week') {
            $res = RedisIO::zRevRange('pkHsySort:' . date('W'), ($page - 1) * $page_size, $page * $page_size - 1);  //获取周排行
        } else {
            $res = RedisIO::zRevRange('pkHsySort', ($page - 1) * $page_size, $page * $page_size - 1);               //总排行
        }

        if (false !== $res) {
            $data = array();
            //过滤$val范围
            foreach ($res as $val) {
                $val = intval($val);
                $value = RedisIO::get('videoId:' . $val);  //获取数据
                if($val > 0 && $val < 10000 && $value) {
                    $data[$val] = json_decode($value, true);
                }
            }
            $this->_json($data);
        } else {
            $this->_json([], 404, 'error');
        }
    }




    /**
     * 获取热门视频接口
     */
    public function getRmAction()
    {
        $page = Request::getQuery('page','int');
        $page_size = 100;
        $res = RedisIO::lRange('rmVideoList', ($page - 1) * $page_size, $page * $page_size - 1);

        if (false !== $res) {
            $data = array();
            foreach ($res as $val) {
                $value = RedisIO::get('videoId:' . $val);  //获取数据
                if($value) {
                    $data[$val] = json_decode($value, true);
                }

            }
            $this->_json($data);
        } else {
            $this->_json([], 404, 'error');
        }
    }

    /**
     * 获取原创视频接口
     */
    public function getYcAction()
    {
        $page = Request::getQuery('page','int');
        $page_size = 100;
        $res = RedisIO::lRange('ycVideoList', ($page - 1) * $page_size, $page * $page_size - 1);

        if (false !== $res) {
            $data = array();
            foreach ($res as $val) {
                $value = RedisIO::get('videoId:' . $val);  //获取数据
                if($value) {
                    $data[$val] = json_decode($value, true);
                }
            }
            $this->_json($data);
        } else {
            $this->_json([], 404, 'error');
        }
    }

    /**
     * 获取原创视频接口
     */
    public function getRqAction()
    {
        $page = Request::getQuery('page','int',1);

        $page_size = 100;
        $res = RedisIO::lRange('rqVideoList', ($page - 1) * $page_size, $page * $page_size - 1);

        if (false !== $res) {
            $data = array();
            foreach ($res as $val) {
                $value = RedisIO::get('videoId:' . $val);  //获取数据
                if($value) {
                    $data[$val] = json_decode($value, true);
                }
            }
            $this->_json($data);
        } else {
            $this->_json([], 404, 'error');
        }
    }

    /**
     * 获取brand接口
     */
    public function getBrandAction()
    {
        $thumbListName = "thumbListName";
        $res = RedisIO::lRange($thumbListName, 0, -1);

        $data = array();
        foreach ($res as $value){
            $data[$value] = RedisIO::get("pkhsy_thumb:{$value}");
        }
        $this->_json($data);
    }

    /**
     * 提交评论接口
     */
    public function addComMentAction()
    {
        /*//每天只能评论5条
        $ip = $this->getClientIp();    //获取客户端IP
        $vid = Request::getPost('vid', 'int');
        $userComMent = RedisIO::incr(md5("pkhsy_" . $ip . $vid . date("Y-m-d")));
        //每天只能评论5次
        if($userComMent > 5){
            $this->_json($y_good,4100,'对不起！你今天的评论次数超出。');
            exit;
        }*/

        //添加评论
        if (Request::isPost()) {
            $content = Request::getPost('content', 'string');
            $commentModel = new Comment();
            $isFilter = $commentModel->commentFilter($content);  //过虑评论
            if($isFilter){
                $this->_json([], 4005, '非法评论!');
                exit;
            }
            $data['content'] = $content;
            $data['vid'] = Request::getPost('vid', 'int');
            $vid = Request::getPost('vid', 'int');
            $comMentList = 'comMentList' . $data['vid'];  //设置评论链表
            $cid = RedisIO::incr('pkhsy_cid');            //设置自增评论ID
            $data['id'] = $cid;                           //压入评论ID
            $data['user_name'] = '蓝朋友' . substr(uniqid(), 10);   //生成用户名
            $data['status'] = 0;                             //评论状态 0
            $data['create_time'] = date('Y-m-d H:i');
            RedisIO::lPush($comMentList, $cid);   //存入评论链表
            $res = RedisIO::set('pkhsy_cid_' . $cid, json_encode($data));    //存入详细评论内容
            //$ret = RedisIO::get("pkhsy_cid_" . $cid);
            $res = $this->upComMent($vid);


            if (false !== $res) {
                $this->_json($cid);
            } else {
                $this->_json([], 404, 'error');
            }
        } else {
            $this->_json([], 401, 'error');
        }
    }

    /**
     * 获取评论接口
     */
    public function getComMentAction()
    {
        $page = Request::getQuery('page', 'int', 1);   //分页
        $page_size = 10;                             //每页显示数
        $comMentList = 'comMentList' . Request::getQuery('vid', 'int');
        $arr = RedisIO::lRange($comMentList, ($page - 1) * $page_size, $page * $page_size - 1);
        if (false !== $arr) {
            $data = array();
            foreach ($arr as $val) {
                $data[$val] = json_decode(RedisIO::get('pkhsy_cid_' . $val), true);
                //过虑已核的评论
                if ($data[$val]['status'] != 1) {
                    unset($data[$val]);
                }
            }
            //var_dump($data);
            $this->_json($data);
        } else {
            $this->_json([], 404, 'error');
        }
    }

    /**
     * 获取视频详细信息
     */
    public function getVideoInfoAction()
    {
        $vid = Request::getQuery('vid', 'int');
        $info = json_decode(RedisIO::get('videoId:' . $vid), true);
        if (false !== $info) {
            $this->_json($info);
        } else {
            $this->_json([], 404, 'error');
        }
    }

    /**
     * @param $data
     * @param int $code
     * @param string $msg
     */
    protected function _json($data, $code = 200, $msg = "success")
    {
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }

    /**
     * @param $vid
     * @return bool
     */
    public function upComMent($vid)
    {
        //修改评论记录
        $info = json_decode(RedisIO::get('videoId:' . $vid), true);
        $arr = RedisIO::lRange("comMentList" . $vid, 0, -1);   //获取评论总数

        //计算通过审核的评论数
        if (false !== $arr) {
            $comments = array();
            foreach ($arr as $val) {
                $comments[$val] = json_decode(RedisIO::get('pkhsy_cid_' . $val), true);
                //过虑已核的评论
                if ($comments[$val]['status'] != 1) {
                    unset($comments[$val]);
                }
            }
        }
        //获取评论总数
        $info['comments'] = count($comments);
        $res = RedisIO::set('videoId:' . $vid, json_encode($info));
        return $res;
    }

    /**
     * 设置默认站点
     * (non-PHPdoc)
     * @see BaseController::defaultDomainCheck()
     */
    protected function defaultDomainCheck($host)
    {
        $this->domain_id = 6;
        $this->channel_id = 1;
        return true;
    }



    /**
     * 获取客户端IP地址
     * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
     * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
     * @return mixed
     */
    protected function getClientIp($type = 0, $adv = false)
    {
        $type = $type ? 1 : 0;
        static $ip = null;
        if (null !== $ip) {
            return $ip[$type];
        }

        if ($adv) {
            if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
                $pos = array_search('unknown', $arr);
                if (false !== $pos) {
                    unset($arr[$pos]);
                }

                $ip = trim($arr[0]);
            } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
                $ip = $_SERVER['HTTP_CLIENT_IP'];
            } elseif (isset($_SERVER['REMOTE_ADDR'])) {
                $ip = $_SERVER['REMOTE_ADDR'];
            }
        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        // IP地址合法验证
        $long = sprintf("%u", ip2long($ip));
        $ip = $long ? array($ip, $long) : array('0.0.0.0', 0);
        return $ip[$type];
    }


}