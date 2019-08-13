<?php

/**
 * Created by PhpStorm.
 * User: eric
 * Date: 2016/7/1
 * Time: 16:32
 */
class VerifyController extends \BackendBaseController {

	const CANCELVERIFY = 2;
	const VERIFY = 1;
    function commentAction() {
    	//数据库中状态的代码
    	$statusKeyArr = array(1,2);
    	//操作后数据的状态名称
    	$statusArr = array('1'=>'已审核','2'=>'未审核');
    	//操作的名称
    	$operateArr = array('1'=>'取消审核','2'=>'通过审核');
    	//取反操作
    	$opersiteArr = array('1'=>2,'2'=>1);
    	    	
		$id = Request::getPOST('id');
		$st = Request::getPOST('st', 'int');
		
    	if (empty($id) || !in_array($st, $statusKeyArr)) {
			$this->_json([], 404, D::apiError(4001));
    	}
		$channel_id = Session::get("user")->channel_id;

		if(is_array($id)){
            if($st==1) {
		        foreach($id as $v) {
                    $comment = Comment::getComment($v, $channel_id);
                    if ($comment->status != Comment::ACCEPT && Comment::acceptComment($v, $channel_id)) {
                        RedisIO::incr("comment_accept_count_" . $comment->data_id);//通过审核评论总数增加1
                        RedisIO::zAdd("comment_accept_" . $comment->type . "_" . $comment->data_id . "_commentIds", $comment->comment_id, $comment->comment_id);

                        $memCacheKey = "comment_" . $comment->type . "_commentInfos_" . $comment->comment_id;
                        $comment->setCommentCacheStatus($memCacheKey, Comment::ACCEPT);
                    }
			    }
			}
			elseif($st==2) {
		        foreach($id as $v) {
                    $comment = Comment::getComment($v, $channel_id);
                    if ($comment->status != Comment::UNCHACKED && Comment::uncheckedComment($v, $channel_id)) {
                        RedisIO::decr("comment_accept_count_" . $comment->data_id);//通过审核评论总数增加1
                        RedisIO::zRem("comment_accept_" . $comment->type . "_" . $comment->data_id . "_commentIds", $comment->comment_id, $comment->comment_id);

                        $memCacheKey = "comment_" . $comment->type . "_commentInfos_" . $comment->comment_id;
                        $comment->setCommentCacheStatus($memCacheKey, Comment::UNCHACKED);
                    }
			    }
			}
			$this->_json([]);
			exit;
		}
		else {
            $this->_json([]);
            exit;
		}
    	//查看按钮
    	$viewArr = array('1'=>'<a href="http://www.cztv.com/detail/'.$id.'.html" target="_blank">查看</a>','2'=>'');
		$operate ='<a href="javascript:void(0)" onclick="checkVerify('.$id.','.$opersiteArr[$st].')" title="">'.$operateArr[$st].'</a>';		
		$this->_json(['operate'=>$operate, 'view'=>$viewArr[$st], ]);
		$this->_json([]);
    	die();
    }

    function _json($data, $code = 200, $msg = "success") {
        header('Content-type: application/json');
        echo json_encode([
            'code' => $code,
            'msg' => $msg,
            'data' => $data,
        ]);
        exit;
    }

}