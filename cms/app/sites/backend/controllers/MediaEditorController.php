<?php

/**
 * 控制器配套方法
 *
 * @author     Xue Wei
 * @created    2015-11-03
 */
class MediaEditorController extends BackendBaseController {

    /**
     * 编辑器配置选项
     * @var array
     */
    protected $config = [
        //*** 上传图片配置项
        //执行上传图片的action名称
        'imageActionName' => 'uploadimage',
        //提交的图片表单名称
        'imageFieldName' => 'upfile',
        //上传大小限制，单位B
        'imageMaxSize' => 128000000,
        //上传图片格式显示
        'imageAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],
        //是否压缩图片,默认是true
        'imageCompressEnable' => true,
        //图片压缩最长边限制
        'imageCompressBorder' => 2560,
        //插入的图片浮动方式
        'imageInsertAlign' => 'none',
        //图片访问路径前缀
        'imageUrlPrefix' => '',

        //*** 上传文件配置
        'fileActionName' => 'uploadfile',
        'fileFieldName' => 'upfile',
        'fileUrlPrefix' => '',
        'fileMaxSize' => 512000000,
        'fileAllowFiles' => [
            '.png', '.jpg', '.jpeg', '.gif', '.bmp',
            '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg',
            '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid',
            '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso',
            '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'
        ],

        //*** 抓取远程图片配置
        'catcherLocalDomain' => ['127.0.0.1', 'localhost', 'img.baidu.com'],
        'catcherActionName' => 'catchimage',
        'catcherFieldName' => 'source',
        'catcherPathFormat' => '/upload/editor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand => 6}',
        'catcherUrlPrefix' => '',
        'catcherMaxSize' => 128000000,
        'catcherAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],



        //上传保存路径,可以自定义保存路径和文件名格式
        /**
         * {filename} 会替换成原文件名,配置这项需要注意中文乱码问题
         * {rand => 6} 会替换成随机数,后面的数字是随机数的位数
         * {time} 会替换成时间戳
         * {yyyy} 会替换成四位年份
         * {yy} 会替换成两位年份
         * {mm} 会替换成两位月份
         * {dd} 会替换成两位日期
         * {hh} 会替换成两位小时
         * {ii} 会替换成两位分钟
         * {ss} 会替换成两位秒
         * 非法字符 \  =>  * ? ' < > |
         * @see http://fex.baidu.com/ueditor/#use-format_upload_filename
         */
        /**
        'imagePathFormat' => '/upload/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand => 6}',
        //*** 涂鸦图片上传配置项
        'scrawlActionName' => 'uploadscrawl',
        'scrawlFieldName' => 'upfile',
        'scrawlPathFormat' => '/upload/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand => 6}',
        'scrawlMaxSize' => 128000000,
        'scrawlUrlPrefix' => '',
        'scrawlInsertAlign' => 'none',

        //*** 截图工具上传
        'snapscreenActionName' => 'uploadimage',
        'snapscreenPathFormat' => '/upload/ueditor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand => 6}',
        'snapscreenUrlPrefix' => '',
        'snapscreenInsertAlign' => 'none',

        //*** 抓取远程图片配置
        'catcherLocalDomain' => ['127.0.0.1', 'localhost', 'img.baidu.com'],
        'catcherActionName' => 'catchimage',
        'catcherFieldName' => 'source',
        'catcherPathFormat' => '/upload/editor/php/upload/image/{yyyy}{mm}{dd}/{time}{rand => 6}',
        'catcherUrlPrefix' => '',
        'catcherMaxSize' => 128000000,
        'catcherAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],

        //*** 上传视频配置
        'videoActionName' => 'uploadvideo',
        'videoFieldName' => 'upfile',
        'videoPathFormat' => '/upload/ueditor/php/upload/video/{yyyy}{mm}{dd}/{time}{rand => 6}',
        'videoUrlPrefix' => '',
        'videoMaxSize' => 512000000,
        'videoAllowFiles' => [
            '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg',
            '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid'
        ],

        //*** 上传文件配置
        'fileActionName' => 'uploadfile',
        'fileFieldName' => 'upfile',
        'filePathFormat' => '/upload/ueditor/php/upload/file/{yyyy}{mm}{dd}/{time}{rand => 6}',
        'fileUrlPrefix' => '',
        'fileMaxSize' => 512000000,
        'fileAllowFiles' => [
            '.png', '.jpg', '.jpeg', '.gif', '.bmp',
            '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg',
            '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid',
            '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso',
            '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml'
        ],

        //*** 列出指定目录下的图片
        //执行图片管理的action名称
        'imageManagerActionName' => 'listimage',
        //指定要列出图片的目录
        'imageManagerListPath' => '/upload/ueditor/php/upload/image/',
        //每次列出文件数量
        'imageManagerListSize' => 20,
        //图片访问路径前缀
        'imageManagerUrlPrefix' => '',
        //插入的图片浮动方式
        'imageManagerInsertAlign' => 'none',
        //列出的文件类型
        'imageManagerAllowFiles' => ['.png', '.jpg', '.jpeg', '.gif', '.bmp'],

        //*** 列出指定目录下的文件
        'fileManagerActionName' => 'listfile',
        'fileManagerListPath' => '/upload/ueditor/php/upload/file/',
        'fileManagerUrlPrefix' => '',
        'fileManagerListSize' => 20,
        'fileManagerAllowFiles' => [
            '.png', '.jpg', '.jpeg', '.gif', '.bmp',
            '.flv', '.swf', '.mkv', '.avi', '.rm', '.rmvb', '.mpeg', '.mpg',
            '.ogg', '.ogv', '.mov', '.wmv', '.mp4', '.webm', '.mp3', '.wav', '.mid',
            '.rar', '.zip', '.tar', '.gz', '.7z', '.bz2', '.cab', '.iso',
            '.doc', '.docx', '.xls', '.xlsx', '.ppt', '.pptx', '.pdf', '.txt', '.md', '.xml',
        ],
        */
    ];

