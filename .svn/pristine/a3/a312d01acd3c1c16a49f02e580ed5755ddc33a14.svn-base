<?php
/**
 * Created by PhpStorm.
 * User: cyc
 * Date: 2015/12/9
 * Time: 9:23
 */
class SourcesController extends \BackendBaseController {

    public function indexAction(){
        $channel_id=Session::get('user')->channel_id;
        $data = Referer::findAll($channel_id);
        View::setVars(compact('data'));
    }

    public function addAction(){
        $model = new  Referer();
        if ($data=Request::getPost()) {
            $validator = Referer::makeValidator($data);
            $data['channel_id'] = Session::get('user')->channel_id;
            $data['status'] = 1;
            $data['sort'] = 0;
            if (!$validator->fails() && $thumb = $this->validateAndUpload($messages)) {
                $ss=new Referer();
                $data['thumb'] = $thumb;
                $result = $ss->addSource($data);
                if ($result) {
                    $messages[] = Lang::_('success');
                } else {
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages','model'));
    }

    public function deleteAction(){
        $id=$this->request->getQuery("id","int");
        $return=Referer::deleteSource($id);
        if($return){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    public function modifyAction(){
        $id = Request::getQuery('id', 'int');
        $channel_id = Session::get('user')->channel_id;
        $messages = array();
        if( ($data=Request::getPost()) && $thumb = $this->validateAndUpload($messages) ) {

            $data['thumb'] = $thumb;
            $validator = Referer::makeValidator($data);
            if (!$validator->fails()) {
                $ss=Referer::findOne($id);
                $ss->assign($data);
                $result = $ss->update();
                if ($result) {
                    $messages[] = Lang::_('success');
                } else {
                    $messages[] = Lang::_('error');
                }
            } else {
                $messages = $validator->messages()->all();
            }
        }
        if (Request::getQuery()) {
            $id = Request::getQuery('id', 'int');
            $data = Referer::findOne($id);
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages','data'));
    }

    public function getNameAction() {
        $referer_url = Request::getQuery('referer_url');
        preg_match("/^(http[s]?:\/\/)?([^\/]+)/i", $referer_url, $arr_domain);
        $referer_url = "http://".$arr_domain[2];
        $channel_id = Session::get('user')->channel_id;
        $referer_m = Referer::findByDomain($channel_id, $referer_url);
        if($referer_m){
            $refer_name = (false===stripos($referer_m->name, "未知网站"))?$referer_m->name:"未知网站";
            $refer_logo = cdn_url("image",$referer_m->thumb);
            $refer_url  = $referer_m->url;
            $refer_id   = $referer_m->id;
            $arr=array('code'=>200, 'data'=>compact("refer_name","refer_id","refer_logo","refer_url"));
            
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        header('Content-type: application/json');
        echo json_encode($arr);
        exit;
    }

    public function ajaxeditAction()
    {

        $thumb_url= $this->uploadBase64StreamImg();
        $data = Request::getPost();
        if($thumb_url)
        {
            $data['thumb'] = $thumb_url;
        }else{
            unset($data['thumb']); //没有新的数据传上来,不需要更新thumb字段
        }
        $id = $data['id'];
        if($id){
            //修改
            $refer = Referer::findOne($id);
            unset($data['url']);
            $refer->assign($data);
            $refer_url = $refer->url;
            $result  = $refer->update();
            $id = $refer->id;
        }else{
            //增加
            $ss=new Referer();
            $data['channel_id'] = Session::get('user')->channel_id;
            $data['status'] = 1;
            preg_match("/^(http[s]?:\/\/)?([^\/]+)/i", $data['url'], $arr_domain);

            $data['url'] = "http://".$arr_domain[2];
            $data['sort'] = 0;
            $refer_url = $data['url'];
            $result = $ss->addSource($data);
            $id = $result;
        }
        if($result){
            echo json_encode(array("success"=>1,"id"=>$id,"url"=>$refer_url,"thumb"=>$thumb_url?cdn_url("image",$thumb_url):Request::getPost('thumb')));
        }else{
            echo json_encode(array("success"=>0));
        }
        exit();
    }

    public function ajaxgetAction(){
        $id = Request::get("id","int",0);
        $referer = Referer::findFirstOrFail($id);
        if($referer)
        {
            $img_path = str_replace(cdn_url("image",""),$referer->thumb,"");
            echo json_encode(["success"=>true,"data"=>["id"=>$referer->id,"url"=>$referer->url,"name"=>$referer->name,"img_path"=>$img_path,"thumb"=>$referer->thumb?cdn_url("image",$referer->thumb):""]]);
            exit();
        }
        echo json_encode(["success"=>false,"data"=>[]]);
        exit();
    }

    
    protected function validateAndUpload(&$messages) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[0];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/referlogs');
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

    protected function uploadBase64StreamImg(){
        $base64_image_content = Request::getPost("thumb");
        $url ="";
        if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $base64_image_content, $files))
        {
            $url = Auth::user()->channel_id.'/1/referlogs/'.date('Y/m/d/').md5(uniqid(str_random())).".{$files[2]}";
            Oss::uploadContent($url,base64_decode(str_replace($files[1], '', $base64_image_content)));
        }
        return $url;
    }


}