<?php
/**
 * 跑男工作证v1.1.3
 * @author fang
 *
 */

class PaonanController extends WeiXinBaseController {
	/**
	 * 跑男工作证
	 * (non-PHPdoc)
	 * @see BaseController::indexAction()
	 */
	public function indexAction(){
		$_GET['4free'] = true;
		if (!isset($_GET['4free'])) {	
			$cbtvInfoJson = isset($_COOKIE[$this->cookie_key]) ? $_COOKIE[$this->cookie_key] : '';
			$cbtvInfo = json_decode($cbtvInfoJson, true);
			$code = isset($_GET['code']) ? $_GET['code'] : '';
			if (!$code) {
				$redirect_url = 'http://dhudong.cztv.com/paonan/?';
				//$redirect_url = 'http://open.cztv.com/api/wx_tv/static/2016/foolsday/?';
				$redirect_url .= (isset($_GET['4free'])?'&4free=':'');
				$redirect_url .= (isset($_GET['name'])?('&name='.$_GET['name']):'');
				$redirect_url .= (isset($_GET['pos'])?('&pos='.$_GET['pos']):'');
				$url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=' . APP_ID . '&redirect_uri='.urlencode($redirect_url).'&response_type=code&scope=snsapi_base&state=' . time() . '#wechat_redirect';
				header('Location:' . $url);
				exit;
			}
			$url = 'https://api.weixin.qq.com/sns/oauth2/access_token?appid=' . APP_ID . '&secret=' . APP_SECRET . '&code=' . $code . '&grant_type=authorization_code';
			//TODO 统一外网服务接口
//			$userInfo = file_get_contents($url);
			$userInfo = F::curlProxy($url);
			$userInfo = json_decode($userInfo,true); //转为数组
			$openid = isset($userInfo['openid']) ? $userInfo['openid'] : '';	//接收openid		
			//是否展现正常内容
			$showFlag = false;
			if ($cbtvInfo && $cbtvInfo['openid'] == $openid && $cbtvInfoJson['subscribe'] == 1) {
				$showFlag = true;
			} else if ($code) {
				$showFlag = $this->isSubscribe($openid);  //是否已关注
			}
		} else {
			$showFlag = true;
		}
		header("Cache-Control:no-cache");
		View::setVar('showFlag',$showFlag);
    }
    
    /**
     * 生成工作证图片
     */
    public function imageAction(){    	
    	$text1 = $_GET['name'];
    	if (self::filter(str_replace(' ', '', $text1))) {
    	$text1 = '';
    	}
    	$pos = intval($_GET['pos']);
    	$poses = array(
    			'鹿晗助理',
    			'陈赫助理',
    			'邓超助理',
    			'Angelababy助理',
    			'郑恺助理',
    			'李晨助理',
    			'王祖蓝助理',
    			'迪丽热巴助理'
    	);
    	$pos--;
    	$pos = ($pos >= 0) ? $pos : 0;
    	$text2 = $poses[$pos];
    	try {
    		$image = new Imagick('/data/cms/upload/paonan/images/bg_1.jpg');
    		$image->setimageformat('jpg');
    		$draw = new ImagickDraw();
    		$draw->setFont('/data/cms/upload/paonan/images/heiti.ttf');
    		$draw->setFontSize('26');
    		$textColor = new ImagickPixel('#333333');
    		$draw->setFillColor($textColor);
    		$lenText1 = strlen($text1);
    		$image->annotateImage($draw, (365-intval($lenText1/3)*10), 770, 5, $text1);
    		if ($pos == 3) {
    			$image->annotateImage($draw, 280, 830, 5, $text2);
    		} else {
    			$image->annotateImage($draw, 320, 830, 5, $text2);
    		}
    		ob_clean();
    		header("Content-Type: image/jpeg");
    		echo $image;
    	} catch (Exception $ex) {
    		echo $ex->getMessage();
    	}
    }
    

	/**
	 * 过虑方法
	 * @param unknown $str
	 * @return number
	 */
	static function filter($str) {
		$path = dirname(dirname(__FILE__)); //路经
		$mem_key = "chinabluenews_filter_words::";
		$words = MemcacheIO::get($mem_key);
		if (!$words) {
			$filter_words_str = file_get_contents($path.'/views/paonan/filterwords.txt');
			$words = explode('|', $filter_words_str);
			MemcacheIO::set($mem_key, $words, false, 600);
			
		}

		return self::preg_match_array($words, $str);
	}
	
	/**
	 * 正则
	 * @param unknown $pattern_array
	 * @param unknown $subject
	 * @return number
	 */
	static function preg_match_array($pattern_array, $subject) {
		$result = 0;
		foreach ($pattern_array as $v) {
			$result |= preg_match('/' . $v . '/', $subject);
		}
		return $result;
	}
}




