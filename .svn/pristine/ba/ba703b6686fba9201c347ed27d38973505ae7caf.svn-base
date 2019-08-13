<?php
/**
 *  模板管理 xy
 *  model templates
 *  @created    2015-11-16
 */


/*模板管理分类*/

class TplController extends \BackendBaseController {

    public function listAction() {
    	$domain_id = Request::get('domain_id');
        if($domain_id){
            $domain =Domains::findOneDomain($domain_id);
        }
    	$data = Templates::findAll($domain_id);
        if ($domain->service_type == 'interaction')
            $listtype = Templates::listInterMaps();
        else
            $listtype = Templates::listType();
         
    	View::setVars(compact('data','domain_id','listtype','domain'));
    }

    public function addAction() {
        $model = new Templates();
        $messages = [];
        $channel_id = Session::get('user')->channel_id;
        $domain_id = Request::get('domain_id');
        $domain =Domains::findOneDomain($domain_id);
        if (Request::isPost()) {
            $data = Request::getPost();
            $filedata = $this->validateAndUpload($messages);
            $filedatas = json_decode($filedata,true);
            foreach ($filedatas as $key => $value) {
                if($value['path'] != '') {
                    $data['channel_id'] = $channel_id;
                	$data['domain_id'] = $domain_id;
                	$data['author_id'] = Session::get('user')->id;
                    $data['created_at'] = $data['updated_at'] = time();
                    $data['status'] = 1;
                    $data['path'] = $value['path'];
                    $data['name'] = $value['name'];
                    $data['content'] = $value['content'];
                    
                    $unique = Templates::checkUnique($domain_id,$value['path']);
                    if(!empty($unique)) {
                        $value['updated_at'] = time();
                        $unique->update($value);
                    }
                    else if($model->save($data)){
                        if($data['type'] != 'static') {
                            $key = 'smarty:'.$domain_id.':'.$data['path'];
                            $tpldata = array('content'=>$data['content'],'updated_at'=>$data['updated_at']);
                            MemcacheIO::set($key, $tpldata);
                        }
                        $model = new Templates();
                    } 
                }
            }
            $messages[] = Lang::_('success');
        }
        if ($domain->service_type == 'interaction')
            $listtype = Templates::listInterMaps();
        else
            $listtype = Templates::listType();

        View::setMainView('layouts/add');
        View::setVars(compact('model','messages','listtype','domain_id'));
    }

