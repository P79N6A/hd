<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;
use GenialCloud\Helper\Tree;

class Category extends Model {
	const FILE_NOT_UPLOAD = 4;

    use HasChannel;
    const PAGE_SIZE = 20;
    const APP_STYLE_NEWS = 1;
    const APP_STYLE_PICTURE = 2;
    const APP_STYLE_VIDEO = 3;
    const APP_STYLE_INTRO = 4;
    const APP_STYLE_LEADER = 5;
    const APP_STYLE_HOT = 6;
    /**
     * 评论类型
     *
     * @var array
     */
    public static $commentTypes = [
        0 => '媒资自定',
        1 => '禁用评论',
        2 => '先审后发',
        3 => '先发后审',
    ];

    /**
     * APP显示风格类型
     *
     * @var array
     */
    public static $appStyle = [
        0 => 'news',
        1 => 'news',
        2 => 'picture',
        3 => 'video',
        4 => 'intro',
        5 => 'leader',
        6 => 'hot',
    ];

    public function getSource() {
        return 'category';
    }

    public static function listStyle() {
        return self::$appStyle;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'en_name', 'code', 'father_id', 'logo', 'cover', 'terminal', 'comment_type', 'allow_comment', 'app_style', 'allow_type', 'sort', 'publish_status', 'comment_status', 'secret_status', 'wechat_status', 'coerce_status','praise','praise_num',
                'author_id','author_name','redirect_url','timelimit_begin', 'timelimit_end',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'en_name', 'code', 'father_id', 'logo', 'cover', 'terminal', 'comment_type', 'allow_comment', 'app_style', 'allow_type', 'sort', 'publish_status', 'comment_status', 'secret_status', 'wechat_status', 'coerce_status','praise','praise_num',
            	'author_id','author_name','redirect_url','timelimit_begin', 'timelimit_end',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'code', 'father_id', 'terminal', 'comment_type', 'allow_comment', 'app_style', 'allow_type', 'sort',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'en_name' => Column::TYPE_VARCHAR,
                'code' => Column::TYPE_VARCHAR,
                'father_id' => Column::TYPE_INTEGER,
                'logo' => Column::TYPE_VARCHAR,
                'cover' => Column::TYPE_VARCHAR,
                'terminal' => Column::TYPE_INTEGER,
                'comment_type' => Column::TYPE_INTEGER,
                'allow_comment' => Column::TYPE_INTEGER,
                'app_style' => Column::TYPE_INTEGER,
                'allow_type' => Column::TYPE_VARCHAR,
                'sort' => Column::TYPE_INTEGER,
                'publish_status' => Column::TYPE_INTEGER,
                'comment_status' => Column::TYPE_INTEGER,
                'secret_status' => Column::TYPE_INTEGER,
                'wechat_status' => Column::TYPE_INTEGER,
                'coerce_status' => Column::TYPE_INTEGER,
                'praise' => Column::TYPE_INTEGER,
                'praise_num' => Column::TYPE_INTEGER,
                'author_id'	 => Column::TYPE_INTEGER,
                'author_name'=> Column::TYPE_VARCHAR,
                'redirect_url' => Column::TYPE_VARCHAR,
                'timelimit_begin'=> Column::TYPE_INTEGER, 
                'timelimit_end'=> Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'father_id', 'terminal', 'comment_type', 'allow_comment', 'app_style', 'sort', 'publish_status', 'comment_status', 'secret_status', 'wechat_status', 'coerce_status','praise','praise_num','author_id','timelimit_begin', 'timelimit_end',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'en_name' => Column::BIND_PARAM_STR,
                'code' => Column::BIND_PARAM_STR,
                'father_id' => Column::BIND_PARAM_INT,
                'logo' => Column::BIND_PARAM_STR,
                'cover' => Column::BIND_PARAM_STR,
                'terminal' => Column::BIND_PARAM_INT,
                'comment_type' => Column::BIND_PARAM_INT,
                'allow_comment' => Column::BIND_PARAM_INT,
                'app_style' => Column::BIND_PARAM_INT,
                'allow_type' => Column::BIND_PARAM_STR,
                'sort' => Column::BIND_PARAM_INT,
                'publish_status' => Column::BIND_PARAM_INT,
                'comment_status' => Column::BIND_PARAM_INT,
                'secret_status' => Column::BIND_PARAM_INT,
                'wechat_status' => Column::BIND_PARAM_INT,
                'coerce_status' => Column::BIND_PARAM_INT,
                'praise' => Column::BIND_PARAM_INT,
                'praise_num' => Column::BIND_PARAM_INT,
                'author_id'	 => Column::BIND_PARAM_INT,
                'author_name'=> Column::BIND_PARAM_STR,
                'redirect_url' => Column::BIND_PARAM_STR,
                'timelimit_begin'=> Column::BIND_PARAM_INT,
                'timelimit_end'=> Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'comment_type' => '0',
                'allow_comment' => '0',
                'app_style' => '0',
                'allow_type' => 'news',
                'sort' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findPagination($channel_id, $terminal) {
        if ($terminal == "") $terminal = "web";
        return Category::query()->where('channel_id=' . $channel_id . ' and terminal="' . $terminal . '" and father_id = 0')->orderBy('sort desc,id asc')->paginate(Category::PAGE_SIZE, 'Pagination');
    }

    public static function findDepthChildren($tree, $model, &$data, $depth) {
        $children = $tree->getChildren($model['id']);
        $depth++;
        foreach ($children as $child) {
            $child['level'] = $depth;
            $child['has_child'] = !empty($tree->getChildren($child['id']));
            array_push($data, $child);
            Category::findDepthChildren($tree, $child, $data, $depth);
        }
    }

    public static function getRootCategory($channel_id) {
        return Category::find(array(
            'father_id=0 and channel_id=:channel_id:',
            'bind' => array('channel_id' => $channel_id)
        ));
    }

    public static function getRootCategoryWithTerminal($channel_id, $terminal) {
        return Category::find(array(
            'father_id=0 and channel_id=:channel_id: and terminal=:terminal:',
            'bind' => array('channel_id' => $channel_id, 'terminal' => $terminal,)
        ));
    }

    public static function getChildrens($channel_id, $father_id) {
        return Category::find(array(
            'father_id=:father_id: and channel_id=:channel_id:',
            'bind' => array('channel_id' => $channel_id, 'father_id' => $father_id)
        ));
    }

    public static function findById($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return Category::findFirst($parameters);
    }


    public static function makeValidator($input) {
        return Validator::make(
            $input, [
            'terminal' => 'required',
            'name' => 'required|max:255',
            'type' => 'integer',
            'allow_type' => 'required',
        ], [
                'terminal.required' => '终端必须选择一种',
                'name.required' => '请填写栏目名字',
                'name.max' => '栏目名字不能超过255',
                'type.integer' => '没有设置栏目类型',
                'allow_type.required' => '媒资类型至少选择一种'
            ]
        );
    }

    private function setParent($d, $parents) {
        if ($d->father_id) {
            $parents = $this->setParent(Category::findById($d->father_id), $parents);
        }
        array_push($parents, $d);
        return $parents;
    }

    public function getParents() {
        $parents = array();
        $parents = $this->setParent($this, $parents);
        return $parents;
    }

    public function getCategoryCode($name, $father_id, $terminal, $channel_id) {
        $parameters = array();
        $parameters['conditions'] = "channel_id=" . $channel_id . " and code='" . $name . "'";
        if (!Category::findFirst($parameters)) {
            return $name;
        } else if ($father_id) {
            $parameters = array();
            $parameters['conditions'] = "id=" . $father_id;
            $fathercate = Category::findFirst($parameters);
            return $fathercate->code . "/" . $name;
        } else {
            return $terminal . "/" . $name;
        }
    }

    private function checkCategoryCode($code, $channel_id) {
        $parameters = array();
        $parameters['conditions'] = "channel_id=" . $channel_id . " and code='" . $code . "'";
        if (!Category::findFirst($parameters)) {
            return true;
        }
        else {
            return false;
        }
    }

    public function checkCategoryEnName($pinyin, $channel_id, $id) {
    	$parameters = array();
    	if($id != -1) {
            $parameters['conditions'] = "channel_id=" . $channel_id . " and en_name='" . $pinyin . "'". " and id<>'" . $id . "'";
        }
        else {
            $parameters['conditions'] = "channel_id=" . $channel_id . " and en_name='" . $pinyin . "'";
        }
    	if (!Category::findFirst($parameters)) {
    		return true;
    	}
    	else {
    		return false;
    	}
    }

    /**
     * 英文名中含有的空格改为下划线
     * @param $enName 英文名称
     */
    public function changeEnName($enName) {
        $strs = trim($enName);
        $res = "";
        $noe = 0; //是否遇到不是空格的字符
        for ($i = 0; $i < strlen($strs); $i++) { //遍历整个字符串
            if ($noe == 0 && $strs[$i] == ' ') {
                $noe++;
                $strs[$i] = '_';
            }
            elseif ($strs[$i] == ' ') {
                $noe++;
            }
            elseif ($strs[$i] != ' ') {
                $noe = 0;
            }
        }
        $strs = str_replace(' ', '', $strs); //替换连续的空格为一个
        return $strs;
    }

    /**
     * 列出channel_id 下category所有数据
     * @param string $terminal
     * @param bool $onlyTerminal
     * @param string $channel_id
     * @return array
     */
    public static function listCategory($terminal = "", $onlyTerminal = false, $channel_id = "", $select_ids = null) {
        $channel_id = $channel_id ?: Session::get('user')->channel_id;
        // $key = D::memKey('list_category', ['terminal' => $terminal, 'channel_id' => $channel_id]);
        // $data = MemcacheIO::get($key);
        // if (!$data) {
        $query = self::query()
            ->andCondition('channel_id', $channel_id);
        if ($terminal) {
            $query = $query->andCondition('terminal', $terminal);
        }
        if (isset($select_ids) ) {
            if(count($select_ids)){
                $query = $query->andWhere('id in (' . implode(",", $select_ids) . ')');
            }else{
                return array();
            }
        }
        $data = $query->orderBy('sort desc,id asc')
            ->execute()
            ->toArray();
        // MemcacheIO::set($key, $data, 1800);
        // }
        $return = [];
        if (!empty($data)) {
            $return = $onlyTerminal ? array_refine($data, 'id', 'terminal') : array_refine($data, 'id');
        }
        return $return;
    }

    /**
     * 根据当前分类ID获取父类面包屑
     *
     * @param $id
     * @param $channel_id
     * @param $type
     * @param $group
     * @return array
     */
    public static function tplBreadcrumbsById($id, $channel_id, $type, $group) {
        $category = self::channelQuery($channel_id)
            ->andCondition('id', $id)
            ->first();
        $rs = [];
        if ($category) {
            $rs = self::getBreadcrumbsWithFather($category, $type, $group);
        }
        return $rs;
    }

    /**
     * 根据 data id 和 终端类型, 返回分类相应面包屑 - 供模板使用
     *
     * @param $data_id
     * @param $terminal
     * @param $type
     * @param string $group
     * @return array
     */
    public static function tplBreadcrumbs($data_id, $terminal, $type, $group) {
        $category = self::query()
            ->andCondition('data_id', $data_id)
            ->andCondition('terminal', $terminal)
            ->columns(['Category.id', 'father_id', 'code', 'name'])
            ->leftJoin('CategoryData', 'cd.category_id = Category.id', 'cd')
            ->first();
        $rs = [];
        if ($category) {
            $rs = self::getBreadcrumbsWithFather($category, $type, $group);
        }
        return $rs;
    }

    /**
     * 根据父类ID获取子分类 - 供模板使用
     * @param $id
     * @param $channel_id
     * @return array
     */
    public static function tplSub($father_id, $channel_id ,$sort=false) {
        $rs = [];
        $father_id = (int)$father_id;
        if ($father_id > 0) {
            $query = self::channelQuery($channel_id)
                ->andCondition('father_id', $father_id);
            if($sort){
                $query = $query->orderBy('sort desc');
            }
            $rs = $query->execute()->toArray();
        }
        return $rs;
    }

    /**
     * t
     * @param $category
     * @return array
     */
    protected static function getBreadcrumbsWithFather(&$category, $type, $group) {
        $rs[] = [
            'id' => $category->id,
            'name' => $category->name,
            'code' => $category->code,
            'url' => SmartyData::url(['category_id' => $category->id], $type, $group),
        ];
        while ($father_id = (int)$category->father_id) {
            $category = self::findFirst($father_id);
            if ($category) {
                $r = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'code' => $category->code,
                    'url' => SmartyData::url(['category_id' => $category->id], $type, $group),
                ];
                array_unshift($rs, $r);
            }
        }
        return $rs;
    }

