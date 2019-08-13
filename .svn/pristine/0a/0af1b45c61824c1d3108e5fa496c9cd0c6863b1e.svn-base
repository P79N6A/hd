<?php
/**
 * 消息中心
 * User: admin
 * Date: 2015/11/16
 * Time: 9:58
 */
class MessageCenterController extends \BackendBaseController{

    public function indexAction(){
        $mesTasks=$this->taskIndex();
        $data=$this->boardIndex();
        $notice = $this->noticeIndex();
//        var_dump($data);exit;
        View::setVars(compact('mesTasks','data','notice'));
    }

    public function boardIndex(){
        $admin_id=Session::get('user')->id;
        $data= BoardStatus::getBoardStatusByAdmin($admin_id);//一个人所有的留言板
        if(!empty($data)) {
            foreach ($data as $borad) {
                $name = BoardStatus::getAdminByBoard($borad->boardStatus->board_id);//获取留言板用户
                $name_array = array();
                foreach ($name as $n) {
                    array_push($name_array, $n->name);
                }
                $borad->name = $name_array;
                $contents = json_decode($borad->board->contents, true);//消息对象数组
                if (array_key_exists("0", $contents)) {
                    $last_message = array_pop($contents);//最新的一条消息对象
                } else {
                    $last_message = $contents;
                }
                $borad->last_message = $last_message;
            }
        }
        return $data;
    }
    public function noticeIndex(){
        $user=session::get('user')->toarray();
        $notice = AnnounStatus::findSNoticeStatus($user['id']);
        foreach($notice as $k1=>$value){
            if($value['return']) {
                $message = json_decode($value['return'], true);
                foreach ($message as $k => $v) {
                        $data = array_merge($value, $v);
                }
            }else{
                $v=array('r_time'=>'','r_name'=>'','id'=>$user['id'],'img'=>'','r_mess'=>'');
                $data = $data = array_merge($value, $v);
            }
            $data1[$k1] = $data;
        }
        return $data1;
    }

    public function taskIndex(){
//        $user_id = $user_id = Session::get('user')->id;
        $user_id = '86';
        //findLatest取出user所有任务消息，并按时间、任务id排序
        $mestasks = MessageTask::findLatest($user_id);
        //用$latest保存user所有任务的最近消息
        $latest=array();
        $temp='';
        foreach ($mestasks as $mestask) {
            if ($mestask['task_id'] != $temp) {
                array_push($latest,$mestask);
            }
            $temp=$mestask['task_id'];
        }
        //对$latest按timestamp字段排序
        $sortarray = array();
        foreach ($latest as $mes) {
            foreach($mes as $key=>$value) {
                if(!isset($sortarray[$key])) {
                    $sortarray[$key] = array();
                }
                $sortarray[$key][] = $value;
            }
        }
        $orderby = "timestamp";
        array_multisort($sortarray[$orderby],SORT_DESC,$latest);
        return $latest;
    }

}
?>