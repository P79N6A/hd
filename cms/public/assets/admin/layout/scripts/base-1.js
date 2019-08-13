
var lockmenukey = false;
var BaseJs = function() {

    var contentBaseJs = function() {
        //screening click
        $(function() {
            $(".screening-btn").toggle(function() {
                $(".screening-content .screening-box").fadeIn();
            }, function() {
                $(".screening-content .screening-box").fadeOut();
            });
            if ($('.form-group').hasClass('screening-box')) {
                $('.screening-btn').show();
            }
            if ($('.screening-content').find('div').hasClass('search-result')) {
                $('.screening-content').find('.form-group').removeClass('screening-box');
            }
        });

        //edit content
        $(function() {
            $('.edit-content, .quick-sidebar-toggler').click(function() {
                var dataUrl = $(this).attr('data-url');
                $('#iframe').attr('src',''+dataUrl+'');
                $('body').toggleClass('page-quick-sidebar-open');
            });

            $('.page-quick-sidebar-toggler').click(function() {
                $('body').toggleClass('page-quick-sidebar-open');
                $('#iframe').attr('src','');
                setTimeout(function () {
                    window.location.reload();
                }, 60);
            });
        });

        $(function() {
            $(".href-location").click(function() {
                location.href = $(this).attr('data-url');
            });
        });

        //task open
        $(function() {
            $("#task_title_btn").click(function() {
                $('#task_desc_block').toggleClass('hide');
            });
        });


        //sidebar menu
        $(function() {
            //$('.page-sidebar-menu li:first-child').addClass('open');
            //$('.page-sidebar-menu li:first-child ul.sub-menu').css({'display':'block'});
            $('ul.sub-menu li').removeClass('open');
            $('ul.sub-menu li').on("click", function() {
                $('ul.sub-menu').find('li.open').removeClass('open');
                $(this).toggleClass("open");
                if(lockmenukey==false) {
                    lockmenukey = true;
                    if('javascript:;'!=$(this).find('a').attr('data-url')) {
                        window.frames[0].location = $(this).find('a').attr('data-url');
                    }
                    setTimeout(function() {lockmenukey = false;}, 500);
                }
            });
        });

        //trigger fullscreen
        $(function() { 
            $(".trigger-fullscreen").toggle(function() {
                $(".page-content", window.parent.document).addClass('fullscreen-mode');
                $(".trigger-fullscreen i").attr("class", "fa fa-compress");
            }, function() {
                $(".page-content", window.parent.document).removeClass('fullscreen-mode');
                $(".trigger-fullscreen i").attr("class", "fa fa-expand");
            });
        });

        //setting password
        $('#setting-back-btn').click(function() {
            $('.setting-info').show();
            $('.setting-password').hide();
        });

        $('#setting-password-btn').click(function() {
            $('.setting-info').hide();
            $('.setting-password').show();
        });

        //task
        $(function() { 
            $(".task-descbtn").toggle(function() {
                $(".task-descmore").fadeIn();
            }, function() {
                $(".task-descmore").fadeOut();
            });

            $(".taskdetails-btnclose").toggle(function() {
                $(".taskdetails-sub").show();
                $(this).attr("class", "fa fa-minus-square taskdetails-btnclose");
            }, function() {
                $(".taskdetails-sub").hide();
                $(this).attr("class","fa fa-plus-square taskdetails-btnclose");
            });
        });

        //page bar fixed
        /*$(function() {
            $(window).scroll(function() {
                var topheight = $(window).scrollTop();
                if(topheight > 10) {
                    $(".page-bar").addClass("page-bar-fixed");
                }
                else {
                    $(".page-bar").removeClass("page-bar-fixed");
                }
            });
        });*/

        //table checked 全选
        $(function() {
            $('.group-checkable').click(function() {
                var set = $(this).attr("data-set");
                $(set).each(function() {
                    if (this.checked == false) {
                        $(this).attr("checked", true);
                        $(this).parents('tr').addClass("warning");
                    }
                });
                $.uniform.update(set);
            });
        });

        $(function() {
            $('#sorttable tbody tr .checkboxes').change(function() {
                $(this).parents('tr').toggleClass("warning");
            });
        });

        //table checked 取消
        $(function() {
            $('.group-offcheckable').click(function() {
                var set = $(this).attr("data-set");
                $(set).each(function() {
                    if(this.checked == true) {
                        $(this).attr("checked", false);
                        $(this).parents('tr').removeClass("warning");
                    }
                });
                $.uniform.update(set);
            });
        });

        //table checked 反选
        $(function() {
            $('.group-anticheckable').click(function() {
                var set = $(this).attr("data-set");
                $(set).each(function() {
                    if(this.checked == true) {
                        $(this).attr("checked", false);
                        $(this).parents('tr').removeClass("warning");
                    }
                    else {
                        $(this).attr("checked", true);
                        $(this).parents('tr').addClass("warning");
                    }
                });
                $.uniform.update(set);
            });
        });

        //role 权限配置 全选
        $(function() {
            for(var i = 0; i < $('.icheck-inline tr:odd').length; i++) {
                $('.icheck-inline tr:eq('+2*i+') .group-checkable').on('ifChecked', function() {
                    var set = $(this).attr("data-set");
                    if ($(this).hasClass("empty-checked")) {
                        $(set).each(function () {
                            if (!$(this).attr("checked")) {
                                $(this).iCheck("check");
                            }
                        });
                        $(this).removeClass("empty-checked").addClass("all-checked");
                    }
                });
                $('.icheck-inline tr:eq('+2*i+') .group-checkable').on('ifUnchecked', function() {
                    var set = $(this).attr("data-set");
                    if ($(this).hasClass("part-checked")) {
                        $(set).each(function () {
                            if ($(this).attr("checked")) {
                                $(this).iCheck("uncheck");
                            }
                        });
                        $(this).removeClass("part-checked").addClass("empty-checked");
                    }
                    else if ($(this).hasClass("all-checked")) {
                        $(set).each(function() {
                            if($(this).attr("checked")) {
                                $(this).iCheck("uncheck");
                            }
                        });
                        $(this).removeClass("all-checked").addClass("empty-checked");
                    }
                });
            }
        });

        //权限配置初始化
        function checkboxStateInitialize(position) {
            var num = 0;
            var checkboxSelecter = $('.icheck-inline tr:eq('+2*position+') .group-checkable');
            var set = checkboxSelecter.attr("data-set");
            var set_num = $('.icheck-inline tr:eq('+2*position+')').next().find("label").length;
            $(set).each(function() {
                if($(this).attr("checked")) {
                    num++;
                }
            });
            if(num == 0) {
                checkboxSelecter.iCheck("uncheck");
                checkboxSelecter.addClass("empty-checked");
            }
            else if(num > 0 && num < set_num) {
                checkboxSelecter.iCheck("check");
                checkboxSelecter.addClass("part-checked");
            }
            else {
                checkboxSelecter.iCheck("check");
                checkboxSelecter.addClass("all-checked");
            }
        }

        $(function() {
            for(var i = 0; i < $('.icheck-inline tr:odd').length; i++) {
                checkboxStateInitialize(i);
            }
        });

        //权限配置动态设置选择框的状态
        function setCheckboxState(position) {
            var num = 0;
            var checkboxSelecter = $('.icheck-inline tr:eq('+2*position+') .group-checkable');
            var set = checkboxSelecter.attr("data-set");
            var set_num = $('.icheck-inline tr:eq('+2*position+')').next().find("label").length;
            $(set).each(function() {
                if($(this).attr("checked")) {
                    num++;
                }
            });
            if(num == 0) {
                if(checkboxSelecter.hasClass("part-checked")) {
                    checkboxSelecter.removeClass("part-checked").addClass("empty-checked");
                }
                else if(checkboxSelecter.hasClass("all-checked")) {
                    checkboxSelecter.removeClass("all-checked").addClass("empty-checked");
                }
                checkboxSelecter.iCheck("uncheck");
            }
            else if(num > 0 && num < set_num) {
                if(checkboxSelecter.hasClass("all-checked")) {
                    checkboxSelecter.removeClass("all-checked").addClass("part-checked");
                }
                else if(checkboxSelecter.hasClass("empty-checked")) {
                    checkboxSelecter.removeClass("empty-checked").addClass("part-checked");
                }
                checkboxSelecter.iCheck("check");
            }
            else {
                if(checkboxSelecter.hasClass("part-checked")) {
                    checkboxSelecter.removeClass("part-checked").addClass("all-checked");
                }
                else if(checkboxSelecter.hasClass("empty-checked")) {
                    checkboxSelecter.removeClass("empty-checked").addClass("all-checked");
                }
                checkboxSelecter.iCheck("check");
            }
        }

        $(function() {
            for(var i = 0; i < $('.icheck-inline tr:odd').length; i++) {
                var set = $('.icheck-inline tr:eq('+2*i+') .group-checkable').attr("data-set");
                $(set).each(function() {
                    $(this).on('ifChanged', function() {
                        setCheckboxState(($(this).parents("tr").index() - 1) / 2);
                    });
                });
            }
        });

        //鼠标移动显示大图
        $(function() {
            var x = 20;
            var y = 20;
            $('.small-img').hover(function(Axis) {
                $('body').append('<img class="big-img" src="'+ this.src + '"/>');
                $(this).stop().fadeTo('slow', 0.5);
                JudgeDistance(Axis);
                $('.big-img').fadeIn('fast');
            }, function() {
                $(this).stop().fadeTo('slow', 1);
                $('.big-img').remove();
            });
            $('.small-img').mousemove(function(Axis) {
                JudgeDistance(Axis);
            });
            function JudgeDistance(Axis) {
                var marginRight = document.documentElement.clientWidth - Axis.clientX;
                var marginTop = document.documentElement.clientHeight - Axis.clientY;
                var imageWidth = $('.big-img').width();
                var imageHeight = $('.big-img').height();
                if(marginRight > imageWidth && marginTop - 20 > imageHeight) {
                    x = 20;
                    y = 20;
                    $('.big-img').css({top:(Axis.pageY + y) + 'px', left:(Axis.pageX + x) + 'px'});
                }
                else if(marginRight < imageWidth && marginTop - 20 > imageHeight) {
                    x = imageWidth + 20;
                    y = 20;
                    $('.big-img').css({top:(Axis.pageY + y) + 'px', left:(Axis.pageX - x) + 'px'});
                }
                else if(marginRight > imageWidth && marginTop - 20 < imageHeight) {
                    x = 20;
                    y = imageHeight + 20;
                    $('.big-img').css({top:(Axis.pageY - y) + 'px', left:(Axis.pageX + x) + 'px'});
                }
                else {
                    x = imageWidth + 20;
                    y = imageHeight + 20;
                    $('.big-img').css({top:(Axis.pageY - y) + 'px', left:(Axis.pageX - x) + 'px'});
                }
            }
        });
        $(function() {
            var x = 20;
            var y = 20;
            $('.small-img2').hover(function(Axis) {
                $('body').append('<img class="big-img2" src="'+ this.src + '"/>');
                $(this).stop().fadeTo('slow', 0.5);
                JudgeDistance(Axis);
                $('.big-img2').fadeIn('fast');
            }, function() {
                $(this).stop().fadeTo('slow', 1);
                $('.big-img2').remove();
            });
            $('.small-img2').mousemove(function(Axis) {
                JudgeDistance(Axis);
            });
            function JudgeDistance(Axis) {
                var marginRight = document.documentElement.clientWidth - Axis.clientX;
                var marginTop = document.documentElement.clientHeight - Axis.clientY;
                var imageWidth = $('.big-img2').width();
                var imageHeight = $('.big-img2').height();
                if(marginRight > imageWidth && marginTop - 20 > imageHeight) {
                    x = 20;
                    y = 20;
                    $('.big-img2').css({top:(Axis.pageY + y) + 'px', left:(Axis.pageX + x) + 'px'});
                }
                else if(marginRight < imageWidth && marginTop - 20 > imageHeight) {
                    x = imageWidth + 20;
                    y = 20;
                    $('.big-img2').css({top:(Axis.pageY + y) + 'px', left:(Axis.pageX - x) + 'px'});
                }
                else if(marginRight > imageWidth && marginTop - 20 < imageHeight) {
                    x = 20;
                    y = imageHeight + 20;
                    $('.big-img2').css({top:(Axis.pageY - y) + 'px', left:(Axis.pageX + x) + 'px'});
                }
                else {
                    x = imageWidth + 20;
                    y = imageHeight + 20;
                    $('.big-img2').css({top:(Axis.pageY - y) + 'px', left:(Axis.pageX - x) + 'px'});
                }
            }
        });

        //新增、删除个人经历
        $(function() {
            var num = 1;
            $('.add-experience').click(function() {
                $("#personal-experience").find("label").siblings("div:last").after("<div class=\"col-md-10 col-md-offset-2 border-division\">" +
                                                                                        "<span class=\"pull-right\"><button type=\"button\" class=\"btn red remove-experience-"+num+"\"><i class=\"fa fa-trash-o\"></i> 删除</button></span>" +
                                                                                        "<div class=\"input-group input-large date-picker input-daterange\">" +
                                                                                            "<input type=\"text\" class=\"form-control\" placeholder=\"开始时间\" name=\"start-time-"+num+"\" readonly>" +
                                                                                            "<span class=\"input-group-addon\">" +
                                                                                                "到 </span>" +
                                                                                            "<input type=\"text\" class=\"form-control\" placeholder=\"结束时间\" name=\"end-time-"+num+"\" readonly>" +
                                                                                        "</div>" +
                                                                                    "</div>" +
                                                                                    "<div class=\"col-md-10 col-md-offset-2 col-md-adjust\">" +
                                                                                        "<input type=\"text\" class=\"form-control input-large maxlength-handler\" maxlength=\"20\" placeholder=\"地点\" name=\"location-"+num+"\" value=\"\">" +
                                                                                    "</div>" +
                                                                                    "<div class=\"col-md-10 col-md-offset-2 col-md-adjust\">" +
                                                                                        "<textarea placeholder=\"描述\" rows=\"6\" maxlength=\"200\" class=\"form-control maxlength-handler\" name=\"description-"+num+"\">"+"</textarea>" +
                                                                                    "</div>" +
                                                                                    "<script type=\"text/javascript\">" +
                                                                                        "$('.date-picker').datepicker({" +
                                                                                            "rtl:Metronic.isRTL()," +
                                                                                            "format:\"yyyy-mm-dd\"," +
                                                                                            "orientation:\"left\"," +
                                                                                            "autoclose:true" +
                                                                                        "});" +
                                                                                        "$('.maxlength-handler').maxlength({" +
                                                                                            "limitReachedClass:\"label label-danger\"," +
                                                                                            "alwaysShow:true," +
                                                                                            "threshold:5" +
                                                                                        "});" +
                                                                                    "</script>"
                );
                $(".remove-experience-"+num).click(function() {
                    $(this).parent().parent().next().remove();
                    $(this).parent().parent().next().remove();
                    $(this).parent().parent().remove();
                });
                num++;
            });
        });

        //媒资列表 地区添加地址
        $(function() {
            var num = 1;
            $('.add-address').click(function() {
                $("#media-address").append("<div class=\"region-style-adjust\">" +
                                                "<div class=\"addresspost\">" +
                                                    "<label>添加地区</label>" +
                                                    "<span class=\"pull-right\"><button type=\"button\" class=\"btn red btn-xs remove-address-"+num+"\"><i class=\"fa fa-trash-o\"></i> 删除</button></span>" +
                                                "</div>" +
                                                "<select name=\"country_id"+num+"\" class=\"form-control input-small region-control"+(6*num+1)+" region\">" + "</select>" +
                                                "<select name=\"province_id"+num+"\" class=\"form-control input-small region-control"+(6*num+2)+" region region-left\">" + "</select>" +
                                                "<select name=\"city_id"+num+"\" class=\"form-control input-small region-control"+(6*num+3)+" region region-left\">" + "</select>" +
                                                "<select name=\"county_id"+num+"\" class=\"form-control input-small region-control"+(6*num+4)+" region region-left\">" + "</select>" +
                                                "<select name=\"town_id"+num+"\" class=\"form-control input-small region-control"+(6*num+5)+" region region-left\">" + "</select>" +
                                                "<select name=\"village_id"+num+"\" class=\"form-control input-small region-control"+(6*num+6)+" region region-left\">" + "</select>" +
                                            "</div>" +
                                            "<script type=\"text/javascript\">" +
                                                "$($.ajax({" +
                                                    "type: \"POST\"," +
                                                    "url: \"/regions/list\"," +
                                                    "data: \"id = 0\"," +
                                                    "success: function(msg) {" +
                                                        "var str = JSON.parse(msg);" +
                                                        "$(\".region-control"+(6*num+1)+"\").append('<option value=\"0\">请选择</option>');" +
                                                        "for(var i = 0; i < str.length; i++) {" +
                                                            "$(\".region-control"+(6*num+1)+"\").append(\'<option value=\"\'+str[i][\'id\']+\'\">\'+str[i][\'name\']+\'</option>\');" +
                                                        "}" +
                                                        "checkEmpty"+num+"();" +
                                                    "}" +
                                                "}));" +
                                                "$(\".region\").change(function() {" +
                                                    "$(this).nextAll().empty();" +
                                                    "$(this).nextAll().val(\"\");" +
                                                    "findRegion"+num+"(this);" +
                                                "});" +
                                                "function checkEmpty"+num+"() {" +
                                                    "for(var i = "+(6*num+1)+"; i <= "+(6*num+6)+"; i++) {" +
                                                        "$(\".region-control\" + i).show();" +
                                                        "if (!$(\".region-control\" + i).val()) {" +
                                                            "$(\".region-control\" + i).hide();" +
                                                        "}" +
                                                   "}" +
                                                "}" +
                                                "function findRegion"+num+"($this) {" +
                                                    "if($($this).val() != 0) {" +
                                                        "$.ajax({" +
                                                            "type: \"POST\"," +
                                                            "url: \"/regions/list\"," +
                                                            "data: \"id=\" + $($this).val()," +
                                                            "success: function(msg) {" +
                                                                "var str = JSON.parse(msg);" +
                                                                "if (str != \"\") {" +
                                                                    "$($this).next().append('<option value=\"0\">请选择</option>');" +
                                                                    "for (var i = 0; i < str.length; i++) {" +
                                                                        "$($this).next().append(\'<option value=\"\' + str[i][\'id\'] + \'\">\' + str[i][\'name\'] + \'</option>\');" +
                                                                    "}" +
                                                                "}" +
                                                                "checkEmpty"+num+"();" +
                                                            "}" +
                                                        "});" +
                                                    "}" +
                                                    "else {" +
                                                        "checkEmpty"+num+"();" +
                                                    "}" +
                                                "}" +
                                            "</script>"
                );
                $(".remove-address-"+num).click(function() {
                    $(this).parent().parent().nextUntil(".region-style-adjust").remove();
                    $(this).parent().parent().remove();
                });
                num++;
            });
        });

    /*  //个人资料锚链接
        $(function() {
            for(var i = 0; i < $(".anchor-orientation .anchor-inline-menu li").length; i++) {
                $(".anchor-orientation .anchor-inline-menu li:eq("+i+")").hover(function() {
                    $(this).addClass("anchor-popup-effect");
                }, function() {
                    $(this).removeClass("anchor-popup-effect");
                });
            }
        });

        $(function() {
            for(var i = 0; i < $(".anchor-orientation .anchor-inline-menu li").length; i++) {
                $(".anchor-orientation .anchor-inline-menu li:eq("+i+")").click(function() {
                    $(this).addClass("active");
                    $(this).siblings().removeClass("active");
                });
            }
        });  */

        //链接地址
        $(function() {
            $(".advert-style").find(".default-http").focus(function() {
                if($(this).val() == "") {
                    $(this).val("http://");
                }
            });
        });

        //添加节目流cdn
        $(function() {
            $(".form-stationsepg").find("[name='cdn']").focus(function() {
                if($(this).val() == "") {
                    $(this).val("http://");
                }
            });
        });

        //greeting-party
        $(function() {
            for(var i = 0; i < $("#greeting-party tbody tr").length; i++) {
                $(".extra-tickets-"+i).find(".ticket-up-"+i).click(function() {
                    var ticket_up = parseInt($(this).parent().prev().val());
                    $(this).parent().prev().val(ticket_up + 1);
                });
                $(".extra-tickets-"+i).find(".ticket-down-"+i).click(function() {
                    var ticket_down = parseInt($(this).parent().next().val());
                    if (ticket_down > 0) {
                        $(this).parent().next().val(ticket_down - 1);
                    }
                    else if (ticket_down == 0) {
                        $(this).parent().next().val(0);
                    }
                });
            }
        });

        $(function() {
            $(".ticket-input").change(function() {
                if($(this).val() < 0) {
                    alert("额外投票数不能小于0！");
                }
                else if(/[\u4e00-\u9fa5]+/gi.test($(this).val()) || /(?!_)([A-Za-z]+)/.test($(this).val())) {
                    alert("额外投票数不能是字母或汉字！");
                }
             });
        });

        $(function() {
            var current_tickets = new Array();
            var extra_tickets = new Array();
            $(".modify-tickets").click(function() {
                for(var i = 0; i < $("#greeting-party tbody tr").length; i++) {
                    current_tickets[i] = parseInt($("#greeting-party").find(".current-tickets-"+i).children().text());
                    extra_tickets[i] = parseInt($("#greeting-party").find(".extra-tickets-"+i).find(".ticket-input").val());
                    $("#greeting-party").find(".current-tickets-"+i).children().text(current_tickets[i] + extra_tickets[i]);
                    $("#greeting-party").find(".extra-tickets-"+i).find(".ticket-input").val(0);
                }
                $.ajax({
                    type: "POST",
                    url: "/show/vote",
                    data: "vote="+extra_tickets,
                    success: function(msg){
                    }
                });
            });
        });

    /*  //未读变已读
        $(function() {
            $(".todo-content #tab_news").find(".has-been-not-read").click(function() {
                if($(this).hasClass("has-been-not-read")) {
                    var num = $(".todo-content .badge-count-read").find(".badge-news-count").text();
                    $(this).removeClass("has-been-not-read").addClass("has-been-read");
                    if(num > 1) {
                        $(".todo-content .badge-count-read").find(".badge-news-count").text(num - 1);
                    }
                    else {
                        $(".todo-content .badge-count-read").find(".badge-news-count").remove();
                    }
                }
            });
        });

        $(function() {
            $(".todo-content #tab_news").find(".todo-tasklist-item").click(function() {
                $(".todo-content #tab_news #chats").find(".chat-form").show();
                if($(this).hasClass("has-been-read")) {
                    $(this).css("background-color", "#b1ddfc");
                    $(this).siblings().each(function() {
                        if($(this).hasClass("has-been-not-read")) {
                            $(this).css("background-color", "#F5C5D0");
                        }
                        else {
                            $(this).css("background-color", "#ecf7fc");
                        }
                    });
                }
            });
        });  */

    /*  //消息中心tab链接
        $(function() {
            $(".todo-content").find(".create-task").click(function() {
                $(this).siblings("li").find(".dropdown-toggle").html("任务操作 <i class=\"fa fa-angle-down\"></i>");
                $(this).siblings("li").find(".all-read").attr("href", "javascript:;");
                $(this).siblings("li").find(".empty-read").attr("href", "javascript:;");
                $(this).siblings("li:last").find("button").html("新建任务 <i class=\"fa fa-plus\"></i>");
                $(this).siblings("li:last").find("button").val("");
            });
        });

        $(function() {
            $(".todo-content").find(".create-news").click(function() {
                $(this).siblings("li").find(".dropdown-toggle").html("消息操作 <i class=\"fa fa-angle-down\"></i>");
                $(this).siblings("li").find(".all-read").attr("href", "456");
                $(this).siblings("li").find(".empty-read").attr("href", "789");
                $(this).siblings("li:last").find("button").html("新建消息 <i class=\"fa fa-plus\"></i>");
                $(this).siblings("li:last").find("button").val("/board/create");
            });
        });

        $(function() {
            $(".todo-content").find(".create-notice").click(function() {
                $(this).siblings("li").find(".dropdown-toggle").html("公告操作 <i class=\"fa fa-angle-down\"></i>");
                $(this).siblings("li").find(".all-read").attr("href", "789");
                $(this).siblings("li").find(".empty-read").attr("href", "123");
                $(this).siblings("li:last").find("button").html("新建公告 <i class=\"fa fa-plus\"></i>");
                $(this).siblings("li:last").find("button").val("/notice/add");
            });
        });  */

        //table sorting
        /*$('#sorttable > table > thead > tr > th').click(function() {
            //change interface
            switch (this.className) {
                case "sorting":
                    $(this).removeClass();
                    $(this).addClass("sorting_asc");
                    break;
                case "sorting_asc":
                    $(this).removeClass();
                    $(this).addClass("sorting_desc");
                    break;
                case "sorting_desc":
                    $(this).removeClass();
                    $(this).addClass("sorting_asc");
                    break;
            }
            $(this).siblings(':not(.table-checkbox)').removeClass();
            $(this).siblings(':not(.table-checkbox)').addClass("sorting");
        });*/

        //maxlength
        $('.maxlength-handler').maxlength({
            limitReachedClass: "label label-danger",
            alwaysShow: true,
            placement: 'bottom-left'
            //threshold: 10
        });
        //end
    }

    return {
        //main function to initiate the module
        init: function() {
            contentBaseJs();
        }
    };

}();

 //windows open
