<?php
/**
 * Created by Zhangyichi.
 * Date: 2015/11/13
 * Time: 9:16
 */
class BoardController extends \BackendBaseController {

    public function indexAction() {
        redirect('/message_center/index');
    }

    public function createAction() {
        if(Request::isPost()){
            $inputs=Request::getPost();
            if($inputs['contents'] && $inputs['admin_id']) {
                $user=Session::get('user');
                $new=array('name'=>$user->name,
                    'avatar'=>$user->avatar,
                    'time'=>time(),
                    'admin_id'=>$user->id,
                    'message'=>$inputs['contents']
                );
                sort($inputs['admin_id']);
                $user_group=implode(',',($inputs['admin_id']));
                $board=Board::getBoardByUser($user_group);
                if($board){
                    $contents=json_decode($board->contents,true);
                    if(array_key_exists("0",$contents)) {
                        array_push($contents,$new);
                        if(count($contents)>30) {
                            array_shift($contents);
                        }
                    }else{
                        $contents=array($contents);
                        array_push($contents,$new);
                    }
                    $contents=json_encode($contents);
                    $data=get_object_vars($board);
                    $data['contents']=$contents;
                    if($board->modifyBoard($data)){
                        $board_status=new BoardStatus();
                        $board_status->modifyStatusAll($board->id,$user->id);
                        $messages[] = Lang::_('success');
                    }else{
                        $messages[] = Lang::_('failed');
                    }
                }else{
                    $contents=json_encode($new);
                    $board=new Board();
                    $data=array('time'=>time(),'contents'=>$contents,'user_group'=>$user_group);
                    if($board->createBoard($data)) {
                        foreach($inputs['admin_id'] as $admin_id){
                            $board_status=new BoardStatus();
                            $board_status->board_id=$board->id;
                            $board_status->admin_id=$admin_id;
                            $board_status->status=1;
                            $board_status->createBoardStatus(get_object_vars($board_status));
                        }
                        $messages[] = Lang::_('success');
                    }else{
                        $messages[] = Lang::_('failed');
                    }
                }
            }else{
                $messages[] = Lang::_('failed');
            }
        }
        View::setVars(compact('messages'));
    }

    public function readAction() {
        $id=Request::getPost('id');
        if($id!=null) {
            $data = Board::getBoardById($id);
            if($data!=null) {
                echo $data->contents;
                $board_status = BoardStatus::getOneBoardStatus($id, Session::get('user')->id);
                $data = get_object_vars($board_status);
                if ($data['status'] == 1) {
                    $data['status'] = 2;
                    $board_status->modifyBoardStatus($data);
                }
            }
        }
        exit;
    }

    public function sendAction() {
        $board_id=Request::getPost('id');
        $message=Request::getPost('message');
        if($board_id!=0 && $message!=null){
            $user=Session::get('user');
            $new=array('name'=>$user->name,
                'avatar'=>$user->avatar,
                'time'=>time(),
                'admin_id'=>$user->id,
                'message'=>$message
            );
            $board=Board::getBoardById($board_id);
            $contents=json_decode(Board::getBoardById($board_id)->contents,true);
            if(array_key_exists("0",$contents)) {
                array_push($contents,$new);
                if(count($contents)>30) {
                    array_shift($contents);
                }
            }else{
                $contents=array($contents);
                array_push($contents,$new);
            }
            $contents=json_encode($contents);
            $data=get_object_vars($board);
            $data['contents']=$contents;
            $board->modifyBoard($data);
            echo 200;
            $board_status=new BoardStatus();
            $board_status->modifyStatusAll($board_id,$user->id);
        }else{
            echo 403;
        }
        exit;
    }
}

