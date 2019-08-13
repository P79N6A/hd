<?php

class AuditCommentBlockipController extends \BackendBaseController {
    private $_mcPrefix_auth = 'BAD_COMMENTS::';//审核系统 用户屏蔽


    public function indexAction() {
        $data = AuditCommentBlockip::getAll();
        View::setVars(compact('data'));
    }

    public function deleteAction() {
        $id=Request::getQuery();
        $obj = AuditCommentBlockip::getOneById($id['id']);
        $return = false;
        if($obj) {
            $ip = $obj->ip;
            $return = AuditCommentBlockip::deleteAuditCommentBlockip($obj);
        }
        if($return){
            $cacheId = $this->_mcPrefix_auth.'IP::'.$ip;
            RedisIO::delete($cacheId);
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }


    /**
     * @desc 得到无符号整数表示的ip地址
     * @return string
     */
    public function getIntIp($realip)
    {
        return sprintf('%u', ip2long($realip));
    }

    public function searchAction() {
        if(Request::getQuery('keyword','string')) {
            $search['keyword']=$this->getIntIp(Request::getQuery('keyword','string', 0));
            $data = AuditCommentBlockip::search($search);
        }
        else{
            $search=array('keyword'=>'0');
            $data = AuditCommentBlockip::getAll();
        }


        View::pick('audit_comment_blockip/index');
        View::setVars(compact('data','search'));
    }

    public function banipAction() {
        $ip = Request::getQuery('ip');
        $comment_id = Request::getQuery('id');
        $channel_id = Session::get('user')->channel_id;
        $data = array(
            'ip' => $ip,
            'addtime' => time(),
            'audit_name' => Session::get('user')->name
        );

        $cacheId = $this->_mcPrefix_auth.'IP::'.$ip;
        RedisIO::set($cacheId , 1);

        $blockip = AuditCommentBlockip::findFirst(array("conditions"=>"ip='{$ip}'"));

        if($blockip) {
            $keywords = $blockip;
        }
        else {
            $keywords = new AuditCommentBlockip();
        }

        $return = $keywords->createAuditCommentBlockip($data);
        if($return && $comment = Comment::deleteComment($comment_id, $channel_id)){//禁IP同时删除当前评论
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
            if(empty($data['ip'])){
                $messages[] = Lang::_('error');
            }else{
                $data['ip']	= bindec(decbin(ip2long($data['ip'])));
                $data['addtime'] = time();
                $data['audit_name'] = Session::get('user')->name;
                $keywords = new AuditCommentBlockip();
                $return = $keywords->createAuditCommentBlockip($data);
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

}