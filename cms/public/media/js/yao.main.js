//摇动事件钩
var deviceMotionHandler;
//校验码计数
var verify_count;
//请求状态
var is_requesting = 0;
var lottery_disable = false;
var cztv = new CZTVJsObject();

$(".share").click(function () {
    cztv.share(params);
});

function check_signature() {
    var signature = getQueryString("signature");
    if(!signature) {
        show_dialog_box("抱歉，仅在官方应用中才能进入此摇奖活动!", "下载应用", "download");
        disable_lottery();
        return false;
    } else {
        return true;
    }
}

function shake_action() {
    if(!lottery_disable && lottery_id) {
        setTimeout(function () {
            yao();
        }, 200);
        $(".yao_btn").addClass("yao_btn_dong");
        setTimeout(function () {
            $(".yao_btn").removeClass("yao_btn_dong");
        }, 1000);
    }
}

//禁用摇奖
function disable_lottery() {
    lottery_disable = true;
    var btn = $(".yao_btn");
    btn.addClass('gray').removeClass("yao_btn_animation");
    btn.unbind("click");
    if (window.DeviceMotionEvent) {
        window.removeEventListener('devicemotion', deviceMotionHandler, false);
    }
}

//摇一摇
function yao() {
    //发起请求时, 不能在上次请求没有结束前, 不能覆盖弹窗
    if(!is_requesting && $(".layer").is(":hidden")) {
        var items = ["摇不中啊~~~换个姿势吧！", "摇不中啊换只手吧！", "人生最重要两个字是坚持继续摇吧！", "站起来摇一摇也许就中了哦！" , "没中奖不要紧，心情要保持愉快哦！", "也许下个大奖就是你的，加油！", "摇不到没关系，英雄下次再来吧：）", "换个姿势，也许就中奖哦！", "换个方向，也许就能中大奖哦！", "摇不到，不代表你不美哦！", "摇不中，也是一种姿态！", "人生难得摇一摇，不中也可乐逍遥！"];
        var randomItem = items[Math.floor(Math.random() * items.length)];
        if (lottery_disable || !lottery_id) {
            setTimeout(function () {
                show_dialog_box("摇奖次数已用完，请明天再来!", "好的");
                disable_lottery();
            }, 800);
        } else {
            is_requesting = 1;
            cztv.playSound();
            setTimeout(function () {
                $.ajax({
                    method: "get",
                    async: false,
                    url: base_url + 'lottery/draw/'+lottery_id,
                    data: generate_signature(),
                    dataType: "jsonp",
                    success: function (data) {
                        switch(data.code) {
                            case 1:
                                show_dialog_box('<img class="prizeimg" src="' + data.thumb + '">恭喜您!<br>您中了 "' + data.prize + '"', "填写联系信息", "show", data);
                                break;
                            case -1:
                                show_dialog_box("本时段摇奖已达上限!", "好的");
                                disable_lottery();
                                break;
                            case -2:
                                show_dialog_box("活动已结束，可以去其他频道试试!", "去其他频道试运气", "jump");
                                disable_lottery();
                                break;
                            default:
                                if(data.rest == 0) {
                                    show_dialog_box("好遗憾，期待下次好运!", "好的");
                                    disable_lottery();
                                } else {
                                    //show_dialog_box(randomItem + "还有<em>" + data.rest + "</em>次机会等你摇哦!", "好的");
                                    show_dialog_box(randomItem, "好的");
                                }
                                break;
                        }
                        is_requesting = 0;
                    },
                    error: function (data) {
                        show_dialog_box("好遗憾，期待下次好运!", "好的");
                        disable_lottery();
                        is_requesting = 0;
                    }
                });
            }, 800);
        }
    }
}

