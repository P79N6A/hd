<?php
/**
 * Created by chen.banghong.
 * User: sylar
 * Date: 2015/9/21
 * Time: 10:43
 */
class CategoryController extends \BackendBaseController {
    const FILE_NOT_UPLOAD = 4;
    
    public function indexAction() {
        $terminal = Request::get("terminal", "trim");
        $condition = array('terminal' => $terminal);
        if($terminal=="") $terminal="web";
        $channel_id = Session::get('user')->channel_id;
        $data = Category::findPagination($channel_id, $terminal);
        $categories = array();
        $tree = CategoryTree::getCategoryTree($channel_id, $terminal);
        foreach($data->models as $model) {
            $object =  json_decode( json_encode($model),true);
            $object['has_child'] = !empty($tree->getChildren($object['id']));
            $object['level'] = 0;
            array_push($categories, $object);
            Category::findDepthChildren($tree, $object, $categories, 0);
        }

        View::setVars(compact('data', 'categories', 'terminal', 'condition'));
    }

    public function addAction() {
        $terminal = Request::get("terminal", "trim");
        $channel_id = Session::get('user')->channel_id;
        $author = Auth::user()->name;
        
        $savesuccess = false;
        if (Request::isPost()) {
            $data = Request::getPost();
            $category = new Category();
            $enName = $category->changeEnName($data["en_name"]);
            $isHasEnName = $category->checkCategoryEnName($enName, Auth::user()->channel_id, -1);
            if ($isHasEnName) {
                $data["en_name"] = $enName;
                $validator = Category::makeValidator($data);
                if (!$validator->fails()) {


                    $data['father_id'] = $data['category_id'] == "" ? $data['category_id'] : "0";
                    $savesuccess = $category->createDatas($data, $channel_id);

                    $messages = array();
                    if (!$savesuccess) {
                        $messages[] = Lang::_('error')." 创建失败，请检查栏目名称，或已经存在，不允许再创建";
                    } else {
                        CategorySeo::saveDatas($category->id, $data, $category->channel_id);
                        $messages[] = Lang::_('success');
                    }
                } else {
                    $messages = $validator->messages()->all();
                }
                $this->deleteAllCategoryJson();
            }
            else {
                $messages[] = '栏目英文名称已经存在';
            }
        }
        $editAction = false;
        View::setMainView('layouts/add');
        View::setVars(compact('messages', 'channel_id', 'terminal', 'author', 'editAction'));
    }