function winModalLimitScreen(strURL) {
    var sheight = 450;
    var swidth = 1000;
    var winoption ='width= '+ swidth +', height= '+ sheight +', status=yes, scrollbars=yes, resizable=yes, top=150, left=250,';
    var wintmp = window.open(strURL, window, winoption);
    return wintmp;
}

//dialog confirm onclick delete
function show_confirm(datatips) {
    var datatips = $(datatips);
    var datatitle = datatips.attr('data-original-title');
    var datamessage = '您确定要进行'+datatitle+'操作吗？';
    bootbox.dialog({
        message: datamessage,
        title: datatitle,
          success: {
            label: "确定",
            className: "green",
            callback: function() {
                url_confirm(datatips.attr('data-url'));
            }
          },
          danger: {
            label: "取消",
            className: "red",
            callback: function() {
              
            }
          }
        }
    });

    function url_confirm(url) {
        $.ajax({
            url: url,
            method: 'get',
            dataType: 'json',
            success: function (data) {
                if (data.code == 200) {
                    window.location.reload();
                } else {
                    alert(data.msg);
                }
            },
            error: function () {
                alert("error");
            }
        });
    }
}

/**
 * send ajax with post
 * @param url string
 * @param parameters {}
 * @param success callback function(jsondata)
 * @param error callback function()
 */
