<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class RegionDefault extends Model {


    /**
     * 根据媒资id 获取 区域id
     * @param unknown $id 媒资通道号
     * @return multitype:
     */
    public static function fetchByChannelId($id) {
        $channel_data = Channel::query()
            ->andCondition('id', $id)
            ->execute()
            ->toArray();

        $fetch_id = [];
        if (!empty($channel_data)) {
            foreach ($channel_data as $v) {
                $fetch_id['region_id'] = $v['region_id'];
               // $fetch_id['government_department_id'] = $v['government_department_id'];
            }
        }

        $fetch_id = array_unique($fetch_id);
        return $fetch_id;
    }

    /**
     * 根据子节点获取父节点
     * @param unknown $id 子节点id
     */
    public static function findFatherById($id) {
        return Regions::query()->andwhere('id = ' . $id)->execute()->toArray();
    }

    /**
     * 根据传入的channel_id，获取所有有关联的区域id
     * @param unknown $id 媒资通道号
     * @return multitype: 所有关联的区域id，level
     */
    public static function findById($id) {
        $regions = self::fetchByChannelId($id);
        $region_data = [];
        if (!empty($regions)) {
            if (count($regions) == 1) {
                $ids = $regions[0];
                $region_data = self::findFatherById($ids);
            }
        }
        return $region_data;
    }


}