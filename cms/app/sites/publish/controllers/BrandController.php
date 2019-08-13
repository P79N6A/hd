<?php
/**
 * Created by PhpStorm.
 * User: Bw
 * Date: 16/4/29
 * Time: 下午5:19
 */
define('QUEUE_ROOT', APP_PATH.'libraries/CZTVQueue/');


require_once(QUEUE_ROOT.'AliyunMNS/mns-autoloader.php');

use AliyunMNS\Client;
use AliyunMNS\Requests\SendMessageRequest;
use AliyunMNS\Requests\CreateQueueRequest;
use AliyunMNS\Exception\MnsException;


class AliyunMNS
{
	private $accessId;
	private $accessKey;
	private $endPoint;
	private $client;

	public function __construct($accessId, $accessKey, $endPoint)
	{
		$this->accessId = $accessId;
		$this->accessKey = $accessKey;
		$this->endPoint = $endPoint;
		$this->client = new Client($this->endPoint, $this->accessId, $this->accessKey);
	}

	/**
	 * 创建队列
	 * @param $queueName
	 * @return \AliyunMNS\Queue|void
	 */
	public function createQueue($queueName)
	{
		$request = new CreateQueueRequest($queueName);
		try {
			$res = $this->client->createQueue($request);
		} catch (MnsException $e) {
			return false;
		}
		return $res;
	}

	/**
	 * 查看队列
	 * @return \AliyunMNS\Responses\ListQueueResponse|void
	 */
	public function viewQueue()
	{
		$request = new \AliyunMNS\Requests\ListQueueRequest();
		try {
			$res = $this->client->listQueue($request);
		} catch (MnsException $e) {
			return false;
		}
		return $res;
	}

	/**
	 * 删除指定队列
	 * @param $queueName
	 * @return bool|void
	 */
	public function delQueue($queueName)
	{
		try {
			$this->client->deleteQueue($queueName);
		} catch (MnsException $e) {
			return false;
		}
		return true;
	}

	/**
	 * 发送消息
	 * @param $queueName
	 * @return \AliyunMNS\Responses\SendMessageResponse|void
	 */
	public function sendMessage($queueName, $messageBody)
	{
		$queue = $this->client->getQueueRef($queueName);
		$bodyMD5 = md5(base64_encode($messageBody));
		$request = new SendMessageRequest($messageBody);
		try {
			$res = $queue->sendMessage($request);
		} catch (MnsException $e) {
			return false;
		}
		return $res;
	}

	/**
	 * @param $queueName 队列名称
	 * @param bool|true $flag 接收消息后是否删除该消息 默认删除
	 * @return \AliyunMNS\Responses\ReceiveMessageResponse|bool
	 */
	public function receiveMessage($queueName, $flag = true)
	{
		$queue = $this->client->getQueueRef($queueName);
		$receiptHandle = NULL;
		try {
			$res = $queue->receiveMessage();
		} catch (MnsException $e) {
			return false;
		}
		if ($flag) {
			try {
				$res = $queue->deleteMessage($receiptHandle);
			} catch (MnsException $e) {
				return false;
			}
		}
		return $res;
	}

	/**
	 * @param $queueName    队列名称
	 * @param $message
	 * @return \AliyunMNS\Responses\ReceiveMessageResponse|bool
	 */
	public function deleteMessage($queueName, $response)
	{
		$queue = $this->client->getQueueRef($queueName);
		try {
			$res = $queue->deleteMessage($response->getReceiptHandle());
		} catch (MnsException $e) {
			return false;
		}
		return $res;
	}
}
/**
 * 验证控制器
 * @author fang
 *
 */
class BrandController extends LotteryController {
	
	private $user_key;     //生成用户唯一ID
	private $user_info;     //生成用户唯一ID
	
	/**
	 * 初始化数据
	 * 
	 */
	public function initialize(){
		$this->checkSignatureTv() ;
		$uid = Request::getQuery('client_id');                 //接收参数	
		$this->user_key = md5('brand_' . $uid . date('Ymd'));  //生成用户唯一ID，一个用户一天一个KEY	
		$this->user_info = md5('user_info_' . $uid . date('Ymd'));  //生成用户信息插入唯一ID，一个用户一天一个KEY			
	}
		

