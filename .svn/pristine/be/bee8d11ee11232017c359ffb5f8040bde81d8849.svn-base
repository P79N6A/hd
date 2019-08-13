//播放器对象 H5Player
/**********************
    wrapBox:播放器初始的外部元素
    conf:各配置项
****************************/
function H5Player(conf,idName) { 
    this.playerSkin = conf.playerSkin || 0;  //配置播放器皮肤 
    this.sizeRatio = conf.sizeRatio || "4:3";  //默认屏幕尺寸配置
    this.conf = conf.conf;  //播放器配置数据
    this.createVedio(idName);    //创建dom结构
    if(this.conf.status == 0){ //禁播 
        document.getElementById('videoPlayer').innerHTML = '<div class="player-tip">' + this.conf.flag + '</div>';  //填充禁播提示层
        return;  
    } 
    this.confArr = conf.conf;    
    this.lineArr = this.conf.lineArr; //当前线路的数据    
    this.videoPlayer = document.getElementById("h5Player");
    this.playerInfo = {};
    this.rule = conf.rule;
    this.rate = conf.rate || ""; //默认配置清晰度
    this.line = conf.line || ""; //默认配置线路
    this.rateJson = getDefaultRate(this.conf, this.rate,this.line);  
    this.alertDown = conf.alertDown; //每五分钟提示下载
    this.ruleFn = this.rules.guardRule(this.conf); //默认防盗链规则
    this.alertStart = false;     
    this.adSrc = "";     
    this.seekAdTime = 0;
    this.endedFn = true;
    this.adPlayEnd = false;
    this.switchPlay = false; //判断是否开始切换线路，动态设置currentTime(解决不能直接设置currentTime)
    this.currentTime = 0; //存储上一个线路播放的当前时间

    this.skip = this.conf.skip;    //跳播的值   
    this.endControl = false;    //判断是否能开始监视频播放结束
    this.backstrap = false;  //判断是否加载后贴片
    this.playCount = 0; //判断后贴广告开始

    /********* 国双的检测值 **********/
    this.venderName = this.rateJson.venderName;  //默认的厂商名称
    this.bitRate = this.rateJson.rate; 
    this.src = this.rateJson.src;
    this.livePlay = null;  //国双统计对象
    this.startEv = 0; // 开始监听国双事件 
    this.isBuffer = false;
    this.isPause = false; 
    this.playCurTime = -10;

    this.duration = this.conf.duration; //修改为从接口中取总时长，国双在视频还未加载就需要总时长，容易存在差异性
    this.track = null;
    this.metaData = null;
    /********* 国双的检测值 **********/

    //修改皮肤
    this.playerSkin == 1 && addClass(document.getElementById('playerWrap'),'blueTv');
};
H5Player.prototype.constructor = H5Player; 

//播放器播放
H5Player.prototype.play = function(adId) {
    var _self = this;
    var videoPlayer = _self.videoPlayer;
    var conf = _self.conf;   
    var rule = _self.ruleFn; //防盗链  

    // 创建jia.js
    var srcipt = document.createElement('script'); 
    srcipt.setAttribute("src","http://v3.jiathis.com/code/jia.js"); 
    document.body.appendChild(srcipt);  

    /*** 初始化vedio元素 ****/ 
    _self.playerInfo = { //播放器的信息集合 
        "src": _self.rateJson.src, //返回当前视频url
        "type": _self.urlForRule(_self.rateJson.src).type, //返回当前视频type
        "rate": _self.rateJson.rate,
        "channel_name":_self.rateJson.channel_name,
        "tolTime": ""
    };  

    if (!!_self.rule) { //外部有配置防盗链规则,使用新的rule  
        _self.ruleFn = _self.rules[_self.rule]();
    }    


    _self.creatSwicthLine(conf.lineArr, rule); //创建切换线路组件 
    _self.creatSwicthRate(conf.lineArr[0], rule, _self.playerInfo.rate); //创建切换码率组件
    _self.switchLine(rule); //切换码率 
    _self.player(_self.playerInfo, adId); //广告接入 
    _self.playerEv(rule); //执行监听事件
    _self.alertDownFn(); //提示下载app  
    _self.shareWeibo(); //重改新浪微博分享
};

