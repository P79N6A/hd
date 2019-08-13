    //请求接口获取配置文件  
    if(window.testData){  //存在测试的数据，传入测试的数据
       transmitPlayer(window.testData);
       // alert(1);
    }else{ //不存在测试的数据，请求正式接口
      $.ajax({
        //url: "http://api.cms.cztv.com/mms/out/video/playJson?id=67506&domain=www.letv.com&splatid=111&platid=1002&pt=3&at=1", //请求地址    
        //url: "http://api.cms.cztv.com/mms/out/video/playJson?id="+__INFO__.vid+"&platid=111&splatid=1002&format=1&tkey="+new Date().valueOf()+"&pt=4&at=1&domain=tv.cztv.com", //请求地址 
        url: "http://api.cms.cztv.com/mms/out/video/playJson?id=" + __INFO__.vid + "&platid=111&splatid=1002&pt=1&at=1&domain=tv.cztv.com", //请求地址 
        type: "GET",
        data: "",
        dataType: "jsonp",
        success: function(d) {  
          transmitPlayer(d);
        },
        error: function(e) {
          console.log("playJson failed： " + e);
        }
      });
    }

    /**
     * [description] 处理传入的json数据给播放器
     * @param  {[type]} data [description] json数据
     * @return {[type]}      [description]
     */
    function transmitPlayer(data){
      var conf = formatConf(data); //默认播放的视频为第一项第一项码率的资源    
        // 配置线路
        console.log(conf);
        // alert(conf.lineArr.length);
        for (var i = 0; i < conf.lineArr.length; i++) {
          conf.lineArr[i].lineName = '线路' + (i + 1); //配置对应索引的线路名称
        }
        //播放器标题内容写入改到了这里
        //document.querySelector('#playTil').innerText = conf.title; 
        /**
         *  初始化播放器对象
         *
         * @param1 playBox 播放器载入的外层元素id
         * @param2 conf 配置播放器流的相关信息
         * @return player 
         */
        var h5Player = new H5Player({
          "conf": conf,
          "sizeRatio": "16:9", //播放器宽高比 默认不配置为4:3
          "rate": "", //配置默认清晰度  
          "alertDown": false //是否每五分钟提示下载手机客户端  true：提示  false：不提示 
        }, "playerWrap");
        /****************************** 新增新的防盗链规则 ************************/
        //return params video id 入参广告id（默认5808）   新增防盗链规则在play前添加
        // h5Player.addRule("newRule",function(){   //新增防盗链规则
        //  return "k=33333333333&t=222222222222222222";
        // });    
        /****************************** 新增新的防盗链规则 ************************/
        // h5Player.play(10099);
        h5Player.play(10178);
        //当前视频播放或后贴广告结束时执行下一集的操作   
        h5Player.end(function(nextvid) {
          //nextvid:下一集视频的id 若为空则不存在下一集id
          //播放器已结束播放 
          //页面跳转到下一集播放
          if (nextvid) { //存在id跳转 否则回到初始播放状态
            var nowUrl = window.location.href;
            var nexstUrl = nowUrl.replace(/\d+\.html/, nextvid + '.html');
            location.href = nexstUrl;
          }
        }); 
    }  


    /**
     * [formatConf description] 格式化配置文件
     * @return {[type]} [description] 返回格式化后的配置文件 
     * example
     * {
        "lineArr": [{
          "lineName": "fastweb",
          "venderName": "fastweb",
          "codeRate": [{
            "rate": "800",
            "src": "http://v3.cztv.com/cztv/vod/2016/06/28/7fdc15ddc3fc4e8690d2a92f99457938/h264_450k_mp4.mp4"
          }, {
            "rate": "1200",
            "src": "http://v3.cztv.com/cztv/vod/2016/06/28/7fdc15ddc3fc4e8690d2a92f99457938/h264_800k_mp4.mp4"
          }, {
            "rate": "1500",
            "src": "http://v3.cztv.com/cztv/vod/2016/06/28/7fdc15ddc3fc4e8690d2a92f99457938/h264_1500k_mp4.mp4"
          }]
        }],
        "title": "《致青春2》主题曲MV：吴亦凡霸气壁咚强吻刘亦菲",
        "vid": "199947",
        "currentTime": 1469758689,
        "channel_image": "http://i05.cztv.com/cztv/vms/2016/06/29/52de5544070644e6ab156dc931103e93/8_640_400.jpg",
        "mount_name": "lantian",
        "skip": [5, 20],
        "danmu": "1",
        "trylook": "免费",
        "nextvid": "200446"
      }
     */
    function formatConf(conf) {  
      var formatJson = {}; 
      formatJson.status = conf.playstatus.status; 
      if(formatJson.status == 0){  //禁止播放
        formatJson.flag = convertFlag(conf.playstatus.flag);
        formatJson.lineArr = [];
        formatJson.title = "";
      }else{  //可以播放
        var playurl = conf.playurl;   
        var dispatch = playurl.dispatch;  
        var url =  dispatch.url;
        var weight = []; //排序权重数组
        var lineArr = []; //线路数组
        for(var attr in dispatch[0].weight){
          weight.push({
            "name":attr,
            "val":dispatch[0].weight[attr]
          });
        }
        weight = sortWeight(weight);  
        for(var i = 0; i<weight.length; i++){  //生成线路数据
          lineArr.push({
            "lineName":weight[i].name,
            "venderName":weight[i].name,
            "codeRate":[] 
          });
        }
        dispatch.sort(function(a, b) {  //对dispatch排序
          return a.vtype * 1 - b.vtype * 1;
        });
        for (var i = 0; i < dispatch.length; i++) {  //对比url中的线路，生成线路的播放资源地址
          var url = dispatch[i].url;
          for (var j = 0; j < url.length; j++) {
            for (var k = 0; k < lineArr.length; k++) {
              if(!!url[j][lineArr[k].lineName]){
                lineArr[k].codeRate.push({
                  "rate" : dispatch[i].vtype,
                  "src" : url[j][lineArr[k].lineName]
                });
              }
            }
          }
        }
   
        function sortWeight(arr){  
          function compareDate(val1,val2){
                  if(parseInt(val1.val)<parseInt(val2.val)){
                      return 1;
                  }else{
                      return -1;
                  }
              } 
              arr.sort(compareDate);
              return arr;
        }
        //注释的字段先暂时预留
        formatJson = {
          "lineArr": lineArr,  //码率数据集合
          "title": playurl.title,
          "vid":playurl.vid,
          "currentTime": Math.floor(new Date().getTime() / 1000),
          "channel_image": playurl.pic, //视频背景图片
          "mount_name": "lantian",
          "skip": [], //跳过片头片尾 
          "danmu": conf.danmu, //是否开启弹幕 
          "trylook":convertTryLook(conf.trylook), //试看标识
          "duration":playurl.duration, //视频总时长
          "nextvid": playurl.nextvid || ""//下一个视频id 
          // "watermark":"", //活水印
          // "hot":"", //剧情看点
          // "watermark":"",//活水印
          // "downLoad":playurl.downLoad,//下载平台
          // "playplatform":"",//独播平台
          // "firstlook":conf.firstlook,//移动抢先看
          // "stime":""//系统时间戳秒
        };  
      } 
      return formatJson;
    }
    //装换试看标识 生成试看标识字段
    function convertTryLook(trylook){
      var str = "";
      switch(trylook){
        case 0:
          str = "免费";
          break;
        case 3:
          str = "包月";
          break;
        case 4:
          str = "点播";
          break;
        case 5:
          str = "点播且包月";
          break;
      }
      return str;
    }
    //转换flag 生成禁播提示语  提示语可配置
    function convertFlag(flag){ 
      var str = ""; 
      switch(flag){
        case 0:
          str = "亲，视频下线或者未上线呢";
          break;
        case 1:
          str = "亲，版权屏蔽了，换个看吧~";
          break;
        case 2:
          str = "亲，版权到期，换个看吧~";
          break;
        case 3:
          str = "改视频加入网站黑名单，换个看吧~";
          break;
        case 4:
          str = "视频不存在，换个试试吧~";
          break;
        case 5:
          str = "视频被白名单禁播了，看个别的吧~";
          break;

      }
      return str;
    }