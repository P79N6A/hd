<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/5/6
 * Time: 10:35
 */
class UgcyunLiveStreamController extends \BackendBaseController {

    public function indexAction(){
        $adminids = Admin::getAllAdminIds();
        //var_dump($adminids);exit;
        $data = UgcyunLive::findAll('',  $adminids);
        $streams = array();
        if($data->count) {
            $streams = $data->models->toArray();
            foreach ($streams as $index => $stream) {
                $stream['_name'] = 'S' . date('ymd', $stream['start_time']) . str_pad($stream['id'], 3, '0', STR_PAD_LEFT);
                $anchor = Admin::findFirst($stream['admin_id']);
                $stream['_anchor'] = $anchor->name;
                $stream['_trans_code'] = UgcyunLive::countCdnUrl($stream['id']);
                $streams[$index] = $stream;
            }
        }
        View::setVars(compact('streams', 'data'));
    }

    public function detailAction() {
        $stream_ext = '';
        $urls = '';
        if ($id = Request::getQuery('id')) {
            $stream = UgcyunLive::findFirst($id);
            $anchor = Admin::findFirst($stream->admin_id);
            $room = UgcyunLiveRoom::findFirst(array(
                'conditions' => 'admin_id = :admin_id:',
                'bind' => array('admin_id' => $stream->admin_id)));
            $room_name = '';
            if ($room) {
                $room_item_no = 'zbt' . str_pad($room->id, 5, '0', STR_PAD_LEFT);
                $room_name = $room->roomname;
            }
            $stream_item_no = 'S' . date('ymd', $stream->start_time) . str_pad($stream->id, 3, '0', STR_PAD_LEFT);

            $stream_ext = array('anchor' => $anchor->name, 'room_name' => $room_name,'stream_item_no' => $stream_item_no);

            $Qua = UgcyunLive::getQua_name();

            $urls = array();
            array_push($urls, array('type' => '用户推流地址', 'url' => $stream->rtmp_url, 'pic_quality' => ''));
            $num = 1;
            foreach (array('cdn_url1', 'cdn_url2', 'cdn_url3') as $cdn) {
                if (!empty($stream->$cdn)) {
                    $q_i = str_replace('p', '', str_replace('_', '', strrchr($stream->$cdn, '_')));
                    array_push($urls, array('type' => "CDN推流{$num}", 'url' => $stream->$cdn, 'pic_quality' => $Qua[$q_i]));
                }
                $num++;
            }
        }
        View::setVars(compact('stream','stream_ext', 'urls'));
    }

    public function listLiveAction() {
    	$adminids = Admin::getAllAdminIds();
        //var_dump($adminids);exit;
        $data = UgcyunLive::findAll('',  $adminids);
        $streams = array();
        if($data->count) {
            $streams = $data->models->toArray();
            foreach ($streams as $index => $stream) {
                $stream['_name'] = 'S' . date('ymd', $stream['start_time']) . str_pad($stream['id'], 3, '0', STR_PAD_LEFT);
                $anchor = Admin::findFirst($stream['admin_id']);
                $stream['_anchor'] = $anchor->name;
                $stream['_trans_code'] = UgcyunLive::countCdnUrl($stream['id']);
                $streams[$index] = $stream;
            }
        }
        
        View::setVars(compact('streams', 'data'));
    }

}