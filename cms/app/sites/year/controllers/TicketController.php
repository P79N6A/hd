<?php
/**
 *  奖号管理
 *  controller stations
 *  @author     Zhangyichi
 *  @created    2015-9-16
 */


class TicketController extends \YearBaseController {
    
    public function indexAction() {
        $messages = [];
        $data=TicketYear::findAll();
        $reward = RewardYear::findAll();
        $reward_sum = 0;
        if(isset($reward->models) && !empty($models = $reward->models)){
            foreach($models as $r){
                $reward_sum+=$r->sum;
            }
        }
        $ticket_sum = $data->count;
        if($ticket_sum>$reward_sum){
            $messages[] = Lang::_('ticket is too much');
        }
        View::setVars(compact('data','messages'));
    }

    public function createAction() {
        $messages = [];
        if (Request::isPost()) {
            $input=Request::getPost();
            $validator = TicketYear::makeValidator($input);
            if(!$validator->fails()) {
                $ticket = new TicketYear();
                $return = $ticket->createTicket($input);
                if($return) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages'));
    }

    public function autocreateAction() {
        $max_ticket = TicketYear::findMaxNumber();
        $reward = RewardYear::findAll();
        $reward_sum = 0;
        if(isset($reward->models) && !empty($models = $reward->models)){
            foreach($models as $r){
                $reward_sum+=$r->sum;
            }
        }
        $ticket = TicketYear::findAll();
        $ticket_sum = $ticket->count;
        if($reward_sum>=$ticket_sum) {
            $times = $reward_sum-$ticket_sum;
            for(;$times>0;$times--){
                $ticket_new = new TicketYear();
                $ticket_new->createTicket(array('number'=>($max_ticket->number+$times)));
            }
        }
        redirect('/ticket/index');
    }

    public function modifyAction() {
        $messages = [];
        $id = Request::get("id", "int");
        $ticket = TicketYear::findOneById($id);
        //添加404页面
        if(!$ticket){
            $this->accessDenied();
        }
        if (Request::isPost()) {
            $input = Request::getPost();
            $validator = TicketYear::makeValidator($input,$ticket->id);
            if(!$validator->fails()) {
                $return = $ticket->modifyTicket($input);
                if($return) {
                    $messages[] = Lang::_('success');
                }else{
                    $messages[] = Lang::_('failed');
                }
            }else{
                foreach($validator->messages()->all() as $msg){
                    $messages[]=$msg;
                }
            }
        }
        View::setMainView('layouts/add');
        View::setVars(compact('messages','ticket'));
    }

    public function deleteAction() {
        $id = Request::get('id');
        $data = TicketYear::findOneById($id);
        if (!empty($data)) {
            $data->delete();
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('reward has ticket')]);
        }
        exit;
    }

    public function getrewardAction() {
        $id = Request::get('id');
        $data = TicketYear::findOneById($id);
        if (!empty($data)) {
            if($data->reward_id==0){
                echo json_encode(['code' => 'error', 'msg' => Lang::_('ticket has no reward')]);
            }else if($data->status==0){
                $data->status=1;
                $data->update();
                $reward = RewardYear::findOneById($data->reward_id);
                $reward->residue--;
                $reward->update();
                echo json_encode(['code' => '200', 'msg' => Lang::_('ticket get reward')]);
            }else{
                echo json_encode(['code' => 'error', 'msg' => Lang::_('ticket had get')]);
            }
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('ticket has error')]);
        }
        exit;
    }

    public function returnrewardAction() {
        $id = Request::get('id');
        $data = TicketYear::findOneById($id);
        if (!empty($data)) {
            if($data->status!=0){
                $data->status=0;
                $data->update();
                $reward = RewardYear::findOneById($data->reward_id);
                $reward->residue++;
                $reward->update();
                echo json_encode(['code' => '200', 'msg' => Lang::_('ticket has return')]);
            }else{
                echo json_encode(['code' => 'error', 'msg' => Lang::_('ticket dont get')]);
            }
        } else {
            echo json_encode(['code' => 'error', 'msg' => Lang::_('ticket has error')]);
        }
        exit;
    }

    public function searchAction() {
        $number = Request::getQuery('number');
        if($number) {
            $data = TicketYear::findOneByNumber($number);
            View::pick('/ticket/index');
            View::setVars(compact('data', 'number'));
        }else{
            redirect('/ticket/index');
        }
    }

}