    public static function hasChild($id) {
        $parameters = array();
        $parameters['conditions'] = "father_id=" . $id;
        return Category::findFirst($parameters);
    }

    public static function hasContent($id) {
        $parameters = array();
        $parameters['conditions'] = "category_id=" . $id;
        return CategoryData::findFirst($parameters);
    }

    /**
     * 获取某频道下某终端栏目树
     * @param string $terminal
     * @param string $channel_id
     * @return Tree
     */
    public static function getTree($terminal = "", $channel_id = "") {
        $admin = Session::get('user');
        $tree = new Tree();
        if (isset($admin) && $admin->is_admin != 1) {
            $select_ids = CategoryAuth::getCateAuth($admin->id, $terminal);
            if (count($select_ids)) {
                $data = self::listCategory($terminal, false, $channel_id, $select_ids);
                if (!empty($data)) {
                    foreach ($data as $v) {
                        $tree->setNode($v['id'], $v['father_id'], $v['name']);
                    }
                }
            }
        } else {
            $data = self::listCategory($terminal, false, $channel_id);
            if (!empty($data)) {
                foreach ($data as $v) {
                    $tree->setNode($v['id'], $v['father_id'], $v['name']);
                }
            }
        }
        return $tree;
    }

    public static function getAllByCodes($code, $channel_id) {
        return self::channelQuery($channel_id)->inWhere('code', $code)->execute();
    }

