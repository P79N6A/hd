<?php
/**
 * Created by PhpStorm.
 * User:
 * Date: 2015/9/24
 * Time: 14:50
 */

Class VoteController extends \BackendBaseController{

    const VOTE_DATA_MODEL = 'cztv::vote::data::model::';

    public function indexAction(){
        $vote = VoteTheme::query()->paginate(10, 'Pagination');
        View::setVars(compact('vote'));
    }

    /**
     * 新增投票
     */
    public function createAction(){
        $data_option='';
        $messages='';
        if(Request::isPost()){
            $data = Request::getPost();
            $data['vote_star']=strtotime($data['vote_star']);
            $data['vote_end']=strtotime($data['vote_end']);
            $validator = VoteTheme::makeValidators($data);
            $options = explode(',',$data['option']);
            if (!$validator->fails()) {

                $plan = new VoteTheme();
                $plan = $plan->createVote($data);
                foreach($options as $v){
                    $result = new Options();
                    $data_option['theme_id']=$plan;
                    $data_option['options_content']=$v;
                    $data_option['count']=0;
                    $result=$result->createOption($data_option);
                }
                if ($plan) {
                    $messages[]=Lang::_('success');
                } else {
                    $messages[]=Lang::_('error');
                }
            } else {
                $messages=$validator->messages()->all();
            }
        }
        View::setVars(compact('messages'));
        View::setMainView('layouts/add');
    }


    /**
     * 修改投票
     */
    public function modifyAction(){
        $messages=[];
        $data = '';
        if (Request::isPost()) {
            $data = Request::getPost();
            $theme_id=$data['id'];
            $data['vote_star']=strtotime($data['vote_star']);
            $data['vote_end']=strtotime($data['vote_end']);
            $validator = VoteTheme::makeValidators2($data);
            if (!$validator->fails()) {
                $plan = VoteTheme::getOneTheme($theme_id);
                $result = $plan->modifyTheme($data);
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
            $theme_id = Request::getQuery('id', 'int');
            $data = VoteTheme::getOneTheme($theme_id);
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
        if (Request::getQuery()) {
            $theme_id = Request::getQuery('id', 'int');
            if(VoteTheme::deleteTheme($theme_id)&&Options::deleteOption($theme_id)){
                redirect(Url::get('channel/index'));
            }else{
                $messages[]=Lang::_('error');
            }

        }
    }

    public function voteAction(){
        $input = Request::getPost();
        $messages = [];
        $id = Request::get('id', 'int');
        $model = Data::getById($id, Auth::user()->channel_id);
        if(!$model) {
            $this->alert('对应新闻媒资不存在');
        }
        if($input && array_key_exists('title',$input)) {
            $options = [];
            $option_pos = 0;

            foreach ($input['title'] as $k=>$title){
                $o = [];
                foreach ($input as $key=>$value) {
                    $o[$key] = $input[$key][$k];
                }
                $options[] = $o;
            }

            foreach ($options as $k => $option){
                $validators = Vote::makeValidators($option);
                if (!$validators->fails()) {
                    DB::begin();
                    try {
                        //创建投票
                        $vote = new Vote();
                        $data = [];
                        if ($option['toasts'] == 1) {
                            $data['option_min'] = 1;
                            $data['option_max'] = 1;
                        } else {
                            $data['option_min'] = $option['toastsmin'] == '' ? 1 : $option['toastsmin'];
                            $data['option_max'] = $option['toastsmax'] == '' ? 1000 : $option['toastsmax'];//足够大的选项数
                        }
                        if ($option['toastsmin'] > count($option['option_content']) || $option['toastsmin'] > $option['toastsmax']) {//选项数量判断
                            DB::rollback();
                            $messages[] = '投票' . ($k + 1) . '选项数量和限制冲突';
                            continue;
                        }
                        $data['type'] = $option['votetype'];
                        if ($option['votenum'] == 1) {
                            $data['times'] = $option['votenumtext1'] == '' ? 1 : $option['votenumtext1'];
                            $data['rate'] = 86400;
                        } else {
                            $data['times'] = $option['votenumtext2'] == '' ? 1 : $option['votenumtext2'];
                            $data['rate'] = 0;
                        }
                        $data['start_time'] = $option['start_time'] == '' ? time() : strtotime($option['start_time']);
                        $data['end_time'] = $option['end_time'] == '' ? strtotime("+1 year") : strtotime($option['end_time']);
                        $data['status'] = Vote::STATUS_START;
                        $data['captcha_verify'] = $option['captcha_verify']?:Vote::VERIFY_OFF;
                        if (!$vote_id = $vote->saveGetId($data)) {
                            DB::rollback();
                            $messages[] = '投票' . ($k + 1) . '创建不成功';
                            continue;
                        }
                        //创建媒资
                        $new_model = new Data();
                        $user = Session::get('user');
                        $data_model = [];
                        $data_model['channel_id'] = $user->channel_id;
                        $data_model['title'] = $option['title'];
                        $data_model['intro'] = $option['title'];
                        $data_model['created_at'] = time();
                        $data_model['updated_at'] = time();
                        $data_model['author_id'] = $user->id;
                        $data_model['author_name'] = $user->name;
                        $data_model['status'] = 1;
                        $data_model['partition_by'] = date('Y');
                        if (!$data_id = $new_model->doSave($data_model, Data::getAllowed(), 'vote', $vote_id)) {
                            DB::rollback();
                            $messages[] = '投票' . ($k + 1) . '媒资创建不成功';
                            $this->throwDbE('dModel');
                        }
                        //保存验证码配置缓存
                        $this->saveVerifyRedis($data_id,$data['captcha_verify']);
                        //创建选项
                        $option_id = [];
                        $number = 1;
                        foreach ($option['option_content'] as $pos => $value) {
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
                            if (!$option_id[] = $vote_option->saveGetId($data_arr)) {
                                DB::rollback();
                                $messages[] = '投票' . ($k + 1) . '的选项' . $number . '创建不成功';
                                break;
                            }
                            $number++;
                            $option_pos++;
                        }
                        $option_str = implode(',', $option_id);
                        if (!Vote::changeOptionId($vote_id, $option_str)) {
                            DB::rollback();
                            $messages[] = '投票' . ($k + 1) . '的选项关联不成功';
                            continue;
                        }
                        $data_data = json_decode($model->data_data, true);
                        $data_data[] = $data_id;
                        $model->data_data = json_encode($data_data);
                        $data_data_ext = json_decode($model->data_data_ext, true);
                        if(!isset($data_data_ext['vote'])) {
                            $data_data_ext['vote'] = array();
                        }
                        $data_data_ext['vote'][] = array('data_id'=>$data_id, 'template'=>'default');
                        $model->data_data_ext = json_encode($data_data_ext);
                        if (!$model->update()) {
                            DB::rollback();
                            $messages[] = '投票' . ($k + 1) . '添加到对应新闻失败';
                            continue;
                        }
                    }catch (Exception $e){
                        DB::rollback();
                        $messages[] = ''.$e->getMessage();
                    }
                    DB::commit();
                } else {
                    foreach($validators->messages()->all() as $msg) {
                        $messages[] = '第'.($k+1).'个投票表单验证出错：'.$msg;
                    }
                }
            }

            if(empty($messages)){
                redirect( 'votedetail?id='.$id );
            }else{
                //回到添加页
                View::setVars(compact('messages'));
            }

        }
        View::setMainView('layouts/add');
    }

    public function votedetailAction(){
        $messages = [];
        $votes = [];
        $options = [];
        $id = Request::get('id', 'int');
        $channel_id = Auth::user()->channel_id;
        $data = Data::getById($id, $channel_id);//此媒资为载体媒资
        if(!$data) {
            $this->alert('对应新闻媒资不存在');
        }
        $data_data = json_decode($data->data_data);
        $option_pos = 0;
        foreach ($data_data as $key => $data_data_id ){
            $model = Data::getById($data_data_id , $channel_id);
            if($model->type != 'vote') continue;
            $vote = Vote::findVoteById($model->source_id);
            $vote->title = $model->title;
            $option_arr = explode(',',$vote->option_id);
            /*foreach ($option_arr as $k => $option_id){
                $option = VoteOption::findOptionById($option_id);
                $options[$option_pos][] = $option;
            }*/
            $option = VoteOption::getOptionsByVoteId($vote->id);
            $options[$option_pos] = $option;
            $votes[] = $vote;
            $option_pos++;
        }

        View::setVars(compact('votes','options','messages'));
        View::setMainView('layouts/add');
    }

    public function voteeditAction(){
        $messages = [];
        $input = Request::getPost();
        $id = Request::get('id', 'int');
        $channel_id = Auth::user()->channel_id;
        $model = Data::getById($id, $channel_id);//此媒资为载体媒资
        if(!$model) {
            $this->alert('对应新闻媒资不存在');
        }
        if($input && array_key_exists('title',$input)) {
            $options = [];
            $option_pos = 0;
            foreach ($input['title'] as $k => $title) {
                $o = [];
                foreach ($input as $key => $value) {
                    $o[$key] = $input[$key][$k];
                }
                $options[] = $o;
            }

            //清除原有载体媒资中的所有vote媒资
            $data_data = json_decode($model->data_data, true);
            /*foreach ($data_data as $key => $data_data_id ) {
                $m = Data::getById($data_data_id, $channel_id);
                if($m->type != 'vote') continue;
                $key = array_search($m->id, $data_data);
                if ($key !== false) {
                    array_splice($data_data, $key, 1);
                }
                $v = Vote::findVoteById($m->source_id);
                $m->delete();
                $v->delete();
            }
            $model->data_data = json_encode($data_data);
            if(!$model->update()){
                $messages[] = '添加到对应新闻失败';
            }*/
            foreach ($options as $k => $option){
                $validators = Vote::makeValidators($option);
                if (!$validators->fails()) {
                    DB::begin();
                    try {
                        $data = [];
                        if ($option['toasts'] == 1) {
                            $data['option_min'] = 1;
                            $data['option_max'] = 1;
                        } else {
                            $data['option_min'] = $option['toastsmin'] == '' ? 1 : $option['toastsmin'];
                            $data['option_max'] = $option['toastsmax'] == '' ? 1000 : $option['toastsmax'];//足够大的选项数
                        }
                        if ($option['toastsmin'] > count($option['option_content']) || $option['toastsmin'] > $option['toastsmax']) {//选项数量判断
                            DB::rollback();
                            $messages[] = '投票' . ($k + 1) . '选项数量和限制冲突';
                            continue;
                        }
                        $data['type'] = $option['votetype'];
                        if ($option['votenum'] == 1) {
                            $data['times'] = $option['votenumtext1'] == '' ? 1 : $option['votenumtext1'];
                            $data['rate'] = 86400;
                        } else {
                            $data['times'] = $option['votenumtext2'] == '' ? 1 : $option['votenumtext2'];
                            $data['rate'] = 0;
                        }
                        $data['start_time'] = $option['start_time'] == '' ? time() : strtotime($option['start_time']);
                        $data['end_time'] = $option['end_time'] == '' ? strtotime("+1 year") : strtotime($option['end_time']);
                        $data['status'] = Vote::STATUS_START;
                        $data['captcha_verify'] = $option['captcha_verify']?:Vote::VERIFY_OFF;
                        //是否传递id来判断是否是修改与新建
                        if ($option['vote_id']) {
                            $vote = Vote::findVoteById($option['vote_id']);
                            if (!$return = $vote->update($data)) {
                                DB::rollback();
                                $messages[] = '投票' . ($k + 1) . '保存不成功';
                                continue;
                            } else {
                                $vote_id = $option['vote_id'];
                                $vote_data = Data::getBySourceId($vote_id,$channel_id,'vote');
                                $data_id = $vote_data->id;
                                $is_new_vote = false;
                                RedisIO::delete(self::VOTE_DATA_MODEL . $vote_id);
                            }
                        } else {
                            //创建投票
                            $vote = new Vote();
                            if (!$vote_id = $vote->saveGetId($data)) {
                                DB::rollback();
                                $messages[] = '投票' . ($k + 1) . '创建不成功';
                                continue;
                            }
                            //创建媒资
                            $new_model = new Data();
                            $user = Session::get('user');
                            $data_model = [];
                            $data_model['channel_id'] = $user->channel_id;
                            $data_model['title'] = $option['title'];
                            $data_model['intro'] = $option['title'];
                            $data_model['created_at'] = time();
                            $data_model['updated_at'] = time();
                            $data_model['author_id'] = $user->id;
                            $data_model['author_name'] = $user->name;
                            $data_model['status'] = 1;
                            $data_model['partition_by'] = date('Y');
                            if (!$data_id = $new_model->doSave($data_model, Data::getAllowed(), 'vote', $vote_id)) {
                                DB::rollback();
                                $messages[] = '投票' . ($k + 1) . '媒资创建不成功';
                                $this->throwDbE('dModel');
                            }
                            $is_new_vote = true;
                        }
                        //保存验证码配置缓存
                        $this->saveVerifyRedis($data_id,$data['captcha_verify']);
                        //创建选项
                        $option_id = [];
                        $number = 1;
                        if ($is_new_vote) {
                            foreach ($option['option_content'] as $pos => $value) {
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
                                $data_arr['actual_sum'] = $option['option_actual_sum'][$pos] == '' ? 0 : $option['option_actual_sum'][$pos];
                                if (!$option_id[] = $new_option_id = $vote_option->saveGetId($data_arr)) {
                                    DB::rollback();
                                    $messages[] = '投票' . ($k + 1) . '的选项' . $number . '创建不成功';
                                    continue;
                                }
                                $data_arr['id'] = $new_option_id;
                                RedisIO::zAdd(self::VOTE_DATA_MODEL . $data_id . '::zset', $new_option_id, $data_arr['sum']);
                                RedisIO::hSet(self::VOTE_DATA_MODEL . $data_id . '::hash', $new_option_id, json_encode($data_arr));
                                $number++;
                                $option_pos++;
                            }
                        } else {
                            $old_options = VoteOption::getOptionsByVoteId($vote_id);
                            $max = VoteOption::query()
                                ->andCondition('vote_id', $vote_id)
                                ->orderBy('number desc')
                                ->limit(1)
                                ->execute()
                                ->toArray();
                            $max_number = $max['number'] + 1;
                            foreach ($option['option_content'] as $pos => $value) {
                                //是否传递id来判断是否是修改与新建
                                if ($option['option_id'][$pos]) {
                                    $data_arr = [];
                                    $data_arr['picture'] = $this->validateAndUpload($messages, $option_pos);//图片上传地址
                                    if ($data_arr['picture'] == 'nopicture') {//图片上传地址
                                        $data_arr['picture'] = $option['option_picture'][$pos] == '' ? '' : $option['option_picture'][$pos];
                                    }
                                    foreach ($old_options as $old_options_key => $old_option) {
                                        if ($option['option_id'][$pos] == $old_option['id']) {
                                            if ($option['option_content'][$pos] != $old_option['content'] || $data_arr['picture'] != $old_option['picture'] || $option['option_video_url'][$pos] != $old_option['video_url'] || $option['option_other'][$pos] != $old_option['other'] || $option['option_sum'][$pos] != $old_option['sum']) {
                                                $vote_option = VoteOption::findOptionById($option['option_id'][$pos]);
                                                $vote_option->content = $option['option_content'][$pos];
                                                $vote_option->video_url = $option['option_video_url'][$pos];
                                                $vote_option->other = $option['option_other'][$pos];
                                                $vote_option->sum = $option['option_sum'][$pos] == '' ? 0 : $option['option_sum'][$pos];
                                                $vote_option->actual_sum = $option['option_actual_sum'][$pos] == '' ? 0 : $option['option_actual_sum'][$pos];
                                                if (!$vote_option->save()) {
                                                    DB::rollback();
                                                    $messages[] = '投票' . ($k + 1) . '的选项' . $number . '创建不成功';
                                                    continue;
                                                }
                                                RedisIO::zAdd(self::VOTE_DATA_MODEL . $data_id . '::zset', $option['option_id'][$pos], $vote_option->sum);
                                                RedisIO::hSet(self::VOTE_DATA_MODEL . $data_id . '::hash', $option['option_id'][$pos], json_encode($vote_option));
                                            } else {
                                                $option_id[] = $option['option_id'][$pos];
                                            }
                                            unset($old_options[$old_options_key]);
                                        }
                                    }
                                } else {
                                    $vote_option = new VoteOption();
                                    $data_arr = [];
                                    $data_arr['vote_id'] = $vote_id;
                                    $data_arr['number'] = $max_number;
                                    $data_arr['content'] = $option['option_content'][$pos];

                                    $data_arr['picture'] = $this->validateAndUpload($messages, $option_pos);//图片上传地址
                                    if ($data_arr['picture'] == 'nopicture') {//图片上传地址
                                        $data_arr['picture'] = $option['option_picture'][$pos] == '' ? '' : $option['option_picture'][$pos];
                                    }

                                    $data_arr['video_url'] = $option['option_video_url'][$pos];
                                    $data_arr['other'] = $option['option_other'][$pos];
                                    $data_arr['sum'] = $option['option_sum'][$pos] == '' ? 0 : $option['option_sum'][$pos];
                                    $data_arr['actual_sum'] = $option['option_actual_sum'][$pos] == '' ? 0 : $option['option_actual_sum'][$pos];
                                    if (!$option_id[] = $new_option_id = $vote_option->saveGetId($data_arr)) {
                                        DB::rollback();
                                        $messages[] = '投票' . ($k + 1) . '的选项' . $number . '创建不成功';
                                        continue;
                                    }
                                    $data_arr['id'] = $new_option_id;
                                    RedisIO::zAdd(self::VOTE_DATA_MODEL . $data_id . '::zset', $data_arr['sum'], $new_option_id);
                                    RedisIO::hSet(self::VOTE_DATA_MODEL . $data_id . '::hash', $new_option_id, json_encode($data_arr));
                                }
                                $option_pos++;
                            }
                            foreach($old_options as $del_key => $del_val) {
                                $option = VoteOption::findOptionById($del_val['id']);
                                if (!$option->delete()) {
                                    DB::rollback();
                                    $messages[] = '投票' . ($k + 1) . '的选项' . $number . '创建不成功';
                                    continue;
                                }
                                RedisIO::zDelete(self::VOTE_DATA_MODEL . $data_id . '::zset', $del_val['id']);
                                RedisIO::hDel(self::VOTE_DATA_MODEL . $data_id . '::hash', $del_val['id']);
                            }
                        }
                        $option_str = implode(',', $option_id);
                        if (!Vote::changeOptionId($vote_id, $option_str)) {
                            DB::rollback();
                            $messages[] = '投票' . ($k + 1) . '的选项关联不成功';
                            continue;
                        }

                        $data_data = json_decode($model->data_data, true);
                        if (!in_array($data_id, $data_data)) {
                            $data_data[] = $data_id;
                        }
                        $model->data_data = json_encode($data_data);
                        if (!$model->update()) {
                            DB::rollback();
                            $messages[] = '投票' . ($k + 1) . '添加到对应新闻失败';
                            continue;
                        }
                    }catch (Exception $e){
                        DB::rollback();
                        $messages[] = ''.$e->getMessage();
                    }

                    DB::commit();
                } else {
                    foreach($validators->messages()->all() as $msg) {
                        $messages[] = '第'.($k+1).'个投票表单验证出错：'.$msg;
                    }
                }
            }

            if(empty($messages)){
                redirect( 'votedetail?id='.$id );
            }else{
                //回到添加页
                View::setVars(compact('messages'));
            }
        }
        $data_data = json_decode($model->data_data,true);
        $option_pos = 0;
        $votes = [];
        $options = [];
        foreach ($data_data as $key => $data_data_id ){
            $model = Data::getById($data_data_id , $channel_id);
            if($model->type != 'vote') continue;
            $vote = Vote::findVoteById($model->source_id);
            $vote->title = $model->title;
            $option_arr = explode(',',$vote->option_id);
            /*foreach ($option_arr as $k => $option_id){
                $option = VoteOption::findOptionById($option_id);
                $options[$option_pos][] = $option;
            }*/
            $option = VoteOption::getOptionsByVoteId($vote->id);
            $options[$option_pos] = $option;
            $votes[] = $vote;
            $option_pos++;
        }

        View::setVars(compact('votes','options','messages'));
        View::setMainView('layouts/add');
    }

    public function votedeleteAction() {
        $id = Request::get('id', 'int');
        $vote_id = Request::get('vote_id', 'int');
        // redis = only_redis只删除缓存
        $only_redis = Request::get('redis', 'string');
        $channel_id = Auth::user()->channel_id;
        if($id && $vote_id) {
            if($only_redis != 'only_redis') {
                $data = Data::getById($id, $channel_id);
                $vote_data = Data::getBySourceId($vote_id, $channel_id, 'vote');
                $vote = Vote::findVoteById($vote_id);
                $data_data = json_decode($data->data_data, true);
                $key = array_search($vote_data->id, $data_data);
                if ($key !== false) {
                    array_splice($data_data, $key, 1);
                }
                $data->data_data = json_encode($data_data);
                $vote->delete();
                $vote_data->delete();
                $data->update();
            }
            RedisIO::delete(self::VOTE_DATA_MODEL . $vote_id, self::VOTE_DATA_MODEL . $vote_id . '::zset', self::VOTE_DATA_MODEL . $vote_id . '::hash');
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

    const VOTE_VERIFY = 'cztv::vote::data::verify::';

    protected function saveVerifyRedis($vote_id , $captcha_verify) {
        $vote = RedisIO::set(self::VOTE_VERIFY . $vote_id , $captcha_verify);
        return $vote;
    }
}