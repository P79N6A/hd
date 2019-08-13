<?php

class UeditorController extends \MediaBaseController {
    public function imageUp(){
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/thumbnails');
                } else {
                    $messages[] = Lang::_('please upload valid index image');
                }
            } elseif($error == 4) {
                $path = Request::getPost('thumb', null, '');
                if(!$path) {
                    $messages[] = Lang::_('please choose upload index image');
                }
            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload poster image');
        }
        return $path;
    }
}