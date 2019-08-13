<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Comment extends Model {
    const EXPORT_LIMIT = 10000;
    protected $_cacheConf = false;
    /**
     * 来源信息
     * @var array
     */
    protected $_fromType = array(
        '1' => 'PC',
        '2' => 'iPhone',
        '3' => 'Ipad',
    );


    public static $properties = [
        'data_id' => '视频id',
        'user_id' => '用户id',
        'username' => '昵称',
        'content' => '评论内容',
        'create_at' => '创建时间',
        'status' => '状态'
    ];

    /**
     * 审核配置信息
     *
     * @var array
     */
    public $_authConfig = array(
        'authRule' => 0,
        'authInterval' => 30,        //发帖间隔
        'authCRuntime' => 43200,        //发帖频率-时长，单位秒
        'authCnum' => 50,        //发帖频率-评论数量 同一个uid一天最多发50条
        'authPidArr' => array(),    //审核限制的专辑(_authRule=0 先发后审时有效)
        'authXidArr' => array()    //审核限制的单视频(_authRule=0 先发后审时有效)
    );


    public function onConstruct() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
        $this->__getAuditConf();
    }

    public function getSource() {
        return 'comment';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'comment_id', 'channel_id', 'user_id', 'username', 'data_id', 'cid', 'pid', 'type', 'title', 'cmtType', 'father_id', 'content', 'create_at', 'status', 'replynum', 'likes', 'down', 'domain', 'client', 'ip', 'location', 'partition_by',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'user_id', 'username', 'data_id', 'cid', 'pid', 'type', 'title', 'cmtType', 'father_id', 'content', 'create_at', 'status', 'replynum', 'likes', 'down', 'domain', 'client', 'ip', 'location',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'user_id', 'username', 'data_id', 'cid', 'pid', 'type', 'title', 'cmtType', 'father_id', 'content', 'create_at', 'status', 'replynum', 'likes', 'down', 'domain', 'client', 'ip', 'location', 'partition_by',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'comment_id' => Column::TYPE_CHAR,
                'channel_id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'username' => Column::TYPE_VARCHAR,
                'data_id' => Column::TYPE_INTEGER,
                'cid' => Column::TYPE_INTEGER,
                'pid' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_CHAR,
                'title' => Column::TYPE_VARCHAR,
                'cmtType' => Column::TYPE_CHAR,

                'father_id' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_VARCHAR,
                'create_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'replynum' => Column::TYPE_INTEGER,
                'likes' => Column::TYPE_INTEGER,
                'down' => Column::TYPE_INTEGER,
                'domain' => Column::TYPE_VARCHAR,
                'client' => Column::TYPE_INTEGER,
                'ip' => Column::TYPE_VARCHAR,
                'location' => Column::TYPE_VARCHAR,
                'partition_by' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'comment_id', 'channel_id', 'user_id', 'data_id', 'cid', 'pid', 'father_id', 'create_at', 'status', 'replynum', 'likes', 'down', 'client', 'partition_by',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'comment_id' => Column::BIND_PARAM_STR,
                'channel_id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'username' => Column::BIND_PARAM_STR,
                'data_id' => Column::BIND_PARAM_INT,
                'cid' => Column::BIND_PARAM_INT,
                'pid' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_STR,
                'title' => Column::BIND_PARAM_STR,
                'cmtType' => Column::BIND_PARAM_STR,

                'father_id' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'create_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'replynum' => Column::BIND_PARAM_INT,
                'likes' => Column::BIND_PARAM_INT,
                'down' => Column::BIND_PARAM_INT,
                'domain' => Column::BIND_PARAM_STR,
                'client' => Column::BIND_PARAM_INT,
                'ip' => Column::BIND_PARAM_STR,
                'location' => Column::BIND_PARAM_STR,
                'partition_by' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'comment_id' => '',
                'type' => '',
                'cmtType' => '',
                'status' => '0',
                'likes' => '0',
                'client' => 'ios'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    //+++++++++++++++++++++++++++++++++++++++NEW ADD+++++++++++++++++++++++++++++++++++++++++++++

    /**
     * @desc 获取多个评论ID的评论内容.
     * @param array $ids
     * @param string $type
     * @return array
     */
    public function getCommentByIds($ids = array(), $type = 'video') {
        if (empty($ids) || !is_array($ids)) {
            return array();
        }
        $memCommentList = $this->__getCommentByIdsFromCache($ids, $type);
        $memCommentList = (empty($memCommentList) || !is_array($memCommentList)) ? array() : $memCommentList;
        foreach ($ids as $index => $commentid) {
            if (isset($memCommentList[$commentid])) { //如果缓存拿到则删除该键值
                unset($ids[$index]);
            }
        }
        if (empty($ids)) { //如果从缓存拿到全部评论,则返回缓存结果
            return $memCommentList;
        }

        // 如果缓存拿不到部分或只拿到全部结果,则从数据库查询结果
        $sqlCommentList = $this->__getCommentByIdsFromMysql($ids, $type);
        //  raojia Added 2016/07/11
        // 数据库字段转换
        $dbCommentList = array();
        if (!empty($sqlCommentList) && is_array($sqlCommentList)) {
            foreach ($sqlCommentList as $item) {
                $dbCommentList = array($item['comment_id'] => array(
                        'commentId' => $item['comment_id'],
                        'uid' => $item['user_id'],
                        'ssouid' => $item['user_id'],
                        'cid' => isset($item['cid']) ? $item['cid'] : 0,
                        'pid' => isset($item['pid']) ? $item['pid'] : 0,
                        'xid' => $item['data_id'],
                        'mmsid' => 0,
                        'username' => isset($item['username']) ? $item['username'] : '',
                        'type' => isset($item['type']) ? $item['type'] : '',
                        'ctime' => $item['create_at'],
                        'title' => isset($item['title']) ? $item['title'] : '',
                        'content' => $item['content'],
                        'cmtType' => $item['cmtType'],
                        'like' => isset($item['likes']) ? $item['likes'] : 0,
                        'share' => 0,
                        'ip' => $item['ip'],
                        'area' => isset($item['location']) ? $item['location'] : '未知',
                        'from' => isset($item['client']) && $item['client'] === "pc" ? 1 : ((isset($item['client']) && $item['client'] === "ios") ? 2 : 3),
                        'ispic' => false,
                        'imgurl' => "",
                        'htime' => 0,
                        'source' => isset($item['client']) && $item['client'] === "pc" ? 1 : ((isset($item['client']) && $item['client'] === "ios") ? 2 : 3),
                        'replyid' => 0,
                        'replynum' => 0,
                        'replycommentid' => isset($item['father_id']) ? $item['father_id'] : null,
                    )) + $dbCommentList;
            }
        }
        //释放掉数据库记录
        unset($sqlCommentList);
        //var_dump($dbCommentList);die();
        return $dbCommentList;
    }

    /**
     * @desc 修改缓存评论内容的状态
     * @param string $memkey
     * @param int $status
     * @return boolean
     */
    public function setCommentCacheStatus($memkey, $status) {
        //修改缓存评论内容的状态
        $memComment = json_decode(RedisIO::get($memkey), true);
        $memComment['status'] = $status;
        RedisIO::set($memkey, json_encode($memComment));
        return true;
    }


    /**
     * @desc 获取多个评论ID的评论内容，从缓存获取
     * @access private
     * @param array $ids (default: array())
     * @param mixed $type
     * @return void
     */
    private function __getCommentByIdsFromCache($ids = array(), $type = 'video') {
        $memCacheKeysToCommentIdMap = array();
        $memCommentList = array();
        foreach ($ids as $id) {
            $memCacheKey = "comment_" . $type . "_commentInfos_" . $id;
            $memCommentList[$id] = json_decode(RedisIO::get($memCacheKey), true);
            //检查评论数据中的审核状态
            if(!isset($memCommentList[$id]['status'])) {
                $comment = Comment::getComment($id, LETV_CHANNEL_ID);
                if($comment->status==Comment::ACCEPT) {
                    RedisIO::incr("comment_accept_count_" . $comment->data_id);//通过审核评论总数增加1
                    RedisIO::zAdd("comment_accept_" . $comment->type . "_" . $comment->data_id . "_commentIds", $comment->comment_id, $comment->comment_id);
                }
                $this->setCommentCacheStatus($memCacheKey, $comment->status);
            }

            $memCacheKeysToCommentIdMap[$memCacheKey] = $id;
        }
        if (empty($memCommentList)) {
            return array();
        }

        $returnMemCommentList = array();
        foreach ($memCommentList as $memCacheKey => $data) {
            $memCacheKey = "comment_" . $type . "_commentInfos_" . $memCacheKey;
            if (!isset($memCacheKeysToCommentIdMap[$memCacheKey])) {
                continue;
            }
            $commentid = $memCacheKeysToCommentIdMap[$memCacheKey];
            $returnMemCommentList[$commentid] = $data;
        }
        unset($memCommentList);
        return $returnMemCommentList;
    }

    /**
     * @desc 获取多个评论ID的评论内容，从数据库取
     * @param array $ids
     * @param string $type
     * @return array
     */
    private function __getCommentByIdsFromMysql($ids = array(), $type = 'video') {
        foreach ($ids as $key => $val) {
            $ids[$key] = "'" . $val . "'";
        }
        $strIds = implode(",", $ids);
        if ($this->getAuthRule() == 0) {
            $status = array(0, 1);
        } else {
            $status = array(1);
        }
        $comments = $this->query()
            ->andWhere("comment_id in ({$strIds})")
            ->inWhere("status", $status)
            ->orderBy("id DESC")
            ->execute()
            ->toArray();
        return $comments;
    }

    /**
     * @desc 获得审核方式
     * @param int $pid
     * @param int $xid
     * @return boolean
     */
    public function getAuthRule($pid = 0, $xid = 0) {
        global $config;
        $authRule = $this->_authConfig['authRule'];
        if ($config['_authRuleOff'] == $authRule) {
            if ($pid) {
                if (in_array($pid, $this->_authConfig['authPidArr'])) $authRule = 1;
            } else {
                if ($xid > 0 && in_array($xid, $this->_authConfig['authXidArr'])) $authRule = 1;
            }
        }
        return intval($authRule);
    }


    //根据IP得到归属地
    public function getCityFromIP($ipInt = 0) {
        return '未知';

        if (empty($ipInt)) return '未知';


        $ipInt = floatval($ipInt);
        $query = array(
            "intbegin" => array(
                '$lte' => $ipInt
            ),
            "intend" => array(
                '$gte' => $ipInt
            )
        );
        try {
            return '未知';
            //$mdbrIp       = $this->mongo('mdbrIp');
            //$ipItem = $mdbrIp->findOne("ipdata", $query);

        } catch (Exception $e) {

            return '未知';
        }

        if (empty($ipItem) || !is_array($ipItem) || !isset($ipItem['city'])) return '未知';

        return $ipItem['city'];
    }


    /**
     * 获取访问者来源
     * 默认：1：pc
     * @return int $from 来源标识 1：pc 2：iphone 3：ipad
     */
    public function get_agent_from() {
        $from = 1;
        if (isset($_SERVER["HTTP_USER_AGENT"]) && preg_match('~iPhone~is', $_SERVER['HTTP_USER_AGENT'])) {
            $from = 2;//iPhone
        } elseif (isset($_SERVER["HTTP_USER_AGENT"]) && preg_match('~iPad~is', $_SERVER['HTTP_USER_AGENT'])) {
            $from = 3;//iPad
        }
        return $from;
    }


    /**
     * 获取安全字符串.
     *
     * @access protected
     * @param mixed $textString
     * @param bool $htmlspecialchars (default: true)
     * @return void
     */
    public function __getSafeText($textString, $htmlspecialchars = true) {
        return $htmlspecialchars ? htmlspecialchars(trim(strip_tags($textString)),ENT_NOQUOTES,'UTF-8') : trim(strip_tags($textString));
    }

    public function __call($method, $arguments) {
        parent::__call($method, $arguments); // TODO: Change the autogenerated stub
    }

    /**
     * 检测字符长度函数
     *
     * @access protected
     * @param mixed $string
     * @param string $min (default: "")
     * @param string $max (default: "")
     * @return void
     */
    public function __checkStringLength($string, $min = "", $max = "") {
        $length = mb_strlen($string, 'UTF-8');
        if ($min != "" && $length < $min) {
            return false;
        }
        if ($max != "" && $length > $max) {
            return false;
        }

        return true;
    }

    /**
     * 新版敏感词过滤.
     *
     * @access public
     * @param mixed $content
     * @return void
     */
    public function commentFilter($content) {//TODO 安装PHP敏感词扩展
        $vowels = array(
            '`' => '', '~' => '', '!' => '', '@' => '', '#' => '', '$' => '', '%' => '', '^' => '', '&' => '', '*' => '', '(' => '', ')' => '', '-' => '', '_' => '', '=' => '', '+' => '',
            '[' => '', ']' => '', '{' => '', '}' => '', '\\' => '', '|' => '', ';' => '', ':' => '', '"' => '', '\'' => '', ',' => '', '<' => '', '>' => '', '.' => '', '/' => '', '?' => '',
            '零' => '0', '壹' => '1', '贰' => '2', '参' => '3', '肆' => '4', '伍' => '5', '陆' => '6', '柒' => '7', '捌' => '8', '玖' => '9',
            '①' => '1', '②' => '2', '③' => '3', '④' => '4', '⑤' => '5', '⑥' => '6', '⑦' => '7', '⑧' => '8', '⑨' => '9',
            '一' => '1', '二' => '2', '三' => '3', '四' => '4', '五' => '5', '六' => '6', '七' => '7', '八' => '8', '九' => '9',
            '㈠' => '1', '㈡' => '2', '㈢' => '3', '㈣' => '4', '㈤' => '5', '㈥' => '6', '㈦' => '7', '㈧' => '8', '㈨' => '9', '㈩' => '10',
            'Ⅰ' => '1', 'Ⅱ' => '2', 'Ⅲ' => '3', 'Ⅳ' => '4', 'Ⅴ' => '5', 'Ⅶ' => '6', 'Ⅷ' => '7', 'Ⅸ' => '8', 'Ⅹ' => '9',
            '⒈' => '1', '⒉' => '2', '⒊' => '3', '⒋' => '4', '⒌' => '5', '⒍' => '6', '⒎' => '7', '⒏' => '8', '⒐' => '9', '⒑' => '10',
            '⑴' => '1', '⑵' => '2', '⑶' => '3', '⑷' => '4', '⑸' => '5', '⑹' => '6', '⑺' => '7', '⑻' => '8', '⑼' => '9', '⑽' => '10',
        );

        //替换关键词,方便过滤工作
        $tmpNumArr = array();
        $tmpcontent = strtr($this->__qj2bj($content), $vowels);

        preg_match_all('~[0-9]~is', $tmpcontent, $tmpNumArr);
        $tmpNumCount = count($tmpNumArr['0']);
        if ($tmpNumCount >= 11) return true;
        $strContent = $content;
        $newArrWord = array();

        $resTrie = trie_filter_get(TRIEFILTERID);
        $rsRefresh = $this->isRefreshKeywords();
        if (!$resTrie || $rsRefresh)//如果获取内存中的敏感词为空或者修改过敏感词，就执行数据库查询，并存储到到内存中
        {
            //error_log("select \r\n", 3 , "/tmp/triefilter.log");
            $resTrie = trie_filter_new(TRIEFILTERID, true);
            $arrWord = AuditCommentKeywords::find(array("conditions" => "type='filter'"))->toArray();
            foreach ($arrWord as $val) {
                $newArrWord[] = $val['keyword'];
            }
            foreach ($newArrWord as $v) {
                trie_filter_store($resTrie, $v);
            }
            $pidKey = $_SERVER['SERVER_ADDR'] . '_' . getmypid();
            $publicVersion = $this->getPbulicVersion();
            RedisIO::set($pidKey, $publicVersion);
        }
        $arrRet = trie_filter_search($resTrie, $strContent);

        if (count($arrRet)) {
            return TRUE;
        }
        return FALSE;
    }

    public function trieFilterFree() {
        $resTrie = trie_filter_get(TRIEFILTERID);
        if ($resTrie) {
            trie_filter_free($resTrie);//释放内存中的敏感词
            return true;
        }
        return false;
    }

    /**
     * 旧版敏感词过滤，如果新版出问题，可以重装旧版敏感词扩展，启用该方法.
     *
     * @access public
     * @param mixed $content
     * @return void
     */
    public function commentFilterOld($content) {
        $vowels = array(
            '`' => '', '~' => '', '!' => '', '@' => '', '#' => '', '$' => '', '%' => '', '^' => '', '&' => '', '*' => '', '(' => '', ')' => '', '-' => '', '_' => '', '=' => '', '+' => '',
            '[' => '', ']' => '', '{' => '', '}' => '', '\\' => '', '|' => '', ';' => '', ':' => '', '"' => '', '\'' => '', ',' => '', '<' => '', '>' => '', '.' => '', '/' => '', '?' => '',
            '零' => '0', '壹' => '1', '贰' => '2', '参' => '3', '肆' => '4', '伍' => '5', '陆' => '6', '柒' => '7', '捌' => '8', '玖' => '9',
            '①' => '1', '②' => '2', '③' => '3', '④' => '4', '⑤' => '5', '⑥' => '6', '⑦' => '7', '⑧' => '8', '⑨' => '9',
            '一' => '1', '二' => '2', '三' => '3', '四' => '4', '五' => '5', '六' => '6', '七' => '7', '八' => '8', '九' => '9',
            '㈠' => '1', '㈡' => '2', '㈢' => '3', '㈣' => '4', '㈤' => '5', '㈥' => '6', '㈦' => '7', '㈧' => '8', '㈨' => '9', '㈩' => '10',
            'Ⅰ' => '1', 'Ⅱ' => '2', 'Ⅲ' => '3', 'Ⅳ' => '4', 'Ⅴ' => '5', 'Ⅶ' => '6', 'Ⅷ' => '7', 'Ⅸ' => '8', 'Ⅹ' => '9',
            '⒈' => '1', '⒉' => '2', '⒊' => '3', '⒋' => '4', '⒌' => '5', '⒍' => '6', '⒎' => '7', '⒏' => '8', '⒐' => '9', '⒑' => '10',
            '⑴' => '1', '⑵' => '2', '⑶' => '3', '⑷' => '4', '⑸' => '5', '⑹' => '6', '⑺' => '7', '⑻' => '8', '⑼' => '9', '⑽' => '10',
        );
        //替换关键词,方便过滤工作
        $tmpNumArr = array();
        $tmpcontent = strtr($this->__qj2bj($content), $vowels);
        preg_match_all('~[0-9]~is', $tmpcontent, $tmpNumArr);
        $tmpNumCount = count($tmpNumArr['0']);
        if ($tmpNumCount >= 11) {
            return true;
        }
        $strContent = $content;
        if (!file_exists('/tmp/blackword.tree')) {
            $resTrie = trie_filter_new(); //create an empty trie tree
            $arrWord = AuditCommentKeywords::find(array("conditions" => "type='filter'"))->toArray();
            foreach ($arrWord as $val) {
                $newArrWord[] = $val['keyword'];
            }
            foreach ($newArrWord as $v) {
                trie_filter_store($resTrie, $v);
            }

            trie_filter_save($resTrie, '/tmp/blackword.tree');
            error_log(date('Y-m-d H:i:s') . ':select' . "\n\r", 3, '/tmp/keywords.log');
        } else {
            $resTrie = trie_filter_load('/tmp/blackword.tree');
        }
        //判断是否存在过滤词
        $arrRet = trie_filter_search($resTrie, $strContent);
        if (count($arrRet)) {
            return TRUE;
        }
        return FALSE;
    }

    private function __qj2bj($string) {
        $convert_table = Array(
            '０' => '0', '１' => '1', '２' => '2', '３' => '3', '４' => '4', '５' => '5', '６' => '6', '７' => '7', '８' => '8', '９' => '9',
            'Ａ' => 'A', 'Ｂ' => 'B', 'Ｃ' => 'C', 'Ｄ' => 'D', 'Ｅ' => 'E', 'Ｆ' => 'F', 'Ｇ' => 'G', 'Ｈ' => 'H', 'Ｉ' => 'I', 'Ｊ' => 'J',
            'Ｋ' => 'K', 'Ｌ' => 'L', 'Ｍ' => 'M', 'Ｎ' => 'N', 'Ｏ' => 'O', 'Ｐ' => 'P', 'Ｑ' => 'Q', 'Ｒ' => 'R', 'Ｓ' => 'S', 'Ｔ' => 'T',
            'Ｕ' => 'U', 'Ｖ' => 'V', 'Ｗ' => 'W', 'Ｘ' => 'X', 'Ｙ' => 'Y', 'Ｚ' => 'Z', 'ａ' => 'a', 'ｂ' => 'b', 'ｃ' => 'c', 'ｄ' => 'd',
            'ｅ' => 'e', 'ｆ' => 'f', 'ｇ' => 'g', 'ｈ' => 'h', 'ｉ' => 'i', 'ｊ' => 'j', 'ｋ' => 'k', 'ｌ' => 'l', 'ｍ' => 'm', 'ｎ' => 'n',
            'ｏ' => 'o', 'ｐ' => 'p', 'ｑ' => 'q', 'ｒ' => 'r', 'ｓ' => 's', 'ｔ' => 't', 'ｕ' => 'u', 'ｖ' => 'v', 'ｗ' => 'w', 'ｘ' => 'x',
            'ｙ' => 'y', 'ｚ' => 'z', '　' => ' ', '：' => ':', '。' => '.', '？' => '?', '，' => ',', '／' => '/', '；' => ';', '［' => '[',
            '］' => ']', '｜' => '|', '＃' => '#', '——' => '-', '、' => '', '‘' => '\'', '“' => '"', '【' => '[', '】' => ']', '｛' => '{',
            '｝' => '}', '’' => '\'', '＼' => '\\', '～' => '~', '！' => '!', '＠' => '@', '￥' => '$', '％' => '%', '……' => '...', '＆' => '&',
            '× ' => '*', '（' => '(', '）' => ')', '＋' => '+', '＝' => '=', '·' => '.', '－' => '-',
        );
        return strtr($string, $convert_table);
    }

    /**
     * 评论加入删除表
     * 系统过滤
     *
     * @param array $cmt
     * @return array
     */
    public function addToDelete($cmt, $isComment = true) {
        //$mdbw 			= $this->getMdb();
        $cmt['uid'] = intval($cmt['uid']);
        $cmt['ssouid'] = intval($cmt['ssouid']);
        $cmt['isflag'] = 1;    //标识为系统过滤
        if (false === $isComment) {//不做实际操作
            //$result = $mdbw->insert("reply_deleted", $cmt);
        } else {
            //$result = $mdbw->insert("comment_deleted", $cmt);
        }
        return $cmt;
    }

    public function createCommentId($uid, $vid) {
        return $_SERVER['REQUEST_TIME'] . '_' . str_replace('.', '', $_SERVER['SERVER_ADDR']) . '_' . getmypid() . '_' . $uid . '_' . $vid . '_' . rand(1, 1000);
    }

    /**
     * 新增评论.
     *
     * @access public
     * @param array $cmt (default: array())
     * @return void
     */
    public function addComment($cmt = array(), $ctype = 0) {
        global $config;
        if (empty($cmt) || !is_array($cmt)) {
            return false;
        }
        $cmt['uid'] = intval($cmt['uid']);
        $cmt['ssouid'] = intval($cmt['ssouid']);
        $pid = intval($cmt['pid']);
        $xid = intval($cmt['xid']);
        $type = $cmt['type'];

        //生成评论id time_ip_pid_uid_vid
        $commentId = $this->createCommentId($cmt['uid'], $cmt['xid']);
        try {
            // 生成数据库表结构对应的键值对
            $commentData = array(
                'comment_id' => $commentId,
                'user_id' => $cmt['ssouid'],
                'channel_id' => isset($cmt['channel_id']) ? $cmt['channel_id'] : LETV_CHANNEL_ID,
                'username' => isset($cmt['username']) ? $cmt['username'] : '',
                'replynum' => 0,
                'down' => '',
                'domain' => '',
                'partition_by' => date('Ym'),
                'cid' => $cmt['cid'],
                'pid' => $cmt['pid'],
                'data_id' => $cmt['xid'],
                'type' => $cmt['type'],
                'create_at' => $cmt['ctime'],
                'title' => $cmt['title'],
                'content' => $cmt['content'],
                'cmtType' => $cmt['cmtType'],
                'likes' => $cmt['like'],
                'ip' => $cmt['ip'],
                'location' => $cmt['area'],
                // 2016/07/12 added
                'client' => isset($cmt['source']) && $cmt['source'] === '2' ? 'ios' : ((isset($cmt['source']) && $cmt['source'] === '3') ? 'android' : 'pc'),
                'father_id' => $cmt['replycommentid'],
            );

            // 将评论按先后顺序放入缓存队列
            RedisIO::lPush("comment_video".date('Ymd'), json_encode($commentData));
            if (WRITE_RULE) { // 0为异步入库方式,1为同步入库方式
                $flag1 = $this->create($commentData);
            }

            //写人redis
            $cmt['commentId'] = $commentId;
            //新增评论 状态未审核
            $cmt['status'] = 0;
            $flag1 = RedisIO::zAdd("comment_" . $type . "_" . $xid . "_commentIds", $commentId, $commentId);
            $flag2 = RedisIO::set("comment_" . $type . "_commentInfos_" . $commentId, json_encode($cmt));
            $max_count = defined(MAX_COMMENT_COUNT) ? MAX_COMMENT_COUNT : 10000;
            if (RedisIO::zSize("comment_" . $type . "_" . $xid . "_commentIds") > $max_count) {
                $oldCommentId = RedisIO::zRange("comment_" . $type . "_" . $xid . "_commentIds", 0, 0);
                RedisIO::zRem("comment_" . $type . "_" . $xid . "_commentIds", $oldCommentId[0]);//删除集合中的commentid
                RedisIO::del("comment_" . $type . "_commentInfos_" . $oldCommentId[0]);//删除字符串中commentid对应的信息
            }
        } catch (Exception $e) {
            $userModel = new User();
            $userModel->signSysLog('addCommentError', 'commentlog', 'comment', 'api/add', array(date('Y-m-d H:i:s'), $commentData));
            return false;
        }
        if (!$flag1 || !$flag2) {
            $userModel = new User();
            $userModel->signSysLog('addCommentError', 'commentlog', 'comment', 'api/add', array(date('Y-m-d H:i:s'), $commentData));
            return false;
        }

        //当评论不需要审核的时候, 才在这里(添加评论)时计数 (否则在审核通过后计数)
        $authRule = $this->getAuthRule($pid, $xid);
        //if ($config['_authRuleOff'] == $authRule)
        //{
        RedisIO::incr("comment_count_" . $xid);//评论总数增加1
        //}
        return $cmt;
    }

    /**
     * @desc 设置上次发评论或发回复的最后时间，用于频次限制.
     * @param $ssouid
     * @param $ctime
     * @param bool $isComment
     * @return bool
     */
    public function setLastCommentTime($ssouid, $ctime, $isComment = true) {
        if (empty($ssouid)) {
            return false;
        }

        $_cacheConf = $this->__loadCacheConf();
        if (false === $isComment) {
            $memCacheKey = sprintf($_cacheConf['memCacheKeys']['user_lastrtime'], $ssouid);
        } else {
            $memCacheKey = sprintf($_cacheConf['memCacheKeys']['user_lastctime'], $ssouid);
        }

        if (!empty($this->_authConfig['authInterval'])) {
            $expire = $this->_authConfig['authInterval'] + 30;
        } else {
            $expire = $_cacheConf['memCacheExpire']['user_lastctime'];
        }
        return RedisIO::set($memCacheKey, $ctime, $expire);
    }

    /**
     * @desc 新版评论内容格式化.
     * @param array $list
     * @param string $type
     * @param int $flag
     * @param array $formatArr
     * @return array
     */
    public function newFormatCommentList($list = array(), $type = 'video', $flag = 0, $formatArr = array()) {
        global $config;
        if (empty($list)) {
            return array();
        }
        if (!is_array($formatArr) || empty($formatArr)) {
            $formatArr = array();
        }
        $formatArr = $this->__filterFormatArr($formatArr);
        $_cmtType = $config['_cmtType'];

        $userModel = new User();
        $data = array();
        $replyIds = array();
        $replyList = array();
        $voteList = array();
        foreach ($list as $comment) {
            $commentid = strval($comment['commentId']);
            $fData = $this->__formatMainCommentSingle($comment, $formatArr);
            $ssouid = $comment['ssouid'];
            $uids[$ssouid] = $ssouid;
            $type = strval($comment['type']);
            $expand = array();

            //vip扩展字段
            if ('vip' == $type && isset($comment['expand']) && !empty($comment['expand'])) {
                $expand = unserialize($comment['expand']);
                if (!is_array($expand)) {
                    $expand = array();
                }
            }
            $fData['flag'] = $flag;
            $data[$commentid] = $fData;
        }
        //获取回复内容
        if (!empty($replyIds)) {
            $replyList = $this->getCommentByIds($replyIds, $type);
            if (!empty($replyList)) {
                foreach ($replyList as $index => $replyComment) {
                    $ssouid = $replyComment['ssouid'];
                    $uids[$ssouid] = $ssouid;
                }
            }
        }
        $uids = implode(",", $uids);
        $usersInfo = $userModel->getSsoUserInfoArr($uids);

        //处理回复以及用户信息
        foreach ($data as $index => $comment) {
            $ssouid = $comment['ssouid'];
            if (!isset($usersInfo[$ssouid])) {
                if (isset($comment['cooperation']) && is_array($comment['cooperation']) && !empty($comment['cooperation'])) {
                    $data[$index]['user'] = $this->__formatMainCommentCooperationUser($comment['cooperation']);
                } else {
                    //处理不登录发评论的用户
                    $data[$index]['user'] = $this->__formatMainCommentNoUidUser($comment['city']);
                }
            } else {
                $userInfo = $usersInfo[$ssouid];
                $userInfo['ssouid'] = $ssouid;
                $data[$index]['user'] = $this->__formatMainCommentUser($userInfo);
            }
            unset($data[$index]['cooperation']);
            //处理回复
            if (isset($comment['replyid']) && !empty($comment['replyid']) && isset($replyList[$comment['replyid']]) && !empty($replyList[$comment['replyid']])) {
                $replyId = strval($comment['replyid']);
                $data[$index]['reply'] = $this->__formatMainCommentSingle($replyList[$replyId], $formatArr);
                $replyssouid = $replyList[$replyId]['ssouid'];
                if (!isset($usersInfo[$replyssouid])) {
                    $data[$index]['reply'] = array();
                } else {
                    if (!isset($usersInfo[$replyssouid])) {
                        //处理不登录发评论的用户
                        $data[$index]['reply']['user'] = $this->__formatMainCommentNoUidUser($data[$index]['reply']['city']);
                    } else {
                        $userInfo = $usersInfo[$replyssouid];
                        $userInfo['ssouid'] = $replyssouid;
                        $data[$index]['reply']['user'] = $this->__formatMainCommentUser($userInfo);
                    }
                }
                unset($data[$index]['reply']['replyid']);
            }
            unset($data[$index]['replyid']);
            if ($_cmtType['vote'] == $comment['cmtType'] && !empty($voteList)) {
                $voteid = $comment['voteid'];
                if (isset($voteList[$voteid])) {
                    $data[$index]['vote'] = $voteList[$voteid];
                } else {
                    unset($data[$index]);
                }
            }
            unset($data[$index]['voteid']);
        }
        return $data;
    }

    /**
     * @desc 过滤格式化配置参数.
     * @param array $formatArr
     * @return array
     */
    protected function __filterFormatArr($formatArr = array()) {
        $formatConf = array(
            'ifFormatIcon' => false,
            'ifStripHtml' => true,
        );

        if (empty($formatArr)) {
            return $formatConf;
        }

        foreach ($formatArr as $formatKey => $formatValue) {
            if (!is_bool($formatValue)) {
                unset($formatArr[$formatKey]);
                continue;
            }
            if (!isset($formatConf[$formatKey])) {
                unset($formatArr[$formatKey]);
                continue;
            }
        }
        if (empty($formatArr)) {
            return $formatConf;
        }
        return $formatArr;
    }

    /**
     * @desc 格式化评论内容的主要字段.
     * @param array $comment
     * @param array $formatArr
     * @return array
     */
    private function __formatMainCommentSingle($comment = array(), $formatArr = array()) {
        global $config;
        $commentid = strval($comment['commentId']);
        $uid = isset($comment['uid']) ? intval($comment['uid']) : false;
        $ssouid = intval($comment['ssouid']);
        if (empty($uid)) {
            $uid = $ssouid;
        }
        $ctime = $comment['ctime'];
        $vtime = $this->_timeFormat($ctime);
        $city = strval($comment['area']);
        $pid = intval($comment['pid']);
        $xid = intval($comment['xid']);
        $cid = isset($comment['cid']) ? intval($comment['cid']) : 0;
        $title = isset($comment['title']) ? htmlspecialchars($comment['title']) : "";
        $content = $comment['content'];
        $content = htmlspecialchars($content);
        $content = str_replace("\n", " ", str_replace("\r\n", " ", $content));
        $content = preg_replace("/\\s{2,}/", " ", $content);
        $replycommentid = isset($comment['replycommentid']) ? htmlspecialchars($comment['replycommentid']) : "";

        if (empty($uid)) {
            $uid = $ssouid;
        }

        if (true === $formatArr['ifFormatIcon']) {
            $content = $this->replace_face($content);
        }

        if (empty($comment['area']) || '未知' == $comment['area'] || '局域网' == $comment['area'] || 'IANA保留地址' == $comment['area']) {
            $city = '未知';
        }

        $from = isset($comment['from']) ? $this->getFromType($comment['from']) : 'PC';
        $like = isset($comment['like']) ? intval($comment['like']) : 0; //喜欢的条数
        $like = isset($comment['like']) ? intval($comment['like']) : 0; //喜欢的条数
        $htime = isset($comment['htime']) ? intval($comment['htime']) : 0; //播放记录时间
        $_cmtType = $config['_cmtType'];
        $cmtType = isset($comment['cmtType']) && isset($_cmtType[$comment['cmtType']]) ? $_cmtType[$comment['cmtType']] : $_cmtType['cmt'];

        $_source2cn = $config['_source2cn'];
        $source = (isset($comment['source']) && isset($_source2cn[$comment['source']])) ? intval($comment['source']) : $config['_sourceDefault'];
        $source2Cn = $_source2cn[$source];

        return array(
            '_id' => $commentid,
            'uid' => $uid,
            'ssouid' => $ssouid,
            'content' => $content,
            'vtime' => $vtime,
            'ctime' => $ctime,
            'city' => $city,
            'from' => $from,
            'like' => $like,
            'htime' => $htime,
            'pid' => $pid,
            'xid' => $xid,
            'cid' => $cid,
            'title' => $title,
            'replycommentid' => $replycommentid,
            'source' => array(
                'link' => '',
                'id' => $source,
                'detail' => $source2Cn,
            ),
            'cmtType' => $cmtType,
            'cooperation' => (isset($comment['cooperation']) && is_array($comment['cooperation'])) ? $comment['cooperation'] : array(),
        );
    }

    /**
     * @desc 时间格式化.
     * @param $time
     * @return int|string
     */
    protected function _timeFormat($time) {
        $now = time();
        if (strpos($time, '-') !== false) {
            $time = strtotime($time);
        }
        $dur = $now - $time;
        if ($dur < 3600) {
            if ($dur < 60) {
                if ($dur < 1) {
                    $dur = 2;
                }
                $time = sprintf("%s秒前", $dur);
            } else {
                $minutes = intval($dur / 60);
                $time = sprintf("%s分钟前", $minutes);
            }
        } else if ($dur >= 3600 && $dur < 86400) {
            $hour = intval($dur / 3600);
            $time = sprintf("%s小时前", $hour);
        } else if ($dur >= 86400 && $dur <= 86400 * 30) {
            $day = intval($dur / 86400);
            $time = sprintf("%s天前", $day);
        } else if ($dur > 86400 * 30 && $dur <= 86400 * 365) {
            $month = intval($dur / (86400 * 30));
            $time = sprintf("%s个月前", $month);
        } else {
            $year = intval($dur / (86400 * 365));
            $time = sprintf("%s年前", $year);
        }
        return $time;
    }

    /**
     * @desc 替换表情
     * @param $content
     * @return mixed
     */
    public function replace_face($content) {
        return str_replace(array('[:D]', '[:)]', '[:O]', '[:(]', '[:*]'), array('<img src="http://i0.letvimg.com/playlist/201206/27/b_01.png" />', '<img src="http://i0.letvimg.com/playlist/201206/27/b_02.png" />', '<img src="http://i2.letvimg.com/playlist/201206/27/b_03.png" />', '<img src="http://i0.letvimg.com/playlist/201206/27/b_04.png" />', '<img src="http://i3.letvimg.com/playlist/201206/27/b_05.png" />'), $content);
    }


    public function getListTotalFromDb($xid, $pid, $type = 'video', $ctype = 0) {
        if (empty($xid) && empty($pid)) {
            return array();
        }
        if ($this->getAuthRule() == 0) {
            $status = 0;
        } else {
            $status = 1;
        }
        try {
            $tmpTotal = $this->query()
                ->columns("count(*) as total")
                ->andWhere("data_id = :data_id:")
                ->andWhere("status >= :status:")
                ->bind(array("data_id" => $xid, "status" => $status))
                ->execute()
                ->toArray();
            $total = $tmpTotal[0]['total'];
            /*
            $total = CommentCounts::query()
                ->columns("total")
                ->andWhere("xid = :xid:")
                ->bind(array("xid"=>$xid))
                ->execute()
                ->toArray();
            $total = $total[0]['total'];
            */
        } catch (Exception $e) {
            return 0;
        }
        return $total;
    }

    public function getListDuringSumFromDb($xid, $type = 'video', $during_type = 1) {
        if (empty($xid)) {
            return 0;
        }
        if ($during_type == 1) {//当天的时间点
            $begin_time = strtotime(date("Y-m-d"));
            $end_time = time();
        }elseif ($during_type == 2) {//上周的时间段
            $end_time = strtotime('last Sunday');
            $begin_time = $end_time-60*60*24*7;
        }else{
            return 0;
        }

        if ($this->getAuthRule() == 0) {
            $status = array(0, 1);
        } else {
            $status = array(1);
        }
        try {
            $tmpTotal = $this->query()
                ->columns("count(*) as total")
                ->andWhere("data_id = {$xid}")
                ->andWhere("type = '{$type}'")
                ->andWhere("create_at >= {$begin_time}")
                ->andWhere("create_at <= {$end_time}")
                ->inWhere("status", $status)
                ->execute()
                ->toArray();
            $total = $tmpTotal[0]['total'];
            RedisIO::set('comment_count_during_' . $during_type . '_' . $xid, $total, 10);
        } catch (Exception $e) {
            return 0;
        }
        return $total;
    }

    public function setAuthCommentFlag($uid, $pid = 0, $xid = 0) {
        global $config;
        if (empty($uid)) return false;
        if (empty($xid) && empty($pid)) return false;

        $memCacheKeys = array();
        $_cacheConf = $this->__loadCacheConf();
        if (!empty($pid)) {
            $memCacheKeys[] = sprintf($_cacheConf['memCacheKeys']['userAuthCommentFlag'], $uid, 'pid', $pid);
        }

        if (!empty($xid)) {
            $memCacheKeys[] = sprintf($_cacheConf['memCacheKeys']['userAuthCommentFlag'], $uid, 'xid', $xid);
        }

        $memCacheExpire = $_cacheConf['memCacheExpire']['userAuthCommentFlag'];

        if (empty($memCacheKeys)) return true;

        $_authCommentFlag = $config['_authCommentFlag'];

        foreach ($memCacheKeys as $memCacheKey) {

            $mem_res = RedisIO::set($memCacheKey, $_authCommentFlag, $memCacheExpire);
        }

        return true;
    }

    /**
     *
     * 2017/3/20
     *
     * @desc 获取评论总数
     * @param int $xid 视频id
     * @param int $pid 专辑id
     * @param string $type 视频类型
     * @param int $during_type 1.当天0点开始 2.上一完整周，周日0点到周六24点
     * @return int
     * @author zhangyichi
     */
    function getTotalLimitDuring($xid, $type = 'video', $during_type = 1) {
        global $config;
        if (empty($xid)) {
            return 0;
        }

        $total = RedisIO::get('comment_count_during_' . $during_type . '_' . $xid);
        if ($total === false) {//只有当缓存里数据不存在时才去数据库里查
            $total = $this->getListDuringSumFromDb($xid, $type, $during_type);
        }
        return $total;
    }

    /**
     *
     * 2016/7/7 饶佳修改逻辑,
     * 评论为0时不再读库
     *
     * @desc 获取评论总数
     * @param int $xid
     * @param int $pid
     * @param string $type
     * @return unknown
     */
    function getTotal($xid, $pid, $type = 'video', $ctype = 0) {
        global $config;
        if (empty($xid) && empty($pid)) {
            return 0;
        }

        //如果是0先发后审就读取redis缓存，1先审后发就直接读取数据库
        if ($this->getAuthRule() == $config['_authRuleOff']) {
            //先发后审,读缓存
            $total = RedisIO::get("comment_count_" . $xid);
        } else {
            //先审后发,读库
            //$total = $this->getListTotalFromDb($xid, $pid, $type, $ctype);
            //先发后审,读缓存
            $total = RedisIO::get("comment_accept_count_" . $xid);
        }
        if (empty($total)) {
            return 0;
        }
        return $total;
    }

    /**
     * @desc 获取审核通过的评论ID列表.
     * @param $xid
     * @param string $type
     * @param int $start
     * @param int $limit
     * @param string $pid
     * @param array $sort
     * @param int $ctype
     * @return array
     */
    public function getCommentList($xid, $type = 'video', $start = 0, $limit = 20, $pid = '0', $sort = array('ctime' => -1), $ctype = 0) {
        global $config;
        if ($start <= 1) {
            $start = 0;
        }
        //如果是0先发后审就读取redis缓存，1先审后发就直接读取数据库
        if ($this->getAuthRule() == $config['_authRuleOff']) {
            $commentIds = RedisIO::zRevRange("comment_" . $type . "_" . $xid . "_commentIds", $start, $limit + $start - 1);
            //if (!empty($commentIds) && is_array($commentIds)) {
            return $commentIds;
            //}
        } else {
            $list = RedisIO::zRevRange("comment_" . $type . "_" . $xid . "_commentIds", $start, $limit + $start - 1);
            $this->getCommentByIds($list, $type);//初始化先审后方状态

            $commentIds = RedisIO::zRevRange("comment_accept_" . $type . "_" . $xid . "_commentIds", $start, $limit + $start - 1);

            return $commentIds;
            //$list = $this->__getCommentListFromMysql($xid, $pid, $type, $start, $limit, $sort, $ctype);
            //return $list;
        }
    }

    /**
     * @desc 评论从数据库读取.
     * @param $xid
     * @param $pid
     * @param string $type
     * @param int $start
     * @param int $limit
     * @param array $sort
     * @param int $ctype
     * @return array
     */
    private function __getCommentListFromMysql($xid, $pid, $type = 'video', $start = 0, $limit = 30, $sort = array('ctime' => -1), $ctype = 0) {
        global $config;
        $commentids_json = RedisIO::get("comment_" . $type . "_" . $xid . "_commentIds_rds");
        if(!$commentids_json) {
            $where = "1";
            if ($pid) {
                //$where = array('pid'=>$pid);//pid暂时无用，不会出现获取专辑下的评论列表
                //$where .= " and pid = '{$pid}'";
            }
            if ($xid) {
                //$where = array('xid'=>$xid);
                $where .= " and data_id = '{$xid}'";
            }
            //根据审核机制，取相应评论（先审后发取isflag不存在的数据）
            if ($this->getAuthRule() == $config['_authRuleOn']) {
                //$where['status'] = array(1);
                $where .= " and status = 1";
            } else {
                if (!empty($this->_authConfig['authPidArr']) && $pid > 0 && in_array($pid, $this->_authConfig['authPidArr'])) {
                    //$where['status'] = array(0,1);
                    $where .= " and status in (0,1)";
                } elseif (!empty($this->_authConfig['authXidArr']) && $xid > 0 && in_array($xid, $this->_authConfig['authXidArr'])) {
                    $where .= " and status in (0,1)";
                }
            }

            try {
                $ret = $this->query()
                    ->columns("comment_id")
                    ->where($where)
                    ->orderBy("id desc")
                    ->limit($limit, $start)
                    ->execute()
                    ->toArray();
            } catch (Exception $e) {
                $ret = array();
            }
            if (empty($ret)) {
                return array();
            }
            $newData = array();
            foreach ($ret as $data) {
                $newData[] = strval($data['comment_id']);
            }
            RedisIO::set("comment_" . $type . "_" . $xid . "_commentIds_rds", json_encode($newData));
        }
        else {
            $newData = json_decode($commentids_json);
        }
        return $newData;
    }

    /**
     * @desc 获取回复的评论
     * @param array $ids
     * @param string $type
     * @return array
     */
    public function getQuoteCommentList($ids = array(), $type = 'video') {
        if (empty($ids) || !is_array($ids)) {
            return array();
        }
        $quoteids = array();
        foreach ($ids as $index => $commentid) {
            if (!empty($commentid['replycommentid'])) {
                if (!in_array($commentid['replycommentid'], $quoteids)) {
                    $quoteids[] = $commentid['replycommentid'];
                }
            }
        }
        if (empty($quoteids) || !is_array($quoteids)) {
            return array();
        }
        return $quoteids;
    }

    /**
     * @desc
     * @param array $cooperationUser
     * @return array
     */
    protected function __formatMainCommentCooperationUser($cooperationUser = array()) {
        if (!is_array($cooperationUser) || empty($cooperationUser)) {
            return $this->__formatMainCommentNoUidUser();
        }
        return array(
            'uid' => 0,
            'ssouid' => 0,
            'username' => $cooperationUser['name'],
            'photo' => isset($cooperationUser['avatar_url']) ? $cooperationUser['avatar_url'] : "http://i3.letvimg.com/img/201207/30/tx50.png",
            'isvip' => 0,
            'team' => array(),
            'cooperation' => array(
                'profile_url' => $cooperationUser['profile_url'],
                "original_url" => $cooperationUser['original_url'],
                "source" => $cooperationUser['source'],
                "icon" => "http://www.sinaimg.cn/blog/developer/wiki/LOGO_16x16.png",
            ),
        );
    }

    /**
     * @desc 格式化评论内容的无登录用户信息
     * @param string $ctiy
     * @return array
     */
    protected function __formatMainCommentNoUidUser($ctiy = "北京") {
        return array(
            'uid' => 0,
            'ssouid' => 0,
            'username' => $ctiy . "用户",
            'photo' => 'http://i3.letvimg.com/img/201207/30/tx50.png',
            'isvip' => 0,
            'team' => array(),
            'cooperation' => array(),
        );
    }

    /**
     * @desc 格式化评论内容的用户信息.
     * @param array $userInfo
     * @return array
     */
    protected function __formatMainCommentUser($userInfo = array()) {
        $user = array(
            'uid' => $userInfo['uid'],
            'ssouid' => strval($userInfo['ssouid']),
            'username' => !empty($userInfo['nickname']) ? $userInfo['nickname'] : $userInfo['username'],
            'photo' => $userInfo['picture'],
            'isvip' => isset($userInfo['isvip']) ? intval($userInfo['isvip']) : 0,
            'team' => isset($userInfo['team']) ? $userInfo['team'] : array(),
            'cooperation' => array(),
        );
        $userSsoPic = explode(',', $user['photo']);
        $user['photo'] = isset($userSsoPic[3]) ? 'http://i3.letvimg.com/img/201207/30/tx50.png' : $user['photo'];
        if ('1120619' == $user['ssouid']) {
            $user['username'] = '客服';
        }
        return $user;
    }

    /**
     * @desc 查询上一次发评论时间.
     * @param $ssouid
     * @param bool $isComment
     * @return int
     */
    public function getLastCommentTime($ssouid, $isComment = true) {
        if (empty($ssouid)) {
            return 0;
        }
        $_cacheConf = $this->__loadCacheConf();
        if (false === $isComment) {
            $memCacheKey = sprintf($_cacheConf['memCacheKeys']['user_lastrtime'], $ssouid);
        } else {
            $memCacheKey = sprintf($_cacheConf['memCacheKeys']['user_lastctime'], $ssouid);
        }
        $lastComment = RedisIO::get($memCacheKey);
        if (empty($lastComment)) {
            return 0;
        }
        return intval($lastComment);
    }

    /**
     * @desc 获取缓存配置
     * @param bool $isReload
     * @return \Phalcon\Mvc\Model\Resultset|\Phalcon\Mvc\Phalcon\Mvc\Model
     */
    protected function __loadCacheConf($isReload = false) {
        global $config;
        if (false === $this->_cacheConf || !is_array($this->_cacheConf) || true === $isReload) {
            $this->_cacheConf = $config['_cacheConf'];
        }
        return $this->_cacheConf;
    }

    /**
     * @desc 获得某个ip的评论数
     * @param $ip
     * @return int
     */
    public function get_ip_comment_num($ip) {
        return intval(RedisIO::get("Comment::cmt_total:$ip"));
    }

    /**
     * @desc 获得某个ip的最后一条评论
     * @param $ip
     * @return mixed
     */
    public function get_ip_comment_last($ip,$xid) {
        return RedisIO::get("Comment::userip:$ip:$xid");
    }

    /**
     * 获取评论缓存key.
     *
     * @access private
     * @param mixed $xid
     * @param mixed $pid
     * @param mixed $type
     * @return void
     */
    private function __getCommentMemCacheKey($xid, $pid, $type, $sort = array(), $ctype = 0) {
        global $config;
        $_cacheConf = $this->__loadCacheConf();
        $_cmtTypeMath = $config['_cmtTypeMath'];

        if ($ctype === $_cmtTypeMath['cmt']) { //文字评论
            $cacheConfIndex = 'listNoPicAndNoVote';
        } else if ($ctype === $_cmtTypeMath['vote']) { //投票评论
            $cacheConfIndex = 'listvote';
        } else if ($ctype === $_cmtTypeMath['img']) { //截图评论
            $cacheConfIndex = 'listpic';
        } else if ($ctype === ($_cmtTypeMath['vote'] | $_cmtTypeMath['cmt'])) { //文字+投票评论
            $cacheConfIndex = 'listnopic';
        } else if ($ctype === ($_cmtTypeMath['img'] | $_cmtTypeMath['cmt'])) { //文字+截图评论
            $cacheConfIndex = 'listnovote';
        } else {
            $cacheConfIndex = 'listall';
        }
        return sprintf($_cacheConf['memCacheKeys'][$cacheConfIndex], $type, 'xid', $xid);
    }

    /**
     * @desc 来源类型
     * @param $key
     * @return bool|mixed|\Phalcon\Mvc\ModelInterface|string
     */
    public function getFromType($key) {
        $type = $this->_fromType;
        return isset($type[$key]) ? $type[$key] : '未知';
    }

    public function countCollection($collection, $query = array()) {
        if (empty($collection)) {
            return false;
        }
        if (!is_array($query)) {
            return false;
        }

        //$mdbw        = $this->getMdb();
        //return $mdbw->count($collection, $query);//TODO
        return 1;
    }

    public function fixCommentTotal($type = 'video', $pid, $xid, $totalArr = array()) {
        if (!is_array($totalArr) || empty($totalArr)) {
            return false;
        }
        if (empty($type)) {
            return false;
        }
        if ($pid > 0) {
            try {
                $ctotal = intval($totalArr['ctotal']);
                $nptotal = intval($totalArr['nptotal']);
                $votetotal = intval($totalArr['votetotal']);
                $where = array("type" => $type, "pid" => $pid);
                $initdata = array("type" => $type, "pid" => $pid, "ctotal" => $ctotal, "nptotal" => $nptotal, "votetotal" => $votetotal);
                //$mdbw->update("comment_count", $where, $initdata, array("upsert" => true));TODO
            } catch (Exception $e) {
            }
        }
        if ($xid > 0) {
            try {
                $ctotal = intval($totalArr['ctotal']);
                $nptotal = intval($totalArr['nptotal']);
                $votetotal = intval($totalArr['votetotal']);
                $where = array("type" => $type, "xid" => $xid);
                $initdata = array("type" => $type, "xid" => $xid, "ctotal" => $ctotal, "nptotal" => $nptotal, "votetotal" => $votetotal);
                //$mdbw->update("comment_count", $where, $initdata, array("upsert" => true));TODO
            } catch (Exception $e) {
            }
        }
        return true;
    }

    /**
     * @desc 设置某个ip的最后一条评论
     * @param int $ip
     * @param string $content
     * @return bool
     */
    public function set_ip_comment_last($ip,$xid, $content) {
        return RedisIO::set("Comment::userip:$ip:$xid", $content, 86400);
    }

    /**
     * @desc 设置某个ip的评论数 - 有效期5分钟
     * @param int $ip
     * @param int $total
     * @return bool
     */
    public function set_ip_comment_num($ip, $total) {
        return RedisIO::set("Comment::cmt_total:$ip", $total, $this->_authConfig['authCRuntime']);
    }


    //+++++++++++++++++++++++++++++++++以下为后台使用的方法++++++++++++++++++++++++++++++++++++++++++
    const REJECT = -1;  //审核未通过
    const ACCEPT = 1;  //审核通过
    const UNCHACKED = 0;  //未审核
    const DELETE = 3;
    const ALL = 4;
    const PAGE_SIZE = 50;

    public static function getCommentsAll($channel_id) {

        return Comment::query()
            ->andCondition('channel_id', $channel_id)
            ->orderBy('create_at desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }


    public static function acceptComment($id, $channel_id) {
        return Comment::changeStatus($id, Comment::ACCEPT, $channel_id);
    }

    public static function uncheckedComment($id, $channel_id) {
        return Comment::changeStatus($id, Comment::UNCHACKED, $channel_id);
    }

    public static function deleteComment($id, $channel_id) {
        return Comment::changeStatus($id, Comment::DELETE, $channel_id);
    }

    public static function changeStatus($id, $status, $channel_id) {
        $comment = Comment::getComment($id, $channel_id);
        if ($comment) {
            $comment->status = $status;
            return $comment->save();
        }
        return false;
    }

    public static function getComment($id, $channel_id) {
        return Comment::findFirst(array(
            'id = :id: AND channel_id=:channel_id:',
            'bind' => array('id' => $id, 'channel_id' => $channel_id)
        ));
    }

    public static function getCommentByUserId($user_id, $channel_id, $from = 0, $to = 0) {
        if($from == 0) {
            return self::query()
                ->andCondition('user_id',$user_id)
                ->andCondition('channel_id',$channel_id)
                ->limit(self::EXPORT_LIMIT)
                ->order('create_at desc')
                ->execute();
        }
        else {
            $parameters = array();
            $parameters['conditions'] = "user_id=" . $user_id." and channel_id=".$channel_id." and create_at>".$from. " and create_at<=".$to;
            $parameters['limit'] = self::EXPORT_LIMIT;
            $parameters['order'] = "create_at desc";
            return self::find($parameters);
        }
    }

    public static function getCommentByDataId($data_id, $channel_id, $from = 0, $to = 0 ) {
        if($from == 0) {
            return self::query()
                ->andCondition('data_id',$data_id)
                ->andCondition('channel_id',$channel_id)
                ->limit(self::EXPORT_LIMIT)
                ->order('create_at desc')
                ->execute();
        }
        else {
            $parameters = array();
            $parameters['conditions'] = "data_id=" . $data_id." and channel_id=".$channel_id." and create_at>".$from. " and create_at<=".$to;
            $parameters['limit'] = self::EXPORT_LIMIT;
            $parameters['order'] = "create_at desc";
            return self::find($parameters);
        }
    }


    public static function getCommentByTime($channel_id, $from = 0, $to = 0 ) {
        $parameters = array();
        if($from&&$to) {
            $parameters['conditions'] = " channel_id=".$channel_id." and create_at>".$from. " and create_at<=".$to;
        }
        else if($from) {
            $parameters['conditions'] = " channel_id=".$channel_id." and create_at>".$from;
        }
        else if($to){
            $parameters['conditions'] = " channel_id=".$channel_id." and create_at<=".$to;
        }
        $parameters['limit'] = self::EXPORT_LIMIT;
        $parameters['order'] = "create_at desc";
        return self::find($parameters);
    }

    public function delAuditConfCache() {
        /*初始化审核相关参数*/
        $cacheId = 'video:comment:control';
        $re = RedisIO::delete($cacheId);
        return $re;
    }

    protected function __getAuditConf() {
        /*初始化审核相关参数*/
        $cacheId = 'video:comment:control';
        $auditConf = RedisIO::get($cacheId);
        $auditConf = json_decode($auditConf, true);
        if (empty($auditConf) || !is_array($auditConf)) {
            try {
                $auditConf = AuditCommentControl::findFirst()->toArray();
            } catch (Exception $e) {
            }
            if (!empty($auditConf) && is_array($auditConf)) {
                RedisIO::set($cacheId, json_encode($auditConf));
            }
        }

        if (!empty($auditConf) && is_array($auditConf) && isset($auditConf['global']) && isset($auditConf['ext_field'])) {
            $this->_authConfig['authRule'] = intval($auditConf['global']);
            $ext_field = explode("|", $auditConf['ext_field']);
            if (is_array($ext_field) && isset($ext_field[0]) && $ext_field[0] > 0) {
                $this->_authConfig['authInterval'] = intval($ext_field[0] * 60);
            }
            if (is_array($ext_field) && isset($ext_field[1]) && $ext_field[1] > 0) {
                $this->_authConfig['authCRuntime'] = intval($ext_field[1] * 60);
            }
            if (is_array($ext_field) && isset($ext_field[2]) && $ext_field[2] > 0) {
                $this->_authConfig['authCnum'] = intval($ext_field[2]);
            }
            /*审核限制的专辑*/
            if (isset($auditConf['pidlist'])) {
                $pidArr = explode(',', $auditConf['pidlist']);
                $pidArr = array_filter($pidArr);
                !empty($pidArr) && ($this->_authConfig['authPidArr'] = $pidArr);
            }
            /*审核限制的单视频*/
            if (isset($auditConf['vidlist'])) {
                $vidArr = explode(',', $auditConf['vidlist']);
                $vidArr = array_filter($vidArr);
                !empty($vidArr) && ($this->_authConfig['authXidArr'] = $vidArr);
            }
        }
    }

    /**
     * @desc 判断是否需要更新敏感词库
     */
    public function isRefreshKeywords() {
        $pidKey = $_SERVER['SERVER_ADDR'] . '_' . getmypid();
        $pidVersion = RedisIO::get($pidKey);
        $publicVersion = $this->getPbulicVersion();
        if ($pidVersion == $publicVersion) {
            return false;//版本相等不不需要更新敏感词库
        }
        return true;//版本不相等需要更新敏感词库
    }

    /**
     * @desc 获取公共敏感词版本号
     */
    public function getPbulicVersion() {
        $publicKey = 'public_keyword_version';
        $publicVersion = RedisIO::get($publicKey);
        if (empty($publicVersion)) {
            $publicVersion = AuditCommentKeywordVersion::findFirst()->toArray();
            $publicVersion = $publicVersion['version'];
            RedisIO::set($publicKey, $publicVersion);
        }
        return $publicVersion;
    }
}
