<?php

use GenialCloud\Core\Facade;

/**
 * Class Oss
 *
 * @method static \GenialCloud\Storage\AliyunOSS setBucket($bucket)
 * @method static string generatePath()
 * @method static string uniqueUpload($ext, $file, $prefix='')
 * @method static null deleteFile($path)
 * @method static \Aliyun\OSS\Models\PutObjectResult uploadFile($key, $file)
 * @method static \Aliyun\OSS\Models\PutObjectResult uploadContent($key, $content)
 * @method static mixed getUrl($key, $expire_time)
 * @method static \Aliyun\OSS\Models\Bucket createBucket($bucketName)
 * @method static array getAllObjectKey($bucketName, $prefix='')
 * @method static string url($object='')
 */
class Oss {

    use Facade;

}