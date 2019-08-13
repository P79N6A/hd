/**
 * Created by zhangxiuxiu on 2015/7/17.
 * Modified by chall on 2015/10/28.
 */
/* document.write("<script language='javascript' src='http://s.csbew.com/w.js'></script>"); */
//flag:true，页面已经加载完成，false,页面尚未加载
var flag = false;

$(function(){
    //动态添加下载App
    downLoadApp();

    Request = GetRequest(); 
    spic = Request['spic']; 
    stitle = Request['stitle']; 
    sdesc = Request['sdesc']; 
    //alert(spic)
    if(spic != undefined || stitle != undefined || sdesc != undefined){
        lastspic = utf8to16(base64decode(spic))//分享图
        stitle = utf8to16(base64decode(stitle))//标题
        sdesc = utf8to16(base64decode(sdesc))//摘要
        //alert(stitle)
        if(sdesc == ''){
            sdesc = stitle
        }
        //alert(stitle + '' + lastspic + '' +sdesc + '' +stitle)
        wx_share(stitle,lastspic,sdesc,stitle) // a标题 b 图 c摘要 d分到朋友圈        
    }

    //异步加载大家都在看模块
    //处理大家都在看模块start
    $(".promptu-menu ul li").each(function(){
        var a = $(this).find("a");
        var guest_item_url = a.attr("href");
        var guest_item_name = a.attr("title");
        var guest_item_imgurl = a.find("img").attr("src");

        var guest_item = "";
        guest_item += '<div class="ui-module ui-module-k">';
        guest_item += '<a href=' + guest_item_url + ' class="ui-module-link ui-a-block">';
        guest_item += '<img src=' + guest_item_imgurl + ' class="t" alt=' + guest_item_name + '>';
        guest_item += '<div class="ui-mask-black-text t">';
        guest_item += '<h2>' + guest_item_name + '</h2>';
        guest_item += '</div>';
        $(this).empty().append(guest_item);
    });
    $(".promptu-menu ul li").unwrap().unwrap();
    $(".promptu-menu").show();
    scroolLeft();
    //处理大家都在看模块end

    setInterval(function(){
        playFiveMinute();
    },30000);
	
	//修改抬头图片
	$("#wx_pic").siblings("img").remove();	
	$(".am-logo").append('<img class="wap-logo" src="http://res.cztv.com/templates/project/topic2015/images/runningman_wap/20160512.jpg?ssssss" alt=""/>');	
	
	var tt = '<a class="logo-btn" href="http://hd.cztv.com/appstore/zgltv-cztv" title=""></a>';
	$("#wx_pic").before(tt);
	
	$(".wap-logo").load(function(){
		$(".am-logo,header").css({"height":$(".wap-logo").height()});
	})	
	
	/* var floatFooter = '<div class="floatfooter" style="position:fixed;width:100%;bottom: 0;left: 0;z-index:999;"><img src="http://res.cztv.com/templates/project/topic2015/images/runningman_wap/footer.png?v=1.3" alt="" style="width:100%;left: 0;top: 0;display: block;"/><a class="floatbtn" href="http://mp.weixin.qq.com/s?__biz=MzA5Nzc3NjY4MA==&mid=404097061&idx=1&sn=495363ae7e825d6637afc3f044461491#rd" style="position: absolute;width: 20%; height: 40%;display: block;z-index: 999;top: 30%;right: 5px;" title="" target="_blank"></a></div>'; */
	/*var floatFooter = '<a class="floatfooter" href="http://hd.cztv.com/appstore/zgltv-cztv" style="position:fixed;width:100%;bottom: 0;left: 0;z-index:999;" title="" target="_blank"><img src="http://res.cztv.com/templates/project/topic2015/images/runningman_wap/footer_0519.jpg?v=1.2" alt="" style="width:100%;left: 0;top: 0;display: block;"/></a>';
	$("Body").append(floatFooter);
	$(".floatfooter,.floatfooter img").css("width",$(window).width());
	*/
	//广告代码变量初始化定义
	var _script = '<script type="text/javascript">var ac_content_targeting="zgltv:"+__INFO__.cid+","+__INFO__.pid;</script>';
	$("header").after(_script);
});
//动态添加下载App
function downLoadApp(){
     $("section.am-video-comment").children("a").last().attr({"class":"am-btn am-btn-block am-btn-cztv am-radius am-video-wechats",
     "href":"http://mp.weixin.qq.com/s?__biz=MzA5Nzc3NjY4MA==&mid=207870633&idx=1&sn=746bc6e8db6a482a44159b6e43de5a1c&scene=0#rd"});
    $(".am-video-info:last a").remove(":first");
}
//加载悬浮页面广告
function loadTheAd(){
    var advermentSrc = "<div style='position:fixed;bottom:0px;width:100%;'>";
    advermentSrc+="<a href='http://hd.cztv.com/appstore/zgltv-cztv'><img src='http://tv.cztv.com/cztv/h5/advertising.png' style='width:100%'/></a>";
    advermentSrc+="<a href='javascript:void(0)' id='close'><img src='http://tv.cztv.com/cztv/h5/close_h5wap.png' style='position: absolute;top: 8px;right:5px;width:5%;'/></a>";
    advermentSrc+="</div>";
    $("div.am-modal-alert").after(advermentSrc);
    $("#close").click(function(){
        $(this).parent().hide();
    });
}
//异步加载
function synData(){
    var divHight = $(window).height();
    var divScroll = $("div.guest-like").offset().top-divHight;
    var scroolHight = $(document).scrollTop();
    if(scroolHight>divScroll && flag==false){
        guestLike();
    }
}
//大家都在看触屏效果
function scroolLeft(){
    var width = $(window).width();
    var n =Math.ceil(width/190);
    $('ul.promptu-menu').promptumenu({width:190*n, height:150, rows: 1, columns: n, direction: 'horizontal', pages: false});
    $('ul.promptu-menu li').css("top","0px");
    var liwidth = $('ul.promptu-menu li').css("width");
    $('ul.promptu-menu li').css("width",parseInt(liwidth));
    $(".promptu-menu img").css("height","112px");
}
var Config={
    search_url:"http://so.cztv.com/pc/s",
    comment_url:"http://api.my.cztv.com",
    related_drama_url:"http://api.cms.cztv.com",
    guest_like_url:"http://proxy.app.cztv.com/guessYouLike/api.so.cztv.com",
    collection_url:"http://favorite.cztv.com"
}
function guestLike(){
    flag = true;
    $.ajax({
        type: "get",
        url: Config.guest_like_url + "/interface?stype=1&category="+__INFO__.cid+"&or=3&ps=10&callback=?",
        dataType: "jsonp",
        jsonpCallback:"callback",
        success: function (data) {
            if(data.album_list.length){
                $(".guest_like").show();
                for (var i = 0; i < data.album_list.length; i++) {
                    var guest_item_url = data.album_list[i].videoList[0].url;
                    if (data.album_list[i].videoList[0].images['640*400'] == "") {
                        var guest_item_imgurl = "images/load_logo.png";
                    } else {
                        if (data.album_list[i].videoList[0].images['640*400'].indexOf("../") == -1) {
                            var guest_item_imgurl = data.album_list[i].videoList[0].images['640*400'];
                        } else {
                            var guest_item_imgurl = "http://tv.cztv.com/cztv/user/load_logo.png";
                        }
                    }
                    var guest_item_name = data.album_list[i].name;
                    var guest_item_shortDesc = data.album_list[i].shortDesc;
                    var guest_item = '';
                    guest_item += '<li>';
                    guest_item += '<div class="ui-module ui-module-k">';
                    guest_item += '<a href=' + guest_item_url + ' class="ui-module-link ui-a-block" title=' + guest_item_name + '>';
                    guest_item += '<img src=' + guest_item_imgurl + ' class="t" alt=' + guest_item_name + ' title=' + guest_item_name + '>';
                    guest_item += '<div class="ui-mask-black-text t">';
                    guest_item += '<h2>' + guest_item_name + '</h2>';
                    guest_item += '</div>';
                    guest_item += '</li>';
                    $(".guest-like").find("ul").append(guest_item);
                }
                scroolLeft();
            }else{
                $(".guest-like").hide();
            }
        },
        error: function () {
            console.log("fail");
        }
    });
}