    public static function getSingleOne($category_id) {
        $result = self::query()->where("id='{$category_id}'")->first();
        return $result;
    }

    public static function getTitle($c_id, $channel_id) {
        $result = Category::query()->where("Category.channel_id = '{$channel_id}' AND Category.id='{$c_id}'")->first();
        return $result;
    }

    public static function queByDataID($data_id) {
        $category_data = CategoryData::query()
            ->andCondition('data_id', $data_id)
            ->execute()
            ->toArray();
        $category_ids = [];
        if (!empty($category_data)) {
            foreach ($category_data as $cd) {
                $category_ids[] = $cd['category_id'];
            }
        }
        $category_ids = array_unique($category_ids);
        return $category_ids;
    }

    
    /**
	 * 强制开关数据组合
     */
    public static function setCoerceDatas($data) {
    	$coerce_comment = intval($data['coerce_comment']);		// 评论
    	$coerce_secret = intval($data['coerce_secret']);		// 口令
    	$coerce_wechat = intval($data['coerce_wechat']);		// 微信
    	
    	$comment = $coerce_comment == 1 ? $coerce_comment << 1 : 0;
    	$secret = $coerce_secret == 1 ? $coerce_secret << 2 : 0;
    	$wechat = $coerce_wechat == 1 ? $coerce_wechat << 3 : 0;
    	
//     	var_dump("comment: ".$comment);
//     	var_dump("secret: ".$secret);
//     	var_dump("wechat: ".$wechat);
    	
    	$setData = $comment | $secret | $wechat;
    	return $setData;
//     	var_dump($setData);
//     	var_dump(($setData & 2) >> 1);
//     	var_dump(($setData & 4) >> 2);
//     	var_dump(($setData & 8) >> 3);
    }

