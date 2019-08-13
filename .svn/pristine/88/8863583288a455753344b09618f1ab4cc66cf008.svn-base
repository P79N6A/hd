<?php

use Phalcon\Mvc\View as V;

class PublishBaseController extends BaseController {

    public function initialize() {
        parent::initialize();
    }

    /**
     * 信息提示
     *
     * @param string $href
     * @param string $msg
     * @param string $type
     */
    protected function alert($msg, $type = 'danger', $btn_text = '', $href = '') {
        View::setRenderLevel(V::LEVEL_AFTER_TEMPLATE);
        View::setVars(compact('msg', 'type', 'btn_text', 'href'));
        View::setVar('model', []);
        View::pick('layouts/alert');
    }

    protected function _json($data, $code = 200, $msg = "success") {
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }

    protected function accessDenied() {
        throw new Exception(Lang::_('noauth'), 404);
    }

}
