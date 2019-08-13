<?php

/**
 * Created by PhpStorm.
 * User: wangdonghao
 * Date: 2016/4/29
 * Time: 14:04
 */
class Img {

    public static function upload($file, $ext = '') {

        if (file_exists($file)) {
            $prefix = "3/socials";
            $path = $file;
            if ($path) {
                if ($ext == '') {
                    $ext = substr(strrchr($path, '.'), 1);
                }
                $img_url = Oss::uniqueUpload($ext, $path, $prefix . '/usericon');
            }

            if ($img_url) {
                $result ['state'] = 1;
                $result ['file'] = $img_url;
            } else {
                throw new Exception('图片上传失败');
            }
            return $result;
        } else {
            throw new Exception('文件不存在');
        }
    }

    public static function resizeImage($image, $width, $height, $scale, $SetW, $SetH) {

        if (CZTV_VPC_OSS == 1) { //使用VPC专用OSS
            $vpc_domain = app_site()->vpc_domain;
            $image = str_ireplace(cdn_url('image'), $vpc_domain, $image);
        }
        $imginfo = getimagesize($image);
        if (!$imginfo) {
            throw new Exception('参数错误');
        }
        if ($imginfo['mime'] == "image/pjpeg" || $imginfo['mime'] == "image/jpeg") {
            $source = imagecreatefromjpeg($image);
        } elseif ($imginfo['mime'] == "image/x-png" || $imginfo['mime'] == "image/png") {
            $source = imagecreatefrompng($image);
        } elseif ($imginfo['mime'] == "image/gif") {
            $source = imagecreatefromgif($image);
        }
        if (!$source) {
            throw new Exception('参数错误');
        }

        $newImageWidth = ceil($width * $scale);
        $newImageHeight = ceil($height * $scale);
        $newImage = imagecreatetruecolor($SetW, $SetH);
        $color = imagecolorAllocate($newImage, 255, 255, 255);
        imagefill($newImage, 0, 0, $color);

        $dx = ($SetW - $newImageWidth) / 2;
        $dy = ($SetH - $newImageHeight) / 2;
        imagecopyresampled($newImage, $source, $dx, $dy, 0, 0, $newImageWidth, $newImageHeight, $width, $height);
        imagejpeg($newImage, $image, 100);
        imagedestroy($newImage);
        chmod($image, 0777);
    }

    /**
     * @desc 批量裁剪图片，优化了$img为url时的性能
     * @param $img
     * @param $cropx
     * @param $cropy
     * @param $cropw
     * @param $croph
     * @param array $cropedImg
     * @throws Exception
     */
    public static function cropImgBatch($img, $cropx, $cropy, $cropw, $croph, array $cropedImg) {

        if (CZTV_VPC_OSS == 1) { //使用VPC专用VPC
            $vpc_domain = app_site()->vpc_domain;
            $img = str_ireplace(cdn_url('image'), $vpc_domain, $img);
        }

        $source = imagecreatefromjpeg($img);
        if (!$source) {
            throw new Exception('参数错误');
        }
        foreach ($cropedImg as $crop) {
            $newImage = imagecreatetruecolor($crop['width'], $crop['height']);
            imagecopyresampled($newImage, $source, 0, 0, $cropx, $cropy, $crop['width'], $crop['height'], $cropw, $croph);
            imagejpeg($newImage, $crop['file'], 100);
            imagedestroy($newImage);
            chmod($crop['file'], 0777);
        }
    }

    /**
     * @desc 从$img的($cropx,$cropy)开始，裁剪一块尺寸为$cropw*$croph的像素，
     * 填充到尺寸为$cropedImageWidth*$cropedImageHeight的$cropedImg中
     * ($cropx,$cropy)是相对于图片左上角为原点的起始裁剪坐标。
     * 注意，该函数就地修改了$cropedImg，并没有返回值
     *
     * @param $img
     * @param $cropx
     * @param $cropy
     * @param $cropw
     * @param $croph
     * @param $cropedImg
     * @param $cropedImgWidth
     * @param $cropedImgHeight
     */
    public static function cropImage($img, $cropx, $cropy, $cropw, $croph, $cropedImg, $cropedImgWidth, $cropedImgHeight) {

        if (CZTV_VPC_OSS == 1) { //使用VPC专用OSS
            $vpc_domain = app_site()->vpc_domain;
            $img = str_ireplace(cdn_url('image'), $vpc_domain, $img);
        }
        $newImage = imagecreatetruecolor($cropedImgWidth, $cropedImgHeight);
        $source = imagecreatefromjpeg($img);
        imagecopyresampled($newImage, $source, 0, 0, $cropx, $cropy, $cropedImgWidth, $cropedImgHeight, $cropw, $croph);
        imagejpeg($newImage, $cropedImg, 100);
        imagedestroy($newImage);
        chmod($cropedImg, 0777);
    }
}
