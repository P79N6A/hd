<?php namespace GenialCloud\Storage;

use GenialCloud\Exceptions\StorageException;

class Storage {

    protected static $image_extensions = ['png', 'jpg', 'gif', 'jpeg'];

    /**
     * 创建文件夹
     * @param $path
     * @param int $mode
     * @param bool $recursive
     * @return bool
     */
    public static function makeDir($path, $mode=0775, $recursive=true) {
        return file_exists($path) || mkdir($path, $mode, $recursive);
    }

    /**
     * 创建 hash 文件夹
     * @param string $pre_path
     * @param string $hash
     * @param int $level
     * @param int $mode
     * @param bool $recursive
     * @return string
     */
    public static function hashMakeDir($pre_path, $hash, $level=2, $mode=0775, $recursive=true) {
        $paths = [];
        for($i=0;$i<$level;$i++) {
            $paths[] = substr($hash, ($i+1)*-2, 2);
        }
        $sub_path = implode('/', $paths);
        $path = rtrim($pre_path,'\\/').'/'.implode('/', $paths);
        if(self::makeDir($path, $mode, $recursive)) {
            return $sub_path;
        } else {
            return '';
        }
    }

    /**
     * 隐藏 path 中用到的 hash
     * @param string $hash
     * @param int $level
     * @return string
     */
    public static function hiddenHash($hash, $level=2) {
        return substr($hash, 0, -2*$level);
    }

    /**
     * 生成唯一 Hash
     * @param string $fun
     * @return string
     */
    public static function uniqueHash($fun='sha1') {
        return $fun(uniqid());
    }

    /**
     * 是否是图片
     * @param $ext
     * @return bool
     */
    public static function isImage($ext) {
        return self::extAllowed($ext, static::$image_extensions);
    }

    /**
     * 扩展是否允许
     * @param $ext
     * @param $extensions
     * @return bool
     */
    public static function extAllowed($ext, $extensions) {
        return in_array($ext, $extensions);
    }

    /**
     * 扩展是否是否
     * @param $ext
     * @param $extensions
     * @return bool
     */
    public static function extDenied($ext, $extensions) {
        return in_array($ext, $extensions);
    }

    /**
     * 文件是否过大
     * @param int $size
     * @param int $limit KB
     * @return bool
     */
    public static function largerThan($size, $limit=1024) {
        return $size > $limit * 1024;
    }

    /**
     * 批处理存储
     * @param $commands
     * @return array
     * @throws StorageException
     */
    public static function batch($commands) {
        $rs = [];
        foreach($commands as $method => $command) {
            list($error_condition, $params, $message) = $command;
            $r = call_user_func_array('self::'.$method, $params);
            if($r === $error_condition) {
                throw new StorageException($message);
            }
            $rs[$method] = $r;
        }
        return $rs;
    }

}