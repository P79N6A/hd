<?php

use \GenialCloud\Helper\Tree;
/**
 * @RoutePrefix("/xianghu")
 */
class XianghuController extends SobeyBaseController {


    protected $db2;
    protected $channel_id;
    protected $domain = 'xianghunet.com';

    protected $openOption = false;


    protected function option($option) {
        if($this->openOption) {
            DB::$option();
        }
    }


    public function dzzzAction(){

    }

    protected function DataToDataAction() {
        DB::begin();
        try {
            // web数据向app转移
            $config = [
                1 => 2,
                2 => 3
            ];
            foreach ($config as $k => $v) {
                $data = CategoryData::query()->andCondition('category_id', $k)->execute()->toArray();
                if (!empty($data)) {
                    foreach ($data as $d) {
                        $model = new CategoryData();
                        $s = $d;
                        unset($s['id']);
                        $s['category_id'] = $v;
                        $model->saveGetId($s);
                    }
                }
            }
            DB::commit();
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            var_dump($e);
        }
    }

    protected function urlalbumAction() {
        DB::begin();
        try {
            $data = AlbumImage::query()->execute()->toArray();
            if (!empty($data)) {
                foreach ($data as $v) {
                    $model = new AlbumImage();
                    $image = $model->findFirst($v['id']);
                    $path = str_replace('http://i02.cztv.com', '/' . $this->channel_id, $image['path']);
                    $path = str_replace('http://img01.cztv.com', '/' . $this->channel_id, $path);
                    $image->saveGetId([
                        'path' => $path,
                    ]);
                }
            }
            DB::commit();
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            var_dump($e);
        }
    }

    protected function urldataAction() {
        DB::begin();
        try {
            $data = Data::query()->execute()->toArray();
            if (!empty($data)) {
                foreach ($data as $v) {
                    $model = new Data();
                    $image = $model->findFirst($v['id']);
                    $path = str_replace('http://i02.cztv.com', '/' . $this->channel_id, $image['thumb']);
                    $path = str_replace('http://img01.cztv.com', '/' . $this->channel_id, $path);
                    $image->saveGetId([
                        'thumb' => $path,
                    ]);
                }
            }
            DB::commit();
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            var_dump($e);
        }
    }

    protected function urlnewsAction() {
        DB::begin();
        try {
            $data = News::query()->execute()->toArray();
            if (!empty($data)) {
                foreach ($data as $v) {
                    $model = new Data();
                    $image = $model->findFirst($v['id']);
                    $path = str_replace('http://i02.cztv.com', '/' . $this->channel_id, $image['content']);
                    $path = str_replace('http://img01.cztv.com', '/' . $this->channel_id, $path);
                    $image->saveGetId([
                        'content' => $path,
                    ]);
                }
            }
            DB::commit();
        } catch (DatabaseTransactionException $e) {
            DB::rollback();
            var_dump($e);
        }
    }