var flag2=1;
function playFiveMinute(){
    var date = new Date();
    var dateWeek = date.getDay();
    var dataHours = date.getHours();
    var dataMinutes = date.getMinutes();
    var videoPlayer = document.getElementById("h5Player");
    videoPlayer.addEventListener("timeupdate", function () {
        //if(videoPlayer.currentTime>=300&&dateWeek==5&&dataHours==21&&10<=dataMinutes<=59){
        if(videoPlayer.currentTime >= 300*flag2){			
            warningMessage(videoPlayer);
        }
    }, false);
}

function warningMessage(videoPlayer){
	videoPlayer.pause();//暂停视频
	flag2+=3;
		
		var rea = window.confirm("是否下载中国蓝TV手机客户端，观看高清视频？");
		if(rea){
			window.location.href="http://hd.cztv.com/appstore/zgltv-cztv";
		}else{
			videoPlayer.play();
			return;
		}
		
    //}
}
/*
var url=window.location.href;
var a=url.split("?");
var num=a.length-1;
var str=a[num];
var vid=parseInt(str.replace('zgltv=',''))
if (vid==1||vid==2||vid==3||vid==4) {
    floatSec();
};
function floatSec(){
    var html='<div class="float_sec" style="display: ;position: fixed;top: 0;left: 0;width: 100%;height: 100%;background: url(http://res.cztv.com/templates/project/topic2015/images/runningman_wap/bg.png);z-index: 2147483647;">'+
        '<a href="http://hd.cztv.com/appstore/zgltv-cztv">'+
            '<img style="position: absolute;top: 15%;left: 50%;width: 69.355%;-webkit-transform: translate(-50%,0);transform: translate(-50%,0);" class="float_txt" src="http://res.cztv.com/templates/project/topic2015/images/runningman_wap/float_bg.png" alt=""></a>'+
        '<span style="position: absolute;top: 16%;right: 18%;width: 30px;height: 30px;background: url(http://res.cztv.com/templates/project/topic2015/images/runningman_wap/btn.png) no-repeat;background-size: 100%;" class="close_btn"></span>'+
    '</div>';
    $('body').append(html)
    $(".close_btn").click(function(){
        $(".float_sec").hide();
    })
    if (navigator.userAgent.match(/(iPhone|iPod|iPad);?/i)) {
        var loadDateTime = new Date();
        window.setTimeout(function() {
          var timeOutDateTime = new Date();
          if (timeOutDateTime - loadDateTime < 5000) {
            //window.location = "http://hd.cztv.com/appstore/zgltv-cztv";
          } else {
            window.close();
          }
        },
        25);
        $(".float_sec").show();
      } else if (navigator.userAgent.match(/android/i)) {
        var state = null;
        try {
          state = window.open("apps custom url schemes ", '_blank');
        } catch(e) {}
        if (state) {
          window.close();
        } else {
          $(".float_sec").show();
        }
      }
}*/
var  Request = new Object();
var  base64EncodeChars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/";
var  base64DecodeChars = new Array(-1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, -1, 62, -1, -1, -1, 63, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61, -1, -1, -1, -1, -1, -1, -1, 0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, -1, -1, -1, -1, -1, -1, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, -1, -1, -1, -1, -1);

