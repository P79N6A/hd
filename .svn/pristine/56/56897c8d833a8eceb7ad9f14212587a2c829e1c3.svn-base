var old = 1;
var bushu = 0;
	$(document).ready(function(){
		
		fenxiang('点击一下赢跑男同款，你来决定谁是人气王！','跑男7只谁的人气最高？加入跑男助跑团，贡献步数赢明星同款 ','点击赢跑男同款，你来决定谁是人气王！',fenxiang_url);
		init()
		$('.list').hide();
	
		
	});

	function init(){
								w=$(window).width();//获取浏览器宽
								h=$(window).height();//获取浏览器高
								bl=h/w
								//alert(bl)
								w1=w/750;//宽的比例
								h1=h/1334;//高的比例
								wxx=(w-750)/2;//X坐标位置
								wy=(h-1334)/2; //y坐标位置
								//判断比例
								if(w1<h1){
										w0=h1;
										h0=w1;
									}else{
										w0=w1;
										h0=h1;
								}			
								$('.codebgt').css('transform','scale('+w0+')').css('left',wxx).css('top',wy);
								$('.left_ul,.right_ul,.zp,.list_ul').css('top',w/(750/483))
								$('.shuzi').css('width',(w*0.8*0.9)-25-35-60-35)
								$('.list_shuzi').css('width',(w*0.8)-85)
								$('.list_but').css('top',(w/(750/483)) + $('.list_ul').height() + 45)
								$('.sm').css('top',(w/(750/483)) + $('.list_ul').height() + 125)
								$('.img1').css('height',w*0.9)

						
	}	

var params = {
		'title': '跑男7只谁的人气最高？加入跑男助跑团，贡献步数赢明星同款',
		'content': '跑男投票--中国蓝TV',
		'url': fenxiang_url,
		'img': 'http://yun-cztv.oss-cn-hangzhou.aliyuncs.com/static/zgltv-images/runman/fx.jpg'
	};
var cztv = new CZTVJsObject();

$('.list_but').on("click", function() {
	if(ua.wechat){
	  // $(".fx2").fadeIn(500);
	  // $('video').fadeOut(350)
	  $('.fx').fadeIn(500)
	  
	}else{
		//$(".fx2").fadeIn(500);
	   //$('video').fadeOut(350)
		cztv.share(params);
		//	alert(2)
	}
});



var leftok=1;
$('.left_li').click(function(){
	
	if(leftok==1){
		
			leftok++;
			var d = $(this).attr('name')	
			$('.right_ul').fadeOut(500)
			$('.right_ul'+d).fadeIn(500)
			$('.left_ul li span').removeClass('bingo')
			$(this).find('span').addClass('bingo')
			setTimeout(function(){
				
				leftok=1;
				
			},500);
		//return false
		
	}

})


$('.litwo_input').click(function(){

	//alert(old)
	var number = parseInt($(this).parent().find('span').html());
	var spann = $(this).parent().find('span');
	
	
	//alert(bushu)
	var li_id = $(this).parent().attr('id');
		
	if( old == 1 ){
		$.getJSON(
				'http://pyun.cztv.com/hfive/vote?id='+li_id+'&callback=?',
				//'https://pyun.cztv.com:911/hfive/vote?id=15&_token=0&callback=?',
														
				function(msg){
					
					number+=10;
					spann.html(number)
					$('.content').fadeOut(500)
					$('.zp').fadeIn(500)
					
				}
								
		)
		
		
	}/*else if(old == 2){
		$.getJSON(
				'https://pyun.cztv.com:911/hfive/vote?id=15&callback=?',
				//'https://pyun.cztv.com:911/hfive/vote?id=15&_token=0&callback=?',
														
				function(msg){
					
					number+=10;
					spann.html(number)
					$('.content').fadeOut(500)
					$('.list').animate({'opacity':'1'},500)
					$('.list').show();
					
				}
								
		)
		
	}*/else if(old == 3){
		$.getJSON(
				'http://pyun.cztv.com/hfive/vote?id='+li_id+'&_token='+bushu+'&callback=?',
				//'https://pyun.cztv.com:911/hfive/vote?id=15&_token=0&callback=?',
														
				function(msg){
					last()
					number+=50;
					spann.html(number)
					$('.content').fadeOut(500)
					$('.list').animate({'opacity':'1'},500)
					$('.list').show();
					
				}
								
		)
		
	}else if(old == 4){
		$.getJSON(
				'http://pyun.cztv.com/hfive/vote?id='+li_id+'&_token='+bushu+'&callback=?',
				//'https://pyun.cztv.com:911/hfive/vote?id=15&_token=0&callback=?',
														
				function(msg){
					last()
					number+=100;
					spann.html(number)
					$('.content').fadeOut(500)
					$('.list').animate({'opacity':'1'},500)
					$('.list').show();
					
				}
								
		)

	}
	
	
	

})


