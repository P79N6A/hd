<?php

/**
 * 接名牌控制器
 * @author JasonFang
 *
 */
class GetbrandController extends \PublishBaseController {

	private $user_key;     //生成用户唯一ID
	private $user_info;     //生成用户唯一ID
	/**
	 * 初始化数据
	 *
	 */
	public function initialize(){
		
		$uid = Request::getQuery('client_id');
		
		$this->user_key = md5('brand_' . $uid . date('Ymd'));  //生成用户唯一ID，一个用户一天一个KEY
		$this->user_info = md5('user_info_' . $uid . date('Ymd'));  //生成用户唯一ID，一个用户一天一个KEY
		
	}
	
	
	/**
	 * 游戏首页
	 * 
	 */
	public function indexAction() {
		//简单验证是不是APP端
	    $input = Request::getQuery();
        $keeps = ['client_id', 'timestamp', 'type', 'signature'];
        if(!issets($input, $keeps)) {
        	Header("Location: http://hd.cztv.com/appstore/zgltv-cztv");
        	exit; 
        }
		
		$res = RedisIO::get($this->user_key);  //获取当前用户数据
		$res = json_decode($res,true);         //redis只能存json		
		if($res['extra_value']) {              //已玩过直接进入结果页面
			View::setVar('done',$res['extra_value']);	
		}else{
			View::setVar('done',0);
			
		}
	}
	
	
	/**
	 * 开发用删除redis
	 * 
	 */
	public function delAction(){
		$admin = $_GET['passwrd'];
		if($admin == '18968111180'){
			if(RedisIO::del($this->user_key) && RedisIO::del($this->user_info)){
				echo "删除成功";
			}else{
				echo "失败";
			}
		}
	}
	
	
	/**
	 * 获取用户游戏轨迹
	 */
	public function getGameAction(){
		$data = RedisIO::get($this->user_key);
		$queue_start = RedisIO::get('queue_end');
		$queue_end = RedisIO::get('queue_end');		
		echo "用户信息：$data<br>";
		echo "插入队列时间：$queue_start<br>";
		echo "处理队列时间：$queue_end<br>";
	}
	
/**
	 * 获取场次ID
	 */
	protected function getlottery(){
		$key = "brand_" . date(Ymd);
		$res = MemcacheIO::get($key);
		if(!$res){
			$parameters = array();
			$parameters['conditions'] = "name='".$key."'";
			$res = Lotteries::findFirst($parameters);			
			$res = $res->toArray();
			$res = json_encode($res);
			MemcacheIO::set($key, $res, 600);    //存入redis
		}
		$res = json_decode($res,true);
		return $res;

	}
	


}