//生成播放器DOM结构
H5Player.prototype.createVedio = function(idName){   
    var playerBoxWrap = document.getElementById(idName);
    var styleNone = 'display:none';
    this.conf.channel_image && (styleNone = '')
    //播放器html结构 
    var htmlStr = '<div id="videoPlayer">'+   
                    '<video webkit-playsinline preload="auto" autoplay="true" controls="controls" id="h5Player"></video>'+  
                    '<div class="playError"><div class="playErrorTxt">外星人把直播信号带走了，马上回来！</div></div>'+  //信号中断提示
                    '<div class="posterError" id="posterError"><div class="video-img"><img  style="'+styleNone+'" width="100%" height="100%" src="'+ this.conf.channel_image +'"></div>'+
                    '<div class="m-alertBox"><div class="t"><p class="t1">⊙﹏⊙∥ </p><p class="t2">线路出现点小状况，听说要换个试试！</p></div></div></div>'+
                    '<div id="loading"  style="display:none"><span></span></div>'+
                    '<div id="playerbtn" style="display:none"><span></span></div>'+
                    '<div id="poster"  style="display:none"><img style="'+styleNone+'" src="'+ this.conf.channel_image +'"></div>'+   //图层
                    '<div id="front"  style="display:none"><img src=""></div>'+
                    '<div class="screenImg"><img src="" /></div>'+
          '<div class="moreBox"><div id="timer"  style="display:none"><div id="getmore"  style="display:none">了解更多</div><span id="lefttime">0</span></div></div>'+ 
                '</div>'+   
                '<div id="switchRate">'+
                    '<span class="btn-todown chkBg" id="btn-todown"></span>'+
                '</div>'+
                '<div class="use-app" id="player-use-app">'+
                    '<i class="traggle"></i>'+
                    '<a class="close" id="player-app-close" href="#">X</a>'+
                    '<p class="lic">(╭￣3￣)╭</p>'+
                    '<p class="say">产品经理说 :“看超清要使用APP~”</p>'+
                '</div>'+ 
                '<div class="download-app share" id="download-app">'+
                    '<i class="traggle"></i>'+ 
                    '<div class="jiaWrap">'+
                        '<div class="jiaBox">'+
                            '<div class="jiaThis jiathis_style_32x32">'+
                                '<span class="pengyou"><a class="jiaIcon share-weixing" title="分享到朋友圈"></a></span>'+
                                '<span class="weixing"><a class="jiaIcon share-weixing" title="分享到微信"></a></span>'+
                                '<span class="xinlang"><a class="jiaIcon share-weibo" title="分享到新浪微博"></a></span>'+
                                '<span class="qqHaoyou"><a class="jiathis_button_cqq jiaIcon" title="分享到QQ好友"></a></span>'+
                                // '<span class="email"><a class="jiathis_button_email jiaIcon" title="分享到邮箱"></a></span>'+
                            '</div>'+ 
                        '<div>'+
                    '</div>'+
                '</div>'+
                //预加载分享按钮的图片
                '<div class="preload" style="display:none"><img src="http://ohudong.cztv.com/1/240737/images/APP5.gif"/><img src="http://ohudong.cztv.com/1/240737/images/APP4.gif"/><img src="http://ohudong.cztv.com/1/240737/images/APP4.gif"/><img src="http://ohudong.cztv.com/1/240737/images/APP3.gif"/><img src="http://ohudong.cztv.com/1/240737/images/APP2.gif"/><img src="http://ohudong.cztv.com/1/240737/images/APP1.gif"/><img src="http://ohudong.cztv.com/1/240737/images/play.png"/><img src="http://ohudong.cztv.com/1/240737/images/sc-icon1.png"/><img src="http://ohudong.cztv.com/1/240737/images/sc-icon2.png"/></div>'+  
                '<div class="share-tv player-share-tv"><span class="share-text"></span><span class="share-lan"></span></div>';  //点击微信弹出的分享页
    playerBoxWrap.innerHTML = htmlStr;  //生成播放器dom结构 
    //设置播放器外观尺寸比例
    var sizeRatioArr = this.sizeRatio.split(':'); 
    var boxWid = playerBoxWrap.offsetWidth;
    var boxHig = boxWid * parseFloat(sizeRatioArr[1].Trim())/parseFloat(sizeRatioArr[0].Trim()); 
    document.getElementById("videoPlayer").style.height = boxHig + 'px';
    document.getElementById("playerWrap").style.height = 'auto';   

    
};
//重改新浪微博分享
H5Player.prototype.shareWeibo = function(){ 
    var weibo = document.getElementById('playerWrap').getElementsByClassName('share-weibo')[0]; 
    var _self = this;
    //触发分享动作
    addEvent(weibo, 'click', function(){
        share();
    }); 
    //微博分享
    function share(){ 
        (function(s, d, e) { 
            try {} catch (e) {}
            var f = 'http://v.t.sina.com.cn/share/share.php?',
                u = location.href,
                p = ['url=', e(u), '&title=', e(document.title), '&appkey=2992571369', '&pic=', e(_self.conf.channel_image)].join('');

            function a() { 
                if(!window.open([f, p].join(''))) location.href = [f, p].join(''); 
            };
            if (/Firefox/.test(navigator.userAgent)) {
                setTimeout(a, 0)
            } else {
                a()
            }
        })(screen, document, encodeURIComponent);
    }
};

