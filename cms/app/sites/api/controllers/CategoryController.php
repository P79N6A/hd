<?php


/**
 * @RoutePrefix("/category")
 */
class CategoryController extends ApiBaseController
{

    public function initialize() {
        parent::initialize();
        $this->checkToken();
    }

    //根据配置获取有权限的栏目列表
    /**
     * @Get("/")
     * @return json
     */
    public function listAction() {

        $id = Request::get("channel_id");
        $categoryConf = F::getConfig('app_category', $id);
        $redisKey = __FUNCTION__ . "app_category" . "channel_id:" . $id . ":category" . md5($categoryConf) . ":userid" . $this->user->id;
        $categoryIds = explode(",",$categoryConf['categorys']);

        if (RedisIO::exists($redisKey)) {
            $category = json_decode(RedisIO::get($redisKey), true);
        } else {
            $query = Category::query()
                ->columns("Category.*")
                ->leftJoin("CategoryAuth", "CategoryAuth.category_id=Category.id")
                ->andWhere("channel_id = {$id} and CategoryAuth.user_id={$this->user->id} ");

            if (count($categoryIds) > 0) {
                $query = $query->inWhere("Category.id", $categoryIds);
            }
            $category = $query->execute()
                ->toArray();
//            RedisIO::set($redisKey,json_encode($category));
        }


        if ($category) {
            $this->_json($category);
        } else {
            $this->_json([], 404);
        }

    }
}
