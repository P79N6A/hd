<?php
    class Analysis {
        const GET = 1;
        const POST = 2;
		const KEY = '4PkZBxWgBuI7sthBDHo8QYRXtZLmTcGj';
        private function async($url, $params = array(), $encode = true, $method = self::GET) {
            $ch = curl_init();
            if ($method == self::GET) {
                $url = $url . '?' . http_build_query($params);
                $url = $encode ? $url : urldecode($url);
                curl_setopt($ch, CURLOPT_URL, $url);
            } else {
                curl_setopt($ch, CULROPT_URL, $url);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);
            return $response;
        }
		
		public function report($type = 'lastmonth') {
			$params = array();
			$params['timestamp'] = time();
			$params['key'] = self::KEY;
			ksort($params);
			$str = http_build_query($params);
			$params['signature'] = md5(base64_encode($str));
			unset($params['key']);
			$params['type'] = $type;
			echo $this->async("http://www.cms.com/analysis/report", $params);
		}
	}