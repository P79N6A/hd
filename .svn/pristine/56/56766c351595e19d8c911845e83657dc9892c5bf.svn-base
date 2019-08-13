<?php
/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/4/28
 * Time: 14:58
 */


class  SmsSenceController extends \BackendBaseController
{
    private $terminal = array('pc','app','wap');



    public function indexAction()
    {
        $data = SmsScene::findAll();
        $template_list = SmsTemplate::getkvList();
        foreach($template_list as $item)
            $templatelist[$item['id']] = $item['name'];
        $piples = SmsPiple::getKvPipleList();
        foreach($piples as $v)
        {
            $piple_list[$v['id']] = $v['name'];
        }
        View::setVars(compact('data','templatelist','piple_list'));
    }
    public  function addAction()
    {
        $messages = [];
        $channel_id =Session::get('user')->channel_id;
        $user_id = Session::get('user')->id;
        if(Request::isPost()) {
            $data = $this->request->getPost();
            $data['user_id'] = $user_id;
            $data['channel_id'] = $channel_id;
            $data['create_at'] = time();
            $data['status'] = 0;
            $data['terminal'] = join(',',$data['ter']);
            $model = new SmsScene();
            if($model->create($data)) {
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        $tempdata = SmsTemplate::findList($channel_id);
        $arrterminal = $this->terminal;
        $piples = SmsPiple::getKvPipleList();
        View::setMainView('layouts/add');
        View::setVars(compact('arrterminal','messages','tempdata','piples'));
    }



    public function editAction()
    {
        $id = Request::getQuery('id','int',0);
        $channel_id =Session::get('user')->channel_id;
        $user_id = Session::get('user')->id;
        if(Request::isPost())
        {
            $data = $this->request->getPost();
            $scene = SmsScene::findFirst($id);
            $scene->terminal = join(',',$data['ter']);
            foreach($data as $key=>$v)
                $scene->$key = $v;
            if($scene->save()) {
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        $tempdata = SmsTemplate::findList($channel_id);
        $arrterminal = $this->terminal;
        $piples = SmsPiple::getKvPipleList();
        $scene = SmsScene::getItem($id);
        View::setMainView('layouts/add');
        View::setVars(compact('arrterminal','messages','tempdata','piples','scene'));
    }
    /*
     * @desc 审核场景
     *
     * */
    public function auditAction()
    {
        $id = Request::getQuery('id','int',0);

        $channel_id =Session::get('user')->channel_id;
        $user_id = Session::get('user')->id;
        if(Request::isPost())
        {

            $row = SmsScene::findFirst(Request::getPost('id'));
            $row->status = Request::getPost('status');
            if($row->save()) {
                $messages[] = Lang::_('success');
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        $tempdata = SmsTemplate::findList($channel_id);
        $arrterminal = $this->terminal;
        $piples = SmsPiple::getKvPipleList();
        $scene = SmsScene::getItem($id);
        View::setMainView('layouts/add');
        View::setVars(compact('arrterminal','messages','tempdata','piples','scene'));
    }

}