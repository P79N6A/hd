<?php

class UpdatesetTask extends Task {

    public function updateAction() {
        if(RedisIO::get(SubscriptionSetInfo::$get_status_key)==1) {
            RedisIO::set(SubscriptionSetInfo::$get_status_key , 2);

            try {
                $file_contents = F::curlProxyCli('http://tv.cztv.com/block/albumlist.json');
//                $file_contents = file_get_contents('http://tv.cztv.com/block/albumlist.json');
                $resp = json_decode($file_contents, true);

                if (!$resp || empty($resp)) {
                    throw new Exception();
                }

                $return = SubscriptionSet::deleteAll();
                if (!$return) {//清楚旧数据失败,暂停并写入日志
                    throw new Exception();
                }

                foreach ($resp as $key =>$value) {
                    $subscription_set = new SubscriptionSet();
                    $subscription_set->channel_id = 1;
                    $subscription_set->set_id = $value['id'];
                    $subscription_set->name = $value['title'];
                    $subscription_set->create();

                    $set_info_get = F::curlProxyCli('http://api.cms.cztv.com/mms/out/album/get?pid='.$value['id']);
//                    $set_info_get = file_get_contents('http://api.cms.cztv.com/mms/out/album/get?pid=' . $value['id']);
                    $set_info_arr = json_decode($set_info_get, true);
                    sleep(1);
                    $set_cover = '';
                    if ($set_info_arr && isset($set_info_arr['picCollections'])) {
                        foreach ($set_info_arr['picCollections'] as $pixel => $url) {
                            if ($url) {
                                $url = strpos($url,'vmsdefault.png')==false?$url:'';
                                $set_cover = $url;
                                break;
                            }
                        }
                    }

                    $set_info = SubscriptionSetInfo::findOneBySetId($value['id']);
                    if (!$set_info) {
                        $set_info = new SubscriptionSetInfo();
                        $set_info->set_id = $value['id'];
                        $set_info->set_cover = $set_cover;
                        $set_info->create();
                    }else{
                        $set_info->set_cover = $set_cover;
                        $set_info->update();
                    }
                }

                RedisIO::set(SubscriptionSetInfo::$get_status_key, 0);
                $key = D::memKey('apiFindSetAll', []);
                RedisIO::delete($key);
            }catch (Exception $e){
                RedisIO::set(SubscriptionSetInfo::$get_status_key, 1);
                //可以写日志
            }
        }
    }
}


?>