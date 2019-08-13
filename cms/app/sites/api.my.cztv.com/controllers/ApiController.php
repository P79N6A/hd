<?php

class ApiController extends ApiBaseController {

    /**
     * 用户发评论功能.
     *
     * @access private
     * @return void
     */
    public function addAction() {
        global $config;
        $commentModel = new Comment();
        //获取公共参数(*如果获取必要参数失败,则终止后面的操作)
        $commonReqParams = $this->__getListReqParams();

        $jscript = (trim(Request::getQuery('jscript'))) ? trim(Request::getQuery('jscript')) : 0;

        //验证用户是否登录
        if (empty($commonReqParams['ssouid']) || empty($commonReqParams['loginUserInfo'])) {
            $this->output(array('result' => $this->_Err['notlogged']), false, Request::getQuery('jscript'));
        }

        //压测时打开
        /*$commonReqParams = array(
            'ssouid' => 103442062,
            'loginUserInfo' => null,
            'clientIp' => 0,
            'source' => 2,
            'type' =>'video'
        );*/

        //获取登录信息
        $ssouid = intval($commonReqParams['ssouid']);
        $uid = empty($commonReqParams['ssouid']) ? $ssouid : intval($commonReqParams['ssouid']);

        //参数
        $xid = intval(Request::getQuery('xid'));    //视频ID
        $pid = intval(Request::getQuery('pid'));    //专辑ID
        $mmsid = intval(Request::getQuery('mmsid'));    //媒资ID
        $cid = intval(Request::getQuery('cid'));    //视频类型ID
        $title = trim(Request::getQuery('title'));
        $content = trim(Request::getQuery('content'));
        $replycommentid = (trim(Request::getQuery('replycommentid'))) ? trim(Request::getQuery('replycommentid')) : 0;
        //获取点赞参数
        $htime = (trim(Request::getQuery('htime'))) ? trim(Request::getQuery('htime')) : 0;        //播放时间点
        $replyid = 0;
        $type = $commonReqParams['type'];

        if (!in_array($type, array('video', 'news', 'broadcast', 'chat'))) {
            $this->output(array('result' => '参数不符合要求'), false, $jscript);
        }

        if (empty($xid) && empty($pid)) {
            $this->output(array('result' => $this->_Err['error']), false, $jscript);
        }

        if (!is_numeric($xid) && !is_numeric($pid)) {
            $this->output(array('result' => $this->_Err['error']), false, $jscript);
        }

        //判断视频类型
        $_videoType = $config['_videoType'];
        $cid = isset($_videoType[$cid]) ? $cid : 0;

        $imgurl = '';
        $ispic = false;

        $clientIp = empty($commonReqParams['clientIp']) ? 0 : intval($commonReqParams['clientIp']);
        $source = $commonReqParams['source'];
        //处理Title
        $title = $commentModel->__getSafeText($title, false);
        $title = (false == $commentModel->__checkStringLength($title, 1, "")) ? "" : $title;
        $_cmtType = $config->_cmtType;
        //审核方式
        $authRule = $commentModel->getAuthRule($pid, $xid);
        $row = array();
        $row['uid'] = $uid;
        $row['ssouid'] = $ssouid;
        //视频相关属性
        $row['cid'] = $cid;    //频道ID
        $row['pid'] = $pid;    //专辑ID
        $row['xid'] = $xid;    //视频ID
        $row['mmsid'] = $mmsid;    //媒资ID
        // 去掉cookie读取用户名 $row['username'] = Cookie::getValue('sso_nickname') ? Cookie::getValue('sso_nickname') : $commonReqParams['loginUserInfo']['nickname'];
        $row['username'] = !empty($commonReqParams['loginUserInfo']['nickname']) ? $commonReqParams['loginUserInfo']['nickname'] : "匿名用户";
        $row['type'] = $type;
        $row['ctime'] = time();
        $row['title'] = $title;
        $row['content'] = $content;
        $row['cmtType'] = (true === $ispic) ? $_cmtType['img'] : $_cmtType['cmt'];
        $row['like'] = 0;        //赞
        $row['share'] = 0;        //分享
        $row['ip'] = $clientIp;
        $row['area'] = $commentModel->getCityFromIP($row['ip']);
        $row['from'] = $commentModel->get_agent_from();
        $row['ispic'] = $ispic;
        $row['imgurl'] = $imgurl;
        $row['htime'] = intval($htime);
        $row['source'] = $source;

        $row['replyid'] = $replyid;    //回复的ID
        $row['replynum'] = 0;            //回复的条数
        $row['replycommentid'] = $replycommentid;

        $isSpanComment = false;
        $comment = false;

        $row['content'] = $this->__filterCommentContent($row['content']);

        //30秒内频率限制
        $this->__antiSpam($row);

        //检查是否有屏蔽词
        if (true === $commentModel->commentFilter($row['content'])) {
            //含敏感词
            $comment = $commentModel->addToDelete($row);
            $isSpanComment = true;
        } else {
            //不含敏感词
            $comment = $commentModel->addComment($row, $commonReqParams['ctype']);
        }

        $commentModel->setLastCommentTime($ssouid, $comment['ctime']);

        if (empty($comment)) {
            $this->output(array('result' => $this->_Err['fail']), false, $jscript);
        }

        if ($isSpanComment != true) { //不含敏感词，正常格式化评论 rj added
            $list = array();
            $commentId = strval($comment['commentId']);
            $list[$commentId] = $comment;
            $flist = $commentModel->newFormatCommentList($list, $type, $authRule);

            $comment = $flist[$commentId];
            $comment['isLike'] = false;
            $comment['isVote'] = false;
        }
        $ctype = $commonReqParams['ctype'];

        //$this->__fixCommentCount(array($xid),array($pid));//重置评论计数 不需要每次评论时调用，异步入库后可以在异步入库时调用

        $data = array(
            'result' => '200',
            'total' => $commentModel->getTotal($xid, $pid, $type, $ctype),
            'data' => array($comment),
            'rule' => $authRule
        );

        $this->__setNewAntiApanRule($row);

        $commentModel->setAuthCommentFlag($ssouid, $pid, $xid);

        //每日发评论统计
        $isReply = empty($comment['reply']) || !isset($comment['reply']) ? "0" : "1";
        $isPic = empty($comment['img']) || !isset($comment['img']) ? "0" : "1";
        $cid = empty($comment['cid']) || !isset($comment['cid']) ? "0" : strval($comment['cid']);
        $pid = empty($comment['pid']) || !isset($comment['pid']) ? "0" : strval($comment['pid']);
        $xid = empty($comment['xid']) || !isset($comment['xid']) ? "0" : strval($comment['xid']);
        $cmtId = empty($comment['_id']) || !isset($comment['_id']) ? "0" : strval($comment['_id']);
        $syslogData = array(
            "cid:" . $cid,
            "pid:" . $pid,
            "xid:" . $xid,
            "isReply:" . $isReply,
            "isPic:" . $isPic,
            "cmtId:" . $cmtId,
            "source:" . $source,
            "content:" . $content,
        );

        if ($isSpanComment === true) {
            $data = array('result' => 'badwords');
        }
        $this->output($data, false, $jscript);
    }

