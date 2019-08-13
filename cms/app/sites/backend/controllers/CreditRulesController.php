<?php
/**
 * Name: 积分规则
 * Author: zhanghaiquan
 * Date: 2016/01/12
 */
class CreditRulesController extends \BackendBaseController {

    public function indexAction() {
        $channel_id = Session::get("user")->channel_id;

        $data = CreditRules::query()
            ->where("channel_id={$channel_id}")
            ->paginate(50, 'Pagination');
        View::setVars(compact('data'));
    }
    
    public function addAction() {
        $msg = "";
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $data['channel_id'] = Session::get("user")->channel_id;
            $validator = CreditRules::makeValidators($data);
            if(!$validator->fails()) {
                $creditrule = new CreditRules();
                $creditrule->createCredit($data);
                $msg[] = Lang::_('success');
            } else {
                $msg[] = Lang::_('error');
            }
        }
        $messages = $msg;
        $listtype = CreditRules::listType();
        View::setMainView('layouts/add');
        View::setVars(compact('messages','listtype'));
    }

    public function editAction() {
        $rule_id = $this->request->getQuery("id","int");
        $creditrule = CreditRules::findFirst($rule_id);
        $msg = "";
        if ($this->request->isPost()) {
            $data = $this->request->getPost();
            $data['channel_id'] = Session::get("user")->channel_id;
            $validator = CreditRules::makeValidators($data);
            if(!$validator->fails()) {                
                $creditrule->updateCredit($data);
                $msg[] = Lang::_('success');
            } else {
                $msg[] = Lang::_('error');
            }
        }
        $messages = $msg;
        $listtype = CreditRules::listType();
        View::setMainView('layouts/add');
        View::setVars(compact('messages', 'creditrule', 'listtype'));
    }

    /**
     * 删除积分规则
     */
    public function deleteAction() {
        exit;//暂不删除
        $rule_id = $this->request->getQuery("id","int");
        $creditrule = CreditRules::findFirst($rule_id);
        $channel_id = Session::get('user')->channel_id;

        if($creditrule->channel_id == $channel_id && $creditrule->delete()) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 规则启用
     */
    public function lockAction() {
        $memprefix = app_site()->memprefix;
        $rule_id = $this->request->getQuery("id","int");
        $creditrule = CreditRules::findFirst($rule_id);
        $channel_id = Session::get('user')->channel_id;

        if($creditrule->channel_id == $channel_id && $creditrule->changeStatus(CreditRules::CHECKED)) {
            $key = $memprefix.'credit.all_rules.channel_'.$channel_id;
            MemcacheIO::set($key, false, 86400*30);
            $key = $memprefix.'credit.channel_'.$channel_id.'.rule_type_'.$creditrule->type;
            MemcacheIO::set($key, false, 86400*30);
            $key = $memprefix.'credit.all_rules.channel_'.$channel_id."_ios";
            MemcacheIO::set($key, false, 86400*30);
            $key = $memprefix.'credit.all_rules.channel_'.$channel_id."_android";
            MemcacheIO::set($key, false, 86400*30);
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    /**
     * 规则停用
     */
    public function unlockAction() {
        $rule_id = $this->request->getQuery("id","int");
        $creditrule = CreditRules::findFirst($rule_id);
        $channel_id = Session::get('user')->channel_id;

        if($creditrule->channel_id == $channel_id && $creditrule->changeStatus(CreditRules::UNCHECKED)) {
            $arr=array('code'=>200);
        } else {
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }
}