    protected function uploadFile($file) {
        $ext = $file->getExtension();
        $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), Auth::user()->channel_id.'/logos');
        return $path;

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

    public function updateAction() {
        if (Request::isPost()) {
        	$author = Auth::user()->name;
            $channel_id = Session::get('user')->channel_id;
            $data = Request::getPost();
            $category = new Category();
            $enName = $category->changeEnName($data["en_name"]);
            $isHasEnName = $category->checkCategoryEnName($enName, $channel_id, $data['id']);
            if($isHasEnName) {
                $data["en_name"] = $enName;
                $data['channel_id'] = $channel_id;
                $id = Request::getPost("id", "int");

                $this->deleteAllCategoryJson();


                $savesuccess = $category->updateDatas($id, $data);
                if (!$savesuccess) {
                    $messages = array();
                    foreach ($category->getMessages() as $m) {
                        array_push($messages, $m->getMessage());
                    }
                }
                $messages[] = Lang::_('success');
            }
            else {
                $messages[] = '栏目英文名称已经存在';
            }
            $this->renderResult($messages, $savesuccess);
        } else {
            echo 'request method is not accept';
        }
    }

    private function renderResult($messages, $savesuccess) {
        View::setVars(compact('messages'));
        View::setMainView('layouts/add');
        View::setVars(compact('savesuccess'));
        View::pick('layouts/save');
    }
    public function deleteAction() {
        View::setRenderLevel(\Phalcon\Mvc\View::LEVEL_NO_RENDER);
        $this->response->setContentType('application/json', 'utf-8');
        $id = Request::get('id');
        $child = Category::hasChild($id);
        $this->deleteAllCategoryJson();
        if (!empty($child)) {
            echo json_encode(['code' => '100', 'msg' => "该栏目存在子栏目[". $child->name. "]不能被删除"]);
            return;
        } else if(Category::hasContent($id)) {
            echo json_encode(['code' => '100', 'msg' => "该栏目存在存在数据资源不能被删除"]);
            return;
        }
        $data = Category::findFirst($id);
        if (!empty($data)) {
            CategorySeo::deleteData($id);
            $data->delete();
        } else {
            echo json_encode(['code' => '100', 'msg' => '栏目已经被删除']);
        }
        echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);

    }

    /**
     * 编辑栏目
     */
    public function editAction($id = 0) {
        if(empty($id))
            $id = Request::getQuery("id", "int");
        if(!$id) {
            redirect(Url::get("category/add"));
        }
        $channel_id = Session::get('user')->channel_id;
        $model = Category::findById($id);
        $seo = CategorySeo::findById($id);
        
        if (empty($seo)) {
            $seo = new CategorySeo();
            $seo->intro = "";
            $seo->desc = "";
            $seo->title = "";
            $seo->keywords = "";

        }

        $this->deleteAllCategoryJson();
		$author = $model->author_name;
        $parents = $model->getParents();
        $parentcount =  count($parents);
        $allow_types = explode(",", $model->allow_type);
        $cateogry = new Category();
        $cateogrywrited = $cateogry->formatcateogry($id);
        $editAction = true;
        
        View::setMainView('layouts/add');
        View::setVars(compact('model', 'seo', 'parents', 'channel_id', 'allow_types', 'parentcount', 'author', 'cateogrywrited', 'editAction'));
    }

    public function searchAction() {
        Category::findById(Request::get('id'));
    }

    public function addAuthAction() {
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = CategoryAuth::makeValidator($data);
            if (!$validator->fails()) {
                $categoryAuth = new CategoryAuth();
                if ($categoryAuth->save($data)) {
                    $msg[] = Lang::_('success');
                    $this->deleteAllCategoryJson();
                } else {
                    foreach ($categoryAuth->getMessages() as $m) {
                        $msg[] = $m->getMessage();
                    }
                }
            } else {
                $msg = $validator->messages()->all();
            }
            View::setVars(compact('msg'));
        }
        View::setMainView('index/index');
    }

    public function deleteAuthAction() {
        $data = CategoryAuth::findFirst(Request::get('id'));
        if (!empty($data)) {
            $data->delete();
            $this->deleteAllCategoryJson();
        } else {
            echo 'no data was deleted!';
        }
    }

    public function treeAction() {
        dd(CategoryTree::getCategoryTree(1));
    }

    /**
     * json数据
     */
    public function jsonAction() {
        header("Content-Type: application/json");
        $id = Request::get('id', 'int');
        $terminal = Request::get("terminal", "trim");
        $selid = Request::get("selid",'int');
        $id = isset($id) ? $id : 0;
        $channel_id = Session::get('user')->channel_id;
        $user = Session::get('user');
        $redisKey = "category_user_json";
        if(RedisIO::hExists($redisKey,"$channel_id:$terminal:".$user->id)){
            echo RedisIO::hGet($redisKey,"$channel_id:$terminal:".$user->id);
        }else{
            $tree = CategoryTree::getCategoryTree($channel_id, $terminal);
            $temp = $tree->getCategoryTreeJson($id,$selid);
            $json = json_encode($temp);
            RedisIO::hSet($redisKey,"$channel_id:$terminal:".$user->id,$json);
            echo $json;
        }

        exit;
    }


    /**
     * json数据
     */
    public function jsonSelAction() {
        header("Content-Type: application/json");
        $id = Request::get('id', 'int');
        $sel_id = Request::get("selid",'int');
        $terminal = Request::get("terminal", "trim");
        $id = isset($id) ? $id : 0;
        $channel_id = Session::get('user')->channel_id;
        $tree = CategoryTree::getCategoryTree($channel_id, $terminal);
        $temp = $tree->getCategoryTreeJson2($id,$sel_id);
        echo json_encode($temp);
        exit;
    }


    /**
     * 私有栏目联动
     */
    public function privateAjaxAction() {
        $categories = PrivateCategory::query()
            ->andCondition('channel_id', Auth::user()->channel_id)
            ->andCondition('father_id', (int) trim(Request::get('id'), 'cid_'))
            ->execute();
        $rs = [];
        if($categories) {
            foreach($categories as $cate) {
                $rs[] = [
                    'id' => 'cid_'.$cate->id,
                    'text' => $cate->name,
                    'children' => PrivateCategory::count(['conditions' => 'father_id = '.$cate->id]) > 0,
                ];
            }
        }
        header("Content-Type: application/json");
        echo json_encode($rs);
        exit;
    }


    public function bindAction() {
        $this->initFormView();
        $messages = [];
        $id = Request::get('id', 'int');
        $model = Category::findById($id);
        if(!$model) {
            abort(404);
        }
        if(Request::isPost()) {
            $category_bind = (array) Request::getPost('category_bind');
            array_push($category_bind, $id);
            foreach($category_bind as $v) {
                CategoryBind::bind($v, $category_bind);
            }
        }

        $categoryData = CategoryBind::getbindByCategoryid($id);


        View::setVars(compact('model', 'messages', 'categoryData', 'specialCategoryData'));

    }

    private function deleteAllCategoryJson(){
        $redisKey = "category_user_json";
        $keys = RedisIO::hKeys($redisKey);
        foreach ($keys as $key){
            RedisIO::hDel($redisKey,$key);
        }
    }
	/**
	 * 判断栏目英文名称
	 */
    public function checkingCategoryEnNameAction() {
        header("Content-type: application/json;charset='utf-8'");
        $name = Request::getPost('name','string');
        $type = Request::getPost('type','string');
        $id = Request::getPost('id','int',-1);
        $en_name = "";
        $channel_id = Auth::user()->channel_id;
        $category = new Category();
        switch ($type) {
            case "Chinese":
                $pinyin = $this->trimall(Cutf8py::encode($name, 'all'));
                $en_name = $category->changeEnName($pinyin);
                break;
            case "English":
                $en_name = $category->changeEnName($name);
                break;
        }
        $isEnName = $category->checkCategoryEnName($en_name, $channel_id, $id);
        if ($isEnName) {
            $this->echoExit(json_encode(['code' => '200', 'msg' => "success", 'data' => $en_name]));
        } else {
            $this->echoExit(json_encode(['code' => '400', 'msg' => 'error', 'data' => '']));
        }
    }
    
    /**
     * 添加模板
     */