    /**
     * @desc 对某个评论进行喜欢操作.
     */
    public function likeAction() {
        global $config;
        $commentModelObj = new CommentLikes();

        //获取公共参数(*如果获取必要参数失败,则终止后面的操作)
        $commonReqParams = $this->__getListReqParams();

        //验证用户是否登录
        if (empty($commonReqParams['ssouid']) || empty($commonReqParams['loginUserInfo'])) {
            $this->output(array('result' => $this->_Err['notlogged']));
        }

        $ssouid = intval($commonReqParams['ssouid']);
        $type = $commonReqParams['type'];
        $source = $commonReqParams['source'];

        //判断被喜欢的评论ID
        $commentid = strval($this->request->getQuery('commentid', null, 0));
        if (empty($commentid) || 'undefined' == $commentid) {
            $this->output(array('result' => $this->_Err['error']));
        }

        $isComment = true;
        //喜欢类型
        $_vcm_like_type = $config['_vcm_like_type'];
        $attr = strval($this->request->getQuery('attr', null, 'comment'));
        $attr = ('reply' === $attr) ? 'reply' : 'comment';

        if (!isset($_vcm_like_type[$attr])) {
            $this->output(array('result' => $this->_Err['error']));
        } else {
            if ('cmt' === $_vcm_like_type[$attr]) {
                $isComment = true;
            } else if ('reply' === $_vcm_like_type[$attr]) {
                $isComment = false;
            }
        }
        //获取被点赞的评论entity
        $commentInfo = $this->__checkCommentExist($commentid, $type, $isComment);
        //获取总的评论点赞次数
        if (isset($commentInfo['like'])) {
            $likeNum = intval($commentInfo['like']);
        } else {
            $likeNum = 0;
        }

        $data = array(
            'ssouid' => $ssouid,
            'commentid' => $commentid,
            'attr' => $_vcm_like_type[$attr],
        );

        // 判断点赞是否重复,如果新记录则将评论点赞记录进行入库操作
        $ret = $commentModelObj->likeComment($data);
        if (isset($ret['liked'])) {
            $this->output(array('result' => 'liked'));
        }
        //更新改条评论的总点赞次数
        if ($ret) {
            //$incrNum = 1;
            $likeNum += 1;
            $commentModelObj->incrlikeComment($commentid, $type, $likeNum, $isComment);
            //$likeNum += 1;
        }

        if (empty($likeNum)) {
            $likeNum = 0;
        }

        $data = array(
            'result' => '200',
            'data' => array('like' => $likeNum),
        );

        //每日喜欢评论统计
        $isReply = (!isset($commentInfo['replyid']) || empty($commentInfo['replyid'])) ? "0" : "1";
        $isPic = (isset($commentInfo['ispic']) && true === $commentInfo['ispic']) ? "1" : "0";
        $isVote = (isset($commentInfo['voteid']) && !empty($commentInfo['voteid'])) ? "1" : "0";
        $cid = empty($commentInfo['cid']) || !isset($commentInfo['cid']) ? "0" : strval($commentInfo['cid']);
        $pid = empty($commentInfo['pid']) || !isset($commentInfo['pid']) ? "0" : strval($commentInfo['pid']);
        $xid = empty($commentInfo['xid']) || !isset($commentInfo['xid']) ? "0" : strval($commentInfo['xid']);
        $cmtId = empty($commentInfo['_id']) || !isset($commentInfo['_id']) ? "0" : strval($commentInfo['_id']);
        //$source = $source;

        $syslogData = array(
            "cid:" . $cid,
            "pid:" . $pid,
            "xid:" . $xid,
            "isReply:" . $isReply,
            "isPic:" . $isPic,
            "isVote:" . $isVote,
            "cmtId:" . $cmtId,
            "source:" . $source,
        );
        $syslogData = implode(",", $syslogData);
        $this->__syslog('likeComment', $syslogData);
        $this->output($data);
    }

