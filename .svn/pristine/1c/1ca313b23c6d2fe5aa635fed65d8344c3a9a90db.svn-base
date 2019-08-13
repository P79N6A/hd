<?php

class VoteTask extends Task {
    const VOTE_DATA_MODEL = 'cztv::vote::data::model::';
    
    public function updateoptionAction(){
        $vote_arr = Vote::query()->andCondition('status',Vote::STATUS_START)->execute();
        foreach ($vote_arr as $vote){
            if($vote->start_time>time()) {//还未开始
                continue;
            }elseif ($vote->end_time<=time()) {
                $vote->status = Vote::STATUS_END;
                $vote->update();
                continue;
            }else{
                $data = Data::query()
                    ->andCondition('source_id', $vote->id)
                    ->andCondition('type','vote')
                    ->first();
                if($data) {
                    $model = RedisIO::get(self::VOTE_DATA_MODEL . $data->id);
                    $model = json_decode($model, true);
                    if ($model) {
                        foreach ($model['vote_option'] as $k => $option) {
                            if (!$option['id']) {
                                continue;
                            }
                            $o = VoteOption::findOptionById($option['id']);
                            $o->sum = $option['sum'];
                            $o->actual_sum = $option['actual_sum'];
                            $o->update();
                        }
                    } else {
                        $option = RedisIO::hGetAll(self::VOTE_DATA_MODEL. $data->id . '::hash');
                        $option_ids = RedisIO::zRevRange(self::VOTE_DATA_MODEL . $data->id . '::zset', 0, -1, withscores);
                        if (!$option || !$option_ids) {
                            continue;
                        }
                        foreach ($option as $k => $value) {
                            $option_arr = json_decode($value, true);
                            $o = VoteOption::findOptionById($k);
                            $o->sum = $option_ids[$k];
                            $o->actual_sum = $option_ids[$k] - $option_arr['sum'] + $option_arr['actual_sum'];
                            $o->update();
                        }
                    }
                }else{
                    continue;
                }
            }
        }
    }

}