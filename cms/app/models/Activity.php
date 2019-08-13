<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;
use GenialCloud\Helper\IWC;

class Activity extends Model {

    public function getSource() {
        return 'activity';
    }

    /**
     * @param $channel_id
     * @param $type
     * @param $per_page
     * @param $page
     * @return array|mixed
     */
    public static function apiGetActivity($channel_id, $type, $per_page, $page) {
        $key = D::memKey('apiGetActivity', ['channel_id' => $channel_id, 'type' => $type]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $query = Data::query()
                ->columns(['Data.*'])
                ->leftJoin("Activity", "Data.source_id = Activity.id")
                ->andWhere("Data.type = 'activity'")
                ->andWhere("Data.channel_id = {$channel_id}")
                ->andWhere("Data.status = 1");
            if ($time = self::parseType($type)) {
                list($s, $e) = $time;
                if ($s) {
                    $query = $query->andCondition('Activity.start_time', ">=", $s);
                }
                if ($e) {
                    $query = $query->andCondition("Activity.end_time", "<=", $e);
                }
            }
            $query = $query->orderBy('Data.created_at desc');
            $rs = $query
                ->paginate($per_page, '\GenialCloud\Helper\Pagination', $page)
                ->models;
            if (!empty($rs)) {
                $data = $rs->toArray();
            }
            MemcacheIO::set($key, $data, 1800);
        }
        return $data;
    }

    /**
     * @param $type
     * @return array|mixed
     */
    public static function parseType($type) {
        $data = [];
        switch ($type) {
            case 'all':
                break;
            case 'thisweek':
                $data = IWC::thisWeek();
                break;
            case 'lastweek':
                $data = IWC::lastWeek();
                break;
            case 'lastmonth':
                $data = IWC::lastMonth();
                break;
            case 'earlier':
                $data = IWC::earlierMonth();
                break;
            default:
                break;
        }
        return $data;
    }

    /**
     * @param $channel_id
     * @param $id
     * @return mixed
     */
    public static function apiGetActivityById($channel_id, $id) {
        $key = D::memKey('apiGetActivityById', ['channel_id' => $channel_id, 'id' => $id]);
        $data = MemcacheIO::get($key);
        if (!$data || !open_cache()) {
            $rs = self::query()
                ->andCondition('id', $id)
                ->andCondition('channel_id', $channel_id)
                ->first();
            $data = array();
            if ($rs) {
                $data = $rs->toArray();
            }
            MemcacheIO::set($key, $data, 30);
        }
        return $data;
    }


    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'content', 'location', 'start_time', 'end_time', 'singup_count', 'message_template_params', 'params1', 'params2',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'content', 'location', 'start_time', 'end_time', 'singup_count', 'message_template_params', 'params1', 'params2',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'content', 'location', 'start_time', 'end_time', 'singup_count',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'content' => Column::TYPE_TEXT,
                'location' => Column::TYPE_VARCHAR,
                'start_time' => Column::TYPE_INTEGER,
                'end_time' => Column::TYPE_INTEGER,
                'singup_count' => Column::TYPE_INTEGER,
                'message_template_params' => Column::TYPE_VARCHAR,
                'params1' => Column::TYPE_VARCHAR,
                'params2' => Column::TYPE_TEXT,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'start_time', 'end_time', 'singup_count',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'content' => Column::BIND_PARAM_STR,
                'location' => Column::BIND_PARAM_STR,
                'start_time' => Column::BIND_PARAM_INT,
                'end_time' => Column::BIND_PARAM_INT,
                'singup_count' => Column::BIND_PARAM_INT,
                'message_template_params' => Column::BIND_PARAM_STR,
                'params1' => Column::BIND_PARAM_STR,
                'params2' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'location' => '',
                'singup_count' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll($channel_id) {
        $result = Data::query()
            ->columns(array('Data.*', 'Activity.*'))
            ->leftjoin("Activity", "Data.source_id=Activity.id")
            ->andwhere("Data.channel_id='{$channel_id}'")
            ->andwhere("Data.status=1")
            ->andwhere("Data.type='activity'")
            ->paginate(20, 'Pagination');
        return $result;
    }

    public function createActivity($data) {
        $this->assign($data);
        return $this->save();
    }

    public function modifyActivity($data) {
        $this->assign($data);
        return ($this->update()) ? true : false;
    }

    public static function makeValidator($inputs) {
        return Validator::make($inputs, [
            'content' => 'required|min:2|max:500',
            'intro' => 'required|min:2|max:500',
            'location' => 'required|min:2|max:250',
            'start_time' => 'required',
            'end_time' => 'required'
        ], [
            'content.required' => '请填写活动详情',
            'content.min' => '活动详情不得小于 2 个字符',
            'content.max' => '活动详情不得大于 500 个字符',
            'intro.required' => '请填写活动简介',
            'intro.min' => '活动简介不得小于 2 个字符',
            'intro.max' => '活动简介不得大于 500 个字符',
            'location.required' => '请填写活动简介',
            'location.min' => '活动地点不得小于 2 个字符',
            'location.max' => '活动地点不得大于 250 个字符',
            'start_time.required' => '请填写开始时间',
            'end_time.required' => '请填写结束时间'
        ]);
    }

    public static function getOneActivity($id) {
        $result = Data::query()
            ->columns(array('Data.*', 'Activity.*'))
            ->andwhere("Activity.id='{$id}'")
            ->andwhere("Data.type='activity'")
            ->leftjoin("Activity", "Data.source_id=Activity.id")->first();
        return $result;
    }

    public static function findOneObject($id) {
        $object = Activity::query()->where("id='{$id}'")->first();
        return $object;
    }

    public static function deleteActivity($id) {
        return Activity::findFirst($id)->delete();
    }

    /**
     * @param $id
     * @return array|bool
     */
    public static function getWithSignup($id) {
        $r = self::findFirst($id);
        if ($r) {
            $r = $r->toArray();
            $r['member'] = ActivitySignup::query()->andCondition('activity_id', $id)->execute()->toArray();
        }
        return $r;
    }


}