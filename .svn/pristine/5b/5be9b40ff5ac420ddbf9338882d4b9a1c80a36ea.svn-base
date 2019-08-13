<?php

/**
 * @class:   ResourceController  静态资源
 * @author:  汤荷
 * @version: 1.0
 * @date:    2017/2/8
 */
class ResourceController extends \BackendBaseController{
    const RESOURCE_ROOT="resource";
    /**
     * @function 列表页
     * @author 汤荷
     * @version 1.0
     * @date
     */
    public function indexAction() {
        $resources = Resources::findAll();
        View::setVars(compact("resources"));
    }

    public function addAction() {
        $error=null;
        $message=[];
        if (Request::isPost()){
            $title = Request::getPost("title");
            $sub_title = Request::getPost("sub_title");
            $intro = Request::getPost("intro");
            $code_version = Request::getPost("code_version");
            $model = Resources::query()
                    ->where("title = {$title}")
                    ->andWhere("code_version = {$code_version}")
                    ->execute()
                    ->toArray();
            $cdn_version = count($model);
            list($path,$error) = $this->doUpload($title,$code_version,$cdn_version);
            if (!$error) {
                $res = new Resources();
                $res->title = $title;
                $res->sub_title = $sub_title;
                $res->intro = $intro;
                $res->code_version = $code_version;
                $res->cdn_version = $cdn_version;
                $res->path = $path;
                $res->updated_at = time();
                $res->created_at = time();
                $res->save();
                $message[] = "成功!";
            }else{
                $message[] = $error;
            }
        }

        View::setVars(compact("message"));
        View::setMainView('layouts/add');
    }

    public function editAction() {
        $error=null;
        $message=[];
        $id = Request::get("id");
        $res = Resources::findFirstOrFail("id = {$id}");

        if (Request::isPost()){
            $sub_title = Request::getPost("sub_title");
            $intro = Request::getPost("intro");
            $code_version = Request::getPost("code_version");
            $res->sub_title = $sub_title;
            $res->intro = $intro;
            $res->code_version = $code_version;

            //如果有文件
            if($_FILES["file"]["error"] == 0){
                $cdn_version = $res->cdn_version;
                list($path,$error) = $this->doUpload( $res->title,$code_version,$cdn_version);
                if (!$error) {
                    if ($path != "") {
                        $res->path = $path;
                    }
                }
            }

            if(!$error){
                $res->updated_at = time();
                $res->save();
                $message[] = "成功!";
            }else{
                $message[] = $error;
            }
        }

        View::setVars(compact("message","res"));
        View::setMainView('layouts/add');
    }

    protected function doUpload($title,$code_version,$cdn_version) {
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $ext = $file->getExtension();
            $filename = $file->getName();
            $error = $file->getError();
            $path = "";
            if (!$error) {
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png','js','css'])) {
                    $path = self::RESOURCE_ROOT."/$title/$code_version/$cdn_version/$filename";
                    Oss::uploadFile($path, $file->getTempName());//todo::
                } else {
                    $error = Lang::_('please upload valid poster image');
                }
            } elseif ($error == 4) {
                $error = Lang::_('please choose upload poster image');
            } else {
                $error = Lang::_('unknown error');
            }
        } else {
            $error = Lang::_('please choose upload poster image');
        }
        return [$path,$error];
    }
}