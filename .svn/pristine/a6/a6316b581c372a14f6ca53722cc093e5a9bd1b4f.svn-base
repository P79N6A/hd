<?php

trait HasChannel {

    /**
     * @param $channel_id
     * @return \GenialCloud\Database\Criteria
     */
    public static function channelQuery($channel_id, $with_class_name = false) {
        $prefix = '';
        if ($with_class_name) {
            $prefix = __CLASS__ . '.';
        }
        return self::query()->andWhere($prefix . 'channel_id = :channel_id:', ['channel_id' => $channel_id]);
    }

    /**
     * @param $channel_id
     * @return \GenialCloud\Database\Criteria
     */
    public static function channelQueryAndSystem($channel_id, $with_class_name = false) {
        $prefix = '';
        if ($with_class_name) {
            $prefix = __CLASS__ . '.';
        }
        return self::query()->andWhere($prefix . 'channel_id = :channel_id: or ' . $prefix . 'channel_id =0', ['channel_id' => $channel_id]);
    }

}