function last(){
	
	$.getJSON(
				'http://pyun.cztv.com/hfive/getguest?&callback=?',
					
				function(msg){
					var len = msg.data.length;
					//alert(len)
					for(i=0;i<len;i++){
						if(i<1){	

							//$('.list_ul').append("<li class='right_lione list_li"+i+"'><span class='list_num'>"+i+1+"<img src='images/hg.png'></img></span><img src='"+msg.data[i].guest_img+"' class='list_tx'></img><span class='list_shuzi'>"+msg.data[i].guest_step+"<span>步</span><img src='images/jiao.png'></img></span></li>")
							$('.list_li0').find('.list_tx').attr('src',msg.data[i].guest_img)
							$('.list_li0').find('.number').html(msg.data[i].guest_step)
						
						}else{
							
							//$('.list_ul').append("<li class='right_lione list_li"+i+"'><span class='list_num'>"+i+1+"</span><img src='"+msg.data[i].guest_img+"' class='list_tx'></img><span class='list_shuzi'>"+msg.data[i].guest_step+"<span>步</span><img src='images/jiao.png'></img></span></li>")
							$('.list_li'+i).find('.list_tx').attr('src',msg.data[i].guest_img)
							$('.list_li'+i).find('.number').html(msg.data[i].guest_step)
							
						}
	
						
					}
					
				}
								
	)
	
}



function bingo(a){
	
		$('.img10').attr('src','http://yun-cztv.oss-cn-hangzhou.aliyuncs.com/static/zgltv-images/runman/yes.png')
		$('.fc h1').delay(600).animate({'opacity':'1'},500)
		$('.fc_but').delay(600).animate({'opacity':'1'},500)
		$('.fc h1 span').html(a);
		
		$('.close').click(function(){
			
			$('.fc').fadeOut(500)
			$('.zp').fadeOut(500)
			$('.BingoContent').fadeIn(500);
		
		})
		
}

