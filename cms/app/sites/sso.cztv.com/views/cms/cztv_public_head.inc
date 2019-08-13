<script type="text/javascript">
    var winWidth = 0;
    window.innerWidth ? winWidth = window.innerWidth : document.body && document.body.clientWidth && (winWidth = document.body.clientWidth);
    document.documentElement && document.documentElement.clientWidth && (winWidth = document.documentElement.clientWidth);
    1700 > winWidth && 1240 <= winWidth ? document.body.className = "m" : 1240 > winWidth && (document.body.className = "s");
</script>
<div class="top-nav">
    <dl class="top-nav-con">
        <dd class="fl white">
            <h1 class="logo fl" title="中国蓝TV" id="logo-normal"><a href="http://tv.cztv.com" target="_blank"><img
                        src="http://tv.cztv.com/cztv/user/topic/logo2.png" alt="中国蓝TV" title="中国蓝TV"/></a></h1>
            <a href="http://tv.cztv.com" class="logo-active-l fl hide" id="logo-animate" title="中国蓝TV">
                <div class="bg-02" id="bgBlue"></div>
                <div class="bg-01" id="bgWhite"></div>
                <div class="logo-a" id="Logo"></div>
            </a>
            <ul class="channel fl">
                <li class="pos-re">
                    <span class="arrow">频道</span><i class="line"></i>
                    <ul class="channel-info white hide" id="arrowTitle">
                        <li><a href="http://tv.cztv.com/ap/" target="_blank" title="航拍">航拍</a></li>
                        <li><a href="http://tv.cztv.com/jilu/" target="_blank" title="纪录片">纪录片</a></li>
                        <li><a href="http://tv.cztv.com/comic/" target="_blank" title="少儿">少儿</a></li>
                        <li><a href="http://tv.cztv.com/music/" title="音乐">音乐</a></li>
                        <li><a href="http://tv.cztv.com/xinwen/" target="_blank" title="新闻">新闻</a></li>
                        <li><a href="http://tv.cztv.com/live1/" target="_blank" title="直播">直播</a></li>
                    </ul>
                </li>
                <li><a href="http://tv.cztv.com/zongyi/" title="综艺" target="_blank">综艺</a></li>
                <li><a href="http://tv.cztv.com/bulu/" title="布噜制造" target="_blank">布噜制造</a></li>
                <li><a href="http://tv.cztv.com/movie/" title="电影" target="_blank">电影</a></li>
                <li><a href="http://tv.cztv.com/life/" title="生活" target="_blank">生活</a></li>
                <li><a href="http://tv.cztv.com/tv/" title="电视剧" target="_blank">电视剧</a></li>
            </ul>
        </dd>
        <dd class="search fl">
            <form id="form" action="http://so.cztv.com/pc/s" target="_blank" method="get" accept-charset="utf-8"
                  onsubmit="return check()">
                <div class="text">
                    <input type="text" name="wd" id="searchText" class="input" placeholder="奔跑吧兄弟" autocomplete="off"/>
                    <div class="list">
                        <p class="line-gray"></p>
                        <div id="auto">
                            <p class="icon-search-delate-p"><a href="javascript:;" class="icon-search-delate">清空</a></p>
                        </div>
                    </div>
                </div>
                <input type="submit" value="" class="but"/>
            </form>
        </dd>
        <dd class="right-txt fr white">
            <div class="fl pos-re">
                <a id="viewHistory" href="#" title="观看记录" target="_blank">观看记录</a>
                <div id="box-history" class="badge-tips box-history">
                    <div class="sa"><em>◆</em><span>◆</span></div>
                    <div id="hisDiv" class="badge-cnt ui-cf">
                    </div>
                </div>
            </div>
            <span class="line-black"></span>
            <div class="touxiang">
                <a href="javascript:;" class="ui-a-modal" data-role="login" title="登录">登录</a>/<a
                    href="http://sso.cztv.com/user/login" rel="nofollow"
                    title="注册" target="_blank">注册</a>
            </div>
            <span class="line-black"></span>
            <div class="mobile-bg fl"><a href="http://tv.cztv.com/zt2015/pcdownload/index.shtml" class="ico-mobile"
                                         title="移动端"
                                         target="_blank">移动端</a></div>
        </dd>
    </dl>
    <div style="opacity: 0;-webkit-opacity:0;width: 1px;height: 1px;">
        <div id="storageSwf"></div>
    </div>
</div>
<div class="ui-modal" id="ui-modal-login">
    <div class="ui-modal-warp">
        <a href="javascript:;" class="ui-modal-close ui-modal-closed">X</a>
        <div class="ui-modal-content">
            <iframe id="loginIframe"
                    width="860" height="404" frameborder="no" border="0" marginwidth="0" marginheight="0"
                    scrolling="no"></iframe>
        </div>
    </div>
</div>        