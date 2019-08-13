document.write('<script type="text/javascript" src="http://player.cztv.com/swfobject.js"></script>');
$.fn.VideoPlayerBox = function(options) {
    var default_poster = "http://i02.cztv.com/2013/01/1358815575_75496800.jpg";
    
    var defaults = {
        "videofiles":{},
        "pcplay":"path",
        "mplay":"path2",
        "videoinfo":{},
        "ad":"0",
        "adfiles":[],
        "auto":"0",
        "poster":default_poster,
        "width":"100%",
        "height":"100%",
        "live":{}
    };

    var agentChecker = function(){
        var d=navigator.userAgent.toLowerCase();
        var c={};
        c.iphone=/iphone os/.test(d)||/ipod\;/.test(d);
        c.ipad = /ipad/.test(d);
        c.android=/android/.test(d);
        c.android2=/android 2./.test(d);
        return c;
    };
    
    var flashChecker = function(){
        var hasFlash = 0;            //是否安装了flash
        var isIE = 0;                    //是否IE浏览器
        if (navigator.userAgent.indexOf("MSIE") > 0) {
            isIE = 1;
        } else {
            isIE = 0;
        }
        if (isIE) {
            var swf = new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
            if (swf) {
                hasFlash = 1;
            }
        } else {
            if (navigator.plugins && navigator.plugins.length > 0) {
                var swf = navigator.plugins["Shockwave Flash"];
                if (swf) {
                    hasFlash = 1;
                }
            }
        }
        return hasFlash;
    };
    
    var isHtml5 = function(){
        return typeof(document.createElement('video').canPlayType)!=='undefined'?true:false;
    };
    
    var flashPlay = function(elem,options,ispc){
        var videoinfo = options.videoinfo;
        videoinfo.path  = opts.videofiles.path;
        if(opts.videofiles.path2!==undefined){
            videoinfo.path2 = opts.videofiles.path2;
        }
        if(opts.videofiles.path3!==undefined){
            videoinfo.path3  =opts.videofiles.path3;
        }
        videoinfo.ad = options.ad;
        videoinfo.auto = options.auto;
        if(ispc){
            if(opts.videofiles[opts.pcplay]!==undefined){
                videoinfo.rate = opts.pcplay;
            }else{
                 videoinfo.rate = "path";
            }
        }else{
            if(opts.videofiles[opts.mplay]!==undefined){
                videoinfo.rate = opts.mplay;
            }else if(opts.videofiles.path2!==undefined){
                videoinfo.rate = "path2";
            }else{
                videoinfo.rate = "path";
            }
        }        
        
        elem.html('<div id="flashContent"></div>');
        var swfVersionStr = "11.1.0";
        var xiSwfUrlStr = "http://player.cztv.com/playerProductInstall.swf";
        var flashvars = videoinfo;
        var params = {};
        params.quality = "high";
        params.bgcolor = "#000000";
        params.allowscriptaccess = "always";
        params.allowfullscreen = "true";
        params.wmode = "transparent";
        var attributes = {};                	
        attributes.id = "VzPlayer6";                	
        attributes.name = "VzPlayer6";                	
        attributes.align = "middle";                	
        swfobject.embedSWF("http://player.cztv.com/player/VzPlayer6.swf", "flashContent",options.width, options.height,swfVersionStr, xiSwfUrlStr,flashvars, params, attributes);  	
        swfobject.createCSS("#flashContent", "display:block;text-align:left;");
    };
    
    var flashLivePlay = function(elem,options){
        elem.html('<div id="flashContent"></div>');
        var swfVersionStr = "11.1.0";
        var xiSwfUrlStr = "http://player.cztv.com/playerProductInstall.swf";
        var flashvars = {auto:options.auto};
        if(options.live.id!==undefined&&options.live.id!==''){
            flashvars.id = options.live.id;
        }
        if(options.live.path!==undefined&&options.live.path!==''){
            flashvars.path = options.live.path;
        }
        if(options.live.cdn!==undefined&&options.live.cdn!==''){
            flashvars.cdn = options.live.cdn;
        }
        if(/(gyt|lyt|rjt|taglt|hmg|lft)/.test(opts.live.path)){
            flashvars.multirate = 2;
            flashvars.rate = 2;
        }
        if(options.live.rate!==undefined&&parseInt(options.live.rate)!==0){
            flashvars.rate = options.live.rate;
        }
        if(options.live.multirate!==undefined&&parseInt(options.live.multirate)!==0){
            flashvars.multirate = options.live.multirate;
        }
        if(opts.live.m3u8&&(!(/http:\/\/([a-zA-Z0-9\.]+cztv.com[:0-9]*)\//.test(opts.live.m3u8))||!(/\/channels\/lantian\//).test(opts.live.m3u8))){
            flashvars.path = '<root><m3u8>'+encodeURIComponent(opts.live.m3u8)+'</m3u8></root>';
        }
        if(options.scale!==undefined&&options.scale!==''){
            flashvars.scale = options.scale;
        }
        if(options.ad!==undefined&&options.ad!==''){
            flashvars.ad = options.ad;
        }
        if(options.live.ratestatus!==undefined&&options.live.ratestatus!==''){
            flashvars.ratestatus = options.live.ratestatus;
        }
        if(options.title!==undefined){
            flashvars.title = options.title;
        }
        if(options.column!==undefined){
            flashvars.column = options.column;
        }
        var params = {};
        params.quality = "high";
        params.bgcolor = "#000000";
        params.allowscriptaccess = "always";
        params.allowfullscreen = "true";
        params.wmode = "transparent";
        var attributes = {};
        attributes.id = "CztvPlayerLive";
        attributes.name = "CztvPlayerLive";
        attributes.align = "middle";
        swfobject.embedSWF("http://player.cztv.com/live/CztvPlayerLive.swf", "flashContent", options.width, options.height, swfVersionStr, xiSwfUrlStr, flashvars, params, attributes);
        swfobject.createCSS("#flashContent", "display:block;text-align:left;");
    };
    
    var html5Play = function(elem,options,videopath){
        if(options.adfiles.length>0){
            options.adfiles.push({'path':videopath,'start':'','click':''});
            var adIndex = 0, adLength = options.adfiles.length;
            elem.html('<video name="html5player" id="html5player" width="'+options.width+'" height="'+options.height+'" poster="'+options.poster+'" autoplay="autoplay" controls="controls" preload="auto"><source src="'+options.adfiles[adIndex]['path']+'" type="video/mp4"/></video>');
            var media = document.getElementById("html5player");
            var sendStat = function(){
                var url = options.adfiles[adIndex]['start'];
                if(url&&url.indexOf("http://")==0&&!options.adfiles[adIndex]['isstat']){
                        options.adfiles[adIndex]['isstat'] = 1;
                        $.get(url);
                }
            }
            var clickHandler = function(){
                var url = options.adfiles[adIndex]['click'];
                if(url&&url.indexOf("http://")==0){
                    window.open(url);
                }
            }
            var playHandler = function(){
                sendStat();
                media.addEventListener('click',clickHandler);
            }
            var pauseHandler = function(){
                media.removeEventListener('click',clickHandler);
            }
            var endedHandler = function(){
                adIndex++;
                if(adIndex<adLength){
                    media.src=options.adfiles[adIndex]['path'];
                    if(adIndex===adLength){
                        media.poster = options.poster;
                    }else{
                        media.poster = "";
                    }
                    media.play();
                }
            }
            media.addEventListener('play',playHandler);
            media.addEventListener('pause',pauseHandler);
            media.addEventListener('ended',endedHandler);
        }else{
            elem.html('<video name="html5player" id="html5player" width="'+options.width+'" height="'+options.height+'" poster="'+options.poster+'" autoplay="autoplay" preload="auto" controls="controls"><source src="'+videopath+'" type="video/mp4"/></video>');
        }
    }
    
    var html5LivePlay = function(elem,options,videopath){
        elem.html('<video id="html5Liveplayer" width="'+options.width+'" height="'+options.height+'" poster="'+options.poster+'" autoplay="autoplay" preload="auto" controls="controls"><source src="'+videopath+'" type="video/mp4"/></video>');
    }
    
    var opts = $.extend(defaults, options);
    var agentObj = agentChecker();
    //判断是否为直播
    if((opts.live.id!==undefined&&!!parseInt(opts.live.id))||opts.live.path!==undefined){//为直播
        if(agentObj.iphone||agentObj.ipad||agentObj.android){   //移动设备直播判断
            if(isHtml5()){  //判断是否支持Html5
                if(!opts.live.m3u8){
                    console.log("m3u8地址出错");
                }else{
                    html5LivePlay(this,opts,opts.live.m3u8);
                }
            }else if(flashChecker()){   //判断是否支持flash
                flashLivePlay(this,opts);
            }else{
                this.html('<a href="'+opts.live.rtsp+'"><img width="'+opts.width+'" height="'+opts.height+' src="'+default_poster+'"/></a>');
            }
        }else{
            flashLivePlay(this,opts);
        }
        return;
    }
    //判断是否为点播
    if(opts.videofiles.path===undefined){
        console.log("视频地址出错");
        return;
    }
    opts.pc_path = (opts.videofiles[opts.pcplay]===undefined?opts.videofiles.path:opts.videofiles[opts.pcplay]);
    if(opts.videofiles[opts.mplay]===undefined){
        opts.m_path = opts.videofiles.path;
    }else{
        opts.m_path = opts.videofiles[opts.mplay];
    }
    if(agentObj.android2){
        this.html('<a href="'+opts.m_path+'"><img width="'+opts.width+'" height="'+opts.height+' src="'+default_poster+'"/></a>');
    }else if(agentObj.iphone||agentObj.ipad||agentObj.android){   //判断移动设备
        if(isHtml5()){  //判断是否支持Html5
            if(agentObj.ipad){  //ipad用高码率视频
                var videopath = opts.pc_path;
            }else{
                var videopath = opts.m_path;
            }
            html5Play(this,opts,videopath);
        }else if(flashChecker()){   //判断是否支持flash
            flashPlay(this,opts,false);
        }else{
            this.html('<a href="'+opts.m_path+'"><img width="'+opts.width+'" height="'+opts.height+' src="'+default_poster+'"/></a>');
        }
    }else{
        flashPlay(this,opts,true);
    }
};