function nr(){	
           var Symbol_4=$('.img1');
         
           var rotateFunc = function(awards,angle,text){  //awards:奖项，angle:奖项对应的角度
         
                  		Symbol_4.rotate({         		
                  			angle:0, 
                  			duration: 5000, 
                  			animateTo: angle+360, //angle是图片上各奖项对应的角度，1440是我要让指针旋转4圈。所以最后的结束的角度就是这样子^^
                  			callback:function(){
							//alert(text)
																$('.img10').animate({'top':'25%'},750)
																$('.close').delay(500).animate({'opacity':'1'},500)
															
																$('.fc').fadeIn(500)
																	
																
																
																
         														if(awards == 1){
																	//old=2;
																	//$('.fc').fadeIn(500)
																    bingo(text)
																	
																	
																	
																	
																}else if(awards == 2){
																//old=3;
																	//$('.fc').fadeIn(500)
																	//old=2;
																	$('.img10').attr('src','http://yun-cztv.oss-cn-hangzhou.aliyuncs.com/static/zgltv-images/runman/no.png')
																	$('.fc_but').hide();

																	$('.fc').click(function(){
																		last()
																		$('.fc').fadeOut(500)
																		$('.zp').fadeOut(500)
																		$('.list').animate({'opacity':'1'},500)
																		$('.list').show();
																	
																	})
																	
																}else if(awards == 3){
																
																	//$('.fc').fadeIn(500)
																	
																	bingo(text)
																	
																}else if(awards == 4){
																	old=4;
																	$('.img10').attr('src','http://yun-cztv.oss-cn-hangzhou.aliyuncs.com/static/zgltv-images/runman/100.png')
																	$('.fc_but').hide();

																	$('.fc').click(function(){
																		
																		$('.fc').fadeOut(500)
																		$('.zp').fadeOut(500)
																		$('.content').fadeIn(500)
																	
																	})
																	
																}else if(awards == 5){
																	bingo(text)
																	
																}else if(awards == 6){
																	
																	old=3;
																	
																	$('.img10').attr('src','http://yun-cztv.oss-cn-hangzhou.aliyuncs.com/static/zgltv-images/runman/50.png')
																	$('.fc_but').hide();

																	$('.fc').click(function(){
																		$('.fc').fadeOut(500)
																		$('.zp').fadeOut(500)
																		$('.content').fadeIn(500)
																	
																	})
																
																
																}else if(awards == 7){
																
																	bingo(text)
																
																}else if(awards == 8){
																
																	bingo(text)
																}
																
																	
																	
                  			}
                  		}); 
                  	};


    
							$.getJSON(
									'http://pyun.cztv.com/hfive/win&callback=?',
																			
									function(obj){
										
										bushu = obj.token;
										if( obj.code == -2){
											
											alert('抽奖结束')
											
										}else if( obj.code ==-1){
											
											alert('抽奖次数耗尽')
											
										}else if( obj.code ==0){ 

											rotateFunc(2,67.5,'再投1票')
											
										}else if( obj.code ==1){

											if(obj.thumb==1){
												rotateFunc(7,292.5,'iphone6s')
											}else if(obj.thumb==2){
												rotateFunc(5,202.5,'手机')
											}else if(obj.thumb==3){
												rotateFunc(3,112.5,'跑男同款T恤')
											}else if(obj.thumb==4){
												rotateFunc(1,22.5,'跑男同款眼罩')
											}else if(obj.thumb==5){
												rotateFunc(8,337.5,'流量包')
											}else if(obj.thumb==6){
												rotateFunc(4,157.5,'再投10票')
											}else if(obj.thumb==7){
												rotateFunc(6,247.5,'再投5票')
											}
										
										}
									}
													
							)

						/*	
         				    var length=3200;
							var data = []; //返回的数组
							
                  		 	for(i=1;i<=length;i++){
                  				data.push(i);
                  			}
                  
                  
                           					data = data[Math.floor(Math.random()*data.length)];
											
                           					if(data<=400&&data>=0){
                  
                  
                           						rotateFunc(1,22.5,'跑男同款眼罩')
                           					}if(data<=800&&data>=400){
                  
                  
                           						rotateFunc(2,67.5,'再投1票')
                           					}
                           					if(data<=1200&&data>800){
											
											
                           						rotateFunc(3,112.5,'跑男同款T恤')
                  
                           					}
											if(data<=1600&&data>1200){
											
											
                           						rotateFunc(4,157.5,'再投10票')
                  
                           					}
											if(data<=2000&&data>1600){
											
											
                           						rotateFunc(5,202.5,'手机')
                  
                           					}
											if(data<=2400&&data>2000){
											
											
                           						rotateFunc(6,247.5,'再投5票')
                  
                           					}
											if(data<=2800&&data>2400){
											
											
                           						rotateFunc(7,292.5,'iphone6s')
                  
                           					}
											if(data<=3200&&data>2800){
											
											
                           						rotateFunc(8,337.5,'流量包')
                  
                           					}

		*/


         };
var keyi=1;
$('.img2').click(function(){
	//alert(2)
							
	
	if(keyi==1){
		keyi++;
		nr();
		setTimeout(function(){
			
			keyi=1;
			
		},5000)
	}
		
	
})
      


$('.fx').click(function(){

	$('.fx').fadeOut(500)
	
})

$('.fx').bind('touchmove',function(e){
				//alert(1)
					e.preventDefault();

				})
				
$('.fc').bind('touchmove',function(e){
//alert(1)
	e.preventDefault();

})

$('.bingobut').click(function(){
	

	if($('.bingoinput1').val()==''){
		
		alert('请输入姓名')
		
	}else if($('.bingoinput2').val()==''){
		
		alert('请输入联系方式')

	}else if($('.bingoinput3').val()==''){
		
		alert('请输入住址')
		
	}else{
			
		var tel = $('.bingoinput2').val();
	    var reg = /^0?1[3|4|5|8][0-9]\d{8}$/;
	    if (reg.test(tel)) {
			

			
			$.ajax({
				 type: "POST",
				 url: "http://pyun.cztv.com/hfive/contacts&callback=?",
				 data: "_token="+bushu+"&mobile="+$('.bingoinput2').val()+"&address="+$('.bingoinput3').val()+"&real_name="+$('.bingoinput1').val(),
				 dataType: "json",
				 success: function(msg){
					 
							 alert('信息已发送')
							 last()
							$('.BingoContent').fadeOut(500)
							$('.list').animate({'opacity':'1'},500)
							$('.list').show();
				
						  },
				 failed: function(msg){
					 
					 alert(msg.data)
								
						  }
									  
			 });			 
	    }else{
		  alert("号码有误!");
		};
		
		
		
	}
	
	
})

$('.fc_but').click(function(){
	
			$('.fc').fadeOut(500)
			$('.zp').fadeOut(500)
			$('.BingoContent').fadeIn(500);
	
})