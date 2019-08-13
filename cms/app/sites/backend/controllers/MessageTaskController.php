<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2015/11/20
 * Time: 14:22
 */
class MessageTaskController extends \BackendBaseController {
    //消息中心显示任务详情
    public function showTaskAction() {
        $id=Request::getPost('id');
//        $id='6';
        $message = messageTask::findFirst("id=$id");
        $task_id=$message->task_id;
        $allMes = MessageTask::findAllMes($task_id);
        foreach ($allMes as $key=>$v) {
            $allMes[$key]['date'] = date("Y-m-d H:i:s", $v['date']);
        }
        $taskInfo=MessageTask::taskInfo($task_id);
        $data = array_pop($taskInfo);
        $data['messages'] = json_encode($allMes);
        $data['message_id'] = $message->id;
        $task = Tasks::findFirst("id=353");
        $branchTasks = $task->getBranchTasks();
        $arr = array(
            'title'=>$branchTasks[0]->title,
            'start'=>date("Y-m-d H:i:s", $branchTasks[0]->start),
            'end'=>date("Y-m-d H:i:s", $branchTasks[0]->end),
            'progress'=>$branchTasks[0]->progress
        );
        $data['branchTasks'] = json_encode($arr);
        $data = json_encode($data);
        echo $data;
        exit;
    }

    public function sendMesTaskAction() {
        $curMes_id=Request::getPost('id');
        $message=Request::getPost('message');
        $curMes = MessageTask::findFirst("id='$curMes_id'");
        $nextMes = MessageTask::createNext($curMes,$message);
        $arr = array(
            'sender' => $this->getName($nextMes->send_id),
            'message' => $message,
            'date' => date("Y-m-d H:i:s", $nextMes->timestamp)
        );
        $data = json_encode($arr);
        echo $data;
        exit;
    }

    public function getName($id) {
        return admin::findFirst("id='$id'")->name;
    }

    public function allread() {
    }

    public function clear() {
    }
}