//国双统计
H5Player.prototype.gsCount = function(playerInfo) {
    var _self = this;
    var conf = _self.conf;
    //默认国双统计参数
    var gsCountOpt = { 
        "framesPerSecond": "25", //每秒的帧数
        "isBitRateChangeable": true, //码率是否可变,默认true
        "videoID":getUrlSearch("cid") || "101", //视频id
        "videoOriginalName": _self.conf.title, //视频VMS原始名称
        "videoName": _self.conf.title, //视频名称
        "videoTVChannel": _self.conf.title, //直播频道名称
        "videoFocus": "",
        "videoParent": "",
        "VideoUrl":_self.src,  //当前播放视频的链接
        "cdn": _self.venderName, //cdn名称 
        "extendProperty1": "v1.0", //播放器版本号
        "framesPerSecond": "25", //帧率
        "bitRate": _self.bitRate //比特率
    };  
    var canPlay = 0; //请求数据完成阈值
    //获取url传入的参数  key:参数名
    function getUrlSearch(key){ 
        var searchArr = location.search.substr(1).split("&"),
            searchArrLen = searchArr.length;
        for (var i = 0; i < searchArrLen; i++) {
            var wordArr = searchArr[i].split("=");
            if (wordArr[0] == key) {
                return wordArr[1];
            }
        }
        return false; 
    }
    /***** 国双统计 start *****/
    var gsCount = {
        playerEle: document.getElementById('h5Player'),
        getMetaData: function() {   
            var metaData = {
                videoDuration: _self.duration, //点播视频的总时长
                getFramesPerSecond: function() {
                    return gsCountOpt.framesPerSecond; //每秒的帧数
                },
                getBitRateKbps: function() {
                    return gsCountOpt.bitRate; //码率
                },
                getIsBitRateChangeable: function() {
                    return gsCountOpt.isBitRateChangeable; //码率是否可变
                }
            };
            return metaData;
        },
        getVideoInfo: function() {
            var _parent = this;
            var videoInfo = {
                VideoID: gsCountOpt.videoID, //视频id
                VideoOriginalName: gsCountOpt.videoOriginalName,
                VideoName: gsCountOpt.videoName, //视频名称
                VideoUrl: gsCountOpt.VideoUrl, //不用传加密参数。
                VideoTag: "",
                VideoTVChannel: '-',
                VideoWebChannel: $('meta[name="irCategory"]').attr('content') + '~' + $('meta[name="irAlbumName"]').attr('content'), //频道名称/专辑名称
                VideoFocus: gsCountOpt.videoFocus,
                VideoParent: gsCountOpt.videoParent, //_parent.playerEle.parentNode
                Cdn: gsCountOpt.cdn,
                extendProperty1: gsCountOpt.extendProperty1 //播放器版本号

            }; 
            return videoInfo;
        },
        getInfoProvider: function() {
            var infoProvider = {
                getFramesPerSecond: function() {
                    return gsCountOpt.framesPerSecond; //帧率
                },
                getPosition: function(){
                    return _self.videoPlayer.currentTime.toFixed(2); //视频当前播放位置，单位是秒，需要精确到小数点后两位
                }, 
                getBitRate: function() {
                        return gsCountOpt.bitRate;
                    } //比特率
            }; 
            return infoProvider;
        },
        playerEvent: function(livePlay) { //播放器事件触发  
            if(_self.startEv==0){
                    _self.startEv++;    
                    addEvent(_self.videoPlayer, 'pause', function() { //暂停播放   
                        livePlay.onStateChanged("paused");
                    });
                    addEvent(_self.videoPlayer, 'ended', function() { //播放结束 
                        livePlay.onStateChanged("endPlay");
                    });
                    addEvent(_self.videoPlayer, 'playing', function() { //因暂停卡顿重新播放触发 
                        if(canPlay>0){
                            livePlay.onStateChanged("playing");  
                            _self.isPause = false;  
                        } 
                    });  
                    addEvent(_self.videoPlayer, 'seeking', function() { //点播拖拽时   
                        livePlay.onStateChanged("seeking");
                    }); 

                    addEvent(_self.videoPlayer, 'pause', function() { //暂停播放，设置检测卡顿开关   
                        _self.isPause = true;
                    });  
                    setInterval(function(){   //检测卡顿
                        if(_self.playCurTime == document.getElementById("h5Player").currentTime){ //卡顿  
                            if(!_self.isBuffer && !_self.isPause && _self.playCurTime!=0){  
                                livePlay.onStateChanged("buffering");  
                                _self.isBuffer = true;    
                            }
                        }else{ //不卡顿   
                            _self.playCurTime = document.getElementById("h5Player").currentTime;
                            if(_self.isBuffer){  
                                livePlay.onStateChanged("playing");    
                                _self.isBuffer = false;
                                _self.isPause = false; 
                            } 
                        } 
                    },1000); 
                }
        },
        listenPlay: function() { //监听播放
            var _parent = this;
            var end = 0;
            var y = 0;
            var playerEle = _parent.playerEle; //播放器对象
            var videoInfo = _parent.getVideoInfo();
            var infoProvider = _parent.getInfoProvider();
             _self.track = new GridsumVideoDissector("GVD-200111", "GSD-200111");
             _self.livePlay = _self.track.newVodPlay(videoInfo, infoProvider); //如果是点播播放，通过_self.track.newVodPlay方法进行初始化vodPlay对象

            var startPlay = 0; //开始播阈值 
            var startLoad = 0; //开始请求数据阈值
            //开始执行
            _self.track.setPlatform("Html5");  
            //开始请求数据
            addEvent(_self.videoPlayer,'loadstart', function(){
                if (startLoad == 0) {   
                    _self.livePlay.beginPreparing(); //开始请求数据 调用国双接口
                    startLoad++;
                }
            });
            _self.metaData = _parent.getMetaData();
            //请求数据完成
            addEvent(_self.videoPlayer,'canplaythrough', function(){ 
                if (canPlay == 0) {   
                    var metaData = _parent.getMetaData();
                    _self.metaData = metaData;   
                    _self.livePlay.endPreparing(true, metaData); //数据加载完成 调用国双接口
                    _self.livePlay.onStateChanged("playing");  //开始播放 调用国双接口
                    _parent.playerEvent(_self.livePlay); //开始监听国双事件
                    canPlay++;
                }
            }); 

            played = true;   
        },
        init: function() {
            this.listenPlay();
        }
    };
    gsCount.init();
    /***** 国双统计 end *****/
};

