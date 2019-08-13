<?php

/**
 *  块 管理
 *  model blocks, block_values
 *  @author     Haiquan Zhang
 *  @created    2015-11-27
 *  
 */
class BlockController extends \BackendBaseController {

    public function indexAction() {
        if (Request::isGet()) {
            $channel_id = Session::get("user")->channel_id;
            $code = $this->request->getQuery('code');
            
            $data = Blocks::getBlockList($channel_id, $code);
            View::setVars(compact('data', 'code'));
        }
    }

    public function addAction() {
        if (Request::isPost()) {
            $messages = [];
            $data = Request::getPost();
            $validator = Blocks::makeValidator($data);
            if (!$validator->fails()) {
                $block = new Blocks();
                $messages = $block->createBlock($data);
            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function editAction() {
        $block_id = Request::get('id', 'int');
        if(!$block_id) {
            redirect(Url::get("admin/add"));
        }
        $channel_id = Session::get('user')->channel_id;
        $block = Blocks::getOne($block_id);
        if($block->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (Request::isPost()) {
            $messages = [];
            $data_up = Request::getPost();
            $validator = Blocks::makeValidator($data_up, $block->id);
            if (!$validator->fails()) {
                $messages = $block->modifyBlock($data_up);
            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('block','messages'));
    }

    public function deleteAction() {
        $block_id = (int)Request::get('id');
        $channel_id = Session::get('user')->channel_id;
        $block = Blocks::getOne($block_id);
        if($block && $block->channel_id == $channel_id && $block->deleteBlock()) {
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
