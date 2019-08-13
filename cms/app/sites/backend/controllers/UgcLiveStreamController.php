<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/5/6
 * Time: 10:35
 */
class UgcLiveStreamController extends \BackendBaseController
{
    public function indexAction()
    {
        $data = UgcLive::findAll();
        $streams = $data->__get('models')->toArray();
        foreach ($streams as $index => $stream) {
            $stream['_name'] = 'S' . date('ymd', $stream['start_time']) . str_pad($stream['id'], 3, '0', STR_PAD_LEFT);
            $model = new Admin();
            $anchor = $model->findFirst($stream['admin_id']);
            $stream['_anchor'] = $anchor->name;
            $stream['_trans_code'] = UgcLive::countCdnUrl($stream['id']);
            $streams[$index] = $stream;
        }
        View::setVars(compact('streams', 'data'));
    }

    public function detailAction()
    {
        $stream_ext = '';
        $urls = '';
        if ($id = Request::getQuery('id')) {
            $model = new UgcLive();
            $stream = $model->findFirst($id);
            $model = new Admin();
            $anchor = $model->findFirst($stream->admin_id);

            $model = new UgcLiveRoom();
            $room = $model->findFirst(array(
                'conditions' => 'admin_id = :admin_id:',
                'bind' => array('admin_id' => $stream->admin_id)));
            $room_name = '';
            if ($room) {
                $room_item_no = 'zbt' . str_pad($room->id, 5, '0', STR_PAD_LEFT);
                $room_name = $room->roomname;
            }
            $stream_item_no = 'S' . date('ymd', $stream->start_time) . str_pad($stream->id, 3, '0', STR_PAD_LEFT);

            $stream_ext = array('anchor' => $anchor->name, 'room_name' => $room_name,'stream_item_no' => $stream_item_no);

            $ugc_live_room = new UgcLiveRoomController();
            $Qua = $ugc_live_room->getQua_name();

            $urls = array();
            array_Push($urls, array('type' => '用户推流地址', 'url' => $stream->rtmp_url, 'pic_quality' => ''));
            $num = 1;
            foreach (array('cdn_url1', 'cdn_url2', 'cdn_url3') as $cdn) {
                if (!empty($stream->$cdn)) {
                    $q_i = str_replace('p', '', str_replace('_', '', strrchr($stream->$cdn, '_')));
                    array_push($urls,
                        array('type' => "CDN推流{$num}", 'url' => $stream->$cdn, 'pic_quality' => $Qua[$q_i]));
                }
                $num++;
            }
        }
        View::setVars(compact('stream','stream_ext', 'urls'));
    }


}