    /**
     * @desc 取消喜欢.
     */
    public function unlikeAction() {
        global $config;
        $commentModelObj = new CommentLikes();
        //获取公共参数(*如果获取必要参数失败,则终止后面的操作)
        $commonReqParams = $this->__getListReqParams();
        //验证用户是否登录
        if (empty($commonReqParams['ssouid']) || empty($commonReqParams['loginUserInfo'])) {
            $this->output(array('result' => $this->_Err['notlogged']));
        }

        $ssouid = intval($commonReqParams['ssouid']);
        $type = $commonReqParams['type'];
        $source = $commonReqParams['source'];

        //判断被喜欢的评论ID
        $commentid = strval($this->request->getQuery('commentid', null, 0));//评论的ID
        if (empty($commentid) || 'undefined' == $commentid) {
            $this->output(array('result' => $this->_Err['error']));
        }

        $isComment = true;
        //喜欢类型
        $_vcm_like_type = $config['_vcm_like_type'];
        $attr = strval($this->request->getQuery('attr', null, 'comment'));
        $attr = ('reply' === $attr) ? 'reply' : 'comment';

        if (!isset($_vcm_like_type[$attr])) {
            $this->output(array('result' => $this->_Err['error']));

        } else {
            if ('cmt' === $_vcm_like_type[$attr]) {
                $isComment = true;
            } else if ('reply' === $_vcm_like_type[$attr]) {
                $isComment = false;
            }
        }
        $commentInfo = $this->__checkCommentExist($commentid, $type, $isComment);
        $likeNum = intval($commentInfo['like']);
        $data = array(
            'ssouid' => $ssouid,
            'commentid' => $commentid,
            'attr' => $_vcm_like_type[$attr],
        );
        $ret = $commentModelObj->unlikeComment($data);
        if ($ret) {
            //$incrNum = -1;
            $likeNum -= 1;
            $commentModelObj->incrlikeComment($commentid, $type, $likeNum, $isComment);
        }

        if (empty($likeNum)) {
            $likeNum = 0;
        }
        if (empty($likeNum)) {
            $likeNum = 0;
        }
        $data = array(
            'result' => '200',
            'data' => array(
                'like' => $likeNum,
            ),
        );
        $this->output($data);
    }