//对话表单
function show_dialog_box(msg, btn_text, action, data) {
    var btn = $(".text_con_btn");
    var layer = $(".layer");
    var text_con = $(".text_con");
    layer.show();
    text_con.show();
    if (window.DeviceMotionEvent) {
        window.removeEventListener('devicemotion', deviceMotionHandler, false);
    }
    $(".text_con_top").html(msg);
    btn.html(btn_text);
    btn.off("click");
    if(action == "play") {
        $(".go-luck").on("click", function () {
            cztv.playSound();
        });
    }
    btn.on("click", function() {
        switch(action) {
            case 'jump':
                window.location.href = index_url+'?'+generate_signature();
                break;
            case 'download':
                window.location.href = '/appdownload/index?channel_id=' + channel_id;
                break;
            case 'show':
                layer.hide();
                text_con.hide();
                $(".prize-name").html(data.prize);
                $("#token").val(data.token);
                $("#is_real").val(data.is_real);
                if(data.is_real == 0) {
                    $(".real_need").hide();
                } else {
                    $(".real_need").show();
                }
                $(".layer_yes").show();
                $(".text_con_info").show();
                break;
            default:
                layer.hide();
                text_con.hide();
                if (window.DeviceMotionEvent || !lottery_disable) {
                    window.addEventListener('devicemotion', deviceMotionHandler, false);
                }
                break;
        }
    });
}

//点击提交中奖者信息
$("#submitTel").on("click", function () {
    var name = $("#name").val();
    var tel = $("#tel").val();
    var province = $(".province").val();
    var city = $(".city").val();
    var area = $(".area").val();
    var addr = $("#addr").val();
    var token = $("#token").val();
    var is_real = $("#is_real").val();
    if (!testPhone(tel)) {
        notifier("请输入正确的手机号!");
        return false;
    }
    if(is_real != "0") {
        if (!testName(name)) {
            notifier("请输入正确的姓名!");
            return false;
        }
        if (!testAddr(addr)) {
            notifier("请输入正确的联系地址!");
            return false;
        }
        if(province == "0") {
            notifier("请选择正确的省份");
            return false;
        }
        if(city == "0") {
            notifier("请选择正确的城市");
            return false;
        }
        if(area == "0") {
            notifier("请选择正确的区县");
            return false;
        }
    }
    $.ajax({
        type: "get",
        async: false,
        url: base_url + 'lottery/contact',
        data: generate_signature()+'&mobile='+tel+"&_token="+token+ "&real_name=" + encodeURI(name) + "&mobile=" + tel + "&address=" + encodeURI(addr) + "&province=" + encodeURI(province) + "&city=" + encodeURI(city) + "&area=" + encodeURI(area),
        dataType: "jsonp",
        success: function (data) {
            switch(data.msg) {
                case 'params required':
                    notifier('请检查您提交的参数!');
                    break;
                case 'verify code error':
                    notifier('验证码错误!');
                    break;
                case 'ok':
                case 'info has been set':
                    notifier('您的信息已经录入，感谢您的参与!');
                    $(".layer_yes").hide();
                    break;
                case 'retry':
                    notifier('系统错误, 请重试!');
                    break;
                default:
                    notifier("发送失败，请重新发送!");
                    break;
            }
        },
        error: function () {
            notifier("发送失败，请重新发送!");
        }
    });
});
//地区联动
$.cxSelect.defaults.url = area_json_url;
$('#city_china_val').cxSelect({
    selects: ['province', 'city', 'area'],
    nodata: 'none'
});

$(function() {
    //进行手机摇一摇时间监听
    var SHAKE_THRESHOLD = 3000;
    var last_update = 0;
    var x = y = z = last_x = last_y = last_z = 0;
    deviceMotionHandler = function(eventData) {
        var acceleration = eventData.accelerationIncludingGravity;
        var curTime = new Date().getTime();
        var diffTime = curTime - last_update;
        if (diffTime > 100) {
            last_update = curTime;
            x = acceleration.x;
            y = acceleration.y;
            z = acceleration.z;
            var speed = Math.abs(x + y + z - last_x - last_y - last_z) / diffTime * 10000;
            if (speed > SHAKE_THRESHOLD) {
                shake_action();
            }
            last_x = x;
            last_y = y;
            last_z = z;
        }
    };
    if (window.DeviceMotionEvent) {
        window.addEventListener('devicemotion', deviceMotionHandler, false);
    } else {
        notifier('not support mobile event');
    }
    //摇一摇效果
    $(".yao_btn").click(function () {
        shake_action();
    });
    check_signature();
});