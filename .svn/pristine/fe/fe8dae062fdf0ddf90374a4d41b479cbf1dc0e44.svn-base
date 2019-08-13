<?php

/**
 * 索贝处理推送
 */
class SobeyNewTask extends Task {

    /**
     * @param $msg
     */
    protected function log($msg) {
        echo $msg, PHP_EOL;
    }

    /**
     * 处理队列
     */
    public function videoAction() {
        $data = Sobeysupplies::query()->andCondition('status', 0)->orderBy('created_at asc')->execute()->toArray();
        if(!empty($data)) {
            foreach($data as $v) {
                $this->doSyncOne($v);
            }
        }
    }

    /**
     * @param $v
     * @return bool
     */
    protected function doSyncOne($v) {
        $data = unserialize($v['origin_content']);
        $data['channel_id'] = $v['channel_id'];
        $data['supply_id'] = $v['id'];//supplies表的id
        $supply_category_id = $v['supply_category_id'];//供应商的节目id
        //检查频道对应
        $aim_category_id = $this->savePrivateCategory($data['channel_id'],$supply_category_id,$data);
//        $aim_category_id = SupplyToPrivatecategory::getAimId($data['channel_id'],$supply_category_id);
        //检查视频对应
        $aim_data_id = $this->checkSync($data);
        if($data['status']==0 && $aim_data_id) {//删除操作
            $aim_data = Data::query()->andCondition('id', $aim_data_id)->execute();
            if(count($aim_data)) {

                // 查找栏目id
                $categoryData = CategoryData::findCategoryDataByDataId($aim_data_id);
                $category_id = $categoryData->category_id;
                // 清除redis缓存，类似媒资下架
                CategoryData::deleteListRedis($category_id, $data['channel_id']);
                if (CategoryData::PAGE_CACHE_NUMBER > 0) {
                    for ($i = 0; $i < CategoryData::PAGE_CACHE_NUMBER; $i++) {
                        $page = $i + 1;
                        $key_cache_json_key = "Backend:cache_json:" . $data['channel_id'] . ":" . $category_id . ":" . $page;
                        if (RedisIO::exists($key_cache_json_key)) {
                            RedisIO::delete($key_cache_json_key);
                        }
                    }
                }
                $video_id = $aim_data[0]->source_id;
                $aim_data[0]->delete();
                Videos::query()->andCondition('id', $video_id)->execute()->delete();
                PrivateCategoryData::query()->andCondition('data_id', $aim_data_id)->execute()->delete();
                VideoFiles::query()->andCondition('video_id',$video_id)->execute()->delete();
                $this->log('update success');
            }
            $this->updateSupplies($data['supply_id']);
            return true;
        }else if($aim_data_id ) {//更新操作
            $data['data_id'] = $aim_data_id;
            if($this->updateSyncOne($data) && $this->publish($aim_data_id, $aim_category_id, $data)){
                $this->log('update success');
                $this->updateSupplies($data['supply_id']);
                return true;
            }
        }else {
            try {//创建操作
                DB::begin();
                $this->createCat($data);
                $new = $this->createData($data, 'video');
                if ($new) {
                    if ($this->cpVideoFile($new['source_id'], $data) && $this->publish($new['data_id'], $aim_category_id, $new)) {
                        $sync = new SupplyRsync();
                        if ($sync->save([
                                'channel_id' => $data['channel_id'],
                                'origin_type' => 2,
                                'origin_id' => $data['id'],
                                'data_id' => $new['data_id'],
                            ]) && $this->updateSupplies($v['id'])
                        ) {
                            $this->log('success');
                            DB::commit();
                        }
                    } else {
                        $this->log('cpVideoFile error');
                        DB::rollback();
                        return false;
                    }
                } else {
                    $this->log('createData error');
                    DB::rollback();
                    return false;
                }
            } catch (\Exception $e) {
                dd($e->getMessage());
                DB::rollback();
                return false;
            }
        }
    }

