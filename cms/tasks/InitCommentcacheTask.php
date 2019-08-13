<?php

/**
 * 批量设置评论缓存数据的状态值
 */
class InitCommentcacheTask extends Task{


    public function doAction() {
        $comments = Comment::query()
            ->where("create_at > 1467734668")
            ->execute()
            ->toArray();
        foreach($comments as $c) {
            $memCacheKey = "comment_" . $c['type'] . "_commentInfos_" . $c['comment_id'];
			$cachedata = RedisIO::get($memCacheKey);
            if($cachedata) {			
                $memComment = json_decode(RedisIO::get($memCacheKey), true);
				if(!isset($memComment['status'])&&$c['status'] == Comment::ACCEPT) {
					RedisIO::incr("comment_accept_count_" . $c['data_id']);//通过审核评论总数增加1
					RedisIO::zAdd("comment_accept_" . $c['type'] . "_" . $c['data_id'] . "_commentIds", $c['comment_id'], $c['comment_id']);
				}
				$commentmodel = new Comment();
				$commentmodel->setCommentCacheStatus($memCacheKey, $c['status']);
            }
        }

    }

}
