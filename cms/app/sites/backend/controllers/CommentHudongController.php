<?php

/**
 * Created by PhpStorm.
 * User: fg
 * Date: 2016/7/24
 * Time: 11:01
 */
class CommentHudongController extends \BackendBaseController
{
    public function indexAction()
    {
        $channel_id = Session::get('user')->channel_id;
        $data_id = Request::getQuery('data_id', 'int',0);
        $id = Request::getQuery('id', 'int',0);
        if($id) {
            $data = UserComments::getCommentsById($id,$channel_id);
        } elseif($data_id) {
            $data = UserComments::getCommentsBydataId($data_id,$channel_id);

        } else {
            $data = UserComments::getComments($channel_id);

        }
        $auditComment = AuditCommentControl::findOneById(1);
        $audit = $auditComment->global;
        $counts = UserComments::getCountCommentByDataId($data_id);

        View::setVars(compact('data', 'status', 'audit','counts'));
    }


    public function acceptAction()
    {
        $comment_id = Request::getQuery('id');
        $this->changeStatus($comment_id, UserComments::ACCEPT);
    }

    public function uncheckedAction()
    {
        $comment_id = Request::getQuery('id');
        $this->changeStatus($comment_id, UserComments::UNCHACKED);
    }

    public function rejectAction()
    {
        $comment_id = Request::getQuery('id');
        $this->changeStatus($comment_id, UserComments::REJECT);
    }

    public function deleteAction()
    {
        $comment_id = Request::getQuery('id');
        $this->changeStatus($comment_id, UserComments::DELETE);
    }


    public function accept_allAction()
    {
        $channel_id = Session::get('user')->channel_id;
        $ids = Request::getPost('id');
        foreach ($ids as $id) {
            $comment = UserComments::FindFirst(array("id=:id:", "bind" => array("id" => $id)));
            if ($comment && $comment->status != UserComments::DELETE) {
                $model_comment = new UserComments();
                $model_comment->findCommentTree($id);
                $treeIds = $model_comment->nodes;
                foreach ($treeIds as $id) {
                    if (!UserComments::changeStatus($id, UserComments::ACCEPT,$channel_id)) {
                        echo json_encode(['code' => '101', 'msg' => '评论更新失败']);
                    }
                }
            }
        }
        echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
    }

    public function uncheck_allAction()
    {
        $ids = Request::getPost('id');
        $channel_id = Session::get('user')->channel_id;
        foreach ($ids as $id) {
            $comment = UserComments::FindFirst(array("id=:id:", "bind" => array("id" => $id)));
            if ($comment && $comment->status != UserComments::DELETE) {
                $model_comment = new UserComments();
                $model_comment->findCommentTree($id);
                $treeIds = $model_comment->nodes;
                foreach ($treeIds as $id) {
                    if (!UserComments::changeStatus($id, UserComments::UNCHACKED,$channel_id)) {
                        echo json_encode(['code' => '101', 'msg' => '评论更新失败']);
                    }
                }
            }
        }
        echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
    }

    private function changeStatus($comment_id, $status)
    {
        $data_id = intval(Request::getQuery('data_id','int'));
        $channel_id = Session::get('user')->channel_id;
        if ($comment_id) {
            $model_comment = new UserComments();
            $model_comment->findCommentTree($comment_id);
            $treeIds = $model_comment->nodes;
            foreach ($treeIds as $id) {
                if (!UserComments::changeStatus($id, $status,$data_id,$channel_id)) {
                    echo json_encode(['code' => '101', 'msg' > '评论更新失败']);
                    exit;
                }
            }
            echo json_encode(['code' => '200', 'msg' => Lang::_('success')]);
            exit;
        }
        echo json_encode(['code' => '100', 'msg' => '提交失败，该评论可能已被删除']);
        exit;
    }
}