    public function getCoerceCommentStatus($categoryId) {
        $rs = self::query()
            ->andWhere("Category.id='{$categoryId}'")
            ->first();
        $commentStatus= -1;
        if(isset($rs) && !empty($rs)) {
            $coerceStatus = $rs->coerce_status;
            $commentStatus = ($coerceStatus & 2) >> 1;
        }
        return $commentStatus;
    }

    /**
     * 获取默认发布 设置
     * @param $categoryId
     */
    public function getPublishStatus($categoryId) {
        $rs = self::query()
            ->andWhere("Category.id='{$categoryId}'")
            ->first();
        $commentStatus= -1;
        if(isset($rs) && !empty($rs)) {
            $commentStatus = $rs->publish_status;
        }
        return $commentStatus;
    }

    /**
     * 获取强制设置（评论，微信，加密）
     */
    public function getCoerceStatus($categoryId, &$commentStatus, &$wechatStatus, &$secretStatus) {
        $rs = self::query()
            ->andWhere("Category.id='{$categoryId}'")
            ->execute()
            ->toArray();
        if(isset($rs) && !empty($rs)) {
           foreach ($rs as $r) {
               $coerceStatus = $r['coerce_status'];
               $commentStatus = ($coerceStatus & 2) >> 1;
               $secretStatus = ($coerceStatus & 4) >> 2;
               $wechatStatus = ($coerceStatus & 8) >> 3;
           }
        }
    }