    /**
     * @desc 新版评论列表接口
     */
    public function listAction() {
        global $config;
        $commentModelObj = new Comment();
        //获取公共参数(*如果获取必要参数失败,则终止后面的操作)
        $commonReqParams = $this->__getListReqParams();

        //格式化配置
        $formatArr = array(
            'ifFormatIcon' => false,
            'ifStripHtml' => true,
        );
        //根据各个端不同配置新的格式化参数
        $formatArr['ifFormatIcon'] = (isset($commonReqParams['ifFormatIcon']) && is_bool($commonReqParams['ifFormatIcon'])) ? $commonReqParams['ifFormatIcon'] : $formatArr['ifFormatIcon'];
        $formatArr['ifStripHtml'] = (isset($commonReqParams['ifStripHtml']) && is_bool($commonReqParams['ifStripHtml'])) ? $commonReqParams['ifStripHtml'] : $formatArr['ifStripHtml'];

        $type = $commonReqParams['type'];
        $ctype = $commonReqParams['ctype'];
        $ssouid = intval($commonReqParams['ssouid']);

        //获取默认的参数
        $pid = intval($this->request->getQuery('pid', null, 0)); //专辑ID
        $xid = intval($this->request->getQuery('xid', null, 0)); //视频ID
        $page = intval($this->request->getQuery('page', null, 1));    //获取页数
        $rows = intval($this->request->getQuery('rows', null, 10));    //每页数量

        if (empty($xid) && empty($pid)) {
            $this->output(array('result' => $this->_Err['error']));
        }

        if (!is_numeric($xid) && !is_numeric($pid)) {
            $this->output(array('result' => $this->_Err['error']));
        }

        if (!in_array($type, array('video', 'news', 'broadcast', 'chat'))) {
            $this->output(array('result' => '参数不符合要求'), false, $this->request->getQuery('jscript', null, 0));
        }

        //获取每页数量
        if ($page < 1 || !is_numeric($page)) {
            $page = 1;
        }
        $_pagesize = $config['_pagesize'];
        $rows = $rows > $_pagesize['list'] ? $_pagesize['list'] : $rows; //每页显示数量最大值

        //获取排序规则
        $sort = $this->request->getQuery('sort');
        $sort = ($sort == 'hot') ? array('replynum' => -1, 'ctime' => -1) : array('ctime' => -1);

        //获取审核规则
        $authRule = $commentModelObj->getAuthRule($pid, $xid);

        //获取列表数
        $total = $commentModelObj->getTotal($xid, $pid, $type, $ctype);
        $list = array();
        if (is_numeric($total) && $total > 0) {
            //这里的数据都是审核通过的
            $noAuthRule = 0;
            $start = ($page - 1) * $rows;

            $list = $commentModelObj->getCommentList($xid, $type, $start, $rows, $pid, $sort, $ctype);
            $cxtList = $commentModelObj->getCommentByIds($list, $type);

            $quotlist = $commentModelObj->getQuoteCommentList($cxtList, $type);
            $quotecommentList = $commentModelObj->getCommentByIds($quotlist, $type);

            $cxtList = $commentModelObj->newFormatCommentList($cxtList, $type, $noAuthRule, $formatArr);
            $quotecommentList = $commentModelObj->newFormatCommentList($quotecommentList, $type, $noAuthRule, $formatArr);

            $list = $this->__handleList($list, $cxtList, $commonReqParams);
            $quotlist = $this->__handleList($quotlist, $quotecommentList, $commonReqParams);
            $list = $this->__handList($list, $quotlist);
        }
        $list = (!empty($list) && is_array($list)) ? array_values($list) : array();
        $data = array(
            'result' => '200',
            'total' => $total,
            'data' => $list,
            'rule' => $authRule,
            'authData' => array(),
        );
        $this->output($data);
    }

