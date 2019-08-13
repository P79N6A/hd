<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/5/6
 * Time: 10:35
 */
class UgcLiveRoomController extends \BackendBaseController
{
    private $quaname = array();

    /*
     *
     *
     * */
    public function getQua_name()
    {
        return array(
            UgcLive::STREAM_QUA_HIGH_PIX => Lang::_('qua_high'),
            UgcLive::STREAM_QUA_STANDARD_PIX => Lang::_('qua_standard'),
            UgcLive::STREAM_QUA_SUPPER_PIX => Lang::_('qua_supper')
        );
    }

    public function indexAction()
    {
        $data = UgcLiveRoom::findAll();
        $roominfo = [];
        if(!empty($data->models)){
            $rooms = $data->models->toArray();
            foreach ($rooms as $room) {
                $model_obj = new Admin();
                $anchor = $model_obj->findFirst($room['admin_id']);
                $item = 'zbt' . str_pad($room['id'], 5, '0', STR_PAD_LEFT);
                $anchor_name = $anchor->name;
                $status = UgcLive::getWorkStatus($anchor->id);
                $line_num = UgcLive::getLineNum($anchor->id);
                $roominfo[$room['id']] = compact(array('item', 'anchor_name', 'status', 'line_num'));
            }
        }
        View::setVars(compact('data', 'roominfo'));
    }

    public function editAction()
    {
        $id = Request::get('id', 'int', 0);
        if (Request::isPost()) {
            $data = Request::getPost();
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                $data['thumb'] = $this->validateAndUpload($messages, '0');
            }
            if ($this->validateFields($messages, $data)) {
                $model_obj = new Admin();
                $admin = $model_obj->findFirst($data['admin_id']);
                $data['channel_id'] = $admin->channel_id;
                $model_obj = new UgcLiveRoom();
                $room = $model_obj->findFirst($id);
                if ($room->save($data)) {
                    $messages[] = Lang::_('success');
                } else {
                    $messages[] = Lang::_('failed');
                }
            }
        }
        $objModel = new UgcLiveRoom();
        $live_room = $objModel->findFirst($id);
        $objModel = new Admin();
        $anchor = $objModel->findFirst($live_room->admin_id);
        $objModel = new AdminExt();
        $admin_ext = $objModel->findFirst($anchor->id);
        $gid = $admin_ext->ugc_group_id;
        $userGroupList = AdminGroup::getAll();
        $userList = Admin::getUnLockAdminKVList(AdminExt::getAdminIdsByUgcGroup([$gid]));
        View::setMainView('layouts/add');
        View::setVars(compact(array('live_room', 'userGroupList', 'gid', 'userList')));
    }


    public function addAction()
    {
        //TODO 频道ID
        if (Request::isPost()) {
            $data = Request::getPost();
            if (Request::getUploadedFiles()[0]->getError() == 0) {
                $data['thumb'] = $this->validateAndUpload($messages, '0');
            }

            if ($this->validateFields($messages, $data))
            {
                $data['createat'] = time();
                $data['showstatus'] = 1;
                $data['runstatus'] = 1;
                $model_obj = new Admin();
                $admin = $model_obj->findFirst($data['admin_id']);
                $data['channel_id'] = $admin->channel_id;
                $model_obj = new UgcLiveRoom();
                if ($model_obj->save($data)) {
                    $messages[] = Lang::_('success');
                } else {
                    $messages[] = Lang::_('failed');
                }
            }
        }
        //TODO 获取频道下的用户数据
        $userGroupList = AdminGroup::getAll();
        $def_gid = $userGroupList[0]['id'];
        $userList = Admin::getUnLockAdminKVList(AdminExt::getAdminIdsByUgcGroup([$def_gid]));
        View::setMainView('layouts/add');
        View::setVars(compact(array('userGroupList', 'userList')));
    }

    protected function validateFields(&$message, $i)
    {
        if (!$i['admin_id']) {
            $message[] = Lang::_('please choose an anchor');
            return false;
        }
        return true;
    }

    protected function validateAndUpload(&$messages, $i)
    {
        $path = '';
        if (Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[$i];
            $error = $file->getError();
            if (!$error) {
                $ext = $file->getExtension();
                if (in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id . '/logos');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif ($error == 4) {
                $path = Request::getPost('oldlogo');

            } else {
                $messages[] = Lang::_('unknown error');
            }
        } else {
            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }


    public function adminListByGroupIdsAction()
    {
        header('Content-type:Application/json');
        $groupids[] = Request::getQuery('gid');
        $adminids = AdminExt::getAdminIdsByUgcGroup($groupids);
        $adminlist = [];
        if ($adminids) {
            $adminlist = Admin::getUnLockAdminKVList($adminids);
        }
        echo json_encode($adminlist);
        exit;
    }

    public function detailAction()
    {
        $id = Request::getQuery('id', 'int', '0');
        $model = new UgcLiveRoom();
        if ($room = $model->findFirst($id)) {
            if($runstatus = Request::getQuery('runstatus'))
            {
                $room->runstatus = $runstatus;
                $model->save($room);
            }

            $model_obj = new Admin();
            $anchor = $model_obj->findFirst($room->admin_id);
            $room_ext['item_no'] = 'zbt' . str_pad($room->id, 5, '0', STR_PAD_LEFT);
            $room_ext['anchor_name'] = $anchor->name;
            $room_ext['audience'] = 50;
            $room_ext['line'] = UgcLive::getLineNum($room->admin_id);
            $online_stream_list = UgcLive::getOnlineList($room->admin_id);
            $data = array();
            $Qua = $this->getQua_name();
            foreach ($online_stream_list as $i => $stream) {
                $urls = array();
                array_Push($urls, array('type' => '用户推流地址', 'url' => $stream['rtmp_url'],
                    'pic_quality' => '','pc_url' => $stream['cdn_url1']));
                $num = 1;
                foreach (array('cdn_url1', 'cdn_url2', 'cdn_url3') as $cdn) {
                    if (!empty($stream[$cdn])) {
                        $q_i = str_replace('p', '', str_replace('_', '', strrchr($stream[$cdn], '_')));
                        array_push($urls,
                            array('type' => "CDN推流{$num}", 'url' => $stream[$cdn], 'pic_quality' => $Qua[$q_i]));
                    }
                    $num++;
                }
                $terminal = $stream['terminal'] == '1' ? 'IOS' : $stream['terminal'] == '2' ? '安卓' : '其他';
                $name = '线路' . ($i + 1);
                $id = $stream['id'];
                array_push($data, compact('id','terminal', 'name', 'urls'));
            }
            View::setVars(compact(array('room', 'room_ext', 'data')));
        }
    }

    public function playstreamAction()
    {
        $id = Request::getQuery('id');
        $model = new UgcLive();
        $stream = $model->findFirst($id);
        $url = $stream->cdn_url1;
        $wap_url =$stream->cdn_url2;
        View::setVars(compact(array('url','wap_url')));
    }
}