	/**
	 * 记录成绩测试专用
	 */
	public function addFenShuAction(){	

		$res = RedisIO::get($this->user_key);  //获取当前用户数据
		$exist = MemcacheIO::get($this->user_key);
		if(!empty($res) || $exist){
			echo "今天你已经玩过了！";
			exit;
		}else {
			MemcacheIO::set($this->user_key, true, 10);
		}
		
		$data = Request::getPost('');	
	
		if($data){

			$accessKeyId = 'LTAIcdYW4QQTMuQz';
			$accessKeySecret = '41Q7wtBHBZQBmPWiz0l5fGk8wBMGIL';
			$endPoint = "http://1033683697196472.mns.cn-hangzhou-internal-vpc.aliyuncs.com";
			$mns = new AliyunMNS($accessKeyId,$accessKeySecret,$endPoint);
			
			
			$queueName = 'xlwMNS';
			
			
			$lottery = $this->getlottery();   //获取活动相关参数返回数据
		
			
			$lotterywinning  = array(
				'channel_id'=>3,
				'prize_id'=>5,
				'prize_name'=>'积分',	
				'prize_level'=>1,
				'prize_is_real'=>1,
				'lottery_id'=>$lottery['id'],
				'lottery_group_id'=>$lottery['group_id'],
				'lottery_channel_id'=>1,
				'client_id'=>$data['client_id'],
				'extra_value'=>$data['extra_value'],
				'created_at'=>time(),
			   );
			if(intval($data['extra_value'])&&$mns->sendMessage($queueName, json_encode(array('dbname'=>'lotterywinning', 'data'=>$lotterywinning)))) {	

				RedisIO::set('queue_start',date('Y-m-d H:i:s',time()));  //记录队列开时时间
				$data['ip'] = $this->getClientIp();
				$data = json_encode($data);             //redis只能存json
				RedisIO::set($this->user_key,$data);    //存入redis缓存只能json格式
				RedisIO::incr('lottery_people:'.$lottery['id']); //接名牌统计，用户渲染活动页
				$this->_json();				
			}else{
				$this->_json('',404,'error');
			}
		}else{
			$this->_json('',402,'error');

		}
	}
	
	/**
	 * 记录用户信息测试专用
	 */
	public function addUserInfoAction(){
		
		$res = RedisIO::get($this->user_info);  //获取当前用户数据
		$exist = MemcacheIO::get($this->user_info);
		if(!empty($res) || $exist){
			echo "今天你已经玩过了！";
			exit;
		}else {
			MemcacheIO::set($this->user_info, true, 10);
		}
		
		$data = Request::getPost('');
		if($data){
			
			$accessKeyId = 'LTAIcdYW4QQTMuQz';
			$accessKeySecret = '41Q7wtBHBZQBmPWiz0l5fGk8wBMGIL';
			$endPoint = "http://1033683697196472.mns.cn-hangzhou-internal-vpc.aliyuncs.com";
			$mns = new AliyunMNS($accessKeyId,$accessKeySecret,$endPoint);
				
				
			$queueName = 'xlwMNS';
			$lotterycontact = array(
					'token'=>$data['client_id'],
					'name'=>$data['name'],
					'mobile'=>$data['mobile'],
					'address'=>$data['address'],
					'prize_is_real'=>1,
					'updated_at'=> time(),
					'created_at'=> time(),
			);
			
			//插入消息队列	
			if($mns->sendMessage($queueName, json_encode(array('dbname'=>'lotterycontact', 'data'=>$lotterycontact)))){
				RedisIO::set('queue_start',date('Y-m-d H:i:s',time()));  //记录队列开时时间
				$data = json_encode($data);             //redis只能存json
				RedisIO::set($this->user_info,$data);    //存入redis缓存json格式
				$this->_json('');
			}else{
				$this->_json('err',403,'error');
			}
		}else{
			$this->_json('',402,error);
		}
	}
	

	

	/**
	 * 获取客户端IP地址
	 * @param integer $type 返回类型 0 返回IP地址 1 返回IPV4地址数字
	 * @param boolean $adv 是否进行高级模式获取（有可能被伪装）
	 * @return mixed
	 */
	protected function getClientIp($type = 0, $adv = false)
	{
	    $type      = $type ? 1 : 0;
	    static $ip = null;
	    if (null !== $ip) {
	        return $ip[$type];
	    }
	
	    if ($adv) {
	        if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
	            $arr = explode(',', $_SERVER['HTTP_X_FORWARDED_FOR']);
	            $pos = array_search('unknown', $arr);
	            if (false !== $pos) {
	                unset($arr[$pos]);
	            }
	
	            $ip = trim($arr[0]);
	        } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
	            $ip = $_SERVER['HTTP_CLIENT_IP'];
	        } elseif (isset($_SERVER['REMOTE_ADDR'])) {
	            $ip = $_SERVER['REMOTE_ADDR'];
	        }
	    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
	        $ip = $_SERVER['REMOTE_ADDR'];
	    }
	    // IP地址合法验证
	    $long = sprintf("%u", ip2long($ip));
	    $ip   = $long ? array($ip, $long) : array('0.0.0.0', 0);
	    return $ip[$type];
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