//    public function tplAddAction() {
//    	$category_id = Request::get('id');
//    	$model = new Templates();
//    	$messages = [];
//    	$channel_id = Session::get('user')->channel_id;
//
//    	$domains =Domains::findDomainsByType($channel_id, 'frontend');
//    	$domain_id = Request::get('domain_id');
//    	if(!$domain_id&&count($domains)) {
//    		$domain_id = $domains[0]['id'];
//    	}
//
//    	$tpl = false;
//    	$tplfriend = TemplateFriends::checkUniqueCategory($domain_id, $category_id);
//    	if($tplfriend) {
//    		$tpl = Templates::getOneByType($tplfriend->template_id, 201);
//    	}
//
//    	if (Request::isPost()) {
//    		$data = Request::getPost();
//    		$categoryData = Category::findById($category_id);
//    		$tplController = new TplController();
//    		$filedata = $tplController->validateAndUpload($messages, 0, $data['topicfile'], "category_");
//
//    		$filedatas = json_decode($filedata,true);
//    		if($data['topicfile']=='tpl') {
//    			$datas = array(
//    					"channel_id" => $channel_id,
//    					"domain_id"  =>	$domain_id,
//    					"category_id" =>$category_id,
//    					"en_name" => $categoryData->en_name,
//    					"type" => 201,
//    					"typeTemp" => "category_default_id",
//    			);
//    			$model = $this->saveTplData($tpl, $model, $filedatas, $datas);
//    			header("Location: /category/tplAdd?id=".$category_id);
//    		}
//
//    		$this->sendToCDN($category_id);
//    		$messages[] = Lang::_('success');
//    	}
//    	View::setMainView('layouts/tree3');
//    	View::setVars(compact('model','domains','messages','domain_id', 'tpl', 'category_id'));
//    }


