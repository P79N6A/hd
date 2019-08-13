<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class UserConf extends Model {

    public function getSource() {
        return 'user_conf';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'uid', 'gid', 'spec_conf',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['uid', 'gid', 'spec_conf',],
            MetaData::MODELS_NOT_NULL => ['id', 'uid', 'gid', 'spec_conf',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'uid' => Column::TYPE_INTEGER,
                'gid' => Column::TYPE_INTEGER,
                'spec_conf' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'uid', 'gid',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'uid' => Column::BIND_PARAM_INT,
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
     * @param uid int 会员id
     * @param gids int 会员组ID数组
     * @return boolean
     */
    public function updateGruop($uid, $gids) {
        foreach ($gids as $gid) {
            $spec_conf = UsergroupKV::getGroupPair($gid);

            if (self::count("uid = {$uid} and gid = {$gid}") > 0) {
                $obj = self::findFirst(
                    array(
                        'conditions' => "uid = :uid: and gid = :gid:",
                        'bind' => array('uid' => $uid, 'gid' => $gid)
                    ));
//                添加记录
                $obj->uid = $uid;
                $obj->gid = $gid;
                $obj->spec_conf = $spec_conf;
                $obj->save();
            } else {
//                更新数据
                $this->spec_conf = $spec_conf;
                $this->uid = $uid;
                $this->gid = $gid;
                $this->save();
            }
        }
        $exists = $this->query()->columns('gid')->where('uid = :uid:')->bind(array('uid' => $uid))->execute()->toArray();
        /*删除多余的*/
        foreach ($exists as $row) {
            if (!in_array($row['gid'], $gids) || empty($gids)) {
                $obj = self::findFirst(
                    array(
                        'conditions' => "uid = :uid: and gid = :gid:",
                        'bind' => array('uid' => $uid, 'gid' => $row['gid'])
                    ));
                $obj->delete();
            }
        }
        return true;
    }


    /*
     *  @param uid int 会员id
     *  @return jsonstring 会员JSON配置信息
     *
     * */
    public static function getConfUserGroup($uid) {
        $arrgids = self::query()->where("uid = :uid:")->columns("gid,spec_conf")->bind(array('uid' => $uid))->execute()->toArray();
        $retjson = '';
        foreach ($arrgids as $userconf) {
            $usergroupmodel = new UserGroup($userconf['gid']);
            $prefix = $usergroupmodel->indexname;
            $rets[$prefix] = json_decode($userconf['spec_conf']);
        }
        return json_encode($retjson);
    }

    public static function getUserGids($uid) {
        $data = self::query()->where('uid = :uid:')->bind(array('uid' => $uid))->columns('gid')->execute()->toArray();
        $ret = [];
        foreach ($data as $row) {
            $ret[] = $row['gid'];
        }
        return $ret;
    }


}