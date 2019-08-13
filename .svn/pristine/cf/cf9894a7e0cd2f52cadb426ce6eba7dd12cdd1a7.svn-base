<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/5/6
 * Time: 10:36
 */
class UgcyunLiveVfController extends \BackendBaseController {

    public function indexAction() {
        $streamids = UgcyunLive::getAllStreamids();
        $data = UgcyunLiveVideo::findAll($streamids);
        $data_ext = array();
        foreach ($data->models as $vf) {
            $stream = UgcyunLive::findFirst($vf->stream_id);
            $anchor = Admin::findFirst($stream->admin_id);
            $data_ext[$vf->id] = array(
                'anchor' => $anchor->name,
                'stream' => $stream->toArray(),
              );
        }
        View::setVars(compact('data','data_ext'));
    }

}