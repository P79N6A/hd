<?php

class LotteryCacheController extends \BackendBaseController {

    /**
     * Index action
     */
    public function indexAction() {
    }

    public function clearCdnAction() {
        $type = 'danger';
        $msg = Lang::_('api request failed');
        $key = 'cdn.fast_web.yao';
        /**
         * @var \GenialCloud\Network\Services\FastWebCDN $cdn
         */
        $cdn = $this->getDI()->getShared($key);
        $token = MemcacheIO::get($key);
        if(!$token) {
            $token = $cdn->requestAccessToken()['token'];
            MemcacheIO::set($key, $token, 43200);
        }
        if($token) {
            $rs = $cdn->addPurge($token, [], [
                'http://yao.hd.cztv.com/',
                'http://yao.hd.cztv.com/index.html',
                'http://yao.hd.cztv.com/share.html',
                'http://yao.hd.cztv.com/yao.html'
            ]);
            if($rs->status  == 1) {
                $type = 'success';
            } else {
                $type = 'danger';
                MemcacheIO::delete($key);
            }
            $msg = $rs->info;
        }
        View::setVars(compact('msg', 'type'));
    }

    public function clearListAction() {
        $g = LotteryGroup::listGroups();
        if(!empty($g)) {
            foreach($g as $v) {
                Lotteries::openedLotteries($v['id'], true);
            }
        }
        $type = 'success';
        $msg = Lang::_('complete');
        View::pick('lottery_cache/clearcdn');
        View::setVars(compact('msg', 'type'));
    }

}