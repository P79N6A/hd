<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class CategorySeo extends Model {

    public function getSource() {
        return 'category_seo';
    }

    /**
     * @param $channel_id
     * @return array
     */
    public static function apiListCategorySeo($channel_id) {
        $data = self::query()
            ->andCondition('channel_id', $channel_id)
            ->execute()
            ->toArray();
        return !empty($data) ? array_refine($data, 'category_id') : [];
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'category_id', 'keywords', 'desc', 'title', 'channel_id', 'intro', 'tips',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['category_id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['keywords', 'desc', 'title', 'channel_id', 'intro', 'tips',],
            MetaData::MODELS_NOT_NULL => ['category_id', 'keywords', 'desc', 'title', 'channel_id', 'intro', 'tips',],
            MetaData::MODELS_DATA_TYPES => [
                'category_id' => Column::TYPE_INTEGER,
                'keywords' => Column::TYPE_VARCHAR,
                'desc' => Column::TYPE_TEXT,
                'title' => Column::TYPE_VARCHAR,
                'channel_id' => Column::TYPE_INTEGER,
                'intro' => Column::TYPE_TEXT,
                'tips' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'category_id', 'channel_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'category_id' => Column::BIND_PARAM_INT,
                'keywords' => Column::BIND_PARAM_STR,
                'desc' => Column::BIND_PARAM_STR,
                'title' => Column::BIND_PARAM_STR,
                'channel_id' => Column::BIND_PARAM_INT,
                'intro' => Column::BIND_PARAM_STR,
                'tips' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'title' => ''
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findById($id) {
        $parameters = array();
        $parameters['conditions'] = "category_id=" . $id;
        return CategorySeo::findFirst($parameters);
    }

    /**
     * 保存seo数据
     * @param unknown $data
     */
    public static function saveDatas($id, $data, $channel_id) {
    	$seo = CategorySeo::findById($id);
    	if (!empty($seo)) {
    		$seo->desc = $data['intro'];
    		$seo->intro = $data['intro'];
    		$seo->title = $data['title'];
    		$seo->keywords = $data['keywords'];
    		$seo->channel_id = $channel_id;
    		$seo->save();
    	} else {
    		$seo = new CategorySeo();
    		$seo->category_id = intval($id);
    		$seo->intro = $data['intro'];
    		$seo->title = $data['title'];
    		$seo->desc =  $data['intro'];
    		$seo->channel_id = $channel_id;
    		$seo->tips = "";
    		$seo->keywords = $data['keywords'];
    		$seo->save();
    	}
    }

    public static function deleteData($category_id) {
        $res = true;
        $seo = CategorySeo::findById($category_id);
        if (!empty($seo)) {
            $res = $seo->delete();
        }
        return $res;
    }
    
}