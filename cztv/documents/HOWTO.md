CZTV 项目实用说明
===

### 文档更新记录

1. 2015-09-17 09:22 新增文档
2. 2015-09-17 10:22 新增文档变更记录
3. 2015-09-21 16:48 新增`模型 save 或者 update 出错如何调试`

### 文档说明

本文档为 markdown 格式文档, 具体请参见 [Markdown 语法格式](https://github.com/riku/Markdown-Syntax-CN/blob/master/syntax.md)

如果有阅读有所不适请使用 [Markdown 在线工具](http://tool.oschina.net/markdown) 通过预览阅读, PHPStorm 也有 Markdown 预览功能.

### 目录结构

    bootstrap <- 启动脚本目录
    commands  <- 通用命令目录
    libraries <- 自定义类库目录
    libraries/GenialCloud <- 自定义的核心
    vendor    <- composer 第三方库目录
    
### Facade 列表

大多数的 Facade 类是针对框架既有DI组件的封装, 满足的需求很简单, 就是直接通过类名的静态方法完成共享DI组件的动态方法调用, 以减少代码调用.

目前已有的 Facade 列表如下:

<table width="80%" border="1">
    <tr>
        <th>类名</th>
        <th>对应组件</th>
        <th>说明</th>
    </tr>
    <tr>
        <td>Cookie</td>
        <td>控制器的 $this->cookies 或者其他层里的 $this->getID->get('cookies');</td>
        <td>Cookie</td>
    </tr>
    <tr>
        <td>Crypt</td>
        <td>控制器的 $this->crypt 或者其他层里的 $this->getID->get('crypt');</td>
        <td>加密</td>
    </tr>
    <tr>
        <td>DB</td>
        <td>控制器的 $this->db 或者其他层里的 $this->getID->get('db');</td>
        <td>数据库</td>
    </tr>
    <tr>
        <td>Lang</td>
        <td>lang</td>
        <td>语言包, CZTV 自有组件</td>
    </tr>
    <tr>
        <td>MemcacheIO</td>
        <td>控制器的 $this->cache 或者其他层里的 $this->getID->get('cache');</td>
        <td>缓存类, 为了避免与扩展 Memcache 冲突, 添加了 IO 后缀</td>
    </tr>
    <tr>
        <td>RedisIO</td>
        <td>redis</td>
        <td>Redis 类, 为了避免与扩展 Redis 冲突, 添加了 IO 后缀</td>
    </tr>
    <tr>
        <td>Request</td>
        <td>控制器的 $this->request 或者其他层里的 $this->getID->get('request');</td>
        <td>请求</td>
    </tr>
    <tr>
        <td>Session</td>
        <td>控制器的 $this->session 或者其他层里的 $this->getID->get('session');</td>
        <td>会话, session 增加对了 CSRF token 的快捷支持</td>
    </tr>
    <tr>
        <td>Url</td>
        <td>控制器的 $this->url 或者其他层里的 $this->getID->get('url');</td>
        <td>url 路径生成, 增加了 getRefererElse() 方法, 该方法可以在有来源(Referer)的情况下返回来源, 没有来源的情况下生成新的 url.</td>
    </tr>
    <tr>
        <td>View</td>
        <td>控制器的 $this->view 或者其他层里的 $this->getID->get('view');</td>
        <td>视图</td>
    </tr>
</table>

_注: 点击 [CSRF](http://baike.baidu.com/view/1609487.htm) 了解什么是 CSRF._

### 控制器基类 BaseController

控制器积累, 所有项目中用到的控制器应继承的基, `BaseController` 在 `beforeExecuteRoute` 完成了各个 DI 组件的绑定, 如果需要在子类中重写 `beforeExecuteRoute` 应该重载父类的这个方法 `parent::beforeExecuteRoute`.

### 模型基类 Model

模型积累, 所有模型对象应该继承的方法, 模型为了实现 `paginate` (分页) 方法重写了 `model::query()` 方法

### 模型查询对象 GenialCloud\Database\Criteria

自定的 Criteria 对象, 用于提高 `Model::query` 的便利性

在需要分页的情况下使用 `ModelName::query()->paginate($itemSize)` 即可完成分页, 该方法返回一个 `GenialCloud\Support\Parcel` 对象, 该对象有三个属性 `models`, `count`, `pagination` 分别为结果集, 查询总数 和 分页对象. 分页对象 `$parcel->pagination` 的 `render` 方法直接输出分页 html.

`ModelName::andCondition($column, $operation, $value=null)`, `ModelName::orCondition($column, $operation, $value=null)`

用于简易的条件查询, 如果$value为空, 则自动将判断条件识别为 '=', 将值通过 $operation 传入.

现在用唯一字段 `name` 为 `cztv` 的用户查询来展示 andCondition 和 first 的用法

    Users::query()->andCondition('name', 'cztv')->first();

或者

    Users::query()->andCondition('name', '=', 'cztv')->first();
    
### 模型 save 或者 update 出错如何调试

    $model = new Model;

    if(!$model->save()) {
    
        foreach($model->getMessages() as $m) {
            var_dump($m->getMessage());
        }
        
    }

### 配置获取 Config

配置类, 用于简化全局获取配置参数, 调用方法为 `Config::get('xxx')` 获取配置参数, 如果需要数组输出可以使用 `Config::get('xxx')->toArray()` 方法

### 任务 Task

任务(命令)基类, `Task` 在 `beforeRun` 完成了各个 DI 组件的绑定, 如果需要在子类中重写 `beforeRun` 应该重载父类的这个方法 `parent::beforeRun`.

### 登录校验 Auth

登陆校验类, 参照 Laravel 的 Auth 实现的, 具体用法可以参考 (Laravel 4.2 - 用户认证)[http://www.golaravel.com/laravel/docs/4.2/security/#authenticating-users].

_注: 因为没有 `once` 的使用场景, 所以该方法并未实现._

### 输入 Input

Input 类主要用于输入数据的收集处理, 常用的场景就是 将现有值绑定到表单上.

`Input::init($inputs, $model)` 用于输入数据数据的收集

`Input::fetch($name)` 用于输入数据的获取

例如, 我数据中字段有 `name`, 当我需要通过表单编辑这个字段的时候, 我可以在视图顶部初始化输入 `Input::init(Request::getPost, $model)` . 当需要表单值的时候, 通过 `Input::fetch('name');` 来获取这个值.

### 校验 Validator

校验类直接来源于 Laravel 校验的封装, 具体用法参见 [Laravel 校验](http://www.golaravel.com/laravel/docs/4.2/validation/) .

### 事件 Event

事件用于, 框架固定操作的, 额外注入. 比如在实用 Auth 登陆完成后, 需要对 Session 做额外的处理, 就可以实用 Event 来处理这个问题.

目前已支持的事件:

1. `auth.attempt` 用户成功校验并准备进入 `auth.login`
2. `auth.login` 用户登入, 准备做 Session 处理
3. `auth.logout` 用户登出

### 哈希加密 Hash

Hash 主要用于密码的加密与校验, 目前采用了 sha1 完成, Hash 需要实现 GenialCloud\Support\Hash 接口

### 常用函数 helpers.php

* `dd($var, ...)` dump 变量, 并终止进程
* `csrf_token()` 对 `Session::getCsrfToken()` 的封装, 用于获取 Session 保存的 csrf token 值, 需要先调用 `Session::makeCsrfToken()` 才能正确生成 Token.
* `camel_case($str)` 将以空格, \_ 或 - 分割的字符串, 做换成驼峰命名方式
* `get($arr, $key, $default=null)` 获取数组中的值, 否则返回默认值
* `q($str)` 针对 addslashes 的封装, 主要用于无法过滤的 SQL 输入值
* `redirect($href, $code = 302)` 302 或 301 跳转
* `str_random($length=16)` 生成随机字符串
* `app_site()` 通过域名获取当前site的配置
* `static_url($path)` 通过site配置, 生成js, css, images 等静态文件的 url
* `site_path()` 获取站点目录
* `site_autoload()` 获取站点自动加载列表配置
* `site_view()` 获取站点视图目录
* `site_route()` 获取站点路由配置