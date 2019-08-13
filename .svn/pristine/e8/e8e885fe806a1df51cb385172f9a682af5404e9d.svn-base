<?php

use Phalcon\Mvc\View as V;

class YearBaseController extends BaseController {

    // 当前控制器/方法/参数
    public $c;
    public $a;
    // 设置忽略action
    public $ignore;

    // 当前用户授权原子列表（已解析role）
    public $auth;
    // 全局原子列表
    public $element;
    // 全局模块列表
    public $module;
    // 当前用户授权的路由列表
    public $routerAuth;

    public function initialize() {
        parent::initialize();
        $this->initRouter();
        if ($this->checkLogin()) {
            $this->checkAuth();
        }
    }

    public function getIgnore() {
        return $this->ignore == "*" ? true : in_array($this->a, (array)$this->ignore);
    }

    /**
     * 登录校验
     */
    public function checkLogin() {
        Auth::setAuthModel("Admin");
        if (!(Auth::check() || Auth::viaRemember())) {
            if ($this->c . "/" . $this->a !== "admin/login" && !$this->getIgnore()) {
                $tag = Cookie::getValue('channelTag') ?: 'system';
                redirect(Url::get('admin/login', ['id' => $tag]));
            }
            return false;
        }
        return true;
    }

    /**
     * 全局权限控制
     */
    public function checkAuth() {
        $admin = Session::get('user');
        $this->auth = $auth = AuthAssignYear::getAuth($admin)[0];
//        var_dump($this->auth);
        $this->element = $element = AuthElementYear::getAll();
        $this->module = $module = AuthModuleYear::getAll();
//        var_dump($this->element);exit;
        $checkAuth = $this->parseRouterAuth();
        $router = $this->c . "/" . $this->a;
        View::setVars(compact("auth", "element", "module", "checkAuth"));
        //TODO 增加系统级元子判断
        if ($admin->is_admin || $this->getIgnore() || in_array($router, D::authIgnore())) {
            return;
        }
        foreach ($auth as $v) {

            // 系统级管理员
            // 频道级管理员
            // 拥有权限用户
            $v = $element[$v];
            if (($admin->is_admin && $v['is_system'] == 0) ||
                ($v['controller'] . "/" . $v['action'] == $router)
            ) {
                return;
            }
        }
        $this->accessDenied(Lang::_('noauth'));
    }

    protected function parseRouterAuth() {
        $routers = [];
        $admin = Session::get('user');
        if (!empty($this->element)) {
            foreach ($this->element as $e) {
                // 系统管理员
                $router = $e['controller'] . "/" . $e['action'];
                if ($admin->channel_id == 0 ||
                    ($admin->is_admin == 1 && !$e['is_system']) ||
                    in_array($router, D::authIgnore()) ||
                    in_array($e['id'], $this->auth)
                ) {
                    $routers[] = $router;
                }
            }
        }
        $checkAuth = function ($key) use ($routers) {
            $key = parse_url($key)['path'];
            $key = trim($key, "/");
            return in_array($key, $routers);
        };
        return $checkAuth;
    }

    protected function initRouter() {
        $r = $this->router;
        $this->c = $r->getControllerName() ?: "index";
        $this->a = $r->getActionName() ?: "index";
    }

    protected function initFormView() {

        View::setRenderLevel(V::LEVEL_AFTER_TEMPLATE);
        View::setTemplateAfter('form');
        View::setVar('model', []);

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
        header('Content-type: application/json');
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }

    protected function accessDenied($msg = '') {
        $handle = 'error/index';
        if (isset(app_site()->error_handler)) {
            $handle = implode("/", array_values(app_site()->error_handler->toArray()));
        }
        View::setVars(['msg' => $msg]);
        View::pick($handle);
    }

}
