<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CommentLikes extends Model {

    public function initialize() {
        //使用互动数据库链接
        $this->setConnectionService('db_interactive');
    }

    public function getSource() {
        return 'comment_likes';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'ssouid', 'comment_id', 'attr', 'create_time',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['ssouid', 'comment_id', 'attr', 'create_time',],
            MetaData::MODELS_NOT_NULL => ['id', 'ssouid', 'comment_id', 'attr', 'create_time',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'ssouid' => Column::TYPE_INTEGER,
                'comment_id' => Column::TYPE_CHAR,
                'attr' => Column::TYPE_CHAR,
                'create_time' => Column::TYPE_DATETIME,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'ssouid',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'ssouid' => Column::BIND_PARAM_INT,
                'comment_id' => Column::BIND_PARAM_STR,
                'attr' => Column::BIND_PARAM_STR,
                'create_time' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'attr' => '',
                'create_time' => 'CURRENT_TIMESTAMP'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /**
     * @desc 喜欢某条评论,给某条评论点赞
     * @param array $data
     * @return array|bool
     */
    public function likeComment($data = array()) {
        if (empty($data)) {
            return false;
        }
        $ssouid = intval($data['ssouid']);
        $commentid = strval($data['commentid']);
        $attr = strval($data['attr']);
        $likeData = array(
            'ssouid' => $ssouid,
            'comment_id' => $commentid,
            'attr' => $attr,
        );
        try {
            // 先判断该用户是否点赞, 再插入
            $comment_like = $this->findFirst(array("conditions" => "ssouid='{$ssouid}' and comment_id='{$commentid}'"));
            if ($comment_like) {
                return array('liked' => 1);
            }
            $flag = $this->create($likeData);
        } catch (Exception $e) {
            return false;
        }
        return $flag;
    }

    /**
     * @desc 喜欢数增减. 更新评论表comment_video中的喜欢数
     * @param $commentid
     * @param $type
     * @param int $incrNum
     * @param bool $isComment
     * @return mixed
     */
    public function incrlikeComment($commentid, $type, $incrNum = 1, $isComment = true) {

        //更新缓存中的评论点赞总数状态
        $memCacheKey = "comment_" . $type . "_commentInfos_" . $commentid;
        if (RedisIO::exists($memCacheKey)) {
            //如果存在
            $memCommentInfo = json_decode(RedisIO::get($memCacheKey), true);
            $memCommentInfo["like"] = $incrNum > 0 ? $incrNum : 0;
            //var_dump($memCommentInfo);die();
            RedisIO::set($memCacheKey, json_encode($memCommentInfo));
        }

        //更新数据库的状态
        $commentInfo = Comment::findFirst(array("conditions" => "comment_id='{$commentid}'"));
        if ($commentInfo) {
            return $commentInfo->save(array("likes" => $incrNum));
        }
        return false;
    }

    /**
     * 取消喜欢某条评论.
     *
     * @access public
     * @param array $data (default: array())
     * @return void
     */
    public function unlikeComment($data = array()) {
        if (empty($data)) {
            return false;
        }
        $ssouid = intval($data['ssouid']);
        $commentid = strval($data['commentid']);

        try {
            $comment_like = $this->findFirst(array("conditions" => "ssouid='{$ssouid}' and comment_id='{$commentid}'"));
            $flag =  !empty($comment_like) ? $comment_like->delete() : false;
        } catch (Exception $e) {
            return false;
        }
        return $flag;
    }

    /**
     * @desc 获取一个UID下多个评论ID是否喜欢的数据.
     * @param array $data
     * @param bool $isFlush
     * @return array
     */
    public function getlikeCommentMulit($data = array(), $isFlush = false) {
        global $config;
        if (empty($data)) {
            return array();
        }
        $ssouid = intval($data['ssouid']);
        $commentids = $data['commentids'];
        if (!is_array($data['commentids'])) {
            $commentids = array($commentids => $commentids);
        }
        foreach ($commentids as $key => $val) {
            $commentids[$key] = "'{$val}'";
        }

        if (!empty($commentids)) {
            $mongoList = $this->__getlikeMulitFromMysql($commentids, $ssouid, 'cmt');
            if (!empty($mongoList)) {
                foreach ($mongoList as $commentid) {
                    $memList[$commentid] = $config['_user_liked'];
                }
            }
        }

        $returnData = array();
        if (isset($memList) && !empty($memList)) {
            foreach ($memList as $commentid => $isLiked) {
                if ($config['_user_liked'] == $isLiked) {
                    $returnData[$commentid] = true;
                }
            }
            unset($mongoList, $memList);
        }

        return $returnData;
    }

    public function __getlikeMulitFromMysql($ids = array(), $uid, $attr = 'cmt') {
        $strCommentids = implode(',', $ids);
        try {
            $comment_like = $this->find(array("conditions" => "ssouid='{$uid}' and comment_id in({$strCommentids}) and attr='{$attr}'"))->toArray();

        } catch (Exception $e) {
            $comment_like = array();
        }
        if (empty($comment_like)) {
            return array();
        }
        $newData = array();
        foreach ($comment_like as $data) {
            $commentid = strval($data['comment_id']);
            $newData[$commentid] = $commentid;
        }
        return $newData;
    }


}