<?php namespace GenialCloud\Helper;

use \Session;
use \Config;

class Captcha {

    /**
     * 传递参数的标签
     * @var string
     */
    protected $tag = 'phahub_captcha';

    /**
     * 验证码图片宽度
     * @var int
     */
    public $width = 100;

    /**
     * 验证码图片宽度
     * @var int
     */
    public $height = 32;

    /**
     * 验证码前景色(字符) RGB 16位表示
     * @var string
     */
    public $foregroundColor = '#000000';

    /**
     * 验证码背景色 RGB 16位表示
     * @var string
     */
    public $backgroundColor = '#FFFFFF';

    /**
     * 字体大小
     * @var float
     */
    public $fontSize = 16;

    /**
     * 字符长度
     * @var int
     */
    public $charSize = 5;

    /**
     * 校验码过期时间, 单位:秒
     * @var int
     */
    public $expire = 60;

    public function __construct() {
        $config = Config::get('captcha', []);
        foreach($config as $key => $val) {
            if(property_exists($this, $key)) {
                $this->{$key} = $val;
            }
        }
    }

    /**
     * 解析RGB颜色
     * @param string $color
     * @return array
     */
    private function parseRGB($color) {
        if (strlen($color) < 7) {
            $color = '#FFFFFF';
        }
        $r = hexdec($color[1].$color[2]);
        $g = hexdec($color[3].$color[4]);
        $b = hexdec($color[5].$color[6]);
        return [$r, $g, $b];
    }

    /**
     * 生成问题图片
     * @param string $data 问题内容
     * $return void
     */
    private function generateImage($data) {
        $width = $this->width;
        $height = $this->height;
        //文字宽度 $len*9+20
        $im = imageCreateTrueColor($width, $height);
        //字体颜色
        list($r, $g, $b) = $this->parseRGB($this->foregroundColor);
        $foregroundColor = imageColorAllocate($im, $r, $g, $b);
        list($r, $g, $b) = $this->parseRGB($this->backgroundColor);
        $background = imageColorAllocate($im, $r, $g, $b);
        //背景填充
        imageFill($im, 0, 0, $background);
        //文字填充
        $charWidth = ($this->fontSize+2)*$this->charSize;
        if($charWidth < $this->width) {
            //文字的随机角度
            $angle = rand(-5, 5);
            $sin = sin(deg2rad($angle));
            //计算偏移量避免字符超出图片
            $xOffset = floor($this->width - $charWidth*(1+abs($sin)));
            $yOffset = floor($this->height - $this->fontSize*2*(1+abs($sin))) + $this->fontSize+2;
            if ($yOffset < $this->fontSize+2 ) {
                $yOffset = $this->fontSize+2;
            }
        } else {
            $angle = 0;
            $xOffset = 0;
            $yOffset = $this->fontSize+2;
        }
        imageTTFText(
            $im,
            $this->fontSize,
            $angle,
            $xOffset,
            $yOffset,
            $foregroundColor,
            dirname(__FILE__).'/fonts/1.ttf',
            $data
        );
        $counter = 4;
        $sixColors = array();
        //生成随机颜色
        while($counter > 0) {
            $rColor = rand(0, 255);
            $gColor = rand(0, 255);
            $bColor = rand(0, 255);
            $sixColors[] = imageColorAllocate ($im, $rColor, $gColor, $bColor);
            $counter--;
        }
        while ($counter < 4) {
            $rArc = rand(2, 4);
            $xArc = rand(5, $width-5);
            $yArc = rand(5, $height-5);
            //随机点生成
            imageArc(
                $im,
                $xArc,
                $yArc,
                $rArc,
                $rArc,
                0,
                360,
                $sixColors[$counter % 8]
            );
            //随机线条
            if(($counter+1) % 3 === 0) {
                $lineXStart = rand(1, $width);
                $lineXEnd = rand(1, $width);
                $lineYStart = rand(10, $height);
                $lineYEnd = rand(10, $height);
                imageline (
                    $im,
                    $lineXStart,
                    $lineYStart,
                    $lineXEnd,
                    $lineYEnd,
                    $sixColors[$counter%8]
                );
            }
            $counter++;
        }
        header ('Content-type: image/png');
        imagePng($im);
        imageDestroy($im);
        exit;
    }

    /**
     * 生成问题
     * @return void
     */
    public function generate() {
        $data = array_merge(
            range(1, 9),
            range('A', 'Z'),
            range('a', 'z')
        );
        $data = array_rand(array_flip($data), $this->charSize);
        $data = implode('', $data);
        $this->save(strtolower($data));
        $this->generateImage($data);
    }

    /**
     * 校验是否成功
     * @param string $answer
     * @return int 1 ok , 0 failed, -1 expired
     */
    public function check($answer) {
        $answer = strtolower($answer);
        $time = time();
        $expired = Session::get($this->tag.'_expire', $time - 600);
        if(time() - $expired > $this->expire) {
            $return = -1;
        } else {
            if(($data = $this->fetch()) && $answer === $data) {
                $return = 1;
            } else {
                $return = 0;
            }
        }
        $this->clear();
        return $return;
    }

    /**
     * 保存数据
     * @param string $data
     * @return void
     */
    private function save($data) {
        Session::set($this->tag, $data);
        Session::set($this->tag.'_expire', time());
    }

    /**
     * 获取存储的数据
     * @return string
     */
    private function fetch() {
        return Session::get($this->tag, '');
    }

    /**
     * 清除成功时候的session
     */
    public function clear() {
        Session::remove($this->tag);
        Session::remove($this->tag.'_expire');
    }

}