//广告嵌入
H5Player.prototype.player = function(playerInfo, adId) {
    var _self = this;
    /**** 提供给广告调用的对象 start ****/

    _self.videoPlayer.removeAttribute("controls"); 
    var player = {
        playerEle: document.getElementById('h5Player'),
        byId: function(id) {
            return document.getElementById(id);
        },
        disableControls: function() {
            //禁用默认播放组件   
            _self.videoPlayer.removeAttribute("controls"); 
            // this.byId('switchRate').style.display = "none"; 
        },
        showPoster: function() {
            //展示poster
            this.byId("poster").style.display = "block";
        },
        showFront: function() {
            //展示广告img 前贴广告
            this.byId("front").style.display = "block";
        },
        hideFront: function() {
            //隐藏广告img 前贴广告
            this.byId("front").style.display = "none";
        },
        imgFront: function(src) { //设置前贴广告图片的图片地址
            this.byId("front").querySelector("img").setAttribute("src", src);
        },
        hidePoster: function() {
            //隐藏poster
            this.byId("poster").style.display = "none";
        },
        showPlayBtn: function() {
            //展示播放按钮
            this.byId("playerbtn").style.display = "block";
        },
        hidePlayBtn: function() {
            //隐藏播放按钮 
            this.byId("playerbtn").style.display = "none";
        },
        showLoading: function() {
            //展示loading
            this.byId("loading").style.display = "block";
        },
        hideLoading: function() {
            //隐藏loading
            this.byId("loading").style.display = "none";
        },
        showClickBtn: function() { 
            //展示广告点击按钮
            this.byId("getmore").style.display = "block";
        },
        hideClickBtn: function() { 
            //隐藏广告点击按钮
            this.byId("getmore").style.display = "none";
        },
        showTimer: function() {
            //展示倒计时
            this.byId("timer").style.display = "block";
        },
        hideTimer: function() {
            //隐藏倒计时
            this.byId("timer").style.display = "none";
        },
        setTimer: function(sec) {
            //设置倒计时值
            this.byId("lefttime").innerHTML = sec;
        },
        getPlayerEl: function() {
            //获取video元素
            return this.playerEle;
        },
        getPlayBtn: function() {
            //获取播放按钮元素
            return this.byId("playerbtn");
        },
        getClickBtn: function() {
            //获取点击按钮元素
            return this.byId("getmore");
        },
        setVideoView: function(view) {
            /*
                设置视频可视
                在iPhone中，video元素在广告获取前没有设置src，播放按钮无法捕获到touchstart事件，需要先将视频移出播放按钮的可视区域
                video int
                    0 移出可视区域
                    1 移到可视区域
            */
            if (view) { 
                this.playerEle.style.marginLeft = "0";
                this.playerEle.style.marginTop = "0";
            } else {
                this.playerEle.style.marginLeft = "-1000px";
                this.playerEle.style.marginTop = "-1000px";
            }
        },
        setCtrID: function(id) {
            /*
                设置控制器id
                id int
                    0 媒体视频控制
                    1-6 广告控制，见广告类型
            */
            this.ctrId = id;
        },
        setAdCmp: function(cmp) {
            /*
                设置广告组件
                cmp提供了事件监听的on/un接口 具体见广告对象说明
            */
            var t = this;
            this.adCmp = cmp;
            //开始请求广告
            cmp.on("beforeload", function(ename, error, ad) { 
                var beforeCurrentTime = 0; 
                if(_self.playCount > 0){  //后贴广告开始，隐藏控制栏  
                     document.querySelector('.player-share-tv').style.display = 'none';  //消除返回按分享按钮产生的bug
                    if(navigator.userAgent.indexOf("UCBrowser") != -1){   //将video标签移入界面，uc下防止连续触发微信分享导致video未移出视界
                        _self.videoPlayer.style.position = 'relative'; //uc下将video移出视界
                        _self.videoPlayer.style.top = '0px';//uc下将video移出视界
                    }
                    document.getElementById('switchRate').style.display = "none";  //广告加载，隐藏视频控制栏
                    document.getElementById("player-use-app").style.display = 'none';
                    document.getElementById("download-app").style.display = 'none';
                    document.getElementById("lineBox").style.display = 'none'; 
                    var showBtn = document.getElementById('playerWrap').getElementsByClassName('show')[0];  //选中的按钮
                    showBtn && removeClass(document.getElementById('playerWrap').getElementsByClassName('show')[0],'show');
                }
            });
            //广告请求加载完成
            cmp.on("load", function(ename, error, ad) {  
            });
            //广告视频开始播放
            cmp.on("play", function(ename, error, ad) { 
                _self.adSrc = _self.videoPlayer.currentSrc;
                t.showClickBtn(); 
                t.showTimer(); 
                t.adplayed = true; 
                _self.switchPlay = false;
                _self.playCount > 0 && (_self.backstrap = true);   //后贴广告开始 
            });
            //广告视频播放结束
            cmp.on("ended", function(ename, error, ad) { 
            });

            cmp.on("adend", function(ename, err, ad) {
                t.hideClickBtn(); 
                //发生错误
                if (err != null) { 
                } 
                if (ad.type == 1) {
                    //前贴播放结束 开始播放视频
                    if (t.adplayed) {
                        t.play(); 
                    } else {
                        t.showPlayBtn();
                        t.showPoster();
                    }
                }
                if (ad.type == 2) { //中贴广告
                    if (t.adplayed) {
                        t.midPlay(); 
                    } else {
                        t.showPlayBtn();
                        t.showPoster();
                    }
                }
                if (ad.type == 3) { 
                    //后贴播放结束
                    _self.videoPlayer.style.position = 'absolute';
                    _self.videoPlayer.style.top = '-100000px';
                    document.getElementById('poster').style.display = "block";  //广告结束，显示贴图 
                    document.getElementById('playerbtn').style.display = "block";  //广告结束，显示播放按钮 
                    document.getElementById('playerbtn').style.top = "50%";  //广告结束，将按钮移回
                    var videoSource = _self.urlForRule(playerInfo.src); 
                    _self.videoPlayer.setAttribute("src", videoSource.src); 
                    _self.videoPlayer.setAttribute("type", videoSource.type); 
                    _self.videoPlayer.play(); 
                    _self.videoPlayer.pause(); 
                    addEvent(document.getElementById('playerbtn'),'touchstart',function(){  //后贴播放结束，监听播放按钮播放视频
                        if(_self.playCount > 0){
                            var videoSource = _self.urlForRule(playerInfo.src); 
                            _self.videoPlayer.setAttribute("src", videoSource.src); 
                            _self.videoPlayer.setAttribute("controls", "controls");  
                            _self.videoPlayer.setAttribute("type", videoSource.type);  
                            _self.videoPlayer.play();
                            _self.videoPlayer.style.top = '0px';
                            _self.videoPlayer.style.position = 'relative'; 
                            document.getElementById('switchRate').style.display = "block";  //广告结束，显示视频控制栏 
                            document.getElementById('poster').style.display = "none";  //广告结束，隐藏贴图 
                            document.getElementById('playerbtn').style.display = "none";  //广告结束，隐藏播放按钮 
                        }
                    });
                    
                }
                if (err && err.no == 3 && ad.type == 1) {
                    t.setVideoView(0);
                }
            });
        },
        midPlay: function() {
            var v = this.playerEle;
            v.setAttribute("data-ctr", "player");
            v.setAttribute("src", playerInfo.src); 
            _self.videoPlayer.play();
            v.currentTime = this.midTime;
            this.byId('switchRate').style.display = "block";
        },
        getParams: function() {
            /*
                获取广告相关参数
            */
            return {
                vd: _self.duration, //视频时长
                pst: '' //前贴时长
            }
        },
        init: function() { 
            var t = this;
            t.ctrId = 0;
            t.adplayed = false;
            //播放按钮
            t.byId("playerbtn").addEventListener("touchstart", function() {
                t.setVideoView(1);
                if (t.ctrId == 0) {
                    t.hidePoster();
                    t.hidePlayBtn();
                    t.play();
                }
            });
        },
        play: function() { 
            var _parent = this;
            var v = _parent.playerEle;
            var t2 = new Date().getTime();  
            var isIphone = testIphone();
            //解决iphone下广告快进到底直接播放的问题
            if(!_self.adPlayEnd && isIphone && (t2 - _self.seekAdTime < 1000)){    
                v.currentTime = v._currentTime;
                v.play();
                _parent.showClickBtn(); 
                _parent.showTimer(); 
                addEvent(_self.videoPlayer,'ended',function(){ 
                    if(_self.endedFn){
                        _self.seekAdTime = 0;
                        _self.player().play();  //禁用广告快进，广告回到快进前位置播放
                        _self.endedFn = false;
                    }
                    _self.videoPlayer.setAttribute('controls','controls');
                });
                return ;
            }    
            addClass(document.getElementById('playerWrap'), 'startPlay');
            //开始播放视频
            _self.playCount ++; //视频开始播放
            _self.adPlayEnd = true;  //前贴广告播放结束 
            var src = _self.urlForRule(playerInfo.src); 
            document.getElementById('poster').style.display = 'none';
            document.getElementById('playerbtn').style.display = 'none'; 
            document.getElementById('loading').style.display = 'none';  
            _parent.hideClickBtn(); 
            _parent.hideTimer(); 
            v.setAttribute("data-ctr", "player");
            v.setAttribute("src", src.src);  
            v.setAttribute("type", src.type);  
            v.setAttribute("controls","controls");
            this.byId('switchRate').style.display = "block"; 
            if(_self.alertDown){
                _self.alertStart = true;
            }  
            var skipStart = _self.skip[0]; 
            if (_self.skip[0] > 0) { //存在视频快进播放
                _self.videoPlayer.currentTime = skipStart;
            }
            _self.videoPlayer.play();    
            _self.gsCount(_self.playerInfo); // 广告播放结束，开始国双统计


            addEvent(_self.videoPlayer,'ended', function() {  
                _parent.setCtrID(3);  
            });     


            var skipEnd = 0; 
            var durationOnff = false;
            var adEndTimer = setInterval(function(){
                var duration = _self.videoPlayer.duration;
                if(duration){
                    durationOnff = duration;
                    _self.endControl = true;  //前贴广告播放结束  
                    skipEnd = _self.videoPlayer.duration - (_self.skip[1] || 0);   
                    clearInterval(adEndTimer);
                }
                
            },600);

            var timer = setInterval(function(){ 
                if(durationOnff && _self.videoPlayer.currentTime > skipEnd){  //视频播放时间在跳跃播放时间段，结束视频播放
                    _self.videoPlayer.currentTime = _self.videoPlayer.duration; 
                    clearInterval(timer);
                }
            },1000);

                //当前线路流地址错误，自动切换下一路流播放
            addEvent(_self.videoPlayer,'error', function() { 
                var playErrorCode = _self.videoPlayer.error.code; //播放错误码 
                if(playErrorCode == 3 || playErrorCode == 4){  //解码错误，URL无效 
                   document.getElementById('posterError').style.display = "block";
                } 
            }); 

        },
    };
    player.init();
    acp(adId); 


    /*
     * [acp description] 传参给h5ap
     * @param  {[type]} pid 播放器id 默认5808
     * @return {[type]} none
     */
    function acp(id) {
        var pid = id || 5808;
        _ACP({
            pid: pid,
            player: player, //播放器对象
            timeout: 5000,
            sendReferer: true,
            sendTurl: true,
            sendKeyWords: true,
            serverbaseurl: "afpssp.alimama.com/",
            show: false
        });
    }

    return player;
};