    protected function savePrivateCategory($channel_id,$supply_category_id,$data){
        $aim_category_id = SupplyToPrivatecategory::getPrivateCategoryId($channel_id,$supply_category_id);
        $aim_arr = array();
        if($aim_category_id) {
            return $aim_category_id;
        }else {
            for($i=0;$i<=count($data['catalogTree']);$i++) {
                $aim_id = SupplyToPrivatecategory::getPrivateCategoryId($channel_id,$data['catalogTree'][$i]['catalogId']);
                $aim_arr[$i] = $aim_id ? $aim_id: 0;
                if($aim_arr[$i]) {

                }else if($i==0) {
                    $aim_arr[$i] = $this->createPrivateCategory(array('channel_id'=>$channel_id,
                        'name'=>$data['catalogTree'][$i]['catalogName'],
                        'father_id'=>0));
                    $stp = new SupplyToPrivatecategory();
                    $stp->createSupplyToPrivategory(array(
                        'channel_id'=>$channel_id,
                        'supply_category_id'=>$data['catalogTree'][$i]['catalogId'],
                        'private_category_id'=>$aim_arr[$i],
                        'origin_type'=>2
                    ));
                }else {
                    $aim_arr[$i] = $this->createPrivateCategory(array('channel_id'=>$channel_id,
                        'name'=>$data['catalogTree'][$i]['catalogName'],
                        'father_id'=>$aim_arr[$i-1]));
                    $stp = new SupplyToPrivatecategory();
                    $stp->createSupplyToPrivategory(array(
                        'channel_id'=>$channel_id,
                        'supply_category_id'=>$data['catalogTree'][$i]['catalogId'],
                        'private_category_id'=>$aim_arr[$i],
                        'origin_type'=>2
                    ));
                }
            }
            return end($aim_arr);
        }
    }

    /**
     * @param $id
     * @param int $status
     * @return bool
     */
    protected function updateSupplies($id, $status = 1) {
        return Sobeysupplies::findFirst($id)->update(['status' => $status]);
    }

    /**
     * @param $v
     * @return bool
     */
    protected function updateSyncOne($v) {
        $data = Data::findFirst($v['data_id']);
        if(!$data) {
            return false;
        }
        $update = [
            'title' => $v['title'],
//            'status' => $this->parseStatus($v['video_status']),//视频的状态应该由后台管理
            'intro' => $v['description'],
            'created_at' => strtotime($v['createTime']),
            'updated_at' => strtotime($v['playTime']),
        ];
        $update['thumb'] = $this->parseImage($v, 'video');
        return $data->update($update) && $this->updateVideo($data, $v);
    }


    /**
     * @param $data
     * @param $v
     * @return bool
     */
    protected function updateVideo($data, $v) {
        return Videos::findFirst($data->source_id)->update([
            'keywords' => isset($v['tag']) ? $v['tag'] :'',
            'created_at' => strtotime($v['createTime']),
            'updated_at' => strtotime($v['playTime']),
        ]);
    }

    /**
     * 检查是否更新
     * @param $v
     * @return bool
     */
    protected function checkSync($v) {
        $sync = SupplyRsync::query()->andCondition('channel_id', $v['channel_id'])->andCondition('origin_type', 2)->andCondition('origin_id', $v['id'])->first();
        if($sync) {
            return $sync->data_id;
        }
        return false;
    }

    /**
     * 创建主表Data+关联表
     * @param $v
     * @param $type
     * @return bool
     */
    protected function createData($v, $type) {
        $model = new Data();
//        $user = $this->getUser($v);
        $data['type'] = $type;
        $data['channel_id'] = $v['channel_id'];
        $data['source_id'] = $this->createVideo($v);
        $data['title'] = $v['title'];
        $data['intro'] = $v['description'];
        $data['thumb'] = $this->parseImage($v, $type);
        $data['created_at'] = strtotime($v['createTime']);
        $data['updated_at'] = strtotime($v['playTime']);
        $data['author_id'] = 1;
        $data['author_name'] = $v['user_name'];
        $data['hits'] = 0;
        $data['data_data'] = '[]';
        $data['status'] = 1;
        $data['partition_by'] = date("Y", time());
        $data_id = $model->saveGetId($data);
        if($data_id) {
            $data['data_id'] = $data_id;
        } else {
            return false;
        }
        return $data;
    }

    /**
     * @param $v
     * @return \Phalcon\Mvc\ModelInterface
     */
    protected function getUser($v) {
        return Admin::query()
            ->andCondition('channel_id', $v['channel_id'])
            ->andCondition('is_admin', 1)
            ->andCondition('status', 1)
            ->first();
    }

    /**
     * @param $v
     * @param $type
     * @return string
     */
    protected function parseImage($v, $type) {
        if($type == 'video' && !empty($v['imageList'][0]['imagePath'])) {
            $thumb = $v['imageList'][0]['imagePath'];
            $ext = substr(strrchr($thumb, '.'), 1);
            $filename = pathinfo($thumb)['filename'].'.'.$ext;
            $path = httpcopy($thumb, APP_PATH.'../tasks/tmp/'.$filename, 120);
            if($path) {
                return Oss::uniqueUpload($ext, $path, $v['channel_id'].'/videos_thumb');
            }
        }
        return '';
    }

