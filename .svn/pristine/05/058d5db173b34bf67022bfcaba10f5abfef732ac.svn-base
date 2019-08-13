<?php

class CommentController extends \BackendBaseController
{
    const SEARCH_TYPE_COMMENT = 1;
    const SEARCH_TYPE_NICKNAME = 2;

    private function json($value) {
        $this->response->setJsonContent($value, JSON_UNESCAPED_UNICODE);
        return $this->response;
    }

    public function rejectAction() {
        $id = $this->request->get('id', 'int');
        $channel_id = Session::get("user")->channel_id;
        if($id && Comment::rejectComment($id, $channel_id)) {
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
            exit;
        }
        echo json_encode(['code' => '100', 'msg' => '提交失败，该评论可能已被删除']);
        exit;
    }

    public function commentindexAction() {
        $data = SpecComment::getAll();
        View::setVars(compact('data'));
    }

    public function detailAction() {
        if (Request::isGet()) {
            $data_id = Request::getQuery('data_id');
            $data = Comment::getSpecComments($data_id);
            View::setVars(compact('data'));
        }
    }

    public function editAction() {
        View::setMainView('layouts/add');
        View::setVars(compact('data'));
    }

    public function addAction() {
        View::setMainView('layouts/add');
        View::setVars(compact('data'));
    }

    public function detaileditAction() {
        View::setMainView('layouts/add');
        View::setVars(compact('data'));
    }

    //以上未整理

    public function searchAction() {
        $nickname = Request::get('nickname');
        $comment = Request::get('comment');
        $user_id = Request::getQuery('user_id','int');
        $data_id = Request::getQuery('data_id','int');
        $export =  Request::getQuery('export','int');
        $export_request_uri =  $_SERVER['REQUEST_URI'];

        $created_at_from  = (Request::getQuery('created_at_from', 'string'))?strtotime(Request::getQuery('created_at_from')):0;
        $created_at_to = (Request::getQuery('created_at_to', 'string'))?strtotime(Request::getQuery('created_at_to')):0;


        $auditComment = AuditCommentControl::findOneById(1);

        $audit = $auditComment->global;

        if($user_id) {
            $comment = Comment::getCommentByUserId($user_id, Session::get('user')->channel_id, $created_at_from, $created_at_to);
            $data = array();
            $data['rs'] = array();
            if($comment){
                foreach($comment as $c) {
                    $data['rs'][] = $c->toArray();
                }
            }
        }
        elseif($data_id) {
            $comment = Comment::getCommentByDataId($data_id, Session::get('user')->channel_id, $created_at_from, $created_at_to);
            $data = array();
            $data['rs'] = array();
            if($comment){
                foreach($comment as $c) {
                    $data['rs'][] = $c->toArray();
                }
            }
        }
        else {
            $searchtype = 0;
            if ($comment) $searchtype |= self::SEARCH_TYPE_COMMENT;
            if ($nickname) $searchtype |= self::SEARCH_TYPE_NICKNAME;

            if ($searchtype) {
                $q = array('nickname' => $nickname, 'comment' => $comment, 'from'=>$created_at_from, 'to'=>$created_at_to);
                $p = Request::get('p', 'int', 1);
                $d = Session::get('user')->channel_id;
                $solr = $this->getDI()->getShared('solr.comment');
                $page_size = ($export)?1000:50;
                $data = SolrEngine::searchComment($solr, $d, $q, $searchtype, $p, $page_size);
            } else {
                if($created_at_from||$created_at_to) {
                    $comment = Comment::getCommentByTime(Session::get('user')->channel_id, $created_at_from, $created_at_to);
                    $data = array();
                    $data['rs'] = array();
                    if($comment) {
                        foreach($comment as $c) {
                            $data['rs'][] = $c->toArray();
                        }
                    }
                }
                else {
                   $data = array();
                }

            }

        }
        if($export) {
            $this->exportWin($data);
        }

        View::setVars(compact('data','audit', 'export_request_uri'));
    }



    private function exportWin($data) {
        error_reporting(0);
        ini_set("memory_limit", "-1");
        set_time_limit(0);
        $exportData = [];
        $i=0;
        if(!empty($data)) {
            foreach ($data['rs'] as $comment) {
                if(isset($comment['created_at']))
                    $comment['create_at'] = $comment['created_at'];
                $comment['create_at'] = date("Y-n-d H:i:s", $comment['create_at']);
                switch($comment['status']) {
                case Comment::ACCEPT: $comment['status'] = "已审核";break;
                case Comment::REJECT: $comment['status'] = "已拒绝";break;
                case Comment::UNCHACKED: $comment['status'] = "未审核";break;
                case Comment::DELETE: $comment['status'] = "已删除";break;
                }
                $exportData[] = $comment;
                $i++;
                if($i>=10000) break;
            }
        }
        \F::createExcel(Comment::$properties, $exportData, Lang::_('comment result'));
        View::disable();
    }