//    public function tplDetailAddAction() {
//        $category_id = Request::get('id');
//        $model = new Templates();
//        $messages = [];
//        $channel_id = Session::get('user')->channel_id;
//
//        $domains =Domains::findDomainsByType($channel_id, 'frontend');
//        $domain_id = Request::get('domain_id');
//        if(!$domain_id&&count($domains)) {
//            $domain_id = $domains[0]['id'];
//        }
//
//        $tpl = false;
//        $tplfriend = TemplateFriends::checkUniqueCategory($domain_id, $category_id);
//
//        if($tplfriend) {
//            $tpl = Templates::getOneByType($tplfriend->template_id, 101);
//        }
//
//        if (Request::isPost()) {
//            $data = Request::getPost();
//            $categoryData = Category::findById($category_id);
//            $tplController = new TplController();
//            $filedata = $tplController->validateAndUpload($messages, 0, $data['topicfile'], "categorydetail_");
//
//            $filedatas = json_decode($filedata,true);
//            if($data['topicfile']=='tpl') {
//                $datas = array(
//                    "channel_id" => $channel_id,
//                    "domain_id"  =>	$domain_id,
//                    "category_id" =>$category_id,
//                    "en_name" => $categoryData->en_name,
//                    "type" => 101,
//                    "typeTemp" => "category_detail_default_id",
//                );
//                $model = $this->saveTplData($tpl, $model, $filedatas, $datas);
//                header("Location: /category/tplDetailAdd?id=".$category_id);
//            }
//
//            $this->sendToCDN($category_id);
//            $messages[] = Lang::_('success');
//        }
//        View::setMainView('layouts/tree3');
//        View::setVars(compact('model','domains','messages','domain_id', 'tpl', 'category_id'));
//    }

    /**
     * 保存模板数据
     * @param unknown $tpl
     * @param unknown $model
     * @param unknown $filedatas
     * @param unknown $datas
     * @return Templates
     */
//    private function saveTplData($tpl, $model, $filedatas, $datas) {
//    	foreach ($filedatas as $key => $value) {
//    		if($value['path'] != '') {
//    			$data['channel_id'] = $datas['channel_id'];
//    			$data['domain_id'] = $datas['domain_id'];
//    			$data['author_id'] = Session::get('user')->id;
//    			$data['created_at'] = $data['updated_at'] = time();
//    			$data['status'] = 1;
//    			$data['path'] = $value['path'];
//    			$data['name'] = $value['name'];
//    			$data['content'] = $value['content'];
//    			$data['type'] = $datas['type'];
//    			$data['category_id'] = $datas['category_id'];
//    			$data['url_rules'] = "/".$datas['en_name'];
//    			$data['url_prefix_group'] = "/".$datas['en_name'];
//    			if(!empty($tpl)) {
//    				$data['updated_at'] = time();
//    				$tpl->update($data);
//    			}
//    			else if($model->save($data)) {
//    				$modelfriend = new TemplateFriends();
//    				$data_up['channel_id'] = $datas['channel_id'];
//    				$data_up['domain_id'] = $datas['domain_id'];
//    				$data_up['template_id'] = $model->id;
//    				$data_up['category_id'] = $datas['category_id'];
//    				$data_up['region_id'] = 0;
//    				$data_up['data_id'] = 0;
//    				$data_up['url'] = "/".$datas['type']."/".$datas['category_id'];
//    				$data_up['created_at'] = time();
//    				$data_up['updated_at'] = time();
//    				$domain = TemplateFriends::checkUnique($datas['domain_id'],$data_up['url'],0);
//    				if(empty($domain)) {
//    					$modelfriend->save($data_up);
//    				}
//
//    				if($data['type'] == 'tpl') {
//    					$key = 'smarty:'.$datas['domain_id'].':'.$datas['typeTemp'].':'.$datas['category_id'];
//    					$tpldata = array('content'=>$data['content'],'updated_at'=>$data['updated_at']);
//    					MemcacheIO::set($key, $tpldata);
//    				}
//    				$model = new Templates();
//    			}
//    		}
//    	}
//    	return $model;
//    }
    
    private function echoExit($msg){
    	echo $msg;
    	exit;
    }
    
    //删除空格
    function trimall($str) {
    	$qian=array(" ","　","\t","\n","\r");$hou=array("","","","","");
    	return str_replace($qian,$hou,$str);
    }
}