<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2015/9/24
 * Time: 14:50
 */

Class WxkeywordController extends \BackendBaseController{

    public function indexAction(){
        $keyword = WxKeyword::query()->paginate(50, 'Pagination');
        View::setVars(compact('keyword'));
    }

    /**
     * 新增投票
     */
    public function createAction(){
        $data='';
        $messages='';
        if(Request::isPost()){
            $post = Request::getPost();

            $validator = WxKeyword::validators($post);
            if (!$validator->fails()) {
                $data = new WxKeyword();
                $data->assign($post);
                $result = $data->save();
                if ($result) {
                    $messages[]=Lang::_('success');
                } else {
                    $messages[]=Lang::_('error');
                }
            } else {
                $messages=$validator->messages()->all();
            }
        }
        View::setVars(compact('messages','data'));
        View::setMainView('layouts/add');
    }


    /**
     * 修改投票
     */
    public function modifyAction(){
        $messages=[];
        $data = '';
        if (Request::isPost()) {
            $post = Request::getPost();
            $data = WxKeyword::getOneById($post['id']);

            $validator = WxKeyword::validators($post);
            if (!$validator->fails()) {
                $data->assign($post);
                $result = $data->update();
                if ($result) {
                    $messages[]=Lang::_('success');
                } else {
                    $messages[]=Lang::_('error');
                }
            } else {
                $messages=$validator->messages()->all();
            }
        }
        if (Request::getQuery()) {
            $keyword_id = Request::getQuery('id', 'int');
            $data = WxKeyword::getOneById($keyword_id);
        }
        View::setVars(compact('messages','data'));
        View::setMainView('layouts/add');
    }


    /**
     *
     *查看投票细节修改
     */
    public function detailAction(){
        $messages=[];
        if (Request::isPost()) {
            $data='';
            $data_up = Request::getPost();
            $theme_id=$data_up['id'];
            $data_up=array_chunk($data_up, 3);
            foreach($data_up as $k){
                $result = Options::getOneOption($k[0]);
                $data=array(
                    'option_id'=>$k[0],
                    'options_content'=>$k[1],
                    'count'=>$k[2]
                );
                $validator = Options::makeValidators($data);
                if (!$validator->fails()) {
                    if ($result->modifyOption($data)) {
                        $messages[] = Lang::_('success');
                    } else {
                        $messages[] = Lang::_('error');
                    }
                }else {
                    $messages=$validator->messages()->all();
                }
            }
        }
        if (Request::getQuery()) {
            $theme_id = Request::getQuery('id', 'int');
            $data = VoteTheme::getOneTheme($theme_id);
            $query2 = Options::query()->andCondition('theme_id',$theme_id);
            $options = $query2->paginate(50, 'Pagination');
        }

        View::setVars(compact('data','options','messages'));
        View::setMainView('layouts/add');

    }

    /**
     *
     *删除
     */
    public function deleteAction()
    {
        $id=$this->request->getQuery("id","int");
        $data = WxKeyword::getOneById($id);
        if($data->delete()){
            $arr=array('code'=>200);
        }else{
            $arr=array('msg'=>Lang::_('failed'));
        }
        echo json_encode($arr);
        exit;
    }

    protected function validateAndUpload(&$messages,$pos=0) {
        $path = '';
        if(Request::hasFiles()) {
            /**
             * @var $file \Phalcon\Http\Request\File
             */
            $file = Request::getUploadedFiles()[$pos];
            $error = $file->getError();
            if(!$error) {
                $ext = $file->getExtension();
                if(in_array(strtolower($ext), ['jpg', 'jpeg', 'gif', 'png'])) {
                    $path = Oss::uniqueUpload(strtolower($ext), $file->getTempName(), 'vote_option');
                } else {
                    $messages[] = Lang::_('please upload valid image');
                }
            } elseif($error == 4) {
                $path = 'nopicture';
            } else {
//                $messages[] = Lang::_('unknown error');
            }
        } else {
//            $messages[] = Lang::_('please choose upload image');
        }
        return $path;
    }

    const VOTE_VERIFY = 'cztv::vote::data::verify::';

    protected function saveVerifyRedis($vote_id , $captcha_verify) {
        $vote = RedisIO::set(self::VOTE_VERIFY . $vote_id , $captcha_verify);
        return $vote;
    }

}