    /**
     * @desc 评论数量接口
     */
    public function commentcountAction() {
        global $config;
        $commentModelObj = new Comment();
        //获取公共参数(*如果获取必要参数失败,则终止后面的操作)
        $commonReqParams = $this->__getListReqParams();

        //格式化配置
        $formatArr = array(
            'ifFormatIcon' => false,
            'ifStripHtml' => true,
        );
        //根据各个端不同配置新的格式化参数
        $formatArr['ifFormatIcon'] = (isset($commonReqParams['ifFormatIcon']) && is_bool($commonReqParams['ifFormatIcon'])) ? $commonReqParams['ifFormatIcon'] : $formatArr['ifFormatIcon'];
        $formatArr['ifStripHtml'] = (isset($commonReqParams['ifStripHtml']) && is_bool($commonReqParams['ifStripHtml'])) ? $commonReqParams['ifStripHtml'] : $formatArr['ifStripHtml'];

        $type = $commonReqParams['type'];
        $ctype = $commonReqParams['ctype'];
        $ssouid = intval($commonReqParams['ssouid']);
        //获取默认的参数
//        $pid = intval($this->request->getQuery('pid', null, 0)); //专辑ID
        $xid = intval($this->request->getQuery('xid', null, 0)); //视频ID
        $during_type = intval($this->request->getQuery('during_type', null, 1)); //获取评论时间段类型 1.当天2.上周

        if (!$xid) {
            $this->output(array('result' => '视频id不能全为空'));
        }
        if (!$during_type) {
            $this->output(array('result' => '评论时间类型不符合要求'));
        }
        if (!is_numeric($xid) && !is_numeric($during_type)) {
            $this->output(array('result' => $this->_Err['error']));
        }
        if (!in_array($type, array('video', 'news', 'broadcast', 'chat'))) {
            $this->output(array('result' => '参数不符合要求'), false, $this->request->getQuery('jscript', null, 0));
        }

        //获取审核规则 由于专辑id未采集
        $authRule = 0;
//        $authRule = $commentModelObj->getAuthRule($pid, $xid);
        //获取列表数
        $total = $commentModelObj->getTotalLimitDuring($xid, $type, $during_type);

        $data = array(
            'result' => '200',
            'total' => $total,
            'rule' => $authRule,
            'authData' => array(),
        );
        $this->output($data);
    }

