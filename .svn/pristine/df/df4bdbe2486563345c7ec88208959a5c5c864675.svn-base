<?php

class CacheTask extends Task {

    //每分钟一次
    public function lotteriesAction(array $items) {
        $groups = LotteryGroup::find();
        foreach($groups as $group) {
            Lotteries::openedLotteries($group->id, true);
        }
        $this->info('Complete.');
    }

    public function templatesAction() {
        $tpls = Templates::allNoneStatic();
        $i = 0;
        foreach($tpls as $tpl) {
            $i++;
            MemcacheIO::set('smarty:'.$tpl->domain_id.':'.$tpl->path, [
                'content' => $tpl->content,
                'updated_at' => $tpl->updated_at,
            ]);
        }
        $this->info($i.' items cached.');
    }

}