    /**
	 * 创建数据
     */
    public function createDatas($data, $channel_id) {
    	if($data['comment_type']&&$data['comment_type']!=1){
    		$data['allow_comment']=1;
    		if($data['comment_type']==3) $data['comment_type']=0;
    		if($data['comment_type']==2) $data['comment_type']=1;
    	}else{
    		$data['allow_comment']=0;
    	}
    	$data['allow_type'] = implode(",",$data['allow_type']);
    	if (!isset($data['father_id']) || empty($data['father_id'])) {
    		$data['father_id'] = 0;
    	}
    
    	$this->readyThumb($data['logo']);
    	$this->readyThumb($data['cover']);
    	$data['author_id'] = isset($data['author_id']) ? $data['author_id'] : Auth::user()->id;
    	$data['author_name'] = Auth::user()->name;
    	
    	$data['channel_id'] = $channel_id;
    	$data['sort'] = intval($data['sort']);
    	$data['code'] =  $this->getCategoryCode($data['name'], $data['father_id'], $data['terminal'], $data['channel_id']);
    	$data['publish_status'] = intval($data['publish']); 	// 发布默认
    	$data['comment_status'] = intval($data['comment']);		// 评论默认
    	$data['wechat_status'] = intval($data['wechat']);		// 微信默认
    	$data['secret_status'] = intval($data['secret']);		// 口令默认
    	$data['coerce_status'] = Category::setCoerceDatas($data);
    	Data::compareTime($data);
    	$data['category_id'] == '' ? $data['father_id'] = 0 : $data['father_id'] = $data['category_id'];
    	//$data['created_at'] = $data['created_at'] > 0 ? strtotime($data['created_at']) : time() ;

    	// 点赞默认
    	if($data['praise'] == 'on') {
            $data['praise'] = 1;
            $data['praise_num'] = $data['praise_num'] != "" ? intval($data['praise_num']) : 0;
        }else {
            $data['praise'] = 0;
        }
        if($this->checkCategoryCode($data['code'], $data['channel_id'])) {
            $savesuccess = $this->create($data);
        }
    	else {
            $savesuccess = false;
        }
    	
    	return $savesuccess;
    }
    
    public function updateDatas($id, $data) {
    	$category = Category::findById($id);
        if (empty($category)) {
        	$savesuccess = false;
            $messages[] = "Invalid category id";
            $this->renderResult($messages, $savesuccess);
            return;
        }
        //清除栏目lastmodified缓存
        F::_clearCache("media/latest:" . $category->id ,$category->channel_id);
        
        $category->father_id = $data['category_id'];
        if($data['comment_type']==2){
        	$data['allow_comment']=1;
        	$data['comment_type']=1;
        }else if($data['comment_type']==3){
        	$data['allow_comment']=1;
        	$data['comment_type']=0;
        }else {
        	$data['allow_comment']=0;
        }

        $this->readyThumb($data['logo']);
        $this->readyThumb($data['cover']);
        $category->logo = $data['logo'];
        $category->cover = $data['cover'];
        Data::compareTime($data);
        $category->name = $data['name'];
        $category->en_name = $data['en_name'];
        $category->allow_type = implode(",",$data['allow_type']);
        $category->allow_comment = empty($data['allow_comment']) ? 0 : 1;
        $category->comment_type = $data['comment_type'];
        $category->sort = intval($data['sort']);
        $category->app_style = intval($data['app_style']);
        
        $category->redirect_url = $data['redirect_url'];
    	$category->publish_status = intval($data['publish']);		// 发布默认
    	$category->comment_status = intval($data['comment']);		// 评论默认
    	$category->wechat_status = intval($data['wechat']);			// 微信默认
    	$category->secret_status = intval($data['secret']);			// 口令默认
    	$category->coerce_status = Category::setCoerceDatas($data);
    	$category->timelimit_begin = $data['timelimit_begin'];
    	$category->timelimit_end = $data['timelimit_end'];
    	//$category->created_at = $data['created_at'] == '' ? 0 : strtotime($data['created_at']);
    	CategorySeo::saveDatas($category->id, $data, $category->channel_id);

        // 点赞默认
        if($data['praise'] == 'on') {
            $category->praise = 1;
            $category->praise_num = $data['praise_num'] != "" ? intval($data['praise_num']) : 0;
        }else {
            $category->praise = 0;
            $category->praise_num = 0;
        }

    	$savesuccess = $category->save();
    	return $savesuccess;
    }
    
