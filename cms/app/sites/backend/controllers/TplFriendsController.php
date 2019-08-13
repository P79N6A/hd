<?php
/**
 *  模板绑定 xy
 *  model TemplateFriends
 *  @created    2015-11-16
 */

class TplFriendsController extends \BackendBaseController {

    public function listAction() {
        $tpl_id = Request::get('tpl_id');
	    $data = TemplateFriends::findAll($tpl_id);
        View::setVars(compact('data','tpl_id'));
    }

    /**
     * Add action
     */

    public function addAction() {
        $tpl_id = Request::get('tpl_id');
        $tpl = Templates::findFirst($tpl_id);
        preg_match_all('/{region_id}|{category_id}/', $tpl->url_rules,$matchs);
        $model = new TemplateFriends();
        $messages = [];
        $father_id = 0;
        $flag = true;
        $channel_id = Session::get('user')->channel_id;
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['channel_id'] = $channel_id;
            $data['domain_id'] = $tpl->domain_id;
            $data['template_id'] = $tpl_id;
            $data['category_id'] = isset($data['category_id']) ? $data['category_id'] : 0;
            $data['region_id'] = isset($data['region_id']) ? $data['region_id'] : 0;
            if($tpl->type != 2) {
                $messages[] = Lang::_('该模板不允许绑定');     
                $flag = false;
            }
            if(count($matchs[0]) == 2 && ($data['region_id'] == 0 || $data['category_id'] == 0)) {
                $messages[] = Lang::_('栏目和地区都不能为空');     
                $flag = false;
            }
            if(count($matchs[0]) == 1 && $data['category_id'] == 0 && $matchs[0][0] == '{category_id}') {
                $messages[] = Lang::_('栏目不能为空');
                $flag = false;
            }
            if(count($matchs[0]) == 1 && $data['region_id'] == 0 && $matchs[0][0] == '{region_id}') {
                $messages[] = Lang::_('地区不能为空');
                $flag = false;
            }
            
            if(!$data['url']) {
                $data['url'] = str_replace('{category_id}', $data['category_id'], $tpl->url_rules);
                $data['url'] = str_replace('{region_id}', $data['region_id'], $data['url']);
            }
            $domain = TemplateFriends::checkUnique($data['domain_id'],$data['url'],0);
            if($domain) {
                $messages[] = Lang::_('该域名下链接已存在');
                $flag = false;
            }
            if($flag) {
                $data['created_at'] = $data['updated_at'] = time();
                if(!$model->save($data)){
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                }else{
                    $messages[] = Lang::_('success');
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('father_id','messages'));
    }

    /**
     * edit action
     */
    public function editAction() {
        $id = Request::get('id', 'int');
    	$model = TemplateFriends::findFirst($id);
        $tpl = Templates::findFirst($model->template_id);
        $messages = [];
        $flag = true;
        if (Request::isPost()) {
            $data = Request::getPost();
            $data['category_id'] = isset($data['category_id']) ? $data['category_id'] : 0;
            $data['region_id'] = isset($data['region_id']) ? $data['region_id'] : 0;
            if($model->category_id > 0) {
                if($data['category_id'] == 0) {
                    $messages[] = Lang::_('栏目不能为空');
                    $falg = false;
                }
            }
            if($model->region_id > 0) {
                if($data['region_id'] == 0) {
                    $messages[] = Lang::_('地区不能为空');
                    $falg = false;
                }
            }
            if(empty($data['url'])) {
                $data['url'] = str_replace('{category_id}', $data['category_id'], $tpl->url_rules);
                $data['url'] = str_replace('{region_id}', $data['region_id'], $data['url']);
            }
            $domain = TemplateFriends::checkUnique($model->domain_id,$data['url'],$id);
            if($domain) {
                $messages[] = Lang::_('该域名下链接已存在');
                $flag = false;
            }
            if($flag) {
                $data['updated_at'] = time();
                if (!$model->update($data)) {
                    foreach ($model->getMessages() as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $messages[] = Lang::_('success');
                }
            }
        }

        if($model->region_id > 0)  
            $region = Regions::getSingleOne($model->region_id);

        if($model->category_id > 0) {
            $category = Category::findById($model->category_id);
            $parents = $category->getParents();
            $parentcount =  count($parents);    
        }
        if($region) {
            $reparents = $region->getParents();
            $reparentcount =  count($reparents);    
        }

        View::setMainView('layouts/add');
        View::setVars(compact('category', 'parents','parentcount','region','reparents', 'reparentcount','messages','model'));
    }

    public function deleteAction() {
        $id = Request::get('id');
        $data = TemplateFriends::findFirst($id);
        $channel_id = Session::get('user')->channel_id;
        if($data->channel_id != $channel_id) {
            $this->accessDenied();
        }
        if (!empty($data) && $data->channel_id==Session::get("user")->channel_id ) {
            $data->delete();
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('cat not delete')]);
        }        
        exit;
    }

