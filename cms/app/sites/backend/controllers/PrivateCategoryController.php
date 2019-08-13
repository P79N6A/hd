<?php
/**
 * Created by PhpStorm.
 * User: cyc
 * Date: 2015/12/4
 * Time: 15:01
 */

class PrivateCategoryController extends \BackendBaseController {

    public function indexAction(){
            $media_type = Request::get("media_type", "int");
            $condition = array('media_type' => $media_type);
            if($media_type=="") $media_type = PrivateCategory::MEDIA_TYPE_NEWS;
            $channel_id = Session::get('user')->channel_id;
            $data = PrivateCategory::findPagination($channel_id, $media_type);
            $categories = array();
            $tree = PrivateCategoryTree::getCategoryTree($channel_id, $media_type);

            foreach($data->models as $model) {
                $model->level = 0;
                $object =  json_decode( json_encode($model),true);
                $object['has_child'] = !empty($tree->getChildren($object['id']));
                $object['level'] = '0';
                array_push($categories, $object);
                Category::findDepthChildren($tree, $object, $categories, 0);
            }
            $listtype = PrivateCategory::listType();
            View::setVars(compact('data', 'categories', 'media_type', 'condition', 'listtype'));

    }

    public function addAction() {
        $media_type = Request::get("media_type", "int");
        $channel_id = Session::get('user')->channel_id;
        $father_id = 0;
        $savesuccess = false;
        if (Request::isPost()) {
            $data = Request::getPost();
            $validator = PrivateCategory::makeValidator($data);
            if (!$validator->fails()) {
                $category = new PrivateCategory();
                if (!isset($data['father_id']) || empty($data['father_id'])) {
                    $data['father_id'] = 0;
                }
                $data['channel_id'] = $channel_id;
                $data['media_type'] = $media_type;
                $savesuccess = $category->create($data);
                $messages = array();
                if (!$savesuccess) {
                    foreach ($category->getMessages() as $m) {
                        array_push($messages, $m->getMessage());
                    }
                }
            } else {
                $messages = $validator->messages()->all();
            }
            View::setVars(compact('messages'));
            View::setMainView('layouts/add');
            View::setVars(compact('savesuccess'));
        }
        else {
            View::setMainView('layouts/add');
            View::setVars(compact('channel_id', 'father_id', 'media_type'));
        }
    }

    public function updateAction() {
        if (Request::isPost()) {
            $channel_id = Session::get('user')->channel_id;

            $data = Request::getPost();
            $data['channel_id'] = $channel_id;
            $id = Request::getPost("id", "int");
            $category = PrivateCategory::findById($id);
            if (empty($category)) {
                $savesuccess = false;
                $messages[] = "Invalid category id";
                $this->renderResult($messages, $savesuccess);
                return;
            }
            $category->father_id = $data['father_id'];

            $category->name = $data['name'];



            $savesuccess = $category->save();
            if (!$savesuccess) {
                $messages = array();
                foreach ($category->getMessages() as $m) {
                    array_push($messages, $m->getMessage());
                }
            }
            $messages[] = Lang::_('success');
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

    /**
     * 编辑栏目
     */
    public function editAction($id = 0) {
        if(empty($id))
            $id = Request::getQuery("id", "int");
        if(!$id) {
            redirect(Url::get("private_category/add"));
        }
        $channel_id = Session::get('user')->channel_id;
        $category = PrivateCategory::findById($id);

        $parents = $category->getParents();

        $parentcount =  count($parents);
        View::setMainView('layouts/add');
        View::setVars(compact('category', 'parents', 'channel_id', 'parentcount'));
    }

    /**
     * json数据
     */
    public function jsonAction() {
        header("Content-Type: application/json");
        $id = Request::get('id', 'int');

        $media_type = Request::get("media_type", "int");
        $id = isset($id) ? $id : 0;
        $channel_id = Session::get('user')->channel_id;
        $tree = PrivateCategoryTree::getCategoryTree($channel_id, $media_type);
        $temp = $tree->getCategoryTreeJson($id);
        echo json_encode($temp);
        exit;
    }
}