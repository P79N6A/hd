var is_request = 0;
$(function () {
    var url = window.location.href;
    var index = url.indexOf("?");
    var params = "";
    if (index != -1 && index != url.length - 1) {
        params = url.substring(index + 1, url.length);
    }
    setTimeout(function () {
        var hh = document.body.offsetHeight;
        $(".layer").css("height", hh);
    }, 500);

    $.ajax({
        method: "get",
        async: false,
        url: base_url+'lottery/lotteries/'+group_id+'?'+generate_signature(),
        dataType: "jsonp",
        success: function (data) {
            var r, i, str = '';
            if(data.length) {
                str += '<div class="title-info">以下频道<span>正在摇奖中</span><em></em></div>';
                str += '<div class="channels-main clearfix">';
                for(i in data) {
                    r = data[i];
                    str += '<a class="channel-1" href="' + this_url + 'draw/'+r["id"] + '?' + generate_signature() +'"><img src="' + r["style"] + '" /></a>';
                }
                str += '</div>';
            } else {
                str = '<div class="title-info"><span>友情提示</span><em></em></div><div class="channels-alert"><p>摇奖时间未到哦，</p><p>请稍后再来...</p></div>';
            }
            $(".channel-list").html(str);
        },
        error: function () {
            $(".channel-list").html('<div class="title-info"><span>友情提示</span><em></em></div><div class="channels-alert"><p>摇奖时间未到哦，</p><p>请稍后再来...</p></div>');
        }
    });

    function winList(data, title) {
        var i, text = "";
        if(data.rs && data.rs.length > 0) {
            var rs = data.rs;
            for(i in rs) {
                var mobile = rs[i].mobile;
                var name = rs[i].name;
                var prize = rs[i].prize_name;
                text += "<div class='lottery_item'>";
                text += "<div class='am-col-6'>" + mobile + "</div>";
                text += "<div class='am-col-6'>" + prize + "</div>";
                text += "</div>";
            }
        } else {
            if(data.msg == "ok") {
                data.msg = "很遗憾，暂无中奖信息";
            }
            text = "<div class='none_people'>" + data.msg + "</div>";
        }
        $(".lottery_item_container").html(text);
        $(".search-title").html(title);
        is_request = 0;
    }

    function winsearchList(data, title) {
        var i, text = "";
        if(data.rs && data.rs.length > 0) {
            var rs = data.rs;
            for(i in rs) {
                var mobile = rs[i].mobile;
                var prize = rs[i].prize_name;
                var status = parseInt(rs[i].status);
                switch (status) {
                    case 1: status = "未发货"; break;
                    case 2: status = "已发货"; break;
                    case 3: status = "已取消"; break;
                    default: status = "未知状态";
                }
                text += "<div class='lottery_item'>";
                text += "<div class='am-col-6'>" + status + "</div>";
                text += "<div class='am-col-6'>" + prize + "</div>";
                text += "</div>";
            }
        } else {
            if(data.msg == "ok") {
                data.msg = "很遗憾，暂无中奖信息";
            }
            text = "<div class='none_people'>" + data.msg + "</div>";
        }
        $(".lottery_item_container").html(text);
        $(".search-title").html(title);
        is_request = 0;
    }

    //中奖名单
    $(".text_link").on("click", function () {
        var idx = $(this).index();
        $(".layer").show();
        $(".text_con").hide().eq(idx).show();
        if (idx == 1 && !is_request) {
            $.ajax({
                type: "get",
                async: false,
                url: base_url + 'lottery/winners/'+group_id+'?' + generate_signature(),
                dataType: "jsonp",
                success: function (data) {
                    winList(data, "中奖名单");
                },
                error: function () {
                    winList({"rs":[], "msg": "请求出错, 请重试..."}, "中奖名单");
                }
            });

        }
    });

    //中奖查询
    $(".btn_search").on("click", function () {
        if(!is_request) {
            var mobile = $("#search_mobile").val();
            if(testPhone(mobile)) {
                is_request = 1;
                $.ajax({
                    type: "get",
                    async: false,
                    url: base_url + 'lottery/search_winners/'+group_id+'?' + generate_signature(),
                    data: "mobile=" + mobile,
                    dataType: "jsonp",
                    success: function (data) {
                        winsearchList(data, "中奖名单");
                    },
                    error: function () {
                        winsearchList({"rs": [], "msg": "请求出错, 请重试..."}, "搜索 " + mobile + " 结果");
                    }
                });
            } else {
                winsearchList({"rs": [], "msg": "请输入正确的手机号"}, "搜索 " + mobile + " 结果");
            }
        }
    });

    $(".close").on("click", function () {
        $(".layer").hide();
        $(".text_con").hide();
    });
    $(".lottery_btn").on("click", function () {
        $(".layer").hide();
        $(".text_con").hide();
    });
});