    public function doAction() {
        $config = $this->config;
        $action = Request::get('action');
        switch ($action) {
            case 'config':
                $result =  $config;
                break;
            case 'uploadimage':
            case 'uploadfile':
                $result = $this->upload($config, $action);
                break;
            case 'catchimage':
                $result = $this->catchimage($config);
                break;
            default:
                $result = ['state'=> Lang::_('invalid url')];
                break;
        }
        $this->jsonp($result);
    }


    public function uploadBase64(){

    }

    public function upload($config, $action) {
        $state = 'SUCCESS';
        $url = $original = $type = $title = '';
        $size = 0;
        switch($action) {
            case 'uploadimage':
                $allow_field = 'image';
                $prefix = 'posts';
                break;
            case 'uploadfile':
                $allow_field = 'file';
                $prefix = 'attachments';
                break;
        }
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if(!$error) {
                try {
                    $allows = $config[$allow_field.'AllowFiles'];
                    $type = $file->getExtension();
                    if(!in_array('.'.strtolower($type), $allows)) {
                        throw new \Phalcon\Exception(Lang::_('allow upload').implode(', *', $allows).Lang::_('file exts'));
                    }
                    $size = $file->getSize();
                    $allow_size = $config[$allow_field.'MaxSize'];
                    if($size > $allow_size) {
                        throw new \Phalcon\Exception(Lang::_('max file size'));
                    }
                    $path = Oss::uniqueUpload(strtolower($type), $file->getTempName(), Auth::user()->channel_id.'/'.$prefix);
                    $url = cdn_url('image', $path);
                    $original = $file->getName();
                    $title = basename($path);
                } catch(\Phalcon\Exception $e) {
                    $state = $e->getMessage();
                }
            } elseif($error == 4) {
                $state = Lang::_('upload failed');
            } else {
                $state = Lang::_('unknown error');
            }
        } else {
            $state = Lang::_('please choose upload poster image');;
        }
        return compact('state', 'url', 'type', 'size', 'original', 'type', 'title');
    }

    public function listImage($config) {
        return [];
    }

    public function listFile($config) {
        return [];
    }

    public function catchImage($config) {

        $fieldName = $config['catcherFieldName'];
        /* 抓取远程图片 */
        $list = array();
        if (isset($_POST[$fieldName])) {
            $source = $_POST[$fieldName];
        } else {
            $source = $_GET[$fieldName];
        }
        foreach ($source as $imgUrl) {
            $url = $this->getRemoteFile($imgUrl);
            if(!empty($url))
                array_push($list, array(
                    "state" => empty($url)?0:"SUCCESS",
                    "url" => $url,
                    "size" => "",
                    "title" => "",
                    "original" => "",
                    "source" => htmlspecialchars($imgUrl)
                ));
        }

        /* 返回抓取数据 */
       return array(
            'state'=> count($list) ? 'SUCCESS':'ERROR',
            'list'=> $list
        );
    }

    private function getRemoteFile($file){
        $prefix = 'posts';
        $thumb = $file;
        $ext = substr(strrchr($thumb, '.'), 1);
        $legal_ext = "jpg";
        if(false!==stripos(strtolower($ext), 'png')) {
            $legal_ext = "png";
        }
        if(false!==stripos(strtolower($ext), 'gif')) {
            $legal_ext = "gif";
        }

        $filename = pathinfo($thumb)['filename'] . '.' . $legal_ext;
        $path = httpcopy($thumb, APP_PATH . '../tasks/tmp/' . $filename, 120, $proxy=true);
        $osspath = "";

        if ($path) {
            $osspath = Oss::uniqueUpload($legal_ext, $path, Auth::user()->channel_id.'/'.$prefix);
            unlink($path);
        }
        $url = cdn_url('image', $osspath);
        return $url;
    }


}