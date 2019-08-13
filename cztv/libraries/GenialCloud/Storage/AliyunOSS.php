<?php
namespace GenialCloud\Storage;

use Aliyun\OSS\OSSClient;
use Aliyun\OSS\Models\OSSOptions;

class AliyunOSS {

    protected $ossClient;
    protected $bucket;

    public function __construct($serverName, $AccessKeyId, $AccessKeySecret) {
        $this->ossClient = OSSClient::factory([
            OSSOptions::ENDPOINT => $serverName,
            'AccessKeyId' => $AccessKeyId,
            'AccessKeySecret' => $AccessKeySecret
        ]);
    }

    public static function boot($serverName, $AccessKeyId, $AccessKeySecret) {
        return new AliyunOSS($serverName, $AccessKeyId, $AccessKeySecret);
    }

    public function setBucket($bucket) {
        $this->bucket = $bucket;
        return $this;
    }

    public function generatePath() {
        return date('Y/m/d/').md5(uniqid(str_random()));
    }

    /**
     * @param string $ext
     * @param string $file
     * @param string $prefix
     * return string;
     */
    public function uniqueUpload($ext, $file, $prefix = '') {
        $path = rtrim($prefix, '/').'/'.$this->generatePath().'.'.$ext;
        /**
         * @var \Aliyun\OSS\Models\PutObjectResult $r
         */
        $r = $this->uploadFile($path, $file);
        if(!$r->getETag()) {
            $path = '';
        }
        return $path;
    }

    public function deleteFile($path) {
        $this->ossClient->deleteObject([
            'Bucket' => $this->bucket,
            'Key' => $path,
        ]);
    }

    public function uploadFile($key, $file, $content_type="") {
        if($content_type=="") {
            $extname = substr($key,strrpos($key,".")+1);
            switch($extname) {
                case "css": $content_type = "text/css"; break;
                case "js":  $content_type = "text/javascript"; break;
                case "xml": $content_type = "text/xml"; break;
                case "jpg": $content_type = "image/jpeg"; break;
                case "jpeg": $content_type = "image/jpeg"; break;
                case "png": $content_type = "image/png"; break;
                case "gif": $content_type = "image/gif"; break;
                case "swf": $content_type = "application/x-shockwave-flash"; break;
            }
        }

        $handle = fopen($file, 'r');
        if($content_type=="") {
        $value = $this->ossClient->putObject(array(
            'Bucket' => $this->bucket,
            'Key' => $key,
            'Content' => $handle,
            'ContentLength' => filesize($file)
        ));
        }
        else {
            $value = $this->ossClient->putObject(array(
                'Bucket' => $this->bucket,
                'Key' => $key,
                'Content' => $handle,
                'ContentType' => $content_type,
                'ContentLength' => filesize($file)
            ));

        }
        fclose($handle);
        return $value;
    }

    public function uploadContent($key, $content) {
        return $this->ossClient->putObject(array(
            'Bucket' => $this->bucket,
            'Key' => $key,
            'Content' => $content,
            'ContentLength' => strlen($content)
        ));
    }

    public function getUrl($key, $expire_time) {
        return $this->ossClient->generatePresignedUrl([
            'Bucket' => $this->bucket,
            'Key' => $key,
            'Expires' => $expire_time
        ]);
    }

    public function createBucket($bucketName) {
        return $this->ossClient->createBucket(['Bucket' => $bucketName]);
    }

    public function getAllObjectKey($bucketName, $prefix = "") {
        $objectListing = $this->ossClient->listObjects(array(
            'Bucket' => $bucketName,
            'Prefix' => $prefix
        ));

        $objectKeys = [];
        foreach($objectListing->getObjectSummarys() as $objectSummary) {
            $objectKeys[] = $objectSummary->getKey();
        }
        return $objectKeys;
    }

    public function url($object = '') {
        if(strpos($object,'http') !== false) {
            return $object;
        }
        return rtrim(cdn_url('image'), "/")."/".$object;
    }

}