    public function indexAction() {

        $channel_id = Session::get("user")->channel_id;

        $data = Comment::getCommentsAll($channel_id);
       
        $auditComment = AuditCommentControl::findOneById(1);
       
        $audit = $auditComment->global;
        
        View::setVars(compact('data', 'status','audit'));

    }

    public function listsAction() {
        $data_id = Request::getQuery('data_id','int');
        //$channel_id = Session::get("user")->channel_id;

        $data = UserComments::getCommentsBydataId($data_id);
        //var_dump($data);

        $auditComment = AuditCommentControl::findOneById(1);

        $audit = $auditComment->global;

        View::setVars(compact('data', 'status','audit'));

    }

    public function acceptAction() {
        $id = $this->request->get('id', 'int');
        $channel_id = Session::get("user")->channel_id;
        if($id && Comment::acceptComment($id, $channel_id)) {
            $comment = Comment::getComment($id, $channel_id);
            RedisIO::incr("comment_accept_count_" . $comment->data_id);//通过审核评论总数增加1
            RedisIO::zAdd("comment_accept_" . $comment->type . "_" . $comment->data_id . "_commentIds", $comment->comment_id, $comment->comment_id);

            $memCacheKey = "comment_" . $comment->type . "_commentInfos_" . $comment->comment_id;
            $comment->setCommentCacheStatus($memCacheKey, $comment->status);

            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
            exit;
        }
        echo json_encode(['code' => '100', 'msg' => '提交失败，该评论可能已被删除']);
        exit;
    }

    public function uncheckedAction() {
        $id = $this->request->get('id', 'int');
        $channel_id = Session::get("user")->channel_id;
        if($id && Comment::uncheckedComment($id, $channel_id)) {
            $comment = Comment::getComment($id, $channel_id);
            RedisIO::decr("comment_accept_count_" . $comment->data_id);//通过审核评论总数增加1
            RedisIO::zRem("comment_accept_" . $comment->type . "_" . $comment->data_id . "_commentIds", $comment->comment_id, $comment->comment_id);

            $memCacheKey = "comment_" . $comment->type . "_commentInfos_" . $comment->comment_id;
            $comment->setCommentCacheStatus($memCacheKey, $comment->status);

            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
            exit;
        }
        echo json_encode(['code' => '100', 'msg' => '提交失败，该评论可能已被删除']);
        exit;
    }

    public function deleteAction() {
        $id = $this->request->get('id', 'int');
        $channel_id = Session::get("user")->channel_id;
        if($id && $comment = Comment::deleteComment($id, $channel_id)) {
            $comment = Comment::getComment($id, $channel_id);
            RedisIO::decr("comment_count_".$comment->data_id);//评论总数减少1
            RedisIO::zRem("comment_".$comment->type."_".$comment->data_id."_commentIds", $comment->comment_id);//【评论id集合】删除集合中的commentid
            RedisIO::del("comment_".$comment->type."_commentInfos_".$comment->comment_id);//【评论id对应的详细内容】删除字符串中commentid对应的信息
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
            exit;
        }
        echo json_encode(['code' => '100', 'msg' => '提交失败，该评论可能已被删除']);
        exit;
    }



    public function deleteajaxAction() {
        //操作后数据的状态名称
        $statusArr = array('3'=>'已删除');
        //操作的名称
        $operateArr = array('3'=>'删除');

        $id = Request::getPOST('id');
        $st = Request::getPOST('st', 'int');


        if (empty($id)) {
            $this->_json([], 404, D::apiError(4001));
        }
        $channel_id = Session::get("user")->channel_id;

        if(is_array($id)){
            if($st==3) {
                foreach($id as $v) {
                    $comment = Comment::getComment($v, $channel_id);
                    if($comment->status != Comment::DELETE) {
                    Comment::deleteComment($v, $channel_id);
                    RedisIO::decr("comment_count_".$comment->data_id);//评论总数减少1
                    RedisIO::zRem("comment_".$comment->type."_".$comment->data_id."_commentIds", $comment->comment_id);//【评论id集合】删除集合中的commentid
                    RedisIO::del("comment_".$comment->type."_commentInfos_".$comment->comment_id);//【评论id对应的详细内容】删除字符串中commentid对应的信息
                    }

                }
            }
            $this->_json([]);
            exit;
        }
        else {
            $id = intval($id);
        }
        $this->_json([]);
    }

}