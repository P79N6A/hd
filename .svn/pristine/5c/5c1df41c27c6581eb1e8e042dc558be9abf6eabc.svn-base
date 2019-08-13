<?php

/**
 *  爆料管理
 *  model baoliao
 *  @author     Haiquan Zhang
 *  @created    2015-11-11
 *  
 *  status 0:删除/1:审核/2:未审核
 */
class BaoliaoController extends \BackendBaseController {

    public function indexAction() {
        if (Request::isGet()) {
            $channel_id = Session::get("user")->channel_id;
            $status = $this->request->getQuery('status');
            $content = $this->request->getQuery('content');
            if ($status === null) {
                $status = Baoliao::ALL;
            } else if($status != Baoliao::ACCEPT && $status != Baoliao::REJECT && $status != Baoliao::UNCHACKED) {
                $status = Baoliao::ALL;
            }
            $data = Baoliao::getBaoliaoList($channel_id, $status, $content);
            View::setVars(compact('data', 'status'));
        }
    }

    public function detailAction() {
        $baoliao_id = (int)Request::get('id');
        $channel_id = Session::get('user')->channel_id;
        $baoliao = Baoliao::findFirst($baoliao_id);
        if($baoliao->channel_id != $channel_id) {
            $this->accessDenied();
        }
        $data['name'] = Baoliao::getName($baoliao_id);
        $data ['file']= BaoliaoAttachment::getAttachs($baoliao_id);
        View::setVars(compact('data'));
    }

    /**
     * 审核爆料
     */
    public function lockAction() {
        $baoliao_id = (int)Request::get('id');
        $channel_id = Session::get('user')->channel_id;
        $baoliao = Baoliao::findFirst($baoliao_id);
        if($baoliao->channel_id == $channel_id && $baoliao->changeStatus(Baoliao::ACCEPT)) {
            $key = "baoliao_user_id:" . $baoliao->user_id;
            MemcacheIO::set($key, false, 86400 * 30);
            $arr=array('code'=>200);
        }
        else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 取消审核
     */
    public function unlockAction() {
        $baoliao_id = (int)Request::get('id');
        $channel_id = Session::get('user')->channel_id;
        $baoliao = Baoliao::findFirst($baoliao_id);
        if($baoliao->channel_id == $channel_id && $baoliao->changeStatus(Baoliao::UNCHACKED)) {
            $key = "baoliao_user_id:" . $baoliao->user_id;
            MemcacheIO::set($key, false, 86400 * 30);
            $arr=array('code'=>200);
        }
        else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function replyAction() {
        $messages = [];
        $baoliao_id = (int)Request::get('id');
        $baoliao_reply = BaoliaoReply::findFirst(array('condition'=>'baoliao_id='.$baoliao_id));
        if (Request::isPost()) {
            $data = Request::getPost();
            $reply = $data['reply'];
            $user = Session::get('user');
            $data = array(
                'baoliao_id' => $baoliao_id,
                'reply' => $reply,
                'author_id' => $user->id,
                'author_name' => $user->name,
                'create_at' => time()
            );
            if ($baoliao_reply) {
                if ($baoliao_reply->update($data)) {
                    $messages[] = Lang::_('success');
                }
            }
            else {
                $baoliao_reply = new BaoliaoReply();
                if ($baoliao_reply->save($data)) {
                    $messages[] = Lang::_('success');
                }
            }
        }
        View::setVars(compact('messages','baoliao_reply', 'baoliao_id'));
        View::setMainView('layouts/add');
    }

    public function deleteAction() {
        $baoliao_id = (int)Request::get('id');
        $channel_id = Session::get('user')->channel_id;
        $baoliao = Baoliao::findFirst($baoliao_id);
        $baolibao_uid = $baoliao->user_id;
        if($baoliao && $baoliao->channel_id == $channel_id && $baoliao->deleteBaoliao()) {
            $key = "baoliao_user_id:" . $baolibao_uid;
            MemcacheIO::set($key, false, 86400 * 30);
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        }
        else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }
        exit;
    }

    private function json($value) {
        $this->response->setJsonContent($value, JSON_UNESCAPED_UNICODE);
        return $this->response;
    }

}
