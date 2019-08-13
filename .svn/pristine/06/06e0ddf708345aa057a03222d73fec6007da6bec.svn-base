<?php

class AuditCommentKeywordsController extends \BackendBaseController {

    public function indexAction() {
        $data = AuditCommentKeywords::getAll();
        View::setVars(compact('data'));
    }

    public function deleteAction() {
        $id=Request::getQuery();
        $return=AuditCommentKeywords::deleteAuditCommentKeywords($id['id']);
        if($return){
            AuditCommentKeywordVersion::updateVersion();//增加敏感词版本号
            RedisIO::delete('public_keyword_version');//删除缓存
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function addAction(){
        $messages= [];
        if(Request::isPost()) {
            $data = Request::getPost();
            if(empty($data['keyword'])){
                $messages[] = Lang::_('error');
            }else{
                $data['addtime'] = time();
                $data['type'] = 'filter';
                $data['audit_name'] = Session::get('user')->name;
                $comment = AuditCommentKeywords::findOneByKeywords($data['keyword'],$data['type']);
                if($comment){
                    $messages[] = Lang::_('keyword has exit');
                }else {
                    $keywords = new AuditCommentKeywords();
                    $return = $keywords->createAuditCommentKeywords($data);
                    if ($return) {
                        AuditCommentKeywordVersion::updateVersion();//增加敏感词版本号
                        RedisIO::delete('public_keyword_version');//删除缓存
                        $messages[] = Lang::_('success');
                    } else {
                        $messages[] = Lang::_('error');
                    }
                }
            }

        }

        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function searchAction() {
        $input=Request::getQuery();
        $search = array();
        if(isset($input['keyword'])){
            $search['keyword']=Request::getQuery('keyword','string');
        }else{
            $search['keyword'] = '';
        }
        $data = AuditCommentKeywords::searchKeywords($search);
        View::pick('audit_comment_keywords/index');
        View::setVars(compact('data','search'));
    }
}