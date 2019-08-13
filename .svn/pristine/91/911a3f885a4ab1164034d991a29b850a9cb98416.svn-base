<?php

class AuditBanCommentsController extends \BackendBaseController {
    private $_mcPrefix_auth = 'BAD_COMMENTS::';//审核系统 用户屏蔽


    public function indexAction() {
        $data = AuditBanComments::getAll();
        View::setVars(compact('data'));
    }

    public function deleteAction() {
        $id=Request::getQuery();
        $obj = AuditBanComments::getOneById($id['id']);
        $return = false;
        if($obj) {
            $ssoid = $obj->ssoid;
            $return = AuditBanComments::deleteAuditBanComments($obj);
        }
        if($return){
            $cacheId = $this->_mcPrefix_auth.$ssoid;
            RedisIO::delete($cacheId);
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function banuserAction() {
        $user_id = Request::getQuery('user_id');
        $comment_id = Request::getQuery('id');
        $channel_id = Session::get('user')->channel_id;
        $data = array(
            'ssoid' => $user_id,
            'addtime' => time(),
            'audit_name' => Session::get('user')->name
        );



        $blockuser = AuditBanComments::findFirst(array("conditions"=>"ssoid='{$user_id}'"));

        if($blockuser) {
            $keywords = $blockuser;
        }
        else {
            $keywords = new AuditBanComments();
        }





        $return = $keywords->createAuditBanComments($data);

        $cacheId = $this->_mcPrefix_auth.$user_id;
        RedisIO::set($cacheId , 1);

        if($return && $comment = Comment::deleteComment($comment_id, $channel_id)){//禁用户同时删除当前评论
            $comment = Comment::getComment($comment_id, $channel_id);
            RedisIO::decr("comment_count_".$comment->data_id);//评论总数减少1
            RedisIO::zRem("comment_".$comment->type."_".$comment->data_id."_commentIds", $comment->comment_id);//【评论id集合】删除集合中的commentid
            RedisIO::del("comment_".$comment->type."_commentInfos_".$comment->comment_id);//【评论id对应的详细内容】删除字符串中commentid对应的信息
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
            exit;
        }
        echo json_encode(['code' => '100', 'msg' => '提交失败，该评论可能已被删除']);
        exit;
    }

    public function addAction(){
        $messages= [];
        if(Request::isPost()) {
            $data = Request::getPost();
            if(empty($data['ssoid'])){
                $messages[] = Lang::_('error');
            }else{
                $data['addtime'] = time();
                $data['audit_name'] = Session::get('user')->name;
                $keywords = new AuditBanComments();
                $return = $keywords->createAuditBanComments($data);
                if ($return) {
                    $messages[] = Lang::_('success');
                } else {
                    $messages[] = Lang::_('error');
                }
            }

        }

        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function searchAction() {
        $input=Request::getQuery();
        $search = array();
        if(isset($input['ssoid'])){
            $search['ssoid']=Request::getQuery('ssoid','string');
        }else{
            $search['ssoid'] = '';
        }
        $data = AuditBanComments::searchSsoid($search);
        View::pick('audit_ban_comments/index');
        View::setVars(compact('data','search'));
    }
}