//点播结束或点播后贴广告结束 fn 监听到结束时供外部执行的函数
H5Player.prototype.end = function(fn){ 
    var _self = this;
    var id = _self.conf.nextvid; 
    addEvent(_self.videoPlayer,'ended',function(){
        setTimeout(function(){  
            if(_self.endControl && !_self.backstrap){  //视频播放结束且无后贴广告
                fn(id);
            }else if(_self.endControl && _self.backstrap){ //视频播放结束且有后贴广告 
                setTimeout(function(){ 
                    addEvent(_self.videoPlayer,'ended',function(){
                        fn(id); 
                    });
                },1000);
            } 
        },500); 
    });
};

//提示下载app功能
H5Player.prototype.alertDownFn = function(){
   var _self = this;
   setInterval(function(){
      if(_self.alertStart){ 
        if(confirm("是否下载中国蓝TV手机客户端，观看高清视频？")){
            window.location.href="http://hd.cztv.com/appstore/zgltv-cztv";
        } 
      }
   },300*1000);
};

//添加切换码率的按钮（去除切换码率功能）
H5Player.prototype.creatSwicthRate = function(arr, rule ,currRate) {
    var rateArr = arr.codeRate; //获取码率项
    var listArr = [];
    var _self = this;
    for (var i = 0; i < rateArr.length; i++) {
        listArr.push(rateArr[i].rate);
    }
    var bar = document.getElementById('switchRate'); 
    var currentRate = document.createElement('a');
    currentRate.className = "currentRate";
    currentRate.innerText = "超清观看";  
    currentRate.href = "http://hd.cztv.com/appstore/zgltv-cztv";
    bar.appendChild(currentRate);  


    var currentRate = EleByClass('currentRate')[0];    
    //当前的码率按钮点击显示码率列表 
    addEvent(document.getElementById('player-app-close'), 'touchstart', function() { 
        var useApp = document.getElementById("player-use-app");     
           removeClass(currentRate,'show');
           useApp.style.display = 'none'; 
    }); 
    /**
     * [getRateSrc description] 获取切换到的码率的src
     * @param  {[type]} curr 当前切换的码率
     * @param  {[type]} arr  包含码率的数组
     * @return {[type]}      当前码率的src
     */
    function getRateSrc(curr, arr) {
        var src = "";
        for (var i = 0; i < arr.length; i++) {
            if (curr == arr[i].rate) {
                src = arr[i].src;
            }
        }
        return src;
    }
};


