<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2015/9/24
 * Time: 14:50
 */

Class VoteOptionController extends \BackendBaseController{
    const VOTE_DATA_MODEL = 'cztv::vote::data::model::';

    public function indexAction(){
        $vote_id = Request::get('vote_id');
        if ($vote_id) {
            $vote_option = VoteOption::findOptionsByVoteId($vote_id);
        }
        View::setVars(compact('vote_option'));
    }

    /**
     * 新增投票
     */
    public function addAction(){
        $data_option='';
        $messages=[];
        if(Request::isPost() && $vote_id=Request::getPost('vote_id')){
            $option = Request::getPost();
            $options_arr = VoteOption::getOptionsByVoteId($vote_id);
            $number = count($options_arr);

            if($number!=0&&$number!=$options_arr[($number-1)]['number']){
                $this->sortNumber($options_arr ,$messages);
            }

            DB::begin();
            $option_pos = 0;
            foreach ($option['option_content'] as $pos => $value) {
                $number++;

                $vote_option = new VoteOption();
                $data_arr = [];
                $data_arr['vote_id'] = $vote_id;
                $data_arr['number'] = $number;
                $data_arr['content'] = $option['option_content'][$pos];

                $data_arr['picture'] = $this->validateAndUpload($messages, $option_pos);//图片上传地址
                if ($data_arr['picture'] == 'nopicture') {//图片上传地址
                    $data_arr['picture'] = $option['option_picture'][$pos] == '' ? '' : $option['option_picture'][$pos];
                }

                $data_arr['video_url'] = $option['option_video_url'][$pos];
                $data_arr['other'] = $option['option_other'][$pos];
                $data_arr['sum'] = $option['option_sum'][$pos] == '' ? 0 : $option['option_sum'][$pos];
                $data_arr['actual_sum'] = 0;
                if (!$option_id = $vote_option->saveGetId($data_arr)) {
                    DB::rollback();
                    $messages[] = '投票选项' . $number . '创建不成功';
                    break;
                }else{
                    $vote = Vote::findVoteById($vote_id);
                    $arr = explode(',',$vote->option_id);
                    $arr[] = $option_id;
                    $vote->option_id = implode(',',$arr);
                    $vote->update();
                    $data = Data::getByMedia($vote_id,'vote');
                    RedisIO::delete(self::VOTE_DATA_MODEL.$data->id);
                    $data_arr['video_url'] = cdn_url('image', $data_arr['video_url']);
                    $option_json = json_encode($data_arr);
                    RedisIO::hSet(self::VOTE_DATA_MODEL. $data->id . '::hash', $option_id, $option_json);
                    RedisIO::zAdd(self::VOTE_DATA_MODEL. $data->id . '::zset', $data_arr['sum'], $option_id);
                }

                $option_pos++;
            }
            DB::commit();
            if(empty($messages)){
                $messages[]=Lang::_('success');
            }
        }
        View::setVars(compact('messages'));
        View::setMainView('layouts/add');
    }

    //数字的number排序
    private function sortNumber($options_arr ,$messages){
        DB::begin();
        foreach ($options_arr as $key=>$value){
            $option = new VoteOption();
            $value['number'] = $key+1;
            $option->assign($value);
            $return = $option->update();
            if(!$return){
                DB::rollback();
                $messages[] = '投票选项排序出错';
                break;
            }
        }
        DB::commit();
    }

    /**
     * 修改投票
     */
    public function editAction(){
        $messages=[];
        $data = '';
        if (Request::isPost()) {
            $data = Request::getPost();
            $picture = $this->validateAndUpload($messages);//图片上传地址
            if ($picture != '' && $picture != 'nopicture') {//图片上传地址
                $data['picture'] = $picture;
            }
            if (isset($data['id'])) {
                $option = VoteOption::findOptionById($data['id']);
                $option->assign($data);
                $return = $option->update();

                $data = Data::getByMedia($option->vote_id,'vote');
                RedisIO::delete(self::VOTE_DATA_MODEL.$data->id);
                $option->video_url = cdn_url('image', $option->video_url);
                $option_id = $option->id;
                unset($option->id);
                $option_json = json_encode($option);
                RedisIO::hSet(self::VOTE_DATA_MODEL. $data->id . '::hash', $option_id, $option_json);
                RedisIO::zAdd(self::VOTE_DATA_MODEL. $data->id . '::zset', $option->sum, $option_id);
                if ($return) {
                    $messages[]=Lang::_('success');
                } else {
                    $messages[]=Lang::_('error');
                }
            }else{
                $messages[]='对应投票不存在';
            }

        }
        if (Request::getQuery()) {
            $option_id = Request::getQuery('id', 'int');
            $data = VoteOption::findOptionById($option_id);
        }
        View::setVars(compact('messages','data'));
        View::setMainView('layouts/add');
    }

    /**
     *
     *删除
     */
    public function deleteAction()
    {
        $messages = [];
        $id = Request::get('id', 'int');
        if($id) {
            $option = VoteOption::findOptionById($id);
            $vote_id = $option->vote_id;
            $option_id = $option->id;
            $option->delete();

            $vote = Vote::findVoteById($vote_id);
            $arr = explode(',',$vote->option_id);
            $key = array_search($option_id, $arr);
            if ($key !== false) {
                array_splice($arr, $key, 1);
            }
            $vote->option_id = implode(',',$arr);
            $vote->update();

            $data = Data::getByMedia($vote_id,'vote');
            RedisIO::delete(self::VOTE_DATA_MODEL.$data->id);
            RedisIO::hDel(self::VOTE_DATA_MODEL. $data->id . '::hash', $option_id);
            RedisIO::zDelete(self::VOTE_DATA_MODEL. $data->id . '::zset', $option_id);

            $options_arr = VoteOption::getOptionsByVoteId($vote_id);
            $number = count($options_arr);

            if($number!=0&&$number!=$options_arr[($number-1)]['number']){
                $this->sortNumber($options_arr ,$messages);
            }

            $arr = array('code' => 200);
        } else {
            $arr = array('msg' => Lang::_('failed'));
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

}