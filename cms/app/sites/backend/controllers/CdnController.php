<?php

class CdnController extends \BackendBaseController {

    public function clearAction() {
        $urls = [];
        $messages = [];
        if(Request::isPost()) {
            $urls = explode("\n", Request::getPost('urls'));
            $urls = array_filter($urls, function($url) {
                if($url) {
                    return true;
                } else {
                    return false;
                }
            });
            if(!empty($urls)) {
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
                    $rs = $cdn->addPurge($token, [], $urls);
                    if($rs->status  != 1) {
                        MemcacheIO::delete($key);
                    }
                    $messages[] = $rs->info;
                }
            } else {
                $messages[] = 'Invalid url';
            }
        }
        $urls = implode("\n", $urls);
        View::setVars(compact('messages', 'urls'));
    }

}