<?php

/**
 * Created by PhpStorm.
 * User: zhangyichi
 * Date: 2016/8/29
 * Time: 10:00
 */
class VoteController extends InteractionBaseController
{

    public function initialize()
    {
        parent::initialize();
        $this->crossDomain();
        header('Cache-Control: max-age=1');
    }

    /**
     * 允许跨域请求
     */
    private function crossDomain()
    {
        $host = isset($_SERVER['HTTP_ORIGIN']) ? $_SERVER['HTTP_ORIGIN'] : '';

        if (false !== strpos($host, 'cztv')) {
            header('content-type:application:json;charset=utf8');
            header('Access-Control-Allow-Origin:' . $host);
            header('Access-Control-Allow-Methods:POST,GET,PUT');
            header("Access-Control-Allow-Credentials: true");
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }

//        header('content-type:application:json;charset=utf8');
//        header('Access-Control-Allow-Origin:*' );
//        header('Access-Control-Allow-Methods:POST,GET,PUT');
//        header('Access-Control-Allow-Headers:x-requested-with,content-type');

    }

    const VOTE_DATA_MODEL = 'cztv::vote::data::model::';
    const VOTE_IP = 'cztv::vote::ip::';

    //获取投票
    public function getVoteAction()
    {
        $input = Request::getQuery();
        if (isset($input['vote_id'])) {
            $vote_id = $input['vote_id'];
            if (is_numeric($vote_id)) {
                $return = self::getVote($vote_id);
                if ($return) {
                    $return['code'] = 200;
                    $this->jsonp($return);
                } else {
                    $this->jsonp(array('code' => 2004, 'msg' => '参数不为投票id'));
                }
            } else {
                $this->jsonp(array('code' => 2003, 'msg' => '参数类型错误'));
            }
        } else {
            $this->jsonp(array('code' => 2001, 'msg' => '参数为空'));
        }
    }

    /**
     * 新的投票接口
     */
    public function getVoteNewAction()
    {
        $input = Request::getQuery();
        if (isset($input['vote_id'])) {
            $vote_id = $input['vote_id'];
            $limit = $input['limit'];
            $offset = $input['offset'];
            if (is_numeric($vote_id)) {
                $return = self::getVoteNew($vote_id, $limit, $offset);
                if ($return) {
                    if ($return['options'] == 'nodata') {
                        $this->jsonp(array('code' => 2005, 'msg' => '没有数据'));
                    }
                    $return['code'] = 200;
                    $this->jsonp($return);
                } else {
                    $this->jsonp(array('code' => 2004, 'msg' => '参数不为投票id'));
                }
            } else {
                $this->jsonp(array('code' => 2003, 'msg' => '参数类型错误'));
            }
        } else {
            $this->jsonp(array('code' => 2001, 'msg' => '参数为空'));
        }
    }

    private function getVote($vote_id)
    {
        $vote = RedisIO::get(self::VOTE_DATA_MODEL . $vote_id);
        if ($vote) {
            $return = json_decode($vote, true);
        } else {
            $data_vote = Data::getMediaByDataId($vote_id);
            $data = $data_vote[0];//媒资
            $vote = $data_vote[1];//投票
            if ($data->type != 'vote') {
                return false;
            }
            $vote_option_id = explode(',', $vote->option_id);
            $vote_option = [];//选项
            if ($vote_option_id == null || empty($vote_option_id)) {

            } else {
                foreach ($vote_option_id as $k => $option_id) {
                    $option = VoteOption::findOptionById($option_id);
                    if ($option->video_url) {
                        $option->video_url = cdn_url('image', $option->video_url);
                    }
                    if (is_numeric($option->sum)) {
                        $option->sum = (int)$option->sum;
                    }
                    $vote_option[] = $option;
                }
            }
            $return = array('data' => $data, 'vote' => $vote, 'vote_option' => $vote_option);
            $return_json = json_encode($return);
            RedisIO::set(self::VOTE_DATA_MODEL . $vote_id, $return_json);
            $return = json_decode($return_json, true);
        }
        return $return;
    }

    /**
     * 微电影搜索
     */
    public function searchAction()
    {
        $vote_id = intval(Request::getQuery('vote_id', 'int'));
        $vote = json_decode(RedisIO::get(self::VOTE_DATA_MODEL . $vote_id), true);
        $keyword = Request::getQuery('keyword', 'string', '');
        if (empty($keyword)) {
            $this->jsonp(array('code' => 2001, 'msg' => 'keyword Not empty!'));
        }
        $data = array();
        foreach ($vote['vote_option'] as $value) {
            if (false !== strpos($value['content'], $keyword)) {
                $data[] = $value;
            }
        }
        $return = array('data' => 0, 'vote' => 0, 'vote_option' => $data, 'code' => 200);
        $this->jsonp($return);

    }

