<?php
/**
 * Created by PhpStorm.
 * User: zhangyichi
 * Date: 2016/8/21
 * Time: 15:00
 */
class WorkcordController extends InteractionBaseController{

    public function initialize()
    {
        parent::initialize();
        $this->crossDomain();
    }

    /**
     * 允许跨域请求
     */
    private function crossDomain()
    {
        $host = isset($_SERVER['HTTP_ORIGIN'])? $_SERVER['HTTP_ORIGIN'] : '';

        if(false !== strpos($host,'cztv')) {
            header('content-type:application:json;charset=utf8');
            header('Access-Control-Allow-Origin:' . $host);
            header('Access-Control-Allow-Methods:POST,GET,PUT');
            header('Access-Control-Allow-Headers:x-requested-with,content-type');
        }

//        header('content-type:application:json;charset=utf8');
//        header('Access-Control-Allow-Origin:*' );
//        header('Access-Control-Allow-Methods:POST,GET,PUT');
//        header('Access-Control-Allow-Headers:x-requested-with,content-type');

    }

    /**
     * 生成工作证图片
     */
    public function imageAction(){
        $post = Request::getPost();

        if(isset($post['pictrue_url'])&&isset($post['text'])) {
            try {

                $pictrue_url = $post['pictrue_url'];
                $image = new Imagick($pictrue_url);
                $image->setimageformat('jpg');
                $draw = new ImagickDraw();

                $text = $post['text'];
                if (is_array($text)) {
                    for ($i = 0; $i < count($text); $i++) {
                        $message_arr = $text[$i];
                        if (self::filter(str_replace(' ', '', $message_arr['text']))) {
                            $message_arr['text'] = '';
                        }
                        $draw->setFont('../upload/paonan/images/heiti.ttf');
                        $draw->setFontSize($message_arr['font_size']);
                        $textColor = new ImagickPixel($message_arr['font_color']);
                        $draw->setFillColor($textColor);

                        $image->annotateImage($draw, $message_arr['x'], $message_arr['y'], $message_arr['angle'], $message_arr['text']);
                    }
                } else {
                    echo  $this->jsonp(array('msg'=>'错误内容格式'));
                }

                ob_clean();
                header("Content-Type: image/jpeg");
                $url = 'ppzjm_work/'.date('Y/m/d/').md5(uniqid(str_random())).'.jpg';
                Oss::uploadContent($url,(string)$image);
                echo json_encode(array('url'=>cdn_url('image',$url)));

            } catch (Exception $ex) {
                echo $this->jsonp(array('msg'=>$ex->getMessage()));
            }
        }else{
            echo $this->jsonp(array('msg'=>'内容不全'));
        }

    }

    protected $cache_key = 'weiXinAccessToken';

    public function getwxpictureAction() {
        $mediaid = Request::get('mediaid');
        if(isset($mediaid)){
            $accessToken = RedisIO::get($this->cache_key);

            $wx_url = "https://api.weixin.qq.com/cgi-bin/media/get?access_token=".$accessToken."&media_id=".$mediaid;
            $info = F::curlProxy($wx_url);

            $url = 'ppzjm_work/'.date('Y/m/d/').md5(uniqid(str_random())).".jpg";
            Oss::uploadContent($url,$info);
            $data = array();
            $data['url'] = cdn_url('image',$url);
            echo $this->jsonp(array('data'=>$data));

        }else{
            echo $this->jsonp(array('msg'=>'参数不全'));
        }
    }
    
    public function picturestreamAction() {

        $post = Request::getPost();
        if(isset($post['img'])) {

            $base64_image_content = $post['img'];

            if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $result)){
                $type = $result[2];

                $url = 'ppzjm_work/'.date('Y/m/d/').md5(uniqid(str_random())).".{$type}";
                Oss::uploadContent($url,base64_decode(str_replace('%2F','/',urlencode(str_replace($result[1], '', $base64_image_content)))));
                $data = array();
                $data['url'] = cdn_url('image',$url);
                echo $this->jsonp(array('data'=>$data));

            }

        }else{
            echo $this->jsonp(array('msg'=>'内容不全'));
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