    /**
     * @param $v
     * @return int
     */
    protected function createVideo($v) {
        $model = new Videos();
        return $model->saveGetId([
            'keywords' => isset($v['tag']) ? $v['tag'] :'',
            'channel_id' => $v['channel_id'],
            'collection_id' => 0,
            'supply_id' => $v['supply_id'],
            'duration' => $v['vodAddress']['duration'],
            'created_at' => strtotime($v['createTime']),
            'updated_at' => strtotime($v['publishTime']),
            'partition_by' => date('Y', time()),
        ]);
    }

    static $user2Channel = [
        1 => 'video/zjxcwz/vod/',  //浙江新昌网
        6 => 'video/xhw/vod/',  //湘湖网
        12 => 'video/szstw/vod/', //嵊州视听网
        13 => 'video/zjstw/vod/', //诸暨视听网
        14 => 'video/systw/vod/', //上虞视听网
        15 => 'video/txstw/vod/', //桐乡视听网
        16 => 'video/qzxxg/vod/', //衢州信息港
        17 => 'video/yystw/vod/', //余姚视听网
    ];

    protected function cpVideoFile($video_id, $v) {
        if(!empty($v['vodAddress'])) {
            foreach($v['vodAddress']['clips'][0]['urls'] as $url) {
                $model = new VideoFiles();
                $arr[] = array();
                $arr[0] = stripos($url,'_');
                $arr[1] = stripos($url,'_',$arr[0]+1);
                $arr[2] = stripos($url,'_',$arr[1]+1);
                $re_url = SobeyNewTask::$user2Channel[$v['channel_id']]?:'';
                $model->save([
                    'video_id' => $video_id,
                    'path' => $re_url.$url,
                    'rate' => str_ireplace("k", "", substr($url,$arr[1]+1,$arr[2]-$arr[1]-1)),
                    'format' => substr($url,$arr[2]+1,strrpos($url,'.')-$arr[2]-1),
//                    'width' => $file['width'],
//                    'height' => substr($url,$arr[0]+1,$arr[1]-$arr[0]-1),
                    'partition_by' => date("Y", time())
                ]);
            }
        }
        return true;
    }

    protected function createPrivateCategory($arr) {
        $model = new PrivateCategory();
        return $model->saveGetId([
            'channel_id' => $arr['channel_id'],
            'name' => $arr['name'],
            'media_type'=> PrivateCategory::MEDIA_TYPE_VIDEO,
            'father_id' => $arr['father_id'],
        ]);
    }

    /**
     * 多栏目发布
     * @param $new_id
     * @param $category_id
     * @param $data
     * @return bool
     */
    protected function publish($new_id, $category_id, $data) {
        if(!empty($category_id)) {
            PrivateCategoryData::query()->andCondition('data_id', $new_id)->execute()->delete();
            $model = new PrivateCategoryData();
            $model->save([
                'data_id' => $new_id,
                'category_id' => $category_id,
                'partition_by' => date('Y', time())
            ]);
        }
        return true;
    }

    /**
     * @param $data
     * @return bool
     */
    protected function createCat($data) {
        return true;
    }

    protected function parseStatus($status) {
        return $status == 7? 0: 1;
    }

    /**
     * 初始化app数据导入
     */
    public function appAction() {
        // key pc
        $config = [
            6 => 78,
            1 => 79,
            34 => 81,
            13 => 82,
            14 => 83,
            15 => 84,
            16 => 85,
            38 => 86,
            39 => 87,
            35 => 89,
            40 => 90,
            5 => 93,
            8 => 94,
            9 => 95,
            10 => 96,
            11 => 97
        ];
        foreach($config as $k => $v) {
            $data = CategoryData::query()->andCondition('category_id',$k)->execute();
            if(!empty($data)){
                foreach($data as $c){
                    $model = new CategoryData();
                    $has = CategoryData::query()->andCondition('category_id',$v)->andCondition('data_id',$c->data_id)->first();
                    if(!$has) {
                        $model->save([
                            'data_id' => $c->data_id,
                            'category_id' => $v,
                            'sort' => $c->sort,
                            'weight' => $c->weight,
                            'partition_by' => $c->partition_by,
                        ]);
                        echo $c->data_id, PHP_EOL;
                    }
                }
            }
        }
    }

}