    private function __fixCommentCount($xids = array(), $pids = array()) {//TODO
        global $config;
        $_cmtType = $config['_cmtType'];
        $_cmtTypeMath = $config['_cmtTypeMath'];
        $commentModelObj = new Comment();
        $collection = 'comment_video';
        if (!empty($xids) && is_array($xids)) {
            foreach ($xids as $xid) {
                if (!is_numeric($xid)) {
                    continue;
                }
                $xid = intval($xid);
                $authRule = $commentModelObj->getAuthRule(0, $xid);
                $query['xid'] = $xid;
                if ($config['_authRuleOn'] == $authRule) {
                    $query['status'] = 1;
                }
                $nbothTotal = $commentModelObj->countCollection($collection, $query);
                $query = array(
                    'xid' => $xid,
                    'cmtType' => $_cmtType['cmt'],
                );
                if ($config['_authRuleOn'] == $authRule) {
                    $query['isflag'] = array('$exists' => false);
                }
                $nnopicTotal = $commentModelObj->countCollection($collection, $query);
                $query = array(
                    'xid' => $xid,
                    'cmtType' => $_cmtType['vote'],
                );
                if ($config['_authRuleOn'] == $authRule) {
                    $query['isflag'] = array('$exists' => false);
                }
                $nvoteTotal = $commentModelObj->countCollection($collection, $query);
                $totalArr = array(
                    'ctotal' => $nbothTotal,
                    'nptotal' => $nnopicTotal,
                    'votetotal' => $nvoteTotal,
                );
                $commentModelObj->fixCommentTotal('video', 0, $xid, $totalArr);
                //$commentModelObj->flushListTotalCache($xid, 0, 'video', ($_cmtTypeMath['img'] | $_cmtTypeMath['vote'] | $_cmtTypeMath['cmt']));
            }
        }

        if (!empty($pids) && is_array($pids)) {
            foreach ($pids as $pid) {
                if (!is_numeric($pid)) {
                    continue;
                }
                $pid = intval($pid);
                $authRule = $commentModelObj->getAuthRule($pid, 0);
                $query = array(
                    'pid' => $pid,
                );
                if ($config['_authRuleOn'] == $authRule) {
                    $query['isflag'] = array('$exists' => false);
                }
                $nbothTotal = $commentModelObj->countCollection($collection, $query);
                $query = array(
                    'pid' => $pid,
                    'cmtType' => $_cmtType['cmt'],
                );
                if ($config['_authRuleOn'] == $authRule) {
                    $query['isflag'] = array('$exists' => false);
                }
                $nnopicTotal = $commentModelObj->countCollection($collection, $query);
                $query = array(
                    'pid' => $pid,
                    'cmtType' => $_cmtType['vote'],
                );
                if ($config['_authRuleOn'] == $authRule) {
                    $query['isflag'] = array('$exists' => false);
                }
                $nvoteTotal = $commentModelObj->countCollection($collection, $query);
                $totalArr = array(
                    'ctotal' => $nbothTotal,
                    'nptotal' => $nnopicTotal,
                    'votetotal' => $nvoteTotal,
                );
                $commentModelObj->fixCommentTotal('video', $pid, 0, $totalArr);
                //$commentModelObj->flushListTotalCache(0, $pid, 'video', ($_cmtTypeMath['img'] | $_cmtTypeMath['vote'] | $_cmtTypeMath['cmt']));
            }
        }
    }

    private function __handleList($list, $cxtList, $commonReqParams) {
        $commentModelObj = new CommentLikes();
        //获取登录信息
        $islogin = (false !== $commonReqParams['ssouid'] && !empty($commonReqParams['loginUserInfo'])) ? true : false;
        $ssouid = intval($commonReqParams['ssouid']);

        //获取登录用户是否喜欢过这些评论
        $isLikeInfo = array();
        if (true === $islogin) {
            $query = array(
                'ssouid' => $ssouid,
                'commentids' => $list,
            );
            $isLikeInfo = $commentModelObj->getlikeCommentMulit($query);
        }
        foreach ($list as $index => $commentid) {
            if (!isset($cxtList[$commentid])) {
                unset($list[$index]);
                continue;
            }
            $list[$index] = $cxtList[$commentid];
            $list[$index]['isLike'] = isset($isLikeInfo[$commentid]) ? true : false;
            //修正如果用户已经喜欢过这条评论，但是获取的喜欢数为0，则进行修正
            if (true === $list[$index]['isLike'] && 0 === $list[$index]['like']) {
                $list[$index]['like']++;
            }
        }
        unset($isLikeInfo);
        return $list;
    }