    public function catAction() {
        $tree = $this->getTopicTree();
        DB::begin();
        try {
            // 开始处理顶级分类
            $top = $tree->getChild(0);
            foreach($top as $t) {
                $father = $tree->getValue($t);
                if(in_array($father, [
                    '看萧山', '看周边', '看中国', '看世界', '萧然文娱', '萧然健康', '网络教育台', '萧山理财帮', '视界', '广播', '专题', '湘湖视听','萧山原创影视','部门综合','拍客','村集合'
                ])) {
                    $father_id = $this->createCat(0, $tree->getValue($t), $t);
                    $child = $tree->getChild($t);
                    if(!empty($child) && $father_id) {
                        foreach($child as $c) {
                            $name = $tree->getValue($c);
                            if($father == "看萧山") {
                                if(strpos('综合/部门/镇街/民生服务', $name) !== false) {
                                    $father_cc = $this->createCat($father_id, $name, $c);
                                    if($name == '部门') {
                                        $cc = $tree->getChild($c);
                                        if(!empty($cc) && $father_cc) {
                                            foreach($cc as $ccc) {
                                                $father_ccc = $this->createCat($father_cc, $tree->getValue($ccc), $ccc);
                                                $cccc = $tree->getChild($ccc);
                                                if(!empty($cccc) && $father_ccc) {
                                                    foreach($cccc as $end) {
                                                        $this->createCat($father_ccc, $tree->getValue($end), $end);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                    if($name == '镇街') {
                                        $cc = $tree->getChild($c);
                                        if(!empty($cc) && $father_cc) {
                                            foreach($cc as $ccc) {
                                                $cccc = $tree->getChild($ccc);
                                                if(!empty($cccc)) {
                                                    foreach($cccc as $end) {
                                                        if(in_array($tree->getValue($end),['部门介绍','头图','其他'])){
                                                            continue;
                                                        }
                                                        $endValue = str_replace($tree->getValue($ccc),'',$tree->getValue($end));
                                                        $this->createCat($father_cc,$endValue, $end);
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                                // 处理最多四级，级别再深，考虑抛弃或转义
                            } elseif($father == "村集合") {
                                $name = $tree->getValue($c);
                                $cc = $tree->getChild($c);
                                foreach($cc as $end) {
                                    if(in_array($tree->getValue($end),['村名','村地图','其他','村介绍','村大图','滚动介绍四条','电话和邮箱','联系地址','通栏广告'])){
                                        //$this->parseCunInfo($end,$tree);
                                        continue;
                                    }
                                    $this->createCat($father_id, $tree->getValue($end), $end);
                                }
                            } else {
                                $father_cc = $this->createCat($father_id, $name, $c);
                                $cc = $tree->getChild($c);
                                if(!empty($cc) && $father_cc) {
                                    foreach($cc as $ccc) {
                                        $father_ccc = $this->createCat($father_cc, $tree->getValue($ccc), $ccc);
                                        $cccc = $tree->getChild($ccc);
                                        if(!empty($cccc) && $father_ccc) {
                                            foreach($cccc as $end) {
                                                $this->createCat($father_ccc, $tree->getValue($end), $end);
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
            DB::commit();
        } catch(DatabaseTransactionException $e) {
            DB::rollback();
            if($e->getCode() === 0) {
                $_m = $e->getMessage();
                $msgs = $$_m->getMessages();
                foreach($msgs as $msg) {
                    $messages[] = $msg->getMessage();
                }
            } else {
                $messages[] = $e->getMessage();
            }
            var_dump($messages);
        }
    }

    public function cunAreaAction() {
        try {
            DB::begin();
            $father = Regions::query()->andCondition('name', '萧山区')->execute()->toArray()[0];
            $column = $this->db2->fetchAll('SELECT * FROM `cms_topic_column` where topic_id=27 and status=1 and type=1');
            $tree = new Tree();
            if(!empty($column)) {
                foreach($column as $v) {
                    $tree->setNode($v['id'], $v['father'], $v['title']);
                }
            }
            $cun = $tree->getChild('1345');
            if($father && !empty($cun)) {
                foreach($cun as $v) {
                    $value = $tree->getValue($v);
                    $longitude = 0.00000000;
                    $latitude = 0.00000000;
                    $town = $tree->getChild($v);
                    foreach($town as $t) {
                        if($tree->getValue($t) == '村地图') {
                            list($longitude, $latitude) = $this->getPosition($t);
                            $longitude = (float) trim($longitude);
                            $latitude = (float) trim($latitude);
                            break;
                        }
                    }
                    list($z, $c) = $this->getAreaSplit($value);
                    $father_id = $this->createArea($father['id'], $z, 'town', $longitude, $latitude);
                    $op = $this->createArea($father_id, $c, 'village', $longitude, $latitude);
                }
            }
            DB::commit();
        } catch(Exception $e) {
            DB::rollback();
            dd($e);
        }
    }

    private function getAreaSplit($value) {
        $child = [];
        foreach(['镇镇', '镇', '街道', '农场'] as $c) {
            $child = explode($c, $value);
            if(count($child) == 2) {
                if($c == "镇镇") {
                    $child[0] = $child[0].'镇';
                    $child[1] = "镇".$child[1];
                } else {
                    $child[0] = $child[0].$c;
                }
                return $child;
            }
        }
        return $child;
    }

    private function createArea($father_id, $name, $level, $longitude, $latitude) {
        $model = new Regions();
        $data = $model->query()->andCondition('father_id', $father_id)->andCondition('name', $name)->first();
        if($data) {
            return $data->id;
        } else {
            return $model->saveGetId([
                'father_id' => $father_id,
                'name' => $name,
                'pinyin' => PinYin::to_str($name),
                'pinyin_short' => PinYin::to_first_str($name),
                'level' => $level,
                'longitude' => $longitude,
                'latitude' => $latitude
            ]);
        }
    }

    private function getPosition($father_id){
        $data = $this->db2->fetchOne("SELECT params2,params3 FROM `cms_news` WHERE `topic_column_id` = {$father_id}");
        return array_values($data);
    }

    protected function createCat($father_id, $name, $old_id, $terminal = 'web', $ignore = [], $old_table = '') {
        if(empty($ignore)) {
            $ignore = [
                '理财热点图', '理财智囊团', '萧山综合直播', '最新资讯', '图片', '栏目推荐', '2013专题', '政情民意直通车', '2014专题', '嘉宾访谈',
                '网络教育台（新）', '村导航', '萧山发布微博', '萧山发布微信', '萧山发布微博(pc)', '萧山发布微信(pc)', '2015专题', '"智慧萧山"新闻', '浦阳政务',
                '媒体搜索',
            ];
        }
        if(in_array($name, $ignore)) {
            return false;
        }
        $model = new Category();
        $data = $model->query()
            ->andCondition('channel_id', $this->channel_id)
            ->andCondition('father_id', $father_id)
            ->andCondition('name', $name)
            ->andCondition('terminal', $terminal)
            ->first();
        if($data) {
            $this->log("Category {$name} ------------> has");
            $new_id = $data->id;
        } else {
            $hasCode = Category::query()->andCondition('code', $name)->andCondition('channel_id', $this->channel_id)->first();
            if($hasCode) {
                $fatherName = Category::findFirst($father_id);
                if($fatherName) {
                    $fatherName = $fatherName->name;
                } else {
                    $fatherName = '0';
                }
                $code = $fatherName."/".$name;
            } else {
                $code = $name;
            }
            $new_id = $model->saveGetId([
                'channel_id' => $this->channel_id,
                'father_id' => $father_id,
                'name' => $name,
                'code' => $code,
                'terminal' => $terminal,
            ]);
        }
        $old_table = $old_table?: 'cms_topic_column';
        $this->createSync('category', $old_table, 'category', $old_id, $new_id);
        return $new_id;
    }


    public function catalbumAction() {
        $column = $this->db2->fetchAll('SELECT * FROM `cms_topic_column` where topic_id=27 and status=1 and type=2');
        $ignore = [
            'none'
        ];
        // 创建栏目
        $tree = new Tree();
        if(!empty($column)) {
            foreach($column as $v) {
                $tree->setNode($v['id'], $v['father'], $v['title']);
            }
        }
        DB::begin();
        try {
            // 创建视频分类
            $videoId = $this->createCat(0, '相册', 0, 'web', $ignore);
            // 开始处理顶级分类
            $top = $tree->getChild(0);
            foreach($top as $t) {
                $father = $tree->getValue($t);
                // 1级
                $fatherId = $this->createCat($videoId, $father, $t, 'web', $ignore);
                $child = $tree->getChild($t);
                if(!empty($child) && $fatherId) {
                    foreach($child as $c) {
                        $father_cc = $this->createCat($fatherId, $tree->getValue($c), $c);
                        $cc = $tree->getChild($c);
                        if(!empty($cc) && $father_cc) {
                            foreach($cc as $ccc) {
                                $father_ccc = $this->createCat($father_cc, $tree->getValue($ccc), $ccc);
                                $cccc = $tree->getChild($ccc);
                                if(!empty($cccc) && $father_ccc) {
                                    foreach($cccc as $end) {
                                        $this->createCat($father_ccc, $tree->getValue($end), $end);
                                    }
                                }
                            }
                        }
                    }
                }
            }
            DB::commit();
        } catch(DatabaseTransactionException $e) {
            DB::rollback();
            if($e->getCode() === 0) {
                $_m = $e->getMessage();
                $msgs = $$_m->getMessages();
                foreach($msgs as $msg) {
                    $messages[] = $msg->getMessage();
                }
            } else {
                $messages[] = $e->getMessage();
            }
            dd($messages);
        }
    }

    public function catvideoAction() {
        $column = $this->db2->fetchAll('SELECT * FROM `cms_video_column` where status=1 order by id asc');
        $ignore = [
            '网友视频', '录制专区', '网友爆料', '广告专区', '理财'
        ];
        // 创建栏目
        $tree = new Tree();
        if(!empty($column)) {
            foreach($column as $v) {
                $tree->setNode($v['id'], $v['father'], $v['title']);
            }
        }
        DB::begin();
        try {
            // 创建视频分类
            $videoId = $this->createCat(0, '视频', 0, 'web', $ignore, 'cms_video_column');
            // 开始处理顶级分类
            $top = $tree->getChild(0);
            foreach($top as $t) {
                // 1级
                $fatherId = $this->createCat($videoId, $tree->getValue($t), $t, 'web', $ignore, 'cms_video_column');
                $child = $tree->getChild($t);
                if(!empty($child) && $fatherId) {
                    foreach($child as $c) {
                        $father_cc = $this->createCat($fatherId, $tree->getValue($c), $c, 'web', $ignore, 'cms_video_column');
                        $cc = $tree->getChild($c);
                        if(!empty($cc) && $father_cc) {
                            foreach($cc as $ccc) {
                                $father_ccc = $this->createCat($father_cc, $tree->getValue($ccc), $ccc, 'web', $ignore, 'cms_video_column');
                                $cccc = $tree->getChild($ccc);
                                if(!empty($cccc) && $father_ccc) {
                                    foreach($cccc as $end) {
                                        $this->createCat($father_ccc, $tree->getValue($end), $end, 'web', $ignore, 'cms_video_column');;
                                    }
                                }
                            }
                        }
                    }
                }
            }
            DB::commit();
        } catch(DatabaseTransactionException $e) {
            DB::rollback();
            if($e->getCode() === 0) {
                $_m = $e->getMessage();
                $msgs = $$_m->getMessages();
                foreach($msgs as $msg) {
                    $messages[] = $msg->getMessage();
                }
            } else {
                $messages[] = $e->getMessage();
            }
            dd($messages);
        }
    }


    protected function createSync($type, $old_table, $new_table, $old_id, $new_id, $old_ur = "", $new_url = "") {
        $model = new Sync();
        $hasSync = $model->query()->andCondition('channel_id', $this->channel_id)->andCondition('old_table', $old_table)->andCondition('old_id', $old_id)->first();
        if(!$hasSync && $old_id) {
            if($id = $model->saveGetId([
                    'channel_id' => $this->channel_id,
                    'old_table' => $old_table,
                    'new_table' => $new_table,
                    'type' => $type,
                    'old_id' => $old_id,
                    'new_id' => $new_id,
                    'old_ur' => $old_ur,
                    'new_url' => $new_url,
                    'domain' => $this->domain,
                    'status' => 1,
                ]
            )
            ) {
                return $id;
            }
        } else {
            $this->log('[sync] '.$old_id.' ----------> has');
            return !$old_id? 0: $hasSync->id;
        }
    }

    protected function log($msg) {
        echo $msg, PHP_EOL;
    }


    /**
     * @Post('/video')
     */
    public function getVideoPostAction() {

        ini_set("memory_limit", "-1");

        $this->db2 = $this->getDI()->getShared('db2');
        $this->channel_id = 6;
        //$video = $this->db2->fetchAll('SELECT v.* FROM `cms_video` v left join cms_video_image i on v.video_image_id = i.id where v.status=1 and v.video_column_id>0  order by v.id desc');
        $v = Request::getPost();
        $v['updated'] =strtotime($v['play_time']);
        $sync = $this->sync('category', 'cms_video_column');
        $category = $this->listCategory();
        $hasNoColumn = 0;
        //foreach($video as $v) {
            // 检查是否已经cp
            $hasSync = $this->checkSync('cms_video', $v['id'], 'data');
            if($hasSync) {

            $xyz = Sync::query()
                ->andCondition('channel_id', 6) 
                ->andCondition('old_table', 'cms_video')
                ->andCondition('old_id', $v['id'])
                ->first();
            $new_id = $xyz->new_id;
            $parameters = array();
            $parameters['conditions'] = "id=" . $new_id;
            $datax =  Data::findFirst($parameters);
            $datax->title = $v['title'];
            $datax->intro = $v['intro'];
            $datax->thumb = $this->parseImage($v, 'video');
            $datax->updated_at = $v['updated'];
            $datax->save();            
                echo "video exist\n";
                exit;
            }
            try {
                $this->option('begin');
                // 检查映射分类
                $oldCategoryId = $v['video_column_id'];
                if(!isset($sync[$oldCategoryId])) {
                    $hasNoColumn++;
                    $this->log(''.$v['id'].' ----------- > do not has column'.$oldCategoryId);
                    $this->option('rollback');
                    exit;
                }
                $newCategoryId = $sync[$oldCategoryId];
                if(!isset($category[$newCategoryId])) {
                    $this->log($oldCategoryId.' --------> do not has category');
                    $this->option('rollback');
                    exit;
                }
                $this->cpVideo($v);
                $this->option('commit');
            } catch(DatabaseTransactionException $e) {
                $this->option('rollback');
                if($e->getCode() === 0) {
                    $_m = $e->getMessage();
                    $msgs = $$_m->getMessages();
                    foreach($msgs as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $messages[] = $e->getMessage();
                }
                var_dump($messages);
            } catch(Exception $e) {
                $this->option('rollback');
                var_dump($e->getMessage());
            }
        //}
        //$this->log($hasNoColumn + " video do not has column");
    }

    public function videoAction() {
        $video = $this->db2->fetchAll('SELECT v.* FROM `cms_video` v left join cms_video_image i on v.video_image_id = i.id where v.status=1 and v.video_column_id>0  order by v.id desc');
        $sync = $this->sync('category', 'cms_video_column');
        $category = $this->listCategory();
        $hasNoColumn = 0;
        foreach($video as $v) {
            // 检查是否已经cp
            $hasSync = $this->checkSync('cms_video', $v['id'], 'data');
            if($hasSync) {
                continue;
            }
            try {
                $this->option('begin');
                // 检查映射分类
                $oldCategoryId = $v['video_column_id'];
                if(!isset($sync[$oldCategoryId])) {
                    $hasNoColumn++;
                    $this->log(''.$v['id'].' ----------- > do not has column'.$oldCategoryId);
                    $this->option('rollback');
                    continue;
                }
                $newCategoryId = $sync[$oldCategoryId];
                if(!isset($category[$newCategoryId])) {
                    $this->log($oldCategoryId.' --------> do not has category');
                    $this->option('rollback');
                    continue;
                }
                $this->cpVideo($v);
                $this->option('commit');
            } catch(DatabaseTransactionException $e) {
                $this->option('rollback');
                if($e->getCode() === 0) {
                    $_m = $e->getMessage();
                    $msgs = $$_m->getMessages();
                    foreach($msgs as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $messages[] = $e->getMessage();
                }
                var_dump($messages);
            } catch(Exception $e) {
                $this->option('rollback');
                var_dump($e->getMessage());
            }
        }
        $this->log($hasNoColumn + " video do not has column");
    }


    protected function cpVideo($data) {
        $old_id = $data['id'];
        $old_table = 'cms_video';
        $new_table = 'data';
        $new_id = $this->createData($data, 'video');
        $cat = $this->catOld2New('cms_video_column');
        if($new_id && $this->cpVideoFile($new_id, $data) && $this->publish($new_id, $cat[$data['video_column_id']], $data)) {
            $this->createSync('video', $old_table, $new_table, $old_id, $new_id);
        } else {
            $this->log('[error] ---> video '.$old_id);
        }
    }

    protected function updateSync($id, $new_id) {
        $model = new Sync();
        return $model->findFirst($id)->save([
            'new_id' => $new_id,
            'status' => 1
        ]);
    }

    protected function catOld2New($old_table = 'cms_topic_column') {
        $list = Sync::query()->andCondition('old_table', $old_table)->andCondition('type', 'category')->andCondition('channel_id', $this->channel_id)->execute()->toArray();
        $return = [];
        if(!empty($list)) {
            foreach($list as $v) {
                $return[$v['old_id']] = $v['new_id'];
            }
        }
        return $return;
    }

    protected function publish($new_id, $category_id, $data) {

        $catebind = CategoryBind::getbindByCategoryid($category_id);
        foreach($catebind as $v) {
            $model = new CategoryData();
            $model->save([
                'data_id' => $new_id,
                'category_id' => $v,
                'partition_by' => date('Y', $data['created'])
            ]);

        }
        $model = new CategoryData();
        return $model->save([
            'data_id' => $new_id,
            'category_id' => $category_id,
            'partition_by' => date('Y', $data['created'])
        ]);
    }

    protected function cpVideoFile($new_id, $v) {
        //$data = $this->db2->fetchAll("SELECT * FROM `cms_video_file` WHERE `video_id` = {$v['id']}");


        $parameters = array();
        $parameters['conditions'] = "id=" . $new_id;
        $datax =  Data::findFirst($parameters);
        $video_id = $datax->source_id;

        $data = $v['videofiles'];
        if(!empty($data)) {
            foreach($data as $file) {
                $model = new VideoFiles();
                $model->save([
                    'video_id' => $video_id,
                    'path' => $file['path'],
                    'rate' => $file['rate'],
                    'format' => $file['format'],
                    'width' => $file['width'],
                    'height' => $file['height'],
                    'partition_by' => date("Y", $v['created'])
                ]);
            }
        }
        return true;
    }

    public function  getOyunthumb($thumb) {
        return $thumb;
    }



    /**
     * @Post('/')
     */
    public function getPostAction() {
        ini_set("memory_limit", "-1");

        $this->db2 = $this->getDI()->getShared('db2');
        $this->channel_id = 6;

        $v = Request::getPost();
        $tree = $this->getTopicTree();
        $areaCat = $tree->getChilds(1345);
        $town = $tree->getChilds(293);
        $ids = implode(",",$town);

        $sync = $this->sync('category', 'cms_topic_column');

        if(!isset($v['updated'])||(isset($v['updated'])&&!$v['updated'])) $v['updated'] = $v['created'];
        $category = $this->listCategory();
        var_dump($v);


        if($v['news_id']) $v['id'] = $v['news_id'];


        // 检查是否已经cp
        $hasSync = $this->checkSync('cms_news', $v['id'], 'data');
        if($hasSync) {
            echo $v['thumb'];
            $xyz = Sync::query()
                ->andCondition('old_table', 'cms_news')
                ->andCondition('old_id', $v['id'])
                ->first();
            $new_id = $xyz->new_id;
            if($new_id&&$v['actionName']=='edt') {
            $parameters = array();
            $parameters['conditions'] = "id=" . $new_id;
            $datax =  Data::findFirst($parameters);
            $datax->title = $v['title'];
            $datax->intro = $v['intro'];
            $datax->thumb = $this->getOyunthumb($v['thumb']);
            $datax->updated_at = $v['updated'];
            // 解析来源
                $datax->referer_id = $this->parseReferer($v);
                $data_data = $this->parseDataData($v);
                $datax->data_data = json_encode(array_values($data_data));

            $v['content'] = $this->insertDataData($v, $data_data);

            $datax->save();

            $cat = $this->catOld2New();
            $publishx = CategoryData::query()->andCondition('data_id', $datax->id)->andCondition('category_id', $cat[$v['topic_column_id']])->execute();



            if(!$publishx->id){
                $this->publish($datax->id, $cat[$v['topic_column_id']], $v);
				
                $this->postToXiaoshan($datax->id , array($cat[$v['topic_column_id']]));
            }

            $parameters = array();
            $parameters['conditions'] = "id=" . $datax->source_id;
            $newsx =  News::findFirst($parameters);
            $newsx->content = $v['content'];
            $newsx->keywords = $v['keywords'];
            $newsx->save();
            }
            else if($new_id) {
                $parameters = array();
                $parameters['conditions'] = "id=" . $new_id;
                $datax =  Data::findFirst($parameters);
                $datax->status =  ($v['status']==1)?1:9;
                if($v['actionName']=='unpublish') {
                    $datax->status = 9;
                }
                else if($v['actionName']=='publish') {
                    $datax->status = 1;
                }

                $datax->save();
                $cat = $this->catOld2New();
                $publishx = CategoryData::query()->andCondition('data_id', $datax->id)->andCondition('category_id', $cat[$v['topic_column_id']])->execute();



                if(!$publishx->id){
                    $this->publish($datax->id, $cat[$v['topic_column_id']], $v);
                $this->postToXiaoshan($datax->id , array($cat[$v['topic_column_id']]));
                }
            }
            echo "update data";
            exit;
        }
        try {
            $area = false;
            $oldCategoryId = $v['topic_column_id'];
            // 处理村集合
            if(in_array($oldCategoryId,$areaCat)){
                $cunId = $tree->getNodeLever($oldCategoryId)[0];
                list($z, $cunName) = $this->getAreaSplit($tree->getValue($cunId));
                // TODO check
                $cun = Regions::query()->andCondition('name',$cunName)->first();
                if($cun){
                    $area = $cun->toArray();
                }else{
                    $this->log($v['id']."--------------> had area but not cun");
                }
            }
            //处理镇街
            if(in_array($oldCategoryId,$town)){
                $cunId = $tree->getNodeLever($oldCategoryId)[0];
                $cunName = $tree->getValue($cunId);
                $cun = Regions::query()->andWhere("name like '{$cunName}%' and level='town'")->first();

                if($cun){
                    $area = $cun->toArray();
                }else{
                    $this->log($v['id']."--------------> had area but not cun");
                }
            }
            $this->option('begin');
            // 检查映射分类
            if(!isset($sync[$oldCategoryId])) {
                $hasNoColumn++;
                $this->log(''.$v['id'].' ----------- > do not has column');
                $this->option('rollback');
                exit;
            }
            $newCategoryId = $sync[$oldCategoryId];
            if(!isset($category[$newCategoryId])) {
                $this->log($oldCategoryId.' --------> do not has category');
                $this->option('rollback');
                exit;
            }
            $this->cpNews($v,$area);
            $this->option('commit');
        } catch(DatabaseTransactionException $e) {
            $this->option('rollback');
            $messages = [];
            if($e->getCode() === 0) {
                $_m = $e->getMessage();
                $msgs = $$_m->getMessages();
                foreach($msgs as $msg) {
                    $messages[] = $msg->getMessage();
                }
            } else {
                $messages[] = $e->getMessage();
            }
            var_dump($messages);
        } catch(Exception $e) {
            $this->option('rollback');
            var_dump($e->getMessage());
        }

    }
	

    private $sign="f4fna96cdnf27i8W9Jd7bV6T1sadf9z5Zcasdy0W6ob88asdf126OOo659HUhoji";
    private $city_id="330109";

    private function postToXiaoshan($data_id , $media_publish) {
        global $config;

        $obj = $config->zhihuixiaoshanmap;//对应关系写在配置文件中
        /*
         * 有三点需要判断
         * 3.此新闻接收方是新建还是更新--查询SupplyoutRsync表
         * 1.推送的栏目是不是目标栏目--目前使用数组对应，换环境后可能需要修改对应数组
         * 2.查询此新闻的所有信息--Data::getMediaByData
         * 4.针对不同的data类型还要对不同地址进行推送
         */
        $category_arr = array();
        foreach($media_publish as $key => $category){
            if(isset($obj->$category)){
                $category_arr[] = $obj->$category;
            }
        }
        foreach($category_arr as $key => $category){//针对目标栏目推送
            $channel_id = Session::get('user')->channel_id;
            $supply_data = SupplyoutRsync::findOneByDataId(100,$data_id,$channel_id,$category);
            $origin_id = 0;
            if($supply_data){
                $origin_id = $supply_data->origin_id;
            }
            $arr = Data::getMediaByData($data_id);

            $sign = md5($this->city_id.$this->sign.time());//签名
            $referer = Referer::getById($channel_id, $arr[0]->referer_id);//来源

            if($arr[0]->type == 'news') {
                $input_post = array();
                $input_post['cityid'] = $this->city_id;
                $input_post['timestamp'] = time();
                $input_post['sign'] = $sign;
                $input_post['id'] = $origin_id;
                $input_post['title'] = $arr[0]->title;
                $input_post['category_id'] = $category;
                $input_post['pics'] = (false===stripos($arr[0]->thumb, "image.xianghunet.com"))?cdn_url('image',$arr[0]->thumb):$arr[0]->thumb;
                $input_post['source'] = $referer->name ?: "";
                $input_post['author'] = $arr[0]->author_name;
                $input_post['digest'] = $arr[0]->intro;
                $input_post['can_reply'] = $arr[1]->comment_type == 1 ? 0 : 1;
                $input_post['pass'] = $arr[0]->status;
                $input_post['release_time'] = $arr[0]->created_at;
                $input_post['isshow'] = 1;
                $input_post['content'] = $arr[1]->content;
                $input_post['operator'] = $arr[0]->author_name;

                $return_message = F::curlProxy("http://citynews.2500city.com/zxapi/news/regular", 'post', $input_post);
                $return_message = json_decode($return_message, true);
            }
            if($arr[0]->type == 'album') {
                $image_arr = AlbumImage::findByAlbumId($arr[1]->id);
                $altas = array();
                $des = array();
                foreach($image_arr as $num => $value){
                    $altas[] = $value['path'];
                    $des[] = $value['intro'];
                }
                $input_post = array();
                $input_post['cityid'] = $this->city_id;
                $input_post['timestamp'] = time();
                $input_post['sign'] = $sign;
                $input_post['id'] = $origin_id;
                $input_post['title'] = $arr[0]->title;
                $input_post['category_id'] = $category;
                $input_post['pics'] = array(
                    0=>(false===stripos($arr[0]->thumb, "image.xianghunet.com"))?cdn_url('image',$arr[0]->thumb):$arr[0]->thumb
                );
                $input_post['source'] = $referer->name ?: "";
                $input_post['author'] = $arr[0]->author_name;
                $input_post['can_reply'] = $arr[1]->comment_type == 1 ? 0 : 1;
                $input_post['pass'] = $arr[0]->status;
                $input_post['release_time'] = $arr[0]->created_at;
                $input_post['isshow'] = 1;
                $input_post['altas'] = $altas;
                $input_post['des'] = $des;
                $input_post['digest'] = $arr[0]->intro;
                $input_post['operator'] = $arr[0]->author_name;

                $return_message = F::curlProxy("http://citynews.2500city.com/zxapi/news/atlas", 'post', $input_post);
                $return_message = json_decode($return_message, true);
            }
            if($arr[0]->type == 'video') {
                $video_file = VideoFiles::findByVideoId($arr[1]->id);
                $content = "";
                if($video_file) {
                    $content = $video_file->path;
                    $content = (false===stripos($content, "video.xianghunet.com"))?"http://video.xianghunet.com/video/".$content:$content;
                }
                $minute = floor($arr[1]->duration/60)<10?'0'.floor($arr[1]->duration/60):(floor($arr[1]->duration/60));
                $second = $arr[1]->duration%60<10?'0'.$arr[1]->duration%60:$arr[1]->duration%60;
                $video_time = $minute.":".$second;

                $input_post = array();
                $input_post['cityid'] = $this->city_id;
                $input_post['timestamp'] = time();
                $input_post['sign'] = $sign;
                $input_post['id'] = $origin_id;
                $input_post['title'] = $arr[0]->title;
                $input_post['category_id'] = $category;
                $input_post['pics'] = (false===stripos($arr[0]->thumb, "image.xianghunet.com"))?cdn_url('image',$arr[0]->thumb):$arr[0]->thumb;
                $input_post['source'] = $referer->name ?: "";
                $input_post['author'] = $arr[0]->author_name;
                $input_post['digest'] = $arr[0]->intro;
                $input_post['can_reply'] = $arr[1]->comment_type == 1 ? 0 : 1;
                $input_post['pass'] = $arr[0]->status;
                $input_post['release_time'] = $arr[0]->created_at;
                $input_post['isshow'] = 1;
                $input_post['content'] = $content;
                $input_post['video_time'] = $video_time;
                $input_post['tag'] = $arr[1]->keywords;
                $input_post['operator'] = $arr[0]->author_name;

                $return_message = F::curlProxy("http://citynews.2500city.com/zxapi/news/video", 'post', $input_post);
                $return_message = json_decode($return_message, true);
            }

            if($origin_id==0 && isset($return_message['data']['id'])){//有返回时增加对应关系
                SupplyoutRsync::createByDataId(array(
                    'channel_id' => $channel_id,
                    'origin_type' => 100,
                    'origin_id' =>$return_message['data']['id'],
                    'data_id' => $data_id,
                    'category_id' => $category
                ));
            }
        }



    }


    public function newsAction() {
        $tree = $this->getTopicTree();
        $areaCat = $tree->getChilds(1345);
        $town = $tree->getChilds(293);
        $ids = implode(",",$town);
        $video = $this->db2->fetchAll("SELECT n.*,i.path FROM `cms_news` n left join cms_news_image i on n.id=i.news_id where n.topic_column_id >0 and n.status=1");
        $sync = $this->sync('category', 'cms_topic_column');
        $category = $this->listCategory();
        // tree
        $hasNoColumn = 0;
        foreach($video as $v) {
            // 检查是否已经cp
            $hasSync = $this->checkSync('cms_news', $v['id'], 'data');
            if($hasSync) {
			    echo "news exist!";
                exit;
            }
            try {
                $area = false;
                $oldCategoryId = $v['topic_column_id'];
                // 处理村集合
                if(in_array($oldCategoryId,$areaCat)){
                    $cunId = $tree->getNodeLever($oldCategoryId)[0];
                    list($z, $cunName) = $this->getAreaSplit($tree->getValue($cunId));
                    // TODO check
                    $cun = Regions::query()->andCondition('name',$cunName)->first();
                    if($cun){
                        $area = $cun->toArray();
                    }else{
                        $this->log($v['id']."--------------> had area but not cun");
                    }
                }
                //处理镇街
                if(in_array($oldCategoryId,$town)){
                    $cunId = $tree->getNodeLever($oldCategoryId)[0];
                    $cunName = $tree->getValue($cunId);
                    $cun = Regions::query()->andWhere("name like '{$cunName}%' and level='town'")->first();

                    if($cun){
                        $area = $cun->toArray();
                    }else{
                        $this->log($v['id']."--------------> had area but not cun");
                    }
                }
                $this->option('begin');
                // 检查映射分类
                if(!isset($sync[$oldCategoryId])) {
                    $hasNoColumn++;
                    $this->log(''.$v['id'].' ----------- > do not has column');
                    $this->option('rollback');
                    continue;
                }
                $newCategoryId = $sync[$oldCategoryId];
                if(!isset($category[$newCategoryId])) {
                    $this->log($oldCategoryId.' --------> do not has category');
                    $this->option('rollback');
                    continue;
                }
                $this->cpNews($v,$area);
                $this->option('commit');
            } catch(DatabaseTransactionException $e) {
                $this->option('rollback');
                $messages = [];
                if($e->getCode() === 0) {
                    $_m = $e->getMessage();
                    $msgs = $$_m->getMessages();
                    foreach($msgs as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $messages[] = $e->getMessage();
                }
                var_dump($messages);
            } catch(Exception $e) {
                $this->option('rollback');
                var_dump($e->getMessage());
            }
        }
        $this->log($hasNoColumn." news do not has column");
    }

    public function albumAction() {
        $video = $this->db2->fetchAll('SELECT *  FROM `cms_album` WHERE `topic_column_id` > 0 and status=1');
        $sync = $this->sync('category', 'cms_topic_column');
        $category = $this->listCategory();
        $hasNoColumn = 0;
        foreach($video as $v) {
            $hasSync = $this->checkSync('cms_album', $v['id'], 'data');
            if($hasSync) {
                continue;
            }
            try {
                $this->option('begin');
                // 检查映射分类
                $oldCategoryId = $v['topic_column_id'];
                if(!isset($sync[$oldCategoryId])) {
                    $hasNoColumn++;
                    $this->log(''.$v['id'].' ----------- > do not has column');
                    $this->option('rollback');
                    continue;
                }
                $newCategoryId = $sync[$oldCategoryId];
                if(!isset($category[$newCategoryId])) {
                    $this->log($oldCategoryId.' --------> do not has category');
                    $this->option('rollback');
                    continue;
                }
                $this->cpAlbum($v);
                $this->option('commit');
            } catch(DatabaseTransactionException $e) {
                $this->option('rollback');
                $messages = [];
                if($e->getCode() === 0) {
                    $_m = $e->getMessage();
                    $msgs = $$_m->getMessages();
                    foreach($msgs as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $messages[] = $e->getMessage();
                }
                var_dump($messages);
            } catch(Exception $e) {
                $this->option('rollback');
                var_dump($e->getMessage());
            }
        }
        $this->log($hasNoColumn." album do not has column");

    }

    protected function cpAlbum($data) {
        // 检查是否已经cp
        $old_id = $data['id'];
        $old_table = 'cms_album';
        $new_table = 'data';
        $new_id = $this->createData($data, 'album');
        $cat = $this->catOld2New();
        if($new_id && $this->cpAlbumImage(Data::findFirst($new_id)->source_id, $data) && $this->publish($new_id, $cat[$data['topic_column_id']], $data)) {
            $this->createSync('album', $old_table, $new_table, $old_id, $new_id);
        } else {
            $this->log('[error] ---> album '.$old_id);
        }
    }

    protected function createAlbum($data) {
        $model = new Album();
        return $model->saveGetId([
            'channel_id' => $this->channel_id,
            'keywords' => $data['keywords'],
            'created_at' => $data['created'],
            'updated_at' => $data['updated'],
            'partition_by' => date('Y', $data['created']),
        ]);
    }

    protected function cpAlbumImage($new_id, $v) {
        $file = $this->db2->fetchAll("SELECT * FROM `cms_image` WHERE `album_id` = {$v['id']}");
        if(!empty($file)) {
            foreach($file as $i) {
                $model = new AlbumImage();
                $image_id = $model->saveGetId([
                    'album_id' => $new_id,
                    'path' => $i['path'],
                    'intro' => $i['intro'],
                    'uploaded_time' => $i['created'],
                    'sort' => $i['sort'],
                    'partition_by' => date("Y", $i['created'])
                ]);
                $dzzz = $this->db2->fetchOne("SELECT * FROM `cms_dzzz` WHERE `image_id` = {$i['id']}");
                if($dzzz){
                    $magazine = new Magazine();
                    $magazine->save([
                        'image_id'=>$image_id,
                        'hs_area'=>$dzzz['hs_area'],
                        'spotpool'=>$dzzz['spotpool'],
                    ]);
                }
            }
        }
        return true;
    }

    protected function cpNews($data,$area='') {
        // 检查是否已经cp
        $old_id = $data['id'];
        $old_table = 'cms_news';
        $new_table = 'data';
        if($data['news_id']) $old_id = $data['news_id'];
        $sync = $this->createSync('news', $old_table, $new_table, $old_id, 0);
        // 引用传值，再news类型时候，会有用途
        $new_id = $this->createData($data, 'news');
        $cat = $this->catOld2New();
        if($new_id && $this->publish($new_id, $cat[$data['topic_column_id']], $data)) {
            if($area){
                $this->publishArea($new_id,$area);
            }
            $this->updateSync($sync, $new_id);
        } else {
            $this->log('[error] ---> news '.$old_id);
        }
    }

    private function publishArea($data_id,$area){
        $model = new RegionData();
        $model->save([
            'data_id'=>$data_id,
            'country_id'=>1,
            'province_id'=>929,
            'city_id'=>930,
            'county_id'=>937,
            'town_id'=>$area['level']=='town'? $area['id']:$area['father_id'],
            'village_id'=>$area['level']=='village' ? $area['id']:0,
        ]);
    }

    protected function createData($v, $type) {
        if($v['news_id']) {
            $model = new Sync();
            $hasSync = $model->query()->andCondition('channel_id', $this->channel_id)->andCondition('old_table', $old_table)->andCondition('old_id', $old_id)->first();
            if($hasSync) {
                return $datax->new_id;
            }
        }
        $model = new Data();
        $editor = $type == 'video'? $v['editorid']: $v['user_id'];
        $user = $this->getUser($editor);
        $data['channel_id'] = $this->channel_id;
        $data['type'] = $type;
        $func = 'create'.ucfirst($type);
        $data['data_data'] = '[]';
        $data['updated_at'] = $v['created'];
        if($type == 'news') {
            $data['updated_at'] = $v['updated'];
            $data_data = $this->parseDataData($v);
            $data['data_data'] = json_encode(array_values($data_data));
            // 将关联ID插入内容，并传递到下一级参数 $v
            $v['content'] = $this->insertDataData($v, $data_data);
            // 解析来源
            $data['referer_id'] = $this->parseReferer($v);
        }
        $data['source_id'] = $this->$func($v);
        $data['title'] = $v['title'];
        $data['intro'] = $v['intro'];
        $data['thumb'] = $this->parseImage($v, $type);
        $data['created_at'] = $v['created'];
        $data['author_id'] = $user['id'];
        $data['author_name'] = $user['name'];
        $data['hits'] = $v['hits'];
        $data['status'] = 1;
        $data['partition_by'] = date("Y", $v['created']);
        return $model->saveGetId($data);
    }

    protected function parseReferer($v) {
        $name = trim($v['source']);
        $model = new Referer();
        $referer = $model->query()->andCondition('name', $name)->first();
        if($referer) {
            $id = $referer->id;
        } else {
            $id = (int)$model->saveGetId([
                'channel_id' => $this->channel_id,
                'name' => $name
            ]);
        }
        return $id;
    }

    protected function insertDataData($v, $data_data) {
        if(!empty($data_data)) {
            foreach($data_data as $k => $id) {
                if($k == 'video') {
                    $v['content'] =  '<input class="quote-item" data-id="'.$id.'" data-type="video" disabled=""/>'.$v['content'];
                }
                if($v == 'album') {
                    $v['content'] = '<input class="quote-item" data-id="'.$id.'" data-type="album" disabled=""/>'.$v['content'];
                }
            }
        }
        return $v['content'];
    }

    protected function parseDataData($v) {
        // TODO 一个新闻有关联视频和图集的情况，而且一个新闻管理多视频
        $videoId = $v['video_id'];
        $albumId = $v['album_id'];
        $data_data = [];
        if($videoId != 0) {
            $sync = $this->checkSync('cms_video', $videoId, 'videos');
            if($sync && isset($sync->new_id)) {
                $data_data['video'] = $sync->new_id;
            } else {
                $this->log('news:'.$v['id'].' can not find videos sync '.$videoId);
            }
        }
        if($albumId != 0) {
            $sync = $this->checkSync('cms_album', $albumId, 'album');
            if($sync && isset($sync->new_id)) {
                $data_data['album'] = $sync->new_id;
            } else {
                $this->log('news:'.$v['id'].' can not find album sync '.$albumId);
            }
        }
        return $data_data;
    }

    protected function createNews($data) {
        $model = new News();
        return $model->saveGetId([
            'channel_id' => $this->channel_id,
            'keywords' => $data['keywords'],
            'content' => $data['content'] != null? $data['content'] : '',
            'created_at' => $data['created'],
            'updated_at' => $data['created'],
            'partition_by' => date('Y', $data['created']),
        ]);
    }


    /**
     * @param $v
     * @param $type
     * @return string
     */
    protected function parseImage($v, $type) {
        if($type == 'video' && !empty($v['thumb'])) {
            $thumb = $v['thumb'];
            $ext = substr(strrchr($thumb, '.'), 1);
            $filename = pathinfo($thumb)['filename'].'.'.$ext;
            $path = httpcopy($thumb, APP_PATH.'../tasks/tmp/'.$filename, 120);
            if($path) {
                return Oss::uniqueUpload($ext, $path, $this->channel_id.'/videos_thumb');
            }
        }
        return '';
    }

    protected function createVideo($data) {
        $model = new Videos();
        return $model->saveGetId([
            'channel_id' => $this->channel_id,
            'collection_id' => 0,
            'duration' => $data['duration'],
            'created_at' => $data['created'],
            'updated_at' => $data['created'],
            'partition_by' => date('Y', $data['created']),
        ]);
    }


    protected function getUser($user_id) {
        if($user_id != 0) {
            $user = $this->db2->fetchOne("SELECT * FROM `cms_user` WHERE `id` = {$user_id}");
        } else {
            $user = $this->db2->fetchOne("SELECT * FROM `cms_user` WHERE `id` = 582");
        }
        if(!$user['mobile'] || strlen($user['mobile']) != 11) {
            $user['mobile'] == '10000000000';
        }
        $model = new Admin();
        $newUser = $model->query()->andCondition('mobile', $user['mobile'])->andCondition('channel_id', $this->channel_id)->first();
        if(!$newUser) {
            $id = $model->saveGetId([
                'channel_id' => $this->channel_id,
                'is_admin' => 0,
                'mobile' => $user['mobile'],
                'name' => $user['truename'],
                'password' => $user['password'],
                'salt' => 'xianghu',
                'status' => 1,
            ]);
            if($id) {
                $ext = new AdminExt();
                $ext->saveGetId([
                    'admin_id' => $id,
                    'pinyin' => '',
                ]);
                $newUser = $model->findFirst($id);
            }
        }
        return $newUser->toArray();
    }

    protected function checkSync($old_table, $old_id, $new_table) {
        $sync = new Sync();
        return $sync->query()->andCondition('old_id', $old_id)->andCondition('channel_id', $this->channel_id)->andCondition('old_table', $old_table)->first();
    }


    protected function listCategory($terminal = 'web') {
        return Category::listCategory($terminal, false, $this->channel_id);
    }

    protected function sync($type, $old_table) {
        $data = Sync::query()->andCondition('type', $type)->andCondition('channel_id', $this->channel_id)->andCondition('old_table', $old_table)->execute()->toArray();
        return array_refine($data, 'old_id', 'new_id');
    }

    protected function createChannel() {
        if($this->channel_id == null) {
            $data = Channel::query()->andCondition('name', '湘湖网-测试迁移')->first();
            if($data) {
                $this->channel_id = $data->id;
            } else {
                $model = new Channel();
                $this->channel_id = $model->saveGetId([
                    'name' => '湘湖网-测试迁移',
                    'shortname' => 'xianghunet',
                    'tag' => 'xianghunet',
                    'status' => 1,
                    'channel_logo' => 'logo.png',
                    'channel_url' => 'http://www.xianghunet.com',
                    'channel_logo_slave' => 'logo.png',
                    'channel_instr' => '湘湖网',
                    'channel_info' => '湘湖网',
                ]);
            }
            echo 'init channel_id '.$this->channel_id, PHP_EOL;
        }
    }

    public function seoAction() {
        $list = Sync::query()->andCondition('old_table', 'cms_topic_column')->andCondition('type', 'category')->andCondition('channel_id', $this->channel_id)->execute()->toArray();
        if(!empty($list)) {
            foreach($list as $v) {
                $old = $v['old_id'];
                $new = $v['new_id'];
                $oldData = $this->db2->fetchOne("SELECT * FROM `cms_topic_column` where id={$old}");
                $model = new Category();
                $model->findFirst($new)->update([
                    'logo' => 'http://www.xianghunet.com'.$oldData['logo'],
                ]);
                $seo = new CategorySeo();
                if($seo->findFirst($new)) {
                    continue;
                }
                $seo->save([
                    'category_id' => $new,
                    'title' => $oldData['pagetitle'],
                    'keywords' => $oldData['keywords'],
                    'desc' => $oldData['desc'],
                ]);
            }
        }
        return true;
    }

    public function topicAction() {
        try {
            $this->option('begin');
            // 领导人专题
            $tree = $this->getTopicTree();
            $topics = [1192, 1255, 11963];
            foreach($topics as $t) {
                $child = $tree->getChild($t);
                foreach($child as $v) {
                    $children = $tree->getChild($v);
                    $data = [
                        'title' => $tree->getValue($v),
                        'user_id' => 582,
                        'created' => time(),
                        'intro' => $tree->getValue($v),
                        'hits' => 0,
                        'content'=>'',
                    ];
                    $data_id = $this->createData($data, 'special');
                    $topicId = Data::findFirst($data_id)->source_id;
                    // 创建专题内分类
                    if($children) {
                        foreach($children as $sub) {
                            $name = $tree->getValue($sub);
                            $columnId = $this->createTopicColumn($topicId, $name);
                            $this->createTopicNews($sub, $columnId, $tree);
                        }
                    } else {
                        $columnId = $this->createTopicColumn($topicId, '新闻');
                        $this->createTopicNews($v, $columnId, $tree);
                    }
                }
            }
            $this->option('commit');
        } catch(Exception $e) {
            $this->option('rollback');
            var_dump($e->getMessage());
        }
    }

    private function createTopicNews($father_id, $column_id,$tree) {
        $c = $tree->getChilds($father_id);
        if($c){
            $ids = implode(",",$c);
        }else{
            $ids = $father_id;
        }
        $news = $this->findNewsByFatherId($ids);
        foreach($news as $n) {
            $data = $n;
            // 检查是否已经cp
            $old_id = $data['id'];
            $old_table = 'cms_news';
            $new_table = 'data';
            $sync = $this->createSync('news', $old_table, $new_table, $old_id, 0);
            $new_id = $this->createData($data, 'news');
            if($new_id && $this->publishTopic($new_id, $column_id)) {
                $this->updateSync($sync, $new_id);
            } else {
                $this->log('[error] ---> news '.$old_id);
            }
        }
    }

    private function publishTopic($new_id, $column_id) {
        $model = new SpecialCategoryData();
        return $model->saveGetId([
            'special_category_id' => $column_id,
            'data_id' => $new_id,
        ]);
    }

    private function findNewsByFatherId($fatherId){
        return $this->db2->fetchAll("SELECT * FROM `cms_news` WHERE `topic_column_id` in ({$fatherId})");
    }

    private function createSpecial($v) {
        $model = new Specials();
        return $model->saveGetId([
            'channel_id'=>$this->channel_id,
            'keywords'=>'',
            'created_at'=>time(),
            'updated_at'=>time(),
        ]);
    }

    private function createTopicColumn($topicId,$name){
        $model = new SpecialCategory();
        return $model->saveGetId([
            'channel_id'=>$this->channel_id,
            'special_id'=>$topicId,
            'name'=>$name,
            'code'=>$name,
        ]);
    }

    protected function getTopicTree() {
        $column = $this->db2->fetchAll('SELECT * FROM `cms_topic_column` where topic_id=27 and status=1 and type=1');
        $tree = new Tree();
        if(!empty($column)) {
            foreach($column as $v) {
                $tree->setNode($v['id'], $v['father'], $v['title']);
            }
        }
        return $tree;
    }

    public function stationsAction() {
        $station = $this->db2->fetchAll('SELECT * FROM `cztv_activity_channel` where ac_status=1');
        $hasNoColumn = 0;
        foreach($station as $s) {
            // 检查是否已经cp
            $hasSync = $this->checkSync('cztv_activity_channel', $s['id'], 'stations');
            if($hasSync) {
                continue;
            }
            try {
                $this->cpStation($s);
            } catch(DatabaseTransactionException $e) {
                if($e->getCode() === 0) {
                    $_m = $e->getMessage();
                    $msgs = $$_m->getMessages();
                    foreach($msgs as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $messages[] = $e->getMessage();
                }
                dd($messages);
            }
        }
        $this->log($hasNoColumn + " station do not has column");
    }
    protected function cpStation($data) {
        $old_id = $data['id'];
        $old_table = 'cztv_activity_channel';
        $new_table = 'stations';
        $sync = $this->createSync('station', $old_table, $new_table, $old_id, 0);
        $data['is_system']=1;
        $data['channel_id']=$this->channel_id;
        $data['code']=$data['ac_code']?:0;
        $data['name']=$data['ac_name'];
        $data['type']=$data['ac_type'];
        $data['logo']=$data['channel_name'];
        $data['channel_name']=$data['channel_name']?:"0";
        $data['customer_name']=$data['customer_name']?:"0";
        $data['epg_path']=$data['ac_url']?:"0";
        $stations=new Stations();
        $stations->createStations($data);
        $new_id = $stations->id;
        if($new_id) {
            $this->updateSync($sync, $new_id);
        } else {
            $this->log('[error] ---> station '.$old_id);
        }
    }
    public function stationsEpgAction() {
        $stations_epg = $this->db2->fetchAll('SELECT * FROM `cztv_activity_live_streams`');
        $hasNoColumn = 0;
        foreach($stations_epg as $s) {
            // 检查是否已经cp
            $hasSync = $this->checkSync('cztv_activity_live_streams', $s['id'], 'stations_epg');
            if($hasSync) {
                continue;
            }
            try {
                $this->cpStationsEpg($s);
            } catch(DatabaseTransactionException $e) {
                if($e->getCode() === 0) {
                    $_m = $e->getMessage();
                    $msgs = $$_m->getMessages();
                    foreach($msgs as $msg) {
                        $messages[] = $msg->getMessage();
                    }
                } else {
                    $messages[] = $e->getMessage();
                }
                dd($messages);
            }
        }
        $this->log($hasNoColumn + " stations_epg do not has column");
    }
    protected function cpStationsEpg($data) {
        $old_id = $data['id'];
        $old_table = 'cztv_activity_live_streams';
        $new_table = 'stations_epg';
        $sync = $this->createSync('stations_epg', $old_table, $new_table, $old_id, 0);
        $data['stations_id']=$data['ac_code'];
        $data['name']=$data['stream_name'];
        $data['cdn']=$data['cdn1'];
        $data['percent']=$data['percent1'];
        $data['kpbs']=$data['videodatarate'];
        $data['audiokpbs']=$data['audiodatarate'];
        $stations_epg=new StationsEpg();
        $stations_epg->createStationsEpg($data);
        $new_id = $stations_epg->id;
        if($new_id) {
            $this->updateSync($sync, $new_id);
        } else {
            $this->log('[error] ---> stations_epg '.$old_id);
        }
    }

}