    /*
    *批量绑定
    */
     public function batchFriendsAction() {
        $tpl_id = Request::get('tpl_id', 'int');
        $tpl = Templates::findFirst($tpl_id);
        preg_match_all('/{region_id}|{category_id}/', $tpl->url_rules,$matchs);
        $channel_id = Session::get('user')->channel_id;
        $messages = [];
        $data_up = [];
        $flag = false;
        if (Request::isPost()) {
            $data = Request::getPost();
            $cids = isset($data['category_id']) ? explode(',',$data['category_id']) : 0;
            $rids = isset($data['region_id']) ? explode(',', $data['region_id']) : 0;
            $data_up['template_id'] = $tpl_id;
            $data_up['domain_id'] = $tpl->domain_id;
            $data_up['channel_id'] = $channel_id;
            $data_up['data_id'] = 0;
            $data_up['created_at'] = $data_up['updated_at'] = time();
            if(count($matchs[0]) == 2) {
                if(($cids > 0) && ($rids > 0)) {
                    foreach ($cids as $key => $cateid) {
                        $model = new TemplateFriends();
                        $data_up['category_id'] = $cateid;
                        $data_up['region_id'] = $rids[$key];
                        $data_up['url'] = str_replace('{category_id}', $data_up['category_id'], $tpl->url_rules);
                        $data_up['url'] = str_replace('{region_id}', $data_up['region_id'], $data['url']);
                        $domain = TemplateFriends::checkUnique($data_up['domain_id'],$data_up['url'],0);
                        if(!empty($domain))  continue;
                        else $flag = $model->save($data_up);
                    }
                    if($flag) {
                        $messages[] = Lang::_('success');
                    }
                }

            }
            else{
                if(in_array('{category_id}', $matchs[0]) && ($cids > 0)) {
                    foreach ($cids as $key => $cateid) {
                        $model = new TemplateFriends();
                        $data_up['category_id'] = $cateid;
                        $data_up['region_id'] = 0;
                        $data_up['url'] = str_replace('{category_id}', $data_up['category_id'], $tpl->url_rules);
                        $domain = TemplateFriends::checkUnique($data_up['domain_id'],$data_up['url'],0);
                        if(!empty($domain))  continue;
                        else $flag = $model->save($data_up);
                    }
                    if($flag) {
                        $messages[] = Lang::_('success');
                    }
                }
                if(in_array('{region_id}', $matchs[0]) && ($rids > 0)) {
                    foreach ($rids as $key => $reid) {
                        $model = new TemplateFriends();
                        $data_up['category_id'] = 0;
                        $data_up['region_id'] = $reid;
                        $data_up['url'] = str_replace('{region_id}', $data_up['region_id'], $tpl->url_rules);
                        $domain = TemplateFriends::checkUnique($data_up['domain_id'],$data_up['url'],0);
                        if(!empty($domain))  continue;
                        else $flag = $model->save($data_up);
                    }
                    if($flag) {
                        $messages[] = Lang::_('success');
                    }
                }
            }
            
        }

        View::setMainView('layouts/add');
        View::setVars(compact('messages'));

     }
 

}