    /**
     * @desc 检测是否垃圾用户.
     * @param array $data
     * @param bool $isComment
     * @return bool
     */
    protected function __antiSpam($data = array(), $isComment = true) {
        $commentModelObj = new Comment();
        $userModelObj = new User();
        //30秒禁止发贴
        if (false === $isComment) {
            $lastPost = $commentModelObj->getLastCommentTime($data['ssouid'], false);
        } else {
            $lastPost = $commentModelObj->getLastCommentTime($data['ssouid'], true);
        }
        $authInterval = $commentModelObj->_authConfig['authInterval'];
        if (!empty($lastPost) && is_numeric($lastPost) && $lastPost > 0 && (time() - intval($lastPost)) < $authInterval) {
            $this->output(array('result' => $this->_Err['time']), false, $this->request->getQuery('jscript', null, 0));
        }
        //限IP
        if (1 == $userModelObj->checkUserIpAuth($data['ip'])) {
            $this->output(array('result' => $this->_Err['forbidIP']), false, $this->request->getQuery('jscript', null, 0));
        }
        //查看用户是否被禁言
        if (1 == $userModelObj->checkUserAuth($data['ssouid'])) {
            $this->output(array('result' => $this->_Err['forbidUser']), false, $this->request->getQuery('jscript', null, 0));
        }
        //new一天一个uid最多发50条评论【5分钟发评论超过 30条】
        $ip_comment_total = $commentModelObj->get_ip_comment_num($data['uid']);//ip修改为uid
        $authCnum = $commentModelObj->_authConfig['authCnum'];
        if ($ip_comment_total >= $authCnum) {
            $this->output(array('result' => $this->_Err['more']), false, $this->request->getQuery('jscript', null, 0));
        }
        //new防止同一uid发同一评论【防止同一IP发同一评论】
        $lastContent = $commentModelObj->get_ip_comment_last($data['uid'],$data['xid']);//ip修改为uid
        if ($lastContent == $data['content']) {
            $this->output(array('result' => $this->_Err['repeat']), false, $this->request->getQuery('jscript', null, 0));
        }
        return true;
    }


    /**
     * 过滤评论文本.
     *
     * @access protected
     * @param string $content (default: "")
     * @return void
     */
    protected function __filterCommentContent($content = "") {
        $commentModelObj = new Comment();
        $needFilter = true;
        //评论内容 去空格 、 过滤
        #$content = $needFilter ? $this->__getSafeText(preg_replace("~\r\n+~im",'', $content)) : trim($content);
        $content = $needFilter ? $commentModelObj->__getSafeText($content, true) : trim($content);

        //判断内容长度
        if (false == $commentModelObj->__checkStringLength($content, 1, "")) {
            $this->output(array('result' => $this->_Err['short']), false, $this->request->getQuery('jscript', null, 0));
        }

        if (false == $commentModelObj->__checkStringLength($content, "", 140)) {
            $this->output(array('result' => $this->_Err['long']), false, $this->request->getQuery('jscript', null, 0));
        }
        return $content;
    }

    /**
     * @desc 设置反垃圾规则.
     * @access protected
     * @param array $data (default: array())
     * @return void
     */
    protected function __setNewAntiApanRule($data = array()) {
        $commentModelObj = new Comment();
        //更新用户最后发的评论
        $commentModelObj->set_ip_comment_last($data['uid'], $data['xid'], $data['content']);//ip修改为uid
        //更新5分钟发的评论数
        $ip_comment_total = $commentModelObj->get_ip_comment_num($data['uid']);//ip修改为uid
        $ip_comment_total = $ip_comment_total + 1;
        $commentModelObj->set_ip_comment_num($data['uid'], $ip_comment_total);//ip修改为uid
        return true;
    }

    private function __handList($list, $quotlist) {
        $newquotlist = array();
        $newlist = array();
        if (!empty($quotlist) && is_array($quotlist)) {
            foreach ($quotlist as $index => $comment) {
                $id = $comment['_id'];
                $newquotlist[$id] = $comment;
            }
        }
        if (!empty($list) && is_array($list)) {
            foreach ($list as $index => $comment) {
                if (!empty($comment['replycommentid'])) {
                    $replycommentid = $comment['replycommentid'];
                    $comment['replycomment'] = isset($newquotlist[$replycommentid]) ? $newquotlist[$replycommentid] : null;
                }
                $newlist[$index] = $comment;
            }
        }
        return $newlist;
    }

}