//切换线路
H5Player.prototype.switchLine = function(rule) {
    var _self = this;
    var currentLine = EleByClass('currentLine')[0];
    var lineList = EleByClass('lineList')[0];
    var lineSpan = lineList.getElementsByTagName('span');
    if(lineSpan.length==0){
        return;
    }
    var srcRule = rule; //默认防盗链规则
    //当前的码率按钮点击显示码率列表
    addEvent(currentLine, 'touchstart', function() {
        document.getElementById("player-use-app").style.display = 'none';
        document.getElementById("download-app").style.display = 'none';
        var lineBox = document.getElementById('playerWrap').getElementsByClassName('lineBox')[0]; 
        var isShow = lineBox.style.display;
        if(isShow == 'block'){ 
           removeClass(this,'show');     
           removeClass(document.getElementById("switchRate"),'mba');  
           lineBox.style.display = 'none';
           document.getElementById("switchRate").style.paddingBottom = '0px';
        }else{
           addClass(this,'show');    
           addClass(document.getElementById("switchRate"),'mba'); 
           removeClass(EleByClass('currentRate')[0],'show');
           lineBox.style.display = 'block'; 
           removeClass(document.getElementById("btn-todown"),'show');
        }


    //当线路小于5条时，线路居中显示
    if(!$('.lineItemBox').hasClass('has')){ 
      var lineBoxWid = Math.floor(parseInt($('.lineItemBox').width()) / 2); //线路宽度值一半
      var btnLeft = parseInt($('#switchRate .currentLine').css('left')) + Math.floor(parseInt($('#switchRate .currentLine').outerWidth()) / 2); //按钮中部距左边距离 
      var leftMove = btnLeft - lineBoxWid;
      leftMove < 0 && (leftMove = '0px');
      $('.lineItemBox').addClass('has').css('margin-left',leftMove)
    }
    });
    //点击切换线路
    for (var i = 0; i < lineSpan.length; i++) {
        addEvent(lineSpan[i], 'touchstart', function() { 
            if (hasClass(this, 'active')) return;
            _self.switchPlay = true;
            document.getElementById('posterError').style.display = "none";
            for (var j = 0; j < lineSpan.length; j++) {
                removeClass(lineSpan[j], 'active');
            }
            addClass(this, 'active'); 
            currentLine.innerText = this.innerText;
            //切换播放码率 
            _self.currentTime = _self.videoPlayer.currentTime;
            var current = getLineSrc(this.innerText, _self.confArr);
            var currentSrc = current.src;
            var currentRate = current.rate;
            var vedioSrc = _self.urlForRule(currentSrc);
            _self.playerInfo.src = currentSrc;
            _self.playerInfo.rate = currentRate; 
            _self.videoPlayer.setAttribute("src", vedioSrc.src);
            _self.videoPlayer.setAttribute("type", vedioSrc.type);
            _self.videoPlayer.play();  
            /** 解决不能直接设置currentTime */
            addEvent(_self.videoPlayer,'canplay',function(){ 
                if(_self.switchPlay){
                    _self.switchPlay = false; 
                    _self.videoPlayer.currentTime = _self.currentTime; 
                }
            });

            _self.venderName = current.venderName; 
            _self.bitRate = currentRate; 
            _self.src = currentSrc;
            _self.livePlay.endPlay();  //结束当前统计
            _self.gsCount(_self.playerInfo); // 切换线路，开始国双统计
            _self.livePlay.beginPreparing(); //开始请求数据 调用国双接口 
            _self.livePlay.endPreparing(true, _self.metaData); //数据加载完成 调用国双接口 
            _self.livePlay.onStateChanged("playing");  //开始播放 调用国双接口 
        });
    }

    /**
     * [getLineSrc description] 获取切换到的线路的src
     * @param  {[type]} curr 当前切换的线路
     * @param  {[type]} arr  包含线路的数组
     * @return {[type]}      当前线路的src
     */
    function getLineSrc(curr, arr) {
        var arr = arr.lineArr;
        var src = "";
        var rate = "";
        var venderName = "";
        for (var i = 0; i < arr.length; i++) {
            if (curr == arr[i].lineName) {
                var currRate = document.getElementById('switchRate').getElementsByClassName('currentRate')[0].innerText; 
                _self.lineArr = arr[i]; 
                var curLine = _self.lineArr.codeRate;
                var defaultrate = _self.lineArr.defaultrate || "";
                src = arr[i].codeRate[0].src;
                rate = arr[i].codeRate[0].rate;
                venderName =  arr[i].venderName;
                for(var k = 0; k < curLine.length; k++){
                    if(defaultrate== curLine[k].rate){
                        src = curLine[k].src;
                        rate = curLine[k].rate; 
                    }
                }
            }
        }
        return {
            "src": src,
            "rate": rate,
            "venderName":venderName
        };
    }
};

