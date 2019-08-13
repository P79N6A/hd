<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/5/6
 * Time: 10:36
 */
class UgcLiveVfController extends \BackendBaseController
{
    public function indexAction()
    {
        $data = UgcLiveVideo::findAll();
        $video_files = $data->__get('models');
        $model_stream = new UgcLive();
        $model_admin = new Admin();
        $data_ext = array();
        foreach ($video_files as $vf) {
            $stream = $model_stream->findFirst($vf->stream_id);
            $anchor = $model_admin->findFirst($stream->uid);
            $data_ext[$vf->id] = array(
                'anchor' => $anchor->name,
                'stream' => $stream->toArray(),
            );
        }
        View::setVars(compact('data','data_ext'));
    }
}