    /**
     * 全球榜搜索
     */
    public function searchNewAction()
    {
        $keyword = Request::getQuery('keyword', 'string', '');
        if (empty($keyword)) {
            $this->jsonp(array('code' => 2001, 'msg' => 'keyword Not empty!'));
        }
        $vote_id = Request::getQuery('vote_id', 'string');
        $vote_arr = explode(',', $vote_id);
        $data = [];
        foreach ($vote_arr as $k => $val) {
            $vote = RedisIO::hGetAll(self::VOTE_DATA_MODEL. $val . '::hash');
            $option_ids = RedisIO::zRevRange(self::VOTE_DATA_MODEL . $val . '::zset', 0, -1, withscores);

            if (!$vote || !$option_ids) {
                continue;
            }
            foreach ($vote as $k => $value) {
                $vote[$k] = json_decode($value, true);
                if (false !== strpos($vote[$k]['content'], $keyword)) {
                    $vote[$k]['id'] = $k;
                    $vote[$k]['sum'] = $option_ids[$k];
                    $vote[$k]['data_id'] = $val;
                    if ($vote[$k]['picture']) {
                        $vote[$k]['picture'] = cdn_url('image', $vote[$k]['picture']);
                    }
                    if ($vote[$k]['video_url']) {
                        $vote[$k]['video_url'] = cdn_url('image', $vote[$k]['picture']);
                    }
                    $data[] = $vote[$k];
                }
            }
        }
        foreach ($data as $key => $val){
            $name[$key] = $val['sum'];
        }
        array_multisort($name,SORT_DESC,SORT_NUMERIC, $data);
        $return = array('data' => 0, 'vote' => 0, 'vote_option' => $data, 'code' => 200);
        $this->jsonp($return);

    }

    private function getVoteNew($vote_id, $limit = 10, $offset = 0) {
        // 设置为长缓存，根据缓存时长去刷新
        $data_vote = Data::getMediaByDataId($vote_id);
        $data = $data_vote[0];//媒资
        $vote = $data_vote[1];//投票
        if ($data->type != 'vote') {
            return false;
        }
        $start = $offset;
        $end = $limit + $offset -1;
        $count = RedisIO::zSize(self::VOTE_DATA_MODEL . $vote_id . '::zset');
        if ($count && $start + 1 > $count) {
            return  array('options' => 'nodata');
        }
        $option_ids = RedisIO::zRevRange(self::VOTE_DATA_MODEL . $vote_id . '::zset', $start, $end, withscores);
        $option_id = [];
        foreach ($option_ids as $k => $v) {
            $option_id[] = $k;
        }
        $options = RedisIO::hmGet(self::VOTE_DATA_MODEL. $vote_id . '::hash', $option_id);
        if (!$option_ids || !$options) {
            $vote_option_id = explode(',', $vote->option_id);
            $vote_option = [];//选项
            if ($vote_option_id == null || empty($vote_option_id)) {

            } else {
                $vote_option = VoteOption::getOptionsByVoteId($vote->id);
                foreach ($vote_option as $k => $val) {
                    if (is_numeric($val['sum'])) {
                        $vote_option[$k]['sum'] = (int)$val['sum'];
                    }
                    $starData = [];
                    $starData['content'] = $vote_option[$k]['content'];
                    $starData['vote_id'] = $vote_option[$k]['vote_id'];
                    $starData['picture'] = $vote_option[$k]['picture'];
                    $starData['video_url'] = $vote_option[$k]['video_url'];
                    $starData['other'] = $vote_option[$k]['other'];
                    $starData['number'] = $vote_option[$k]['number'];
                    $starData['actual_sum'] = $vote_option[$k]['actual_sum'];
                    RedisIO::hSet(self::VOTE_DATA_MODEL. $vote_id . '::hash', $vote_option[$k]['id'], json_encode($starData));
                    RedisIO::zAdd(self::VOTE_DATA_MODEL. $vote_id . '::zset', $vote_option[$k]['sum'], $vote_option[$k]['id']);
                };
            }
            $option_ids = RedisIO::zRevRange(self::VOTE_DATA_MODEL . $vote_id . '::zset', $start, $end, withscores);
            $option_id = [];
            foreach ($option_ids as $k => $v) {
                $option_id[] = $k;
            }
            $options = RedisIO::hmGet(self::VOTE_DATA_MODEL. $vote_id . '::hash', $option_id);
        }

        foreach ($options as $k => $v) {
            $options[$k] = json_decode($v, true);
            $options[$k]['id'] = $k;
            $options[$k]['sum'] = $option_ids[$options[$k]['id']];
            $options[$k]['rank'] = $start + 1;
            if ($options[$k]['picture']) {
                $options[$k]['picture'] = cdn_url('image', $options[$k]['picture']);
            }
            if ($options[$k]['video_url']) {
                $options[$k]['video_url'] = cdn_url('image', $options[$k]['picture']);
            }
            unset($options[$k]['actual_sum']);
            $option[] = $options[$k];
            $start ++;
        }

        $return = array('data' => $data, 'vote' => $vote, 'vote_option' => $option);
        $return_json = json_encode($return);
        $return = json_decode($return_json, true);
        return $return;
    }

    /**
     * 获取统计
     * 李红刚
     * 2019-07-10
     */
    public function getCountAction()
    {
        $input = Request::getQuery();
        if (isset($input['vote_id'])) {
            $vote_id = $input['vote_id'];
            $vote = RedisIO::get(self::VOTE_DATA_MODEL . $vote_id . '::count') ? RedisIO::get(self::VOTE_DATA_MODEL . $vote_id . '::count') : 0;
            $return['data'] = $vote;
            if ($return) {
                $return['code'] = 200;
                $this->jsonp($return);
            } else {
                $this->jsonp(array('code' => 2004, 'msg' => '参数不为投票id'));
            }
        } else {
            $this->jsonp(array('code' => 2001, 'msg' => '参数为空'));
        }
    }
}