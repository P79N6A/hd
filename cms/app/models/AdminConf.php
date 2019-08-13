<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AdminConf extends Model {

    public function getSource() {
        return 'admin_conf';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'admin_id', 'gid', 'spec_conf',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['admin_id', 'gid', 'spec_conf',],
            MetaData::MODELS_NOT_NULL => ['id', 'admin_id', 'gid', 'spec_conf',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'gid' => Column::TYPE_INTEGER,
                'spec_conf' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'admin_id', 'gid',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
                'gid' => Column::BIND_PARAM_INT,
                'spec_conf' => Column::BIND_PARAM_STR,
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

    /**
     * @param admin_id int 会员id
     * @param gids int 会员组ID数组
     * @return boolean
     */
    public function updateGruop($admin_id, $gids) {
        foreach ($gids as $gid) {
            $spec_conf = AdminGroupKv::getGroupPair($gid);

            if (self::count("admin_id = {$admin_id} and gid = {$gid}") > 0) {
                $obj = self::findFirst(
                    array(
                        'conditions' => "admin_id = :admin_id: and gid = :gid:",
                        'bind' => array('admin_id' => $admin_id, 'gid' => $gid)
                    ));
//                添加记录
                $obj->admin_id = $admin_id;
                $obj->gid = $gid;
                $obj->spec_conf = $spec_conf;
                $obj->save();
            } else {
//                更新数据
                $this->spec_conf = $spec_conf;
                $this->admin_id = $admin_id;
                $this->gid = $gid;
                $this->save();
            }
        }
        $exists = $this->query()->columns('gid')->where('admin_id = :admin_id:')->bind(array('admin_id' => $admin_id))->execute()->toArray();
        /*删除多余的*/
        foreach ($exists as $row) {
            if (!in_array($row['gid'], $gids) || empty($gids)) {
                $obj = self::findFirst(
                    array(
                        'conditions' => "admin_id = :admin_id: and gid = :gid:",
                        'bind' => array('admin_id' => $admin_id, 'gid' => $row['gid'])
                    ));
                $obj->delete();
            }
        }
        return true;
    }


    /*
     *  @param admin_id int 会员id
     *  @return jsonstring 会员JSON配置信息
     *
     * */
    public static function getConfUserGroup($admin_id) {
        $arrgids = self::query()->where("admin_id = :admin_id:")->columns("gid,spec_conf")->bind(array('admin_id' => $admin_id))->execute()->toArray();
        $retjson = '';
        foreach ($arrgids as $userconf) {
            $usergroupmodel = new UserGroup($userconf['gid']);
            $prefix = $usergroupmodel->indexname;
            $rets[$prefix] = json_decode($userconf['spec_conf']);
        }
        return json_encode($retjson);
    }

    public static function getUserGids($admin_id) {
        $data = self::query()->where('admin_id = :admin_id:')->bind(array('admin_id' => $admin_id))->columns('gid')->execute()->toArray();
        $ret = [];
        foreach ($data as $row) {
            $ret[] = $row['gid'];
        }
        return $ret;
    }


}