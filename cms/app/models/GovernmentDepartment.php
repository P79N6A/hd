<?php
/**
 * 部门机构模板类
 * 负责数据的增删改查，相关数据查询
 * 对应数据表government_department
 */
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class GovernmentDepartment extends Model {
    const PAGE_SIZE = 50;
    //缓存过期时间
    const EXPIRES = 60;

    public function getSource() {
        return 'government_department';
    }

    public static function apiGetGovernmentByFather($father_id) {
        $rs = [];
        $father_id = (int)$father_id;
        if ($father_id > 0) {
            $rs = self::query()
                ->andCondition('father_id', $father_id)
                ->execute()->toArray();
        }
        return $rs;
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id','father_id', 'name', 'pinyin', 'pinyin_short', 'level',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['father_id', 'name', 'pinyin', 'pinyin_short', 'level','channel_id'],
            MetaData::MODELS_NOT_NULL => ['id', 'father_id', 'name', 'pinyin', 'pinyin_short', 'level','channel_id'],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'father_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'pinyin' => Column::TYPE_VARCHAR,
                'pinyin_short' => Column::TYPE_VARCHAR,
                'level' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'father_id', 'level','channel_id'
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'father_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'pinyin' => Column::BIND_PARAM_STR,
                'pinyin_short' => Column::BIND_PARAM_STR,
                'level' => Column::BIND_PARAM_INT,
                'channel_id'=> Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'name' => '',
                'pinyin' => '',
                'pinyin_short' => '',
                'level' => 'city',
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function tplFirst($id) {
        $id = (int)$id;
        $r = self::query()
            ->andCondition('id', $id)
            ->first();
        if ($r) {
            return $r->toArray();
        } else {
            return [];
        }
    }

    /**
     * 根据父类ID获取子分类 - 供模板使用
     * @param $id
     * @return array
     */
    public static function tplSub($father_id) {
        $rs = [];
        $father_id = (int)$father_id;
        if ($father_id > 0) {
            $rs = self::query()
                ->andCondition('father_id', $father_id)
                ->execute()
                ->toArray();
        }
        return $rs;
    }

    /**
     * 获取所有的部门数据
     * @return multitype:
     */
    public static function findGovernment($id, $channel_id) {
        $r = self::query()->Where("channel_id={$channel_id}")->andwhere('id > ' . $id)->execute();
        if ($r) {
            return $r->toArray();
        } else {
            return [];
        }
    }

    public static function getRootCategory() {
        return self::find(array(
            'father_id=0'
        ));
    }

    public static function getSingleOne($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return self::findFirst($parameters);
    }

    private function setParent($d, $parents) {
        if ($d->father_id) {
            $parents = $this->setParent(self::getSingleOne($d->father_id), $parents);
        }
        array_push($parents, $d);
        return $parents;
    }

    public function getParents() {
        $parents = array();
        $parents = $this->setParent($this, $parents);
        return $parents;
    }

    public static function fetchById($id) {
        return self::findFirst($id);
    }

    public static function findAll() {
        $channel_id = Session::get('user')->channel_id;
        $data = self::query()->Where("channel_id={$channel_id}")
            ->paginate(self::PAGE_SIZE, 'Pagination');
        return $data;
    }

    public static function findListByFather($father_id) {
        return self::query()->andwhere('father_id = ' . $father_id)->execute()->toArray();
    }

    public function createGovernmentDepartment() {
        return ($this->save()) ? true : false;
    }

    public function modifyGovernmentDepartment() {
        return ($this->update()) ? true : false;
    }

    public static function deleteGovernmentDepartment($id) {
        return self::findFirst($id)->delete();
    }

    public static function findId($name) {
        $result = self::query()->where("name = '{$name}'")->execute()->toarray();
        return $result;
    }

    public static function findList($channel_id)
    {
        $result = self::query()->where("channel_id = '{$channel_id}'")->execute();
        return $result;
    }

    /**
     * 通过频道获取所有部门
     */
    public static function getDeptByChannelId($channel_id){
        $key = "deptbychannelid" . $channel_id;
        $dept = RedisIO::get($key);
        if(!$dept) {
            $dept = GovernmentDepartment::query()
                ->andCondition("channel_id", $channel_id)
                ->execute()->toArray();
            //Redis缓存数据
            $dept = json_encode($dept);
            RedisIO::set($key . $channel_id, $dept, self::EXPIRES);
        }
        return json_decode($dept,true);
    }

    /**
     * 数据更新后的操做
     */
    public function afterSave(){
        //修改缓存时间
        $channel_id = Session::get('user')->channel_id;
        $last_modified_key = "dept/getdept";
        F::_clearCache($last_modified_key, $channel_id);
    }


}