function GetRequest() { 
        var url = location.search; //获取url中"?"符后的字串 
        var theRequest = new Object(); 
        if (url.indexOf("?") != -1) { 
        var str = url.substr(1); 
        strs = str.split("&"); 
        for(var i = 0; i < strs.length; i ++) { 
        theRequest[strs[i].split("=")[0]]=unescape(strs[i].split("=")[1]); 
        } 
        } 
        return theRequest; 
} 
function base64decode(str){
    var c1, c2, c3, c4;
    var i, len, out;
    len = str.length;
    i = 0;
    out = "";
    while (i < len) {
        /* c1 */
        do {
            c1 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
        }
        while (i < len && c1 == -1);
        if (c1 == -1) 
            break;
        /* c2 */
        do {
            c2 = base64DecodeChars[str.charCodeAt(i++) & 0xff];
        }
        while (i < len && c2 == -1);
        if (c2 == -1) 
            break;
        out += String.fromCharCode((c1 << 2) | ((c2 & 0x30) >> 4));
        /* c3 */
        do {
            c3 = str.charCodeAt(i++) & 0xff;
            if (c3 == 61) 
                return out;
            c3 = base64DecodeChars[c3];
        }
        while (i < len && c3 == -1);
        if (c3 == -1) 
            break;
        out += String.fromCharCode(((c2 & 0XF) << 4) | ((c3 & 0x3C) >> 2));
        /* c4 */
        do {
            c4 = str.charCodeAt(i++) & 0xff;
            if (c4 == 61) 
                return out;
            c4 = base64DecodeChars[c4];
        }
        while (i < len && c4 == -1);
        if (c4 == -1) 
            break;
        out += String.fromCharCode(((c3 & 0x03) << 6) | c4);
    }
    return out;
}

function utf8to16(str) {
    var out, i, len, c;
    var char2, char3;
    out = "";
    len = str.length;
    i = 0;
    while(i < len) {
    c = str.charCodeAt(i++);
    switch(c >> 4) {
    case 0: case 1: case 2: case 3: case 4: case 5: case 6: case 7:
    // 0xxxxxxx
    out += str.charAt(i-1);
    break;
    case 12: case 13:
    // 110x xxxx 10xx xxxx
    char2 = str.charCodeAt(i++);
    out += String.fromCharCode(((c & 0x1F) << 6) | (char2 & 0x3F));
    break;
    case 14:
    // 1110 xxxx 10xx xxxx 10xx xxxx
    char2 = str.charCodeAt(i++);
    char3 = str.charCodeAt(i++);
    out += String.fromCharCode(((c & 0x0F) << 12) |
    ((char2 & 0x3F) << 6) |
    ((char3 & 0x3F) << 0));
    break;
    }
    }
    return out;
}


function wx_share(a,b,c,d){
    //alert(1)
    //if (ua.wechat) {
        function getNewsLink() {
            var link = location.search;
            link = link.substr(1);
            var link_arr = link.split('&');
            var link_arr_length = link_arr.length;
            var link_flag = false;
            for (var i = 0; i < link_arr_length; i++) {
                if (link_arr[i].split("=")[0] == "play") {
                    link_flag = true;
                }
            }
            var url = location.href;
            if (!link_flag) {
                return url + (url.indexOf('?') < 0 ? '?' : '&') + 'play=1';
            } else {
                return url;
            }
        }
        $.getJSON('http://open.cztv.com/mobileapp/index.php?module=cbtopic&controller=weixinapi&action=share&callback=?', {url: location.href.split('#')[0]}, function (msg) {
            if (msg.success) {
                //alert("chenggong")
                wx.config({
                    debug: false,
                    appId: msg.appId,
                    timestamp: msg.timestamp,
                    nonceStr: msg.nonceStr,
                    signature: msg.signature,
                    jsApiList: [
                        'onMenuShareTimeline',
                        'onMenuShareAppMessage',
                        'onMenuShareQQ',
                        'onMenuShareWeibo',
                    ]
                });
            }
            wx.ready(function () {
                var news_title = a;
                var news_link = getNewsLink();
                var news_image = b;
                var news_intro = c;
                var news_Timeline = d;
                wx.onMenuShareTimeline({
                    title: news_Timeline,
                    link: news_link,
                    imgUrl: news_image
                });
                wx.onMenuShareAppMessage({
                    title: news_title,
                    desc: news_intro,
                    link: news_link,
                    imgUrl: news_image
                });
                wx.onMenuShareQQ({
                    title: news_title,
                    desc: news_intro,
                    link: news_link,
                    imgUrl: news_image
                });
                wx.onMenuShareWeibo({
                    title: news_title,
                    desc: news_intro,
                    link: news_link,
                    imgUrl: news_image
                });
            });
        });
    //}
}