function remove_item(url, parameters, success, error) {
    bootbox.dialog({
        message: "确定删除",
        title: "确定",
        buttons: {
            success: {
                label: "确定",
                className: "green",
                callback: function() {
                    dialog_success_callback();
                }
            },
            danger: {
                label: "取消",
                className: "red",
                callback: function() {

                }
            }
        }
    });

    function dialog_success_callback() {
        $.ajax({
            url:url,
            data:parameters,
            method:'POST',
            dataType:'json',
            success:function(data) {
                if (success) {
                    success(data);
                } else {
                    if(data === true) {
                        window.location.reload();
                    } else {
                        alert("删除失败");
                    }
                }
            },
            error:function() {
                if (error) {
                    error();
                } else {
                    alert("请求失败");
                }
            }
        });
    }
}

/**
 * remove_item_with_comfirm_dialog
 */
function remove_item_with_comfirm_dialog(that) {
    var url = $(that).data('url');
    var id = $(that).data('id');
    remove_item(url, {id: id});
}

function send_ajax_and_reload_window(that) {
    var url = $(that).data('url');
    var id = $(that).data('id');
    var parameters = {id:id};
    $.ajax({
        url:url,
        data:parameters,
        method:'POST',
        dataType:'json',
        success:function(data) {
            if(data === true) {
                window.location.reload();
            } else {
                alert("请求失败");
            }
        },
        error:function() {
            alert("请求失败");
        }
    });
}

//copy val
function valquote_con(id,quoteid){
    $('#'+id).val($('#'+quoteid).val());
}