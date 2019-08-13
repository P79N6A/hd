<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;
use GenialCloud\Helper\Tree;

class PrivateCategory extends Model {

    use HasChannel;
    const PAGE_SIZE = 20;

    const MEDIA_TYPE_NEWS = 1;
    const MEDIA_TYPE_ALBUM = 2;
    const MEDIA_TYPE_VIDEO = 3;
    const MEDIA_TYPE_SPECIAL = 4;
    const MEDIA_TYPE_LIVE = 5;
    const MEDIA_TYPE_NEWS_COLLECTION = 6;
    const MEDIA_TYPE_ALBUM_COLLECTION = 7;
    const MEDIA_TYPE_VIDEO_COLLECTION = 8;

    /**
     * 类型 map  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $typeMaps = [
        self::MEDIA_TYPE_NEWS => '媒资',
        self::MEDIA_TYPE_ALBUM => '相册',
        self::MEDIA_TYPE_VIDEO => '视频',
        self::MEDIA_TYPE_SPECIAL => '专题',
        self::MEDIA_TYPE_LIVE => '直播',
        self::MEDIA_TYPE_NEWS_COLLECTION => '媒资集',
        self::MEDIA_TYPE_ALBUM_COLLECTION => '相册集',
        self::MEDIA_TYPE_VIDEO_COLLECTION => '视频集',
    ];


    public function getSource() {
        return 'private_category';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'father_id', 'media_type',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'father_id', 'media_type',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'father_id', 'media_type',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'father_id' => Column::TYPE_INTEGER,
                'media_type' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'father_id', 'media_type',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'father_id' => Column::BIND_PARAM_INT,
                'media_type' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'media_type' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function listType() {
        return self::$typeMaps;
    }

    public static function allColumn($channel_id) {
        $result = PrivateCategory::query()->where("channel_id = '{$channel_id}'")
            ->execute()->toarray();
        return $result;
    }

    public static function findOneColumn($colum_id) {
        $result = PrivateCategory::query()->where("id = '{$colum_id}'")
            ->execute()->toarray();
        return $result;
    }

    public static function findAll($channel_id) {
        $result = PrivateCategory::query()->where("channel_id='{$channel_id}'")->paginate(50, 'Pagination');
        return $result;
    }

    public static function deleteActivity($id) {
        return PrivateCategory::findFirst($id)->delete();
    }

    public function addPriCat($data) {
        $this->assign($data);
        return $this->save();
    }

    public function modifyPriCat($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'name' => 'required|min:2|max:50',
        ], [
            'name.required' => '请填写分类名称',
            'name.min' => '分类名称不得小于 2 个字符',
            'name.max' => '分类名称不得大于 10 个字符',
        ]);
    }

    public static function getOne($id) {
        $result = PrivateCategory::query()->where("id='{$id}'")->first();
        return $result;
    }

    public static function findPagination($channel_id, $media_type) {
        if ($media_type == "") $media_type = "news";
        return PrivateCategory::query()->where('channel_id=' . $channel_id . ' and media_type="' . $media_type . '" and father_id = 0')->orderBy('id asc')->paginate(PrivateCategory::PAGE_SIZE, 'Pagination');
    }

    public static function findDepthChildren($tree, $model, &$data, $depth) {
        $children = $tree->getChildren($model['id']);
        $depth++;
        foreach ($children as $child) {
            $child['level'] = $depth;
            $child['has_child'] = !empty($tree->getChildren($child['id']));
            array_push($data, $child);
            PrivateCategory::findDepthChildren($tree, $child, $data, $depth);
        }
    }

    private function setParent($d, $parents) {
        if ($d->father_id) {
            $parents = $this->setParent(PrivateCategory::findById($d->father_id), $parents);
        }
        array_push($parents, $d);
        return $parents;
    }

    public function getParents() {
        $parents = array();
        $parents = $this->setParent($this, $parents);
        return $parents;
    }

    public static function findById($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return PrivateCategory::findFirst($parameters);
    }

    /**
     * 列出channel_id 下category所有数据
     * @param string $media_type
     * @param bool $onlyMedia
     * @param string $channel_id
     * @return array
     */
    public static function listCategory($media_type = "", $onlyMedia = false, $channel_id = "", $select_ids = []) {
        $channel_id = $channel_id ?: Session::get('user')->channel_id;
        $query = self::query()
            ->andCondition('channel_id', $channel_id);
        if ($media_type) {
            $query = $query->andCondition('media_type', $media_type);
        }
        if (count($select_ids)) {
            $query = $query->andWhere('id in (' . implode(",", $select_ids) . ')');
        }
        $data = $query->orderBy('id asc')
            ->execute()
            ->toArray();
        $return = [];
        if (!empty($data)) {
            $return = $onlyMedia ? array_refine($data, 'id', 'media_type') : array_refine($data, 'id');
        }
        return $return;
    }

    /**
     * 获取某频道下某终端栏目树
     * @param string $terminal
     * @param string $channel_id
     * @return Tree
     */
    public static function getTree($media_type = "", $channel_id = "") {
        $tree = new Tree();
        $data = self::listCategory($media_type, false, $channel_id, []);
        if (!empty($data)) {
            foreach ($data as $v) {
                $tree->setNode($v['id'], $v['father_id'], $v['name']);
            }
        }
        return $tree;
    }
}