//添加切换线路DOM结构
H5Player.prototype.creatSwicthLine = function(arr) {
    var _self = this;
    var bar = document.getElementById('switchRate');
    var divEl = document.createElement("div");
    divEl.className = "lineBox";
    divEl.id = "lineBox";
    var currentLine = document.createElement('span'); 
    var str = '<div class="lineList"><div class="lineItemBox">';
    var ind = 0;
    for (var i = 0; i < arr.length; i++) { 
        if(arr[i].lineName == _self.line){
            ind = i;
        }
    }
    for (var i = 0; i < arr.length; i++) { 
        if(arr.length == 1){
                break;
        }
        if (i == ind) {
            str += '<span class="active">' + arr[i].lineName + '</span>';
        } else {
            str += '<span>' + arr[i].lineName + '</span>';
        }
    }
    str += '</div></div><i class="traggle"></i>';
    currentLine.className = "currentLine";
    currentLine.innerText = arr[ind].lineName;
    bar.appendChild(currentLine);
    divEl.innerHTML = str;
    document.getElementById('playerWrap').appendChild(divEl);
    var lineList = EleByClass('lineList',bar)[0]; 
    if(lineList.getElementsByTagName('span').length==0){
        EleByClass('lineBox',bar)[0].style.display = 'none';
    }
};

//给url附加上防盗链规则
H5Player.prototype.urlForRule = function(url) {
    var bool = url.indexOf('?'); 
    var rule = this.rules.guardRule(this.conf,url);
    var ruleStr = "";
    var type = "";
    bool > 0 ? (ruleStr = url + '&' + rule) : (ruleStr = url + '?' + rule);
    var vedioType = ruleStr.indexOf('.m3u8');
    vedioType > 0 ? (type = "application/x-mpegURL") : (type = "video/mp4");
    return {
        type: type,
        src: ruleStr
    };
};

//配置防盗链规则
H5Player.prototype.rules = {
    guardRule: function(urlOpt,url) {  
        var channel_name = "";
        var lineArr = urlOpt.lineArr;
        for (var i = 0; i < lineArr.length; i++) {
            var codeRate = lineArr[i].codeRate;
            for (var j = 0; j < codeRate.length; j++) { 
                if (url == codeRate[j].src) {
                    channel_name = lineArr[i].channel_name; 
                    break;
                }
            }
        } 
        var rule = hex_md5('cztv' + '/' + urlOpt.mount_name + '/' + channel_name + urlOpt.currentTime);
        return 'k=' + rule + '&t=' + urlOpt.currentTime;
    }
};


//播放器事件 
H5Player.prototype.playerEv = function(rule) { 
    var _self = this;   
    var isIphone = testIphone(); 

    //关闭页面结束统计  
    window.addEventListener('beforeunload', addOnBeforeUnload, false);
    function addOnBeforeUnload(){
        if(_self.livePlay){ 
            _self.livePlay.onStateChanged("endPlay");
        }
    } 

    //触发分享微信，弹出分享微信页 
    var shareWeixing = document.getElementsByClassName('share-weixing');
    for(var i = 0; i < shareWeixing.length; i++){
        addEvent(shareWeixing[i],'click',function(){
            if(navigator.userAgent.indexOf("UCBrowser") != -1){   //uc浏览器下 会出现video播放界面层级最高情况    
                    //通过canvas生成截图
                    var canvas = document.createElement("canvas");
                    canvas.width = _self.videoPlayer.videoWidth * 0.25;
                    canvas.height = _self.videoPlayer.videoHeight * 0.25;
                    canvas.getContext('2d')
                       .drawImage(_self.videoPlayer, 0, 0, canvas.width, canvas.height); 
                    var screenImg = document.querySelector('.screenImg');  //截屏元素
                    var img = screenImg.getElementsByTagName('img')[0];  //截屏内部img元素
                    img.src = canvas.toDataURL();
                    screenImg.style.display = "block";  
                    _self.videoPlayer.style.position = 'absolute'; //uc下将video移出视界
                    _self.videoPlayer.style.top = '-100000px';//uc下将video移出视界
            }
            document.querySelector('.player-share-tv').style.display = 'block';
        });
    }
     

    //点击分享微信页，隐藏
    addEvent(document.querySelector('.player-share-tv'),'click',function(){
        if(navigator.userAgent.indexOf("UCBrowser") != -1){   //将video标签移入界面
            _self.videoPlayer.style.position = 'relative'; //uc下将video移出视界
            _self.videoPlayer.style.top = '0px';//uc下将video移出视界
            document.querySelector('.screenImg').style.display = "none";
        }
        document.querySelector('.player-share-tv').style.display = 'none'; 
    });  
 

    //分享区域的显示隐藏
    addEvent(document.getElementById('btn-todown'),'touchstart',function(){
        document.getElementById("player-use-app").style.display = 'none'; 
        document.getElementById("lineBox").style.display = 'none';  
        removeClass(EleByClass('currentLine')[0],'show'); 
        removeClass(document.getElementById("switchRate"),'mba');   
        removeClass(EleByClass('currentRate')[0],'show');   
        var downloadBox = document.getElementById('download-app');
        if(hasClass(this, 'show')){
            downloadBox.style.display = 'none'; 
            removeClass(this,'show');   
        }else{
            downloadBox.style.display = 'block'; 
            addClass(this,'show');  
        }   
    }); 

    //解决部分浏览器退出全屏后不继续播放的问题
    addEvent(_self.videoPlayer,'seeking', function() { //视频/音频（audio/video）暂停或者在缓冲后准备重新开始播放时触发。  
        _self.seekAdTime = new Date().getTime();
    });
    _self.videoPlayer._currentTime = 0;

    addEvent(_self.videoPlayer,'timeupdate', function() { 
        if(!_self.adPlayEnd){
            var a = this;   
                a && a.currentTime != this._currentTime && a.currentTime>2 && Math.abs(a.currentTime - this._currentTime) > 2 && (a.currentTime = this._currentTime);
            this.currentTime >0 && (this._currentTime = this.currentTime);
        }
    });   

    var t1 = 0; 
    addEvent(_self.videoPlayer,'pause', function() { //视频/音频（audio/video）暂停或者在缓冲后准备重新开始播放时触发。    
        t1 = new Date().getTime(); 
    });     
    //解决iphone全屏不自动播放问题
    addEvent(window,'resize',function(){     
        if((new Date().getTime() - t1)<200){
            if(isIphone){  
                _self.videoPlayer.setAttribute("controls","controls");    
               // _self.H5VedioPlayer.controls(true);  
            }else{
                _self.videoPlayer.play();
            }
        }
    });  

    
 
};