    public function editAction() {
        $id = Request::get('id');
        $domain_id = Request::get('domain_id');
        $domain = Domains::findOneDomain($domain_id);
        $messages = [];
        $model = Templates::findFirst($id);
        $channel_id = Session::get('user')->channel_id;
        if($model->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (Request::isPost()) {
            $data_up = Request::getPost();
            $filepath = $this->validateAndUpload($messages,$id);
            $path = json_decode($filepath,true);
            if($path[0]['path']) {
                $data_up['path'] = $path[0]['path'];
                $data_up['name'] = $path[0]['name'];
                $data_up['content'] = $path[0]['content'];
            }
            $data_up['updated_at'] = time();

            if(!$model->update($data_up)){
                foreach ($model->getMessages() as $msg) {
                    $messages[] = $msg->getMessage();
                }
            }else{
                if($data_up['type'] != 'static') {
                    $key = 'smarty:'.$model->domain_id.':'.$data_up['path'];
                    $tpldata = array('content'=>$data_up['content'],'updated_at'=>$data_up['updated_at']);
                    MemcacheIO::set($key, $tpldata);
                }
                $messages[] = Lang::_('success');
            }
        }
        if ($domain->service_type == 'interaction')
            $listtype = Templates::listInterMaps();
        else
            $listtype = Templates::listType();
        View::setMainView('layouts/add');
        View::setVars(compact('messages','model','listtype','domain_id'));

    }

    /**
     * json数据
     */
    public function deletefilesAction() {
        $id = Request::get('id', 'int');
        StaticFiles::deletepath($id);
        echo "ok";
        exit;
    }


    /**
     * json数据
     */
    public function jsonAction() {
        header("Content-Type: application/json");
        $data_id = Request::get('id', 'int');
        $data_id = isset($data_id) ? $data_id : 0;
        $tree = StaticFilesTree::getTree($data_id);
        $temp = $tree->getFileTreeJson(0);
        echo json_encode($temp);
        exit;
    }

    public function topicAction() {
        $data_id = Request::get('id');
        $model = new Templates();
        $messages = [];
        $channel_id = Session::get('user')->channel_id;

        $domains =Domains::findDomainsByType($channel_id, 'frontend');

        $domain_id = Request::get('domain_id');

        if(!$domain_id&&count($domains)) {
            $domain_id = $domains[0]['id'];
        }
        $tpl = false;
        $tplfriend = TemplateFriends::checkUniqueTopic($domain_id, $data_id);
        if($tplfriend) {
            $tpl = Templates::getOne($tplfriend->template_id);
        }

        if (Request::isPost()) {
            $data = Request::getPost();
            $filedata = $this->validateAndUpload($messages, 0, $data['topicfile']);
            $filedatas = json_decode($filedata,true);
            if($data['topicfile']=='tpl') {
            foreach ($filedatas as $key => $value) {
                if($value['path'] != '') {
                    $data['channel_id'] = $channel_id;
                    $data['domain_id'] = $domain_id;
                    $data['author_id'] = Session::get('user')->id;
                    $data['created_at'] = $data['updated_at'] = time();
                    $data['status'] = 1;
                    $data['path'] = $value['path'];
                    $data['name'] = $value['name'];
                    $data['content'] = $value['content'];
                    $data['type'] = 9;
                    $data['data_id'] = $data_id;

                    if(!empty($tpl)) {
                        $value['updated_at'] = time();
                        $tpl->update($value);
                    }
                    else if($model->save($data)) {
                        $modelfriend = new TemplateFriends();
                        $data_up['channel_id'] = $channel_id;
                        $data_up['domain_id'] = $domain_id;
                        $data_up['template_id'] = $model->id;
                        $data_up['category_id'] = 0;
                        $data_up['region_id'] = 0;
                        $data_up['data_id'] = $data_id;
                        $data_up['url'] = "/".$data_id;
                        $data_up['created_at'] = time();
                        $data_up['updated_at'] = time();
                        $domain = TemplateFriends::checkUnique($domain_id,$data_up['url'],0);
                        if(empty($domain)) {
                            $modelfriend->save($data_up);
                        }

                        if($data['type'] == 'tpl') {
                            $key = 'smarty:'.$domain_id.':data_id:'.$data_id;
                            $tpldata = array('content'=>$data['content'],'updated_at'=>$data['updated_at']);
                            MemcacheIO::set($key, $tpldata);
                        }
                        $model = new Templates();
                    }
                }
            }
                header("Location: /tpl/topic?id=".$data_id);
            }
          	
            $this->sendToCDN($data_id);
            $messages[] = Lang::_('success');
        }
        View::setMainView('layouts/tree3');
        View::setVars(compact('model','domains','messages','domain_id', 'tpl', 'data_id'));
    }

    public function deleteAction() {
        $id = Request::get('id');
        $data = Templates::findFirst($id);
        $data_friend = TemplateFriends::find(array('conditions'=>'template_id = '.$id));
        $channel_id = Session::get('user')->channel_id;
        if($data->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (!empty($data) && $data->channel_id==Session::get("user")->channel_id ) {
            $url = oss::url($data->path);
            if(@fopen($url, 'r')) {
                //unlink($url);
            } 
            foreach ($data_friend as $key => $friend) {
                $fdata = TemplateFriends::findFirst($friend->id);
                $fdata->delete();
            }
            $data->delete();
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }        
        exit;
    }

    //type > 10、domain_id、channel_id 是唯一
    public function typeSameAction() {
        $type = Request::get('type');
        $typeexist = '';
        if($type > 10) {
            $domain_id = Request::get('domain_id');
            $channel_id = Session::get('user')->channel_id;
            $parameters = array();
            $parameters['conditions'] = "channel_id = $channel_id and domain_id = $domain_id and type = $type";
            $typeexist = Templates::findFirst($parameters);
        }
        echo (!$typeexist)?'ok':'exist';
        exit;
        
    }

    public function validateAndUpload(&$messages, $id=0, $topicfile='') {
    	$channel_id = Session::get('user')->channel_id;
        $fileerror = false;
        $errormsg = "";
        $origin_name = "none";
        $resultfiles = array();
        $content = "null";
        if($topicfile!='public') {
            $data_id = Request::get('id');
        }
        /* 开始处理文件 */
        if (Request::hasFiles() == true) {
            foreach (Request::getUploadedFiles() as $file) {
                $origin_name  = $file->getName();
                $ext = $file->getExtension();
                if(strtolower($ext) == 'zip'&&$topicfile!="tpl") {
                    if(in_array(strtolower($topicfile), ["js", "css", "images", "vr"])) {
                        $datas = $this->zipFile(strtolower($ext), $file, $topicfile, $data_id, $channel_id);
                    }
                    else {
                        $datas = $this->zipFile(strtolower($ext),$file);
                    }
                    return $datas;
                    exit;
                }

                list($midname, $fileerror) = $this->checkExt($origin_name, $topicfile);

                if($fileerror) {
                    $tempfile = array('path' => '', 'name' => $origin_name.'('.$errormsg.')', 'error'=>1 );
                }
                else if(strtolower($ext) == 'tpl') {
                    $content = file_get_contents($file->getTempName());
                    if($topicfile=="tpl") {
                    	
                    	$dataTitle = Data::getByDataId($channel_id, $data_id);
                    	$configArr = F::getConfig('domain_config');
                    	$url = $configArr['frontend'].$data_id;
                    	$re = F::cdnProxy($dataTitle, $data_id, array($url));

                        StaticFiles::savepath($channel_id."/".$data_id."/tpl/topic_".$data_id.".tpl");
                        $tempfile = array('path'=>"topic_".$data_id.".tpl", 'name'=> "topic_".$data_id.".tpl", 'content'=> $content);
                    }
                    else {
                        $tempfile = array('path'=>$origin_name, 'name'=> $origin_name, 'content'=> $content);
                    }
                }
                else {
                    $path = $channel_id."/public/".$midname."/".$origin_name;
                    Oss::uploadFile($path, $file->getTempName());
                    $tempfile = array( 'path' => $path, 'name' => $origin_name,'content'=>$content);
                }
                
                array_push($resultfiles, $tempfile);
                if($id > 0) continue;
            }
        }
        $datas = json_encode($resultfiles);
        return $datas;
        
    }



    public function zipFile($ext, $file, $res_base='public', $data_id, $channel_id) {
        $channel_id = Session::get('user')->channel_id;
        $content = "null";
        $zip = new ZipArchive;
        $Resource_Dir_Target = ($res_base=='public')?$res_base:($channel_id.'/'.$data_id."/".$res_base);
        @mkdir($Resource_Dir_Target, 0755, true);
                    
        if($ext=='zip' && $zip->open($file->getTempName())===true) {
            for($i=0;$i<$zip->numFiles;$i++){
                $entry=$zip->getNameIndex($i);
                if (strpos($entry, "/")) {
                    $zip->extractTo($Resource_Dir_Target, $entry);
                    $hostdir = $Resource_Dir_Target.'/'.$entry;
                    if(is_file($hostdir)) {
                        $filename = basename($hostdir);
                        list($midname, $fileerror) = $this->checkExt($filename, $res_base);
                        if(!$fileerror) {
                            $path = $Resource_Dir_Target.'/'.$entry;
                            $ext = substr($entry, strrpos($filename, '.')+1);
                            if($ext == 'tpl') {
                                $content = file_get_contents($path);
                                $tempfile[] = array('path'=>$filename, 'name'=> $filename, 'content'=> $content);
                            }
                            else {
                                if($res_base!='public') {
                                    $filepath = $hostdir;
                                }
                                else {
                                    $filepath = $Resource_Dir_Target.'/'.$entry;
                                }
                                StaticFiles::savepath($filepath);
                                Oss::uploadFile($filepath, $path);
                                $tempfile[] = array( 'path' => $filepath, 'name' => $filename,'content'=>$content);
                            }
                            unlink($path);
                        }
                        else {
                            $tempfile[] = array('path' => '', 'name' => $filename.'('.$errormsg.')', 'error'=>1 );    
                        }
                    }
                }
                else {
                    list($midname, $fileerror) = $this->checkExt($entry, $res_base);
                    if(!$fileerror) {
                        $zip->extractTo($Resource_Dir_Target, $entry);
                        $ext = substr($entry, strrpos($entry, '.')+1);
                        $path = $Resource_Dir_Target.'/'.$entry;
                        if($ext == 'tpl') {
                            $content = file_get_contents($path);
                            $tempfile[] = array('path'=>$entry, 'name'=> $entry, 'content'=> $content);
                        }
                        else {
                            $filepath = $Resource_Dir_Target.'/'.$entry;
                            StaticFiles::savepath($filepath);
                            Oss::uploadFile($filepath, $path);
                            $tempfile[] = array( 'path' => $filepath, 'name' => $entry,'content'=>$content);
                        }
                        unlink($path);
                    }
                    else {
                        $tempfile[] = array('path' => '', 'name' => $entry.'('.$errormsg.')', 'error'=>1 );    
                    }
                }
            }
            $zip->close();
        }
        $datas = json_encode($tempfile);
        return $datas;
    }

    public function checkExt($origin_name, $res_base='public') {
        $image_ext=array("jpg", "png", "jpeg", "gif", "js", "css", "swf", "tpl");
        $vr_ext=array("jpg", "png", "jpeg", "gif", "js", "css", 'swf', 'tpl', 'xml');
        $tpl_ext=array("tpl");
        $fileerror = false;
        $errormsg = "";
        $midname = "";
        if(!preg_match("/^([.a-zA-Z0-9_-]*)$/i", $origin_name, $matches)) {
            $errormsg = lang::_('文件名不标准');
            $fileerror = true;
        }
        else {
            $ext = substr($origin_name, strrpos($origin_name, '.')+1);
            if($res_base=='tpl') {
                if(!in_array(strtolower($ext), $tpl_ext)) {
                    $errormsg = Lang::_('无效文件');
                    $fileerror = true;
                }
            }
            else if($res_base=='vr') {
                if(!in_array(strtolower($ext), $vr_ext)) {
                    $errormsg = Lang::_('无效文件');
                    $fileerror = true;
                }
            }
            else {
                if(!in_array(strtolower($ext), $image_ext)) {
                    $errormsg = Lang::_('无效文件');
                    $fileerror = true;
                }
            }
            if(in_array(strtolower($ext),["jpg","png","jpeg","gif"]))
                $midname = 'image';
            if(in_array(strtolower($ext),['js','css']))
                $midname = $ext;
        }
        return array($midname, $fileerror);
    }
    
 	/**
 	 *  根据data_id发送数据到cdn
 	 * @param unknown $data_id
 	 */
 	public function sendToCDN($data_id) {
 		$channelId = !empty(Session::get('user')) ? Session::get('user')->channel_id : "-1";
 		$dataTitle = Data::getByDataId($channelId, $data_id)->title;
 		$staticFiles = StaticFiles::getDataById($data_id);
 		$staticFiles = array_chunk($staticFiles, 10);
 		foreach ($staticFiles as $k => $v) {
 			$data = $this->sendData($v);
	 		$re = F::cdnProxy($dataTitle,$data_id,$data,$channelId);
 		}
 	}

 	/**
 	 * 文件具体数据
 	 * @param unknown $staticFiles
 	 * @return multitype:multitype:number string unknown
 	 */
 	private function sendData($staticFiles) {
 		$data = array();
 		foreach ($staticFiles as $k => $v) {
 			if($v['type'] != 'tpl') {
	 			$filepath = cdn_url('image', $v['path']);
	 			$fileType = $v['type'] == 'png' || $v['type'] == 'jpg' || $v['type'] == 'gif' ? 3 : 4; 
	 			$data[$k] = array(
	 				"item_id"	   => $v['id'],
	 				"operation"    => 1,
	 				"file_type"    => $fileType,
	 				"source_path"  => $filepath,
	 				"publish_path" => $filepath,
	 				"md5"          => md5($filepath),
	 				"file_size"    => "0"
	 			);
 			}
 		}
 		return $data;
 	}
 	
}