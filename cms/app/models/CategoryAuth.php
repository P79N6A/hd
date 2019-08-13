<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CategoryAuth extends Model {

    public function getSource() {
        return 'category_auth';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'user_id', 'category_id', 'terminal',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['user_id', 'category_id', 'terminal',],
            MetaData::MODELS_NOT_NULL => ['id', 'user_id', 'category_id', 'terminal',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'category_id' => Column::TYPE_INTEGER,
                'terminal' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'user_id', 'category_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'category_id' => Column::BIND_PARAM_INT,
                'terminal' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [

            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function makeValidator($input) {
        return Validator::make(
            $input, [
            'user_id' => 'integer',
            'category_id' => 'integer',
        ], [
                'user_id.integer' => '必须选择一个用户',
                'category_id.integer' => '必须选择一个栏目'
            ]
        );
    }


    public static function getCateAuth($user_id, $terminal) {
        $data = CategoryAuth::find(array(
            'user_id=:user_id: and terminal=:terminal:',
            'bind' => array('user_id' => $user_id, 'terminal' => $terminal,)
        ))->toArray();
        $return = [];
        foreach ($data as $v) {
            $return[] = $v['category_id'];
        }
        return $return;
    }

    public static function setCateAuthGreedy($channel_id, $father_id, $fullcategoryids, &$data) {
        $nodes = Category::getChildrens($channel_id, $father_id);
        $check_exist_chosed = false;
        foreach ($nodes as $n) {
            if (in_array($n->id, $fullcategoryids)) {
                $check_exist_chosed = true;
                $data[] = $n->id;
                self::setCateAuthGreedy($channel_id, $n->id, $fullcategoryids, $data);
            }
        }
        if ($check_exist_chosed == false) {
            foreach ($nodes as $n) {
                $data[] = $n->id;
                self::setCateAuthGreedy($channel_id, $n->id, $fullcategoryids, $data);
            }
        }
    }

    public static function setCateAuth($user_id, $categoryids, $terminal, $channel_id = 0, $greedy_mode = false) {
        $fullcategoryids = $categoryids;
        if (true == $greedy_mode) {//贪婪模式
            $roots = Category::getRootCategoryWithTerminal($channel_id, $terminal);
            $greedycategoryids = [];
            foreach ($roots as $r) {
                if (in_array($r->id, $fullcategoryids)) {
                    $greedycategoryids[] = $r->id;
                    self::setCateAuthGreedy($channel_id, $r->id, $fullcategoryids, $greedycategoryids);
                }
            }
            $fullcategoryids = $greedycategoryids;
        } else {
            foreach ($categoryids as $cateid) {
                $cate = Category::findById($cateid);
                foreach ($cate->getParents() as $d) {
                    if (!in_array($d->id, $fullcategoryids)) $fullcategoryids[] = $d->id;
                }
            }
        }
        $existcateauth = CategoryAuth::getCateAuth($user_id, $terminal);
        foreach ($fullcategoryids as $cateid) {
            if ($cateid && !in_array($cateid, $existcateauth)) {
                $cateauth = new CategoryAuth();
                $cateauth->user_id = $user_id;
                $cateauth->category_id = $cateid;
                $cateauth->terminal = $terminal;
                $cateauth->save();
            }
        }
        //删除多余权限
        foreach ($existcateauth as $existcateid) {
            if (!in_array($existcateid, $fullcategoryids)) {
                CategoryAuth::delCateAuth($user_id, $existcateid);
            }
        }
        return true;
    }

    public static function delCateAuth($user_id, $category_id) {
        $parameters = array();
        $parameters['conditions'] = "user_id=" . $user_id . " and category_id=" . $category_id;
        $cateauth = CategoryAuth::findFirst($parameters);
        return $cateauth->delete();
    }

    /**
     * @function 后去某个频道某个栏目下的所有用户id
     * @author 汤荷
     * @version 1.0
     * @date
     * @param $channel_id
     * @param $category_id
     * @return array|mixed
     */
    public function getAuthorIdByCategroyAndChannel($channel_id,$category_id) {

        if(RedisIO::exists(self::categoryAuthRedisKey())){
            $authersJson = RedisIO::get(self::categoryAuthRedisKey());
            $authors = json_decode($authersJson,true);
        }else{
            $authors = CategoryAuth::query()
                ->leftJoin("Category","Category.id = CategoryAuth.category_id")
                ->columns(["CategoryAuth.user_id"])
                ->andWhere("Category.channel_id = {$channel_id}")
                ->andWhere("CategoryAuth.category_id = {$category_id}")
                ->execute()
                ->toArray();

            RedisIO::set(self::categoryAuthRedisKey(),json_encode($authors));
        }

        return $authors;

    }

    public static function categoryAuthRedisKey(){
        $key = __FUNCTION__."admin:category:auth:redis";
        return $key;
    }
}