    /**
     * 处理图片路径
     * @param unknown $imgPath
     */
    private function readyThumb(&$imgPath){
    	$thumb_path = $this->uploadBase64StreamImg($imgPath);
    	if (empty($thumb_path) && strpos($imgPath,cdn_url("image","")) !== false)
    	{
    		$thumb_path = str_replace(cdn_url("image", ""), "", $imgPath);
    	}
    	$imgPath = $thumb_path;
    }
    
    private function uploadBase64StreamImg($thumb) {
    	$url ="";
    	if (preg_match('/^(data:\s*image\/(\w+);base64,)/', $thumb, $files))
    	{
    		$url = Auth::user()->channel_id.'/thumb/'.date('Y/m/d/').md5(uniqid(str_random())).".{$files[2]}";
    		Oss::uploadContent($url,base64_decode(str_replace($files[1], '', $thumb)));
    	}
    	return $url;
    }
    
    
 	public function formatcateogry($category_id) {
        $cateogrywrited = [];
        
        $_cateogrywrited = Category::findFirstOrFail($category_id)->toarray();
        if($_cateogrywrited['father_id'] == 0) {
        	$cateogrywrited[0] = ['fatherid'=>'','fathertext'=>'', 'id'=>$_cateogrywrited['id'], 'text'=>$_cateogrywrited['name'],'terminal'=>$_cateogrywrited['terminal']];
        }
        else {
            $fatherCateogryWrited = Category::findFirstOrFail($_cateogrywrited['father_id'])->toarray();
        	$cateogrywrited[0] = ['fatherid'=>$_cateogrywrited['father_id'], 'fathertext'=>$fatherCateogryWrited['name'], 'id'=>$_cateogrywrited['id'], 'text'=>$_cateogrywrited['name'],'terminal'=>$_cateogrywrited['terminal']];
        }
        return json_encode($cateogrywrited);
    }
    
    /**
	 * 判断是否为父节点
     */
    public function getFatherId($channel_id, $category_id) {
    	$rs = self::query()
            ->andCondition('father_id', $category_id)
            ->andCondition('channel_id', $channel_id)
            ->columns(['id', 'channel_id', 'name', 'father_id', 'terminal'])
            ->orderBy('sort ASC')
            ->execute()
            ->toArray();
    	return $rs;
    }
    
    /**
	 * 更新父节点
     */
    public function updateFather(array $data) {
    	$temp = false;
    	$datas = $this->findById($data["category_id"]);
    	if($data["father_id"] > 0) {
    		$fartherData = $this->findById($data["father_id"]);
    	}
    	
    	if(!empty($datas)) {
    		$datas->father_id = $data["father_id"];
    		$datas->terminal = $data["father_id"] > 0 ? $fartherData->terminal : "web";
    		$temp = $datas->update();
    	}
    	return $temp;
    }
    
 	public function modifyCategory($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    /**
     * 根据category_id 获取栏目名称
     * @param $category_id 栏目id
     */
    public static function getCategoryName($category_id) {
        $query = self::query()
            ->columns(['Category.name'])
            ->andCondition('id', $category_id)
            ->first();
        return $query;
    }


}