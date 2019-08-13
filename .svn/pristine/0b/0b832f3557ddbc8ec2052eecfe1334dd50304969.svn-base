<?php

class ClearTask extends Task {

    /* 清除redis lottery所有数据
    public function lotteryAction(array $items) {
        $keys = RedisIO::keys('lottery:*');
        RedisIO::multi();
        foreach($keys as $key) {
            RedisIO::del($key);
        }
        RedisIO::exec();
    }*/
    
    public function countAction(){
        $keys = RedisIO::keys('lottery:*');
        RedisIO::multi();
        foreach($keys as $key) {
            $lottery_id = explode(":",$key);
            if (isset($lottery_id[1]) && $lottery_id[1]>0){
                Lotteries::incrLotteryCount($lottery_id[1]);
            }
        }
        RedisIO::exec();
        echo count($keys), ' keys found.', PHP_EOL;
    }

    // 标记已发货
    public function statusAction(){
        $data = LotteryContacts::query()
            ->andCondition('created_at','<',strtotime('2015-11-14'))
            ->andCondition('status', 1)
            ->execute();
        if(!empty($data)){
            foreach($data as $v){
                $v->status = 2;
                $v->update();
                $win = LotteryWinnings::findFirst($v->id);
                if($win) {
                    LotteryContacts::saveRedisWinners($v->id, $win->lottery_group_id, $v->mobile, $v->name, $win->prize_name, $win->prize_level, $v->status);
                }
            }
        }
        echo count($data), ' results found.', PHP_EOL;
    }

    public function redisKeysAction($items) {
        if(empty($items)) {
            $this->warning('Empty redis keys.');
            return false;
        }
        RedisIO::multi();
        foreach($items as $item) {
            RedisIO::del($item);
        }
        RedisIO::exec();
        $this->info('Redis clear Done.');
    }

    /**
     * 清理 group 下所有中奖信息
     * @param $items
     */
    public function lotteryWinningsAction($items) {
        if(empty($items)) {
            $this->warning('Empty lottery groups.');
            exit;
        }
        foreach($items as $item) {
            $item = (int) $item;
            DB::begin();
            $winnings = LotteryWinnings::query()
                ->andCondition('lottery_group_id', $item)
                ->execute();
            foreach($winnings as $winning) {
                $contact = LotteryContacts::findFirst($winning->id);
                if($contact) {
                    $contact->delete();
                }
                $winning->delete();
            }
            DB::commit();
            LotteryContacts::latestWinners($item, true);
            $keys = RedisIO::keys('winners:'.$item.':*');
            $this->redisKeysAction($keys);
            $this->clearLotteryPeople($item);
        }
        $this->info('Winnings clear done.');
    }

    private function clearLotteryPeople($group_id){
        $lottery = Lotteries::query()->andCondition('group_id',$group_id)->execute();
        if(!empty($lottery)){
            foreach($lottery as $v){
                $key = 'lottery_people:'.$v->id;
                RedisIO::delete($key);
            }
        }
    }

    public function lotteryCountAction(){
        $keys = RedisIO::keys('lottery_people:*');
        if(!empty($keys)){
            foreach($keys as $v){
                RedisIO::delete($v);
                echo 'delete',$v,PHP_EOL;
            }
        }else{
            echo 'empty',PHP_EOL;
        }
    }

}