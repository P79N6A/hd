var a_url="/regions/default";
var Address = function (obj,opt) {
    var obj = obj;
    var opt = opt;
    if (typeof(obj) !== 'object') obj = $("#address_1");
    if (typeof(opt) !== 'object') opt = { };
    var defopt = {
        province:       0,
        city:           0,
        country:        0,
        village:        0,
        town:           0,
        province_name:  'province_id',
        city_name:      'city_id',
        country_name:   'county_id',
        village_name:   'village_id',
        town_name:      'town_id',
    }

    var setopt = function(){
        for(var name in defopt)
        {
            if(!opt.hasOwnProperty(name)){
                opt[name] = defopt[name];
            }
        }
    }


    var inint = function(){
        setopt();
        inintElement();
        Address1();
        Address2();
        Address3();
    }

    var inintElement = function(){
        obj.append(
            '<div class="a_modal" id="a_modal">' +
            '<div class="a_head" id="a_head">' +
            '<a href="javascript:void(0);" id="a_province" class="active_2">省</a>'+
            '<a href="javascript:void(0);" id="a_city" class="active_1">市</a>'+
            '<a href="javascript:void(0);" id="a_district" class="active_1">区</a>'+
            '<a href="javascript:void(0);" id="a_street" class="active_1">街道</a>'+
            '<a href="javascript:void(0);" id="a_village" class="active_1">村</a>'+
            '</div>'+
            '<div class="a_content">' +
            '<div class="city_content" id="city_province" style="display: block"></div>'+
            '<div class="city_content" id="city_city"></div>'+
            '<div class="city_content" id="city_district"></div>'+
            '<div class="city_content" id="city_street"></div>'+
            '<div class="city_content" id="city_village"></div>'+
            '</div>'+
            '<input type="hidden" class="hide_province" name="'+opt.province_name+'" id="'+opt.province_name+'" value="'+opt.province+'" />'+
            '<input type="hidden" class="hide_city" name="'+opt.city_name+'" id="'+opt.city_name+'" value="'+opt.city+'" />'+
            '<input type="hidden" class="hide_country" name="'+opt.country_name+'" id="'+opt.country_name+'" value="'+opt.country+'" />'+
            '<input type="hidden" class="hide_village" name="'+opt.village_name+'" id="'+opt.village_name+'" value="'+opt.village+'" />'+
            '<input type="hidden" class="hide_town" name="'+opt.town_name+'" id="'+opt.town_name+'" value="'+opt.town+'" />'+
            '</div>'
        )
    }

    // $("#address_1")


    var Address1 =function(){
        $(".a_input").bind("click",function(){
            $(".a_modal").fadeToggle(500);
        });
        $(".a_head a").bind("click",function(){
            $(".a_head a").removeClass("active_2").addClass("active_1");
            $(this).removeClass("active_1").addClass("active_2");
            var ind=$(this).index();
            $(".a_content div:eq("+ind+")").show();
            $(".a_content div:not(:eq("+ind+"))").hide();
        });
        $.post(a_url, { id: 1 },
            function(data){
                var l=data.length;
                var myobj=eval(data);
                for(var i=0;i<myobj.length;i++){
                    $("#city_province").append('<a attr-id="'+myobj[i].id+'" href="javascript:void(0);" class="citys">'+myobj[i].name+'</a>');
                }
            }, "json");
    }
    var Address2=function(){
        $("#city_province").on("click",".citys",function(){
            $("#city_city,#city_district,#city_street,#city_village").empty();
            $(this).parent().fadeOut(0);
            $("#a_province").removeClass("active_2").addClass("active_1");
            $("#a_city").removeClass("active_1").addClass("active_2")
            $("#city_city").fadeIn(0);
            var ai=$(this).attr("attr-id");
            $.post(a_url, { id: ai },
                function(data){
                    var l=data.length;
                    var myobj=eval(data);
                    for(var i=0;i<myobj.length;i++){
                        $("#city_city").append('<a attr-id="'+myobj[i].id+'" href="javascript:void(0);" class="citys">'+myobj[i].name+'</a>');
                    }
                }, "json");
            window.text1=$(this).text();
            $(".a_input").val(text1);
            var id=$(this).attr("attr-id");
            fixRegionValue(id,1)
            if ($("#city_province").children().is("a")==false){
                $("#city_province").append(
                    '<span style="color: red;">此数据为空！</span>'
                );
            }
        });

        $("#city_city").on("click",".citys",function(){
            $("#city_district,#city_street,#city_village").empty();
            $(this).parent().fadeOut(50);
            $("#a_city").removeClass("active_2").addClass("active_1");
            $("#a_district").removeClass("active_1").addClass("active_2")
            $("#city_district").fadeIn(50);
            var ai=$(this).attr("attr-id");
            $.post(a_url, { id: ai },
                function(data){
                    var l=data.length;
                    var myobj=eval(data);
                    for(var i=0;i<myobj.length;i++){
                        $("#city_district").append('<a attr-id="'+myobj[i].id+'" href="javascript:void(0);" class="citys">'+myobj[i].name+'</a>');
                    }
                }, "json");
            window.text2=$(this).text();
            $(".a_input").val(text1+" "+text2);

            var id=$(this).attr("attr-id");
            fixRegionValue(id,2);
            if ($("#city_city").children().is("a")==false){
                $("#city_city").append(
                    '<span style="color: red;">此数据为空！</span>'
                );
            }
        });

        $("#city_district").on("click",".citys",function(){
            $("#city_street,#city_village").empty();
            $(this).parent().fadeOut(50);
            $("#a_district").removeClass("active_2").addClass("active_1");
            $("#a_street").removeClass("active_1").addClass("active_2")
            $("#city_street").fadeIn(50);
            var ai=$(this).attr("attr-id");
            $.post(a_url, { id: ai },
                function(data){
                    var l=data.length;
                    var myobj=eval(data);
                    for(var i=0;i<myobj.length;i++){
                        $("#city_street").append('<a attr-id="'+myobj[i].id+'" href="javascript:void(0);" class="citys">'+myobj[i].name+'</a>');
                    }
                }, "json");
            window.text3=$(this).text();
            $(".a_input").val(text1+" "+text2+" "+text3);
            var id=$(this).attr("attr-id");
            fixRegionValue(id,3);
            if ($("#city_district").children().is("a")==false){
                $("#city_district").append(
                    '<span style="color: red;">此数据为空！</span>'
                );
            }
        });

        $("#city_street").on("click",".citys",function(){
            $("#city_village").empty();
            $(this).parent().fadeOut(50);
            $("#a_street").removeClass("active_2").addClass("active_1");
            $("#a_village").removeClass("active_1").addClass("active_2")
            $("#city_village").fadeIn(50);
            var ai=$(this).attr("attr-id");
            $.post(a_url, { id: ai },
                function(data){
                    var l=data.length;
                    var myobj=eval(data);
                    for(var i=0;i<myobj.length;i++){
                        $("#city_village").append('<a attr-id="'+myobj[i].id+'" href="javascript:void(0);" class="citys">'+myobj[i].name+'</a>');
                    }
                }, "json");
            window.text4=$(this).text();
            var id=$(this).attr("attr-id");
            $(".a_input").val(text1+text2+text3+text4);
            fixRegionValue(id,4);


            if ($("#city_street").children().is("a")==false){
                $("#city_street").append(
                    '<span style="color: red;">此数据为空！</span>'
                );
            }
        });
    }
    var Address3=function(){
        $("#city_village").on("click",".citys",function(){
            $(".a_modal").hide();
            window.text5=$(this).text();
            $(".a_input").val(text1+" "+text2+" "+text3+" "+text4+" "+text5);
            var id=$(this).attr("attr-id");
            fixRegionValue(id,5);
            if ($("#city_village").children().is("a")==false){
                $("#city_village").append(
                    '<span style="color: red;">此数据为空！</span>'
                );
            }
        })
        //$(".city_content a").click(function(){
        //    alert(1);
        //});
        $(document).bind('click',function(e){
            var e = e || window.event; //浏览器兼容性
            var elem = e.target || e.srcElement;
            while (elem) { //循环判断至跟节点，防止点击的是div子元素
                if (elem.id && elem.id=='a_input'||elem.id && elem.id=='a_modal') {
                    return;
                }
                elem = elem.parentNode;
            }

            $('#a_modal').fadeOut(200); //点击的不是div或其子元素
        });
    }

    var fixRegionValue = function(val,i){
        var sels = [opt.province_name,opt.city_name,opt.country_name,opt.town_name,opt.village_name];
        $("#"+sels[i-1]).val(val);
        for(;i<sels.length;i++)
        {
            $("#"+sels[i]).val(0);
        }
    }




    inint();
};