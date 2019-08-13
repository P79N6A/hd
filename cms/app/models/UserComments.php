<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UserComments extends Model {
    const REJECT = 2;  //审核未通过
    const ACCEPT = 1;  //审核通过
    const UNCHACKED = 0;  //未审核
    const DELETE = 3;
    const ALL = 4;
    const PAGE_SIZE = 50;
    const USER_COMMENT_ID = "user_comment_id:";
    const HUDONG_COMMENT_ID = "hudong_comment_id";
    const QUEUENAME = "usercommentqueue";
    const COMMENT_USER = "comment_user:";
    const COMMENT_BY_USER = "comment_by_user:";
    //先审后台开关
    const REVIEW = "review:";



    public $nodes = array();

    const FATHER_FATHER_ACCEPT = "father_father_accept:";
    const MEIZI = "meizi:";  //对这条媒资的所有评论

    const COMMENT_ACCEPT = "comment_accept:";

    const IS_ACCEPT = "is_accept:";  //已审核列表

    const COMMENT_LIKES = "comment_likes:";
    const COMMENT_LIKES_ACCEPT = "comment_likes_accept:";

    const FATHER_FATHER = "father_father:";

    public function getSource() {
        return 'user_comments';
    }

    public static function apiGetCommentByDataId($data_id, $page, $per_page){
        return UserComments::find(array(
            'data_id=:data_id: and status < 2',
            'bind' => array('data_id' => $data_id),
            'columns' => "id,comment_id,father_father, channel_id, user_id, username, data_id, father_id, content, create_at, status, likes, down, location",
            'limit' => $per_page,
            'order' => "create_at desc",
            'offset' => ($page - 1) * $per_page
        ));
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id','comment_id','father_father', 'channel_id', 'user_id', 'username', 'data_id', 'father_id', 'content', 'create_at', 'status', 'likes', 'down', 'domain', 'client', 'ip', 'location', 'partition_by','nickname','avatar','browersinfo','auditerid','aduit_at','audit_memo','isspeccomment'
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id', 'partition_by',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'user_id', 'username', 'data_id', 'father_id', 'content', 'create_at', 'status', 'likes', 'down', 'domain', 'client', 'ip', 'location', 'nickname', 'avatar', 'browersinfo', 'auditerid', 'aduit_at', 'audit_memo', 'isspeccomment','comment_id','father_father'],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'user_id', 'username', 'data_id', 'father_id', 'content', 'create_at', 'status', 'likes', 'down', 'partition_by'],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'father_father' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'comment_id' => Column::TYPE_VARCHAR,
                'username' => Column::TYPE_VARCHAR,
                'data_id' => Column::TYPE_INTEGER,
                'father_id' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_VARCHAR,
                'create_at' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'likes' => Column::TYPE_INTEGER,
                'down' => Column::TYPE_INTEGER,
                'domain' => Column::TYPE_VARCHAR,
                'client' => Column::TYPE_INTEGER,
                'ip' => Column::TYPE_VARCHAR,
                'location' => Column::TYPE_VARCHAR,
                'partition_by' => Column::TYPE_INTEGER,
                'nickname' => Column::TYPE_VARCHAR,
                'avatar' => Column::TYPE_VARCHAR,
                'browersinfo' => Column::TYPE_VARCHAR,
                'auditerid' => Column::TYPE_INTEGER,
                'aduit_at' => Column::TYPE_INTEGER,
                'audit_memo' => Column::TYPE_VARCHAR,
                'isspeccomment' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id','father_father', 'channel_id', 'user_id', 'data_id', 'father_id', 'create_at', 'status', 'likes', 'down', 'client', 'partition_by','aduit_at','isspeccomment','auditerid'
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'father_father' => Column::BIND_PARAM_INT,
                'username' => Column::BIND_PARAM_STR,
                'comment_id' => Column::BIND_PARAM_STR,
                'data_id' => Column::BIND_PARAM_INT,
                'father_id' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'create_at' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'likes' => Column::BIND_PARAM_INT,
                'down' => Column::BIND_PARAM_INT,
                'domain' => Column::BIND_PARAM_STR,
                'client' => Column::BIND_PARAM_INT,
                'ip' => Column::BIND_PARAM_STR,
                'location' => Column::BIND_PARAM_STR,
                'partition_by' => Column::BIND_PARAM_INT,
                'nickname' => Column::BIND_PARAM_STR,
                'avatar' => Column::BIND_PARAM_STR,
                'browersinfo' => Column::BIND_PARAM_STR,
                'auditerid' => Column::BIND_PARAM_INT,
                'aduit_at' => Column::BIND_PARAM_INT,
                'audit_memo' => Column::BIND_PARAM_STR,
                'isspeccomment' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'father_id' => '0',
                'likes' => '0',
                'down' => '0',
                'client' => 'ios',
                'partition_by' => '0',
                'isspeccomment' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    /*
     * 验证字段有效性
     * @param $input
     * @return mixed
     */
    public static function makeValidator($input) {
        return Validator::make(
            $input,
            [
                'id' => 'required',
                'channel_id' => 'required',
                'user_id' => 'required',
                'username' => 'required|max:30',
                'data_id' => 'required',
                'father_id' => 'required',
                'content' => 'required|max:255',
                'create_at' => 'required',
                'status' => 'required',
                'likes' => 'required',
                'down' => 'required',
                'domain' => 'required|max:30',
                'client' => 'required',
                'ip' => 'required|max:15',
                'partition_by' => 'required'
            ],
            [
                'id.required' => '评论ID必填',
                'channel_id.required' => '频道ID必填',
                'user_id.required' => '用户ID必填',
                'username.required' => '用户名必填',
                'username.max' => '用户名最长30字符',
                'data_id.required' => '容器ID必填',
                'father_id.required' => '父ID必填',
                'content.required' => '内容必填',
                'content.max' => '内容最长255字符',
                'create_at.required' => '创建时间必填',
                'status.required' => '状态必填',
                'up.required' => '赞必填',
                'down.required' => '底必填',
                'domain.required' => '域名必填',
                'client.required' => '终端必填',
                'ip.required' => 'IP必填',
                'partition_by.required' => '分区必填'
            ]
        );
    }

    private static function getComment($id, $channel_id) {
        return UserComments::findFirst(array(
            'id = :id: AND channel_id=:channel_id:',
            'bind' => array('id' => $id, 'channel_id' => $channel_id)
        ));
    }

    public static function acceptComment($id, $channel_id) {
        return UserComments::changeStatus($id, UserComments::ACCEPT, $channel_id);
    }

    public static function rejectComment($id, $channel_id) {
        return UserComments::changeStatus($id, UserComments::REJECT, $channel_id);
    }

    public static function uncheckedComment($id, $channel_id) {
        return UserComments::changeStatus($id, UserComments::UNCHACKED, $channel_id);
    }

    public static function deleteComment($id, $channel_id) {
        return UserComments::changeStatus($id, UserComments::DELETE, $channel_id);
    }

    public static function changeStatus($id, $status, $data_id,$channel_id) {
        $comment = UserComments::getComment($id, $channel_id);
        if ($comment) {

            if($status == self::ACCEPT){
                //统计已审核
                RedisIO::incr(self::COMMENT_ACCEPT . $data_id);
                //已审核列表
                RedisIO::zAdd(self::IS_ACCEPT .  "data_id:" . $data_id, $comment->id, $comment->comment_id);
                //已审核列表
                RedisIO::zAdd(self::COMMENT_LIKES_ACCEPT .  "data_id:" . $data_id, $comment->likes, $comment->comment_id);
                //所有点赞队列
                RedisIO::zAdd(self::COMMENT_LIKES . $data_id,$comment->likes,$comment->comment_id);
                //所有评论队列
                RedisIO::zAdd(self::MEIZI . "data_id:" . $data_id,$comment->id, $comment->comment_id);
                //盖楼REDIS数据设置
                //RedisIO::zadd(self::FATHER_FATHER_ACCEPT ."data_id:" . $comment->data_id, $comment->id, $comment->father_father);
                //RedisIO::zadd(self::FATHER_FATHER_ACCEPT ."father_father:" .$comment->father_father, $comment->id, $comment->comment_id);

            }elseif($status == self::REJECT) {
                //统计已审核
                RedisIO::decr(self::COMMENT_ACCEPT . $data_id);
                //审核的评论列表
                RedisIO::zRem(self::IS_ACCEPT . "data_id:" . $data_id,$comment->comment_id);
                //审核的点赞队列
                RedisIO::zRem(self::COMMENT_LIKES_ACCEPT .  "data_id:" . $data_id, $comment->comment_id);
                //所有点赞队列
                RedisIO::zRem(self::COMMENT_LIKES . $data_id,$comment->comment_id);
                //所有评论队列
                RedisIO::zRem(self::MEIZI . "data_id:" . $data_id, $comment->comment_id);
                //盖楼REDIS数据设置
                //RedisIO::zRem(self::FATHER_FATHER_ACCEPT ."data_id:" . $comment->data_id,  $comment->father_father);
                //RedisIO::zRem(self::FATHER_FATHER_ACCEPT ."father_father:" .$comment->father_father, $comment->comment_id);

            }

            RedisIO::decr("comment_unchecked:" . $data_id);  //统计未审核
            $comment->status = $status;
            //对像转json
            RedisIO::set(self::USER_COMMENT_ID . $comment->comment_id, json_encode($comment));
            return $comment->save();
        }
        return false;
    }

    public static function getCommentsByAdmin($channel_id, $status = null, $content = null) {
        $conditions = "channel_id={$channel_id} AND status <> 3";
        if ($status != UserComments::ALL) {
            $conditions = "channel_id={$channel_id} AND status = {$status} AND status <> 0";
        }
        if ($content != null) {
            $conditions = $conditions . " AND content like '%{$content}%'";
        }
        return UserComments::query()
            ->where($conditions)
            ->orderBy('create_at')
            ->paginate(50, 'Pagination');
    }
	
    public static function getCommentsByUser($user_id, $page, $per_page) {
        return UserComments::find(array(
            'user_id=:user_id: and status < 2',
            'bind' => array('user_id' => $user_id),
            'columns' => "id, channel_id, user_id, username, data_id, father_id, content, create_at, status, likes, down, location",
            'limit' => $per_page,
            'offset' => ($page - 1) * $per_page
        ));
    }

    /**
     * 通过用户UID获取评论数据
     * @return array|bool|mixed|string
     */
    public static function getCommentsByUid($uid,$data_id,$limit,$page){
        $comment_ids = RedisIO::zRevRange(self::COMMENT_BY_USER . $uid . "data_id:" . $data_id,($page-1) * $limit,$page * $limit-1);
        $data = array();
        foreach ($comment_ids as $comment_id){
            if(RedisIO::get(self::USER_COMMENT_ID . $comment_id)){
                $res = json_decode(RedisIO::get(self::USER_COMMENT_ID . $comment_id),true);
                $data[] = $res;
            }
        }
        return $data;
    }

    /**
     * 按评论人气获取评论数据
     */
    public static function getCommentsBySort($data_id,$limit=30,$page=1,$accept=true){

        $data = array();
        if($accept){
            $comment_ids = RedisIO::zRevRange(self::COMMENT_LIKES . $data_id,($page - 1) * $limit,$page * $limit - 1);
            foreach ($comment_ids as $comment_id){
                if(RedisIO::get(self::USER_COMMENT_ID . $comment_id)){
                    $res = json_decode(RedisIO::get(self::USER_COMMENT_ID . $comment_id),true);
                    $data[$res['id']] = $res;
                }
            }
        } else {
            $comment_ids = RedisIO::zRevRange(self::COMMENT_LIKES_ACCEPT . "data_id:" . $data_id,($page - 1) * $limit,$page * $limit - 1);
            foreach ($comment_ids as $comment_id){
                if(RedisIO::get(self::USER_COMMENT_ID . $comment_id)){
                    $res = json_decode(RedisIO::get(self::USER_COMMENT_ID . $comment_id),true);
                    $data[] = $res;
                }
            }

        }
        $data = array_values($data);
        return $data;

    }

    /**
     * @param $data_id
     * @param $status
     * @return array
     */
    public static function getCommentsByData($data_id,$status) {
        return self::query()
            ->andCondition('data_id',$data_id)
            ->andCondition('status',$status)
            ->execute()->toArray();
    }

    public static function apiCountComment($channel_id, $data_id, $status = 1) {
        return UserComments::count(array(
            "data_id = $data_id and channel_id = $channel_id and status = $status"
        ));
    }

    public static function apiMarkCommentLikeOrDown($channel_id, $id, $type) {
        $comment = UserComments::getComment($channel_id, $id);
        if ($type == 'like') {
            $comment->likes = $comment->likes + 1;
        } else if ($type == 'down') {
            $comment->down = $comment->down + 1;
        }
        if (!empty($comment)) {
            return $comment->save();
        }
        return false;
    }

    /*
     *  user: fenggu
     *  date: 2016-4-6
     *  time: 15:37
     *  desc: 获取Item 的IDS
     *  parameter：data_id,主题id，status:状态,$direct:排序方向,$num：数量
     */
    public  static  function getItemidsByData($status,$direct,$num,$data_id,$channel_id,$page=1) {
        $query = UserComments::query()->where("data_id = :data_id: AND channel_id = :channel_id:");
        $query =  $status?$query->andWhere("status = :status:")->bind(array('status'=>$status,'data_id'=>$data_id,'channel_id'=>$channel_id)):$query->bind(array('data_id'=>$data_id,'channel_id'=>$channel_id));
        $query = $query->order("id $direct");
        $rs = $query->paginate($num,'Pagination',$page)->models;
        if(!empty($rs)) {
            $arr = $rs->toArray();
        }
        $ret = array();
        if($arr){
            foreach($arr as $k=>$v) {
                $ret[] = $v['id'];
            }
        }
        return $ret;
    }

    /*
     *  user: fenggu
     *  date: 2016-4-6
     *  time: 21:46
     *  desc: 获取某个IP地址下面最后一条消息记录的发送时间
	 */
    public static function lastitemtime($data_id,$client,$ip)
    {
        $ret = self::findFirst(array(
            'conditions'=>"data_id = :data_id: AND client = :client: AND ip = :ip:",
            'bind'=>array('data_id'=>$data_id,'client'=>$client,'ip'=>$ip),
            'order'=>"id desc"));
        if($ret != null)
            return $ret->create_at;
        else
            return 0;
    }

    /*
     *  modifyuser:fenggu
     *  modifydate:2016-4-6
     *  modifytime:17:16
     *  changelog:增加一个默认参数retid,返回id
     */
    public function createComment($data, $retid=false) {
        $this->assign($data);
        RedisIO::incr("allCommentCounts:" . $data['data_id']);  //评论总次数
        RedisIO::incr("comment_unchecked:" . $data['data_id']); //统计未审核数
        //更新father
        if($data['father_id']) {
            $father_father = UserComments::getFatherFather($data['father_id']);
            $data['father_father'] = $father_father;
            $data['id'] = $this->saveGetId($data);
            $result =  $data['id'];
        }
        else {
            $data['id'] = $this->saveGetId($data);
            $data['father_father'] = $data['id'];
            $this->save($data);
            $result =$data['id'];
        }
        //获取内容
        $comment = UserComments::findFirst($result);
        //存入Redis进行缓存
        RedisIO::set(self::USER_COMMENT_ID . $data['comment_id'], json_encode($comment));
        //盖楼REDIS数据设置
        RedisIO::zadd(self::FATHER_FATHER ."data_id:"  . $data['data_id'], $data['id'],  $data['father_father']);
        RedisIO::zadd(self::FATHER_FATHER ."father_father:" .$data['father_father'], $data['id'], $data['comment_id']);
        return $retid?$result:0;
    }

    /*
     *  modifyuser:fenggu
     *  modifydate:2016-4-7
     *  modifytime:2:02
     *  desc：按照ID获取一条ITEM，主键是复合主键，该方法属于过度方法
     */
    public static function getItemById($id) {
        return self::findFirst(array("id = $id"))->toArray();
    }

    /*
     *  user:fenggu
     *  date:2016-4-7
     */
    public static function getSpecComments($data_id) {
        $conditions = "data_id = $data_id";
        return UserComments::query()
            ->where($conditions)
            ->orderBy('create_at DESC')
            ->paginate(500, 'Pagination');
    }
	
	//-------------以下代码为方尖熊编写
	
    //获取所有评论
    public static function getComments($channel_id) {
        return UserComments::query()
            ->andCondition('channel_id', $channel_id)
            ->orderBy('create_at desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    //跟据data_id获取
    public static function getCommentsBydataId($data_id,$channel_id) {
        return UserComments::query()
            ->andCondition('data_id', $data_id)
            ->andCondition('channel_id', $channel_id)
            ->orderBy('create_at desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    //根据father_id获取
    public static function getCommentsById($id,$channel_id) {
        return UserComments::query()
            ->andCondition('id', $id)
            ->andCondition('channel_id', $channel_id)
            ->orderBy('create_at desc')
            ->paginate(self::PAGE_SIZE, 'Pagination');
    }

    //统计
    public static function getCountCommentByDataId($data_id){
        if($data_id) {
            $counts['all'] = UserComments::count('data_id=' . $data_id);
            $counts['accept'] = UserComments::count('status=' . UserComments::ACCEPT . ' and data_id=' . $data_id);
            $counts['unchacked'] = UserComments::count('status=' . UserComments::UNCHACKED . ' and data_id=' . $data_id);
        } else {
            $counts['all'] = UserComments::count();
            $counts['accept'] = UserComments::count('status=' . UserComments::ACCEPT);
            $counts['unchacked'] = UserComments::count('status=' . UserComments::UNCHACKED);
        }
        return $counts;
    }



    public  function findCommentTree($id = 0) {
        if($id) {
            array_push($this->nodes,$id);
        }
        $comments = $this->findChildNodeByPid($id);
        if($comments) {
            foreach($comments as $comment){
                if($comment->id)
                    $this->findCommentTree($comment->id);
            }
        }
    }

    private function findChildNodeByPid($pid) {
        return self::query()->where("father_id = $pid")->execute();
    }

    /**
     * 分页获取数据接口
     */
    public static function getCommentByPage($data_id,$limit=30,$page=1){
        $res = self::query()
            ->andCondition('data_id',$data_id)
            ->andCondition('status',self::ACCEPT)
            ->andCondition('father_id',0)
            ->orderBy("create_at desc")
            ->limit($limit,($page-1) * $limit)
            ->execute()->toArray();
        return $res;
    }

    /**
     * redis 添加
     */
    public static function RSave($data){
        $comment_id = $data['comment_id'];
        $res = RedisIO::set(self::USER_COMMENT_ID . $comment_id , json_encode($data));
        //媒资队列
        RedisIO::zAdd(self::MEIZI . "data_id:" . $data['data_id'],$comment_id,$comment_id);
        //点赞队列
        RedisIO::zAdd(self::COMMENT_LIKES . $data['data_id'], 0, $comment_id);
        //用户队列
        RedisIO::zAdd(self::COMMENT_BY_USER . $data['user_id'] . "data_id:" . $data['data_id'], 0, $comment_id);

        return $res;

    }

    /**
     * redis 获取数据
     */
    public static function Rfind($data_id,$limit=30,$page=1){
        //麻烦的重新排序
        $arr = RedisIO::zRevRange(self::MEIZI,0,-1);
        foreach($arr as $val){
            RedisIO::zadd(self::MEIZI,$val,$val);
        }

        $res = RedisIO::zRevRange(self::MEIZI . $data_id,($page - 1) * $limit,$limit * $page - 1);

        if(!$res){
            $res = self::query()
                ->andCondition('data_id',$data_id)
                ->orderBy("create_at desc")
                ->limit($limit,($page-1) * $limit)
                ->execute()->toArray();
        }
        $data = array();
        foreach ($res as $comment_id){
            //过滤空数据
            $res = RedisIO::get(self::USER_COMMENT_ID . $comment_id);
            if($res){
                $data[$comment_id] = json_decode($res,true);
            } else {
                RedisIO::zRem(self::MEIZI . $data_id,$comment_id);
            }
        }

        return $data;
    }

    /**
     * 删除
     */
    public static function Rdelete($comment_id,$data_id){
        RedisIO::zRem(self::MEIZI . "data_id:" . $data_id);
        return RedisIO::delete(self::USER_COMMENT_ID . $comment_id);
    }

    /**
     * 获取已审核列表
     */
    public static function getAccept_bak($data_id,$limit=30,$page=1){
        //麻烦的重新排序
        $arr = RedisIO::zRevRange(self::IS_ACCEPT,0,-1);
        foreach($arr as $val){
            RedisIO::zadd(self::IS_ACCEPT,$val,$val);
        }
        $res = RedisIO::zRevRange(self::IS_ACCEPT . $data_id,($page - 1) * $limit,$limit * $page - 1);
        if(!$res){
            $array = self::getCommentByPage($data_id,$limit,$page);

            //数据库也没有数据给一个默认值
            if(!$array){
                    $res = array($data_id);
            }else {
                foreach ($array as $val) {
                    RedisIO::zadd(self::IS_ACCEPT . $data_id, $val['comment_id'], $val['comment_id']);
                }
                $res = RedisIO::zRevRange(self::IS_ACCEPT . $data_id, ($page - 1) * $limit, $limit * $page - 1);
            }
        }

        //获取Redis数据
        $data = array();
        $ids = array();
        foreach ($res as $comment_id){
            //过滤空数据
            $res = RedisIO::get(self::USER_COMMENT_ID . $comment_id);
            if($res){
                $data[$comment_id] = json_decode($res,true);
                $id = $data[$comment_id]['id'];
                $ids[] = $id;
            } else {
                RedisIO::zRem(self::IS_ACCEPT . $data_id,$comment_id);
            }
        }


        //从数据库获取子节点并缓存
        $key = "childNodes:" . $data_id .":". $limit .":". $page;
        $childNodes = RedisIO::get($key);

        if($childNodes){
            $childNodes = json_decode($childNodes,true);
        } else {
            $childNodes = UserComments::query()
                ->inWhere('father_father',$ids)
                ->orderBy('create_at desc')
                ->execute()->toArray();
            RedisIO::set($key,json_encode($childNodes),60);

        }
        foreach ($childNodes as $val){
            $datas[$val['id']] = $val;
        }

        return $datas;
    }

    /**
     * 通过id获取father_father
     */
    public static function getFatherFather($father_id){
        if($father_id) {
            $res = self::findFirst($father_id)->toArray();

        }
        return $father_id?$res['father_father']:false;

    }

    /**
     * 生成red
     */
    public static function createCommentId($uid, $vid) {
        return $_SERVER['REQUEST_TIME'] . '_' . str_replace('.', '', $_SERVER['SERVER_ADDR']) . '_' . getmypid() . '_' . $uid . '_' . $vid . '_' . rand(1, 1000);
    }

    /**
     * 获取评论时间序树排列
     */
    public static function getcommentBydesc($data_id,$limit,$page){
        $res = RedisIO::zRevRange(self::IS_ACCEPT  . $data_id,($page - 1) * $limit ,$limit * $page - 1);
        var_dump($res);
    }

    /**
     * 获取已审核的所有评论数据
     */
    public static function getAccept($data_id, $limit=30, $page=1, $tree=true){
        $comment_data = array();
        if($tree) {
            $father_fathers = RedisIO::zRevRange(self::FATHER_FATHER_ACCEPT . "data_id:" . $data_id, ($page - 1) * $limit, $limit * $page - 1);
            foreach ($father_fathers as $father_father) {
                $comment_ids = RedisIO::zRevRange(self::FATHER_FATHER_ACCEPT . "father_father:" . $father_father, 0, -1);
                foreach ($comment_ids as $comment_id) {
                    $rediscomment = json_decode(RedisIO::get(self::USER_COMMENT_ID . $comment_id), true);
                    $comment_data[$rediscomment['id']] = $rediscomment;
                }
            }
        } else {
            $comment_ids = RedisIO::zRevRange(self::IS_ACCEPT . "data_id:" . $data_id, ($page - 1)*$limit, $limit*$page-1);
            foreach($comment_ids as $comment_id){
                $rediscomment = json_decode(RedisIO::get(self::USER_COMMENT_ID . $comment_id), true);
                $comment_data[] = $rediscomment;
            }

        }
        $comment_data = array_values($comment_data);
        return $comment_data;

    }

    /**
     * 获取所有的子节点树
     */
    public static function getCommentAll($data_id, $limit=30, $page=1, $tree = true){
        $comment_data = array();
        if($tree) {
            $father_fathers = RedisIO::zRevRange(self::FATHER_FATHER . "data_id:" . $data_id, ($page - 1)*$limit, $limit*$page-1);
            foreach($father_fathers as $father_father){
                $comment_ids = RedisIO::zRevRange(self::FATHER_FATHER ."father_father:". $father_father, 0, -1);
                foreach($comment_ids as $comment_id){
                    $rediscomment = json_decode(RedisIO::get(self::USER_COMMENT_ID . $comment_id), true);
                        $comment_data[$rediscomment['id']] = $rediscomment;
                }
            }
        } else {
            $comment_ids = RedisIO::zRevRange(self::MEIZI . "data_id:" . $data_id, ($page - 1)*$limit, $limit*$page-1);
            foreach($comment_ids as $comment_id){
                $comment_data[] = json_decode(RedisIO::get(self::USER_COMMENT_ID . $comment_id), true);
            }
        }
        $comment_data = array_values($comment_data);

        return $comment_data;

    }

}