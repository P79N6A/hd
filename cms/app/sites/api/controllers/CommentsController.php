<?php
use GenialCloud\Helper\IWC;

/**
 * @RoutePrefix("/comments")
 */
class CommentsController extends ApiBaseController {

    static $public_actions = array('list');

    public function initialize() {
        parent::initialize();
        $action = $this->dispatcher->getActionName();
        if (!in_array(strtolower($action), self::$public_actions)) {
            $this->checkToken();
        }
    }

    /**
     * @Get("/{id:[0-9]+}")
     */
    public function listAction($id){
        $comments = UserComments::apiGetCommentByDataId($id, $this->page, $this->per_page);

        if($comments) {
            $result = $this->constructInfoResult($comments);
            return $this->_json($result);
        } else {
            return $this->_json([]);
        }
    }

    /**
     * @Post("/{id:[0-9]+}")
     * @param int $id
     * @return json
     */
    public function likeAction($id) {
        $type = Request::getQuery('type');
        if (UserComments::apiMarkCommentLikeOrDown($this->channel_id, $id, $type)) {
            return $this->_json([]);
        } else {
            return $this->_json([], 404, 'Not Found');
        }
    }

    /**
     * @Post("/")
     */
    public function postAction() {
        $input = Request::getPost();
        $comment = new UserComments();
        if(!issets($input, ['id', 'content'])) {
            $this->_json([], 404, 'Params Error');
        }
        $data_id = intval($input['id']);
        if(!Data::findFirst($data_id)){
            $this->_json([], 404, 'Data_id Error');
        }
        $data = [
            'channel_id' => $this->channel_id,
            'user_id' => $this->user->uid,
            'username' => $this->user->username?: $this->user->mobile,
            'data_id' => intval($input['id']),
            'father_id' => isset($input['father_id'])? intval($input['father_id']): 0,
            'father_father' => 0,
            'content' => isset($input['content'])? $input['content']: '',
            'create_at' => time(),
            'status' => UserComments::UNCHACKED,
            'likes' => 0,
            'domain' => $this->domain,
            'client' => isset($input['client'])? $input['client']: 'ios',
            'ip' => Request::getClientAddress(),
            'location' => isset($input['location'])? $input['location']: '',
            'partition_by' => date('Y'),
        ];
        if($comment->save($data)) {
            return $this->_json([]);
        } else {
            return $this->_json([], 404, 'Not Found');
        }
    }

    private function constructInfoResult($comments) {
        $result = array();
        $keys = array('id' => 'name', 'user_id' => 'user_id',
            'location' => 'location', 'create_at' => 'create_at', 'likes' => 'likes', 'status' => 'status', 'down' => 'down', 'content' => 'content');
        $userIds = array();
        foreach($comments as $comment) {
            if (!in_array($comment['user_id'], $userIds)) {
                array_push($userIds, intval($comment['user_id']));
            }
        }
        //array_push($userIds, 16);
        if(count($userIds)) {
            $users = Users::findUsers($userIds);
        }
        else {
            $users = array(); 
        } 

        $users = array_refine($users, 'uid');
        foreach($comments as $comment) {
            $commentArr = array();
            foreach($comment as $key => $value) {
                if (array_key_exists($key, $keys)) {
                    if ($key == 'create_at') {
                        $value = IWC::timeTransform(intval($value));
                    }
                    $commentArr[$keys[$key]] = $value;
                }
            }
            //TODO upstairs 如果把所有的comment加载到内存中查询，将会占用很大内存
            //如果用sql语句查询，将耗费RDS资源。可在评论中加个tree id。
            $commentArr['upstairs'] = array();
            $commentArr['avatar'] = isset($users[$comment['user_id']])&&isset($users[$comment['user_id']]) ? $users[$comment['user_id']]['avatar'] : "";
            if (!empty($commentArr['avatar'])){
                $commentArr['avatar'] = Oss::url($commentArr['avatar']);
            }
            $commentArr['username'] = isset($users[$comment['user_id']])&&isset($users[$comment['user_id']]) ? $users[$comment['user_id']]['username'] : "";
            array_push($result, $commentArr);
        }
        return $result;
    }



}