//添加防盗链规则 ruleName规则名称 fun 方法名
H5Player.prototype.addRule = function(ruleName, fun) {
    if (this.rules[ruleName]) {
        console.error("已经存在该规则，请重新给规则命名");
    }
    this.rules[ruleName] = fun;
};

//extend
H5Player.prototype.extend = function(target, options) {
    for (name in options) {
        target[name] = options[name];
    }
    return target;
};



//匹配默认清晰度,线路对应的视频
function getDefaultRate(conf, rate, line) {
    var arr = conf;
    var defaultConf = null;
    var defaultLineArr = arr.lineArr[0];
    var codeRate = arr.lineArr[0].codeRate;
    if (line) {
        for (var　 k = 0; k < arr.lineArr.length; k++) {
            if (line == arr.lineArr[k].lineName) {
                defaultLineArr = arr.lineArr[k];
                codeRate = arr.lineArr[k].codeRate;
            }
        }
    }  
    var defaultRate = rate || defaultLineArr.defaultrate; 
    if (defaultRate) {
        for (var j = 0; j < codeRate.length; j++) {
            if (codeRate[j].rate == defaultRate) {
                defaultConf = codeRate[j]; 
            }
        }
    }

    if (!defaultRate || !defaultConf) { 
        defaultConf = codeRate[0];
    } 
    defaultConf.channel_name = defaultLineArr.channel_name; 
    defaultConf.venderName = defaultLineArr.venderName; 
    return defaultConf;
}

/************** js常用工具方法 ************************/ 

/**
 * [ajax description]  定义ajax请求
 * @param  {[type]} options [description]
 * @example 
 * ajax({
        url: "./TestXHR.aspx", //请求地址
        type: "POST", //请求方式
        data: {
            name: "super",
            age: 20
        }, //请求参数
        dataType: "json",
        success: function(response, xml) {
            // 此处放成功后执行的代码
        },
        fail: function(status) {
            // 此处放失败后执行的代码
        }
    });
 */
function ajax(options) {
    options = options || {};
    options.type = (options.type || "GET").toUpperCase();
    options.dataType = options.dataType || "json";
    var params = formatParams(options.data);

    //创建 - 非IE6 - 第一步
    if (window.XMLHttpRequest) {
        var xhr = new XMLHttpRequest();
    } else { //IE6及其以下版本浏览器
        var xhr = new ActiveXObject('Microsoft.XMLHTTP');
    }

    //接收 - 第三步
    xhr.onreadystatechange = function() {
        if (xhr.readyState == 4) {
            var status = xhr.status;
            if (status >= 200 && status < 300) {
                options.success && options.success(xhr.responseText, xhr.responseXML);
            } else {
                options.fail && options.fail(status);
            }
        }
    }

    //连接 和 发送 - 第二步
    if (options.type == "GET") {
        xhr.open("GET", options.url + "?" + params, true);
        xhr.send(null);
    } else if (options.type == "POST") {
        xhr.open("POST", options.url, true);
        //设置表单提交时的内容类型
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.send(params);
    }
}


//检测是否是iphone
function testIphone(){ 
    var u = navigator.userAgent,
        app = navigator.appVersion,
        isIphone = u.indexOf('iPhone') > -1 || u.indexOf('Mac') > -1;
    return isIphone;
}

function EleByClass(cls, par) {
    var parent = par || document;
    return document.getElementsByClassName(cls);
}

//格式化参数
function formatParams(data) {
    var arr = [];
    for (var name in data) {
        arr.push(encodeURIComponent(name) + "=" + encodeURIComponent(data[name]));
    }
    arr.push(("v=" + Math.random()).replace(".", ""));
    return arr.join("&");
}

function hasClass(obj, cls) {
    if (!obj.className) return false;
    return obj.className.match(new RegExp('(\\s|^)' + cls + '(\\s|$)'));
}

function addClass(obj, cls) {
    if (!this.hasClass(obj, cls)) obj.className += " " + cls;
}

function removeClass(obj, cls) {

    if (hasClass(obj, cls)) {
        var reg = new RegExp('(\\s|^)' + cls + '(\\s|$)');
        obj.className = obj.className.replace(reg, ' ');
    }
}

function addEvent(obj, type, fn) {  
    if (obj.addEventListener)
        obj.addEventListener(type, fn, false);
    else if (obj.attachEvent) {
        obj["e" + type + fn] = fn;
        obj.attachEvent("on" + type, function() {
            obj["e" + type + fn]();
        });
    }
}


String.prototype.Trim = function(){　
    return this.replace(/(^\s*)|(\s*$)/g, "");　
}　



  