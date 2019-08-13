function fenxiang(a,b,c,d){
			if (ua.wechat) {
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
						var news_link = d;
						var news_image = "http://yun-cztv.oss-cn-hangzhou.aliyuncs.com/static/zgltv-images/runman/fx.jpg";
						var news_intro = b;
						var news_Timeline = c;
						wx.onMenuShareTimeline({
							title: news_title,
							link: news_link,
							imgUrl: news_image
						});
						wx.onMenuShareAppMessage({
							title: news_Timeline,
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
			}
		}