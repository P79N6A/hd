<?php

class AuditCommentControlController extends \BackendBaseController {

    public function indexAction() {
        $data = AuditCommentControl::findOneById(1);
        if($data){
            $data = $data->toArray();
            $data['ext_field'] = explode('|',$data['ext_field']);
        }
        View::setVars(compact('data'));
    }

    public function addAction() {//修改配置参数
        $input = Request::getPost();
        $data = AuditCommentControl::findOneById();
        if($data) {
            if(Request::isPost()) {
                $ext_field = "{$input['authInterval']}|{$input['authCRuntime']}|{$input['authCnum']}";
                $data->ext_field = $ext_field;
                $data->global = $input['switch'];
                $data->updateAuditComment();
                $cacheId = 'video:comment:control';//修改时删除原有缓存
                RedisIO::delete($cacheId);
            }
            $data = $data->toArray();
            $data['ext_field'] = explode('|',$data['ext_field']);
        }
        View::setVars(compact('data'));
        View::pick('/audit_comment_control/index');
    }

}