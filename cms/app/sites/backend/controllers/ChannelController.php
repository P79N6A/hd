<?php
/**
 *  频道系统
 *  controller Channel
 *  @author     Shunfei Zhou
 *  @created    2015-9-16
 *
 */
class ChannelController extends \BackendBaseController {
    const FILE_NOT_UPLOAD = 4;
    public function listAction() {
        $data = Channel::findAll();
        View::setVars(compact('data'));
    }

    public function indexAction() {
        $user_id = $user_id = Session::get('user')->id;
        $user = Admin::findFirst($user_id);
        $channel_id = $user->channel_id;
        if($data = Channel::findFirst($channel_id)) {
            $data=$data->toArray();
            $managers = Channel::findChannelAdmin($data['id']);
            $data['managers'] = $managers;
            $sites = Channel::findChannelSites($channel_id);
            $data['sites'] = $sites;
            $data['QRcode'] = '';
        }
        View::setVars(compact('data'));
    }

    public function masterlistAction() {
        $data = Admin::findMaster();
        $channel = Channel::listChannel(true);
        View::setVars(compact('data', 'channel'));
    }
    public function addmasterAction() {
        $data = new Admin();
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = Admin::masterValidator($data);
            if (!$validator->fails()) {
                $model = new Admin();
                $data['is_admin'] = 1;
                $data['status'] = 1;
                $messages = $model->createAdmin($data);
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $channel = Channel::listChannel(true);
        View::setMainView('layouts/add');
        View::setVars(compact('data','channel','messages'));
    }
    
    public function editmasterAction() {
        $data = Admin::findFirst(Request::get('id', 'int'));
        $messages = [];
        if (Request::isPost()) {
            $input = Request::getPost();
            $validator = Admin::masterValidator($input,$data->id);
            if (!$validator->fails()) {
                if ($data->updateAdmin($data,$input,[
                    'name','mobile','channel_id','password','updated_at'
                ])){
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        $channel = Channel::listChannel(true);
        View::setMainView('layouts/add');
        View::setVars(compact('data','channel','messages'));
    }
    
    /**
     * 创建频道
     */
    public function createAction() {
        $data = '';
        $messages = [];
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = Channel::makeValidators($data);
            if (!$validator->fails()) {
                if (!$this->isFileTypeValid()) {
                    $messages[] = Lang::_('please upload valid image');
                } else {

                    if($data['description'] != ""){
                        $data["address"] = json_encode([
                            "province_id"=> $data["province_id"],
                            "city_id"=> $data["city_id"],
                            "county_id" => $data["county_id"],
                            "town_id" => $data["town_id"],
                            "village_id"=> $data["village_id"],
                            "description" => $data["description"]
                        ],JSON_UNESCAPED_UNICODE);
                    }else{
                        $data['address'] ="";
                    }

                    $uploadFile = '';
                    $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'channel_logo');
                    if ($isFileEmpty) {
                        $messages[] = Lang::_('please choose upload image');
                    } else if (!empty($uploadFile)){
                        $data['channel_logo'] = $this->uploadFile($uploadFile);
                    } else if(!$isFileEmpty && empty($uploadFile)) {
                        $messages[] = Lang::_('logo上传失败');
                    }
                    $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'channel_logo_slave');
                    if (!$isFileEmpty && !empty($uploadFile)) {
                        $data['channel_logo_slave'] = $this->uploadFile($uploadFile);
                    } else if(!$isFileEmpty && empty($uploadFile)) {
                        $messages[] = Lang::_('副logo上传失败');
                    }
                }
                if (empty($messages)) {
                    $channel = new Channel();
                    if ($channel->create($data)) {
                        $messages[] = Lang::_('success');
                    } else {
                        foreach ($channel->getMessages() as $m) {
                            array_push($messages, $m->getMessage());
                        }
                    }
                }

            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setVars(compact('messages'));
        View::setMainView('layouts/add');
    }

    private function isFileTypeValid() {
        if (Request::hasFiles(true)) {
            $files = Request::getUploadedFiles(true);
            foreach ($files as $file) {
                $fileType = $file->getRealType();
                return in_array($fileType, ['image/jpg', 'image/jpeg', 'image/gif', 'image/png']);
            }
        }
        return true;
    }

    private function isFileUploadEmpty(&$uploadFile, $name) {
        $uploadFile = '';
        if(Request::hasFiles()) {
            $files = Request::getUploadedFiles();
            foreach ($files as $file) {
                if ($file->getKey() == $name) {
                    $error = $file->getError();
                    if (!$error) {
                        $uploadFile = $file;
                    }
                    return $error == self::FILE_NOT_UPLOAD;
                }
            }
        }
        return true;
    }
    /**
     * 修改频道
     */
    public function modifyAction() {
        $messages = [];
        $channel_id = Request::getQuery('id', 'int');
        $data = Channel::getOneChannel($channel_id);
        $channel = $data;
        if (Request::isPost()) {
            $data_up = Request::getPost();
            $validator=Channel::editValidators($data_up);
            if($validator->passes()) {

                if (!$this->isFileTypeValid()) {
                    $messages[] = Lang::_('please upload valid image');
                } else {

                    if($data_up['description'] != ""){
                        $data_up["address"] = json_encode([
                            "province_id"=> $data_up["province_id"],
                            "city_id"=> $data_up["city_id"],
                            "county_id" => $data_up["county_id"],
                            "town_id" => $data_up["town_id"],
                            "village_id"=> $data_up["village_id"],
                            "description" => $data_up["description"]
                        ],JSON_UNESCAPED_UNICODE);
                    }else{
                        $data_up['address'] ="";
                    }


                    $uploadFile = '';
                    $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'channel_logo');
                    if (empty($channel->channel_logo) && $isFileEmpty) {
                        $messages[] = Lang::_('please choose upload image');
                    } else if (!$isFileEmpty && !empty($uploadFile)){
                        $channel->channel_logo = $this->uploadFile($uploadFile);
                    } else if(!$isFileEmpty && empty($uploadFile)) {
                        $messages[] = Lang::_('logo上传失败');
                    }
                    $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'channel_logo_slave');
                    if (!$isFileEmpty && !empty($uploadFile)) {
                        $channel->channel_logo_slave = $this->uploadFile($uploadFile);
                    } else if(!$isFileEmpty && empty($uploadFile)) {
                        $messages[] = Lang::_('副logo上传失败');
                    }
                    $isFileEmpty = $this->isFileUploadEmpty($uploadFile, 'watermark');
                    if (!$isFileEmpty && !empty($uploadFile)) {
                        $channel->watermark = $this->uploadFile($uploadFile);
                    }
                }
                if(empty($messages)) {
                    $channel->channel_instr = $data_up['channel_instr'];
                    $channel->channel_info = $data_up['channel_info'];
                    $channel->channel_url = $data_up['channel_url'];
                    $channel->name = $data_up['name'];
                    $channel->shortname = $data_up['shortname'];
                    $channel->tag = $data_up['tag'];
                    $channel->status = $data_up['status'];
                    $channel->address = $data_up['address'];
                    $result = $channel->update();
                    if ($result) {
                        $messages[] = Lang::_('success');
                    } else {
                        foreach ($channel->getMessages() as $m) {
                            array_push($messages, $m->getMessage());
                        }
                    }
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setVars(compact('messages', 'data'));
        View::setVar("channelModify",1);
        View::setMainView('layouts/add');
    }


    public function deleteAction() {
        $channel_id = $this->request->getQuery("id","int");
        $channel = Channel::findFirst($channel_id);
        $return = $channel->delete();
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    protected function uploadFile($file) {
        $ext = $file->getExtension();
        $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/logos');
        return $path;

    }


    public function jsonGetChannelRegionAction(){
        $channelId = Request::getQuery("channel_id","int",0);
        $data = [];
        if($channelId>0){
            $channel = Channel::findFirst($channelId);
            if($channel->address != ""){
               $data = json_decode($channel->address,true);
            }
        }
        echo json_encode(array("success"=>($data?true:false),'data'=>$data));
        exit;
    }

}