<?php

/**
 *  频道管理
 *  model Channel
 * @author     Shunfei Zhou
 * @created    2015-9-16
 */
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Channel extends Model {

    public function getSource() {
        return 'channel';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'name', 'shortname', 'tag', 'status', 'channel_logo', 'channel_logo_slave', 'watermark', 'channel_url', 'channel_instr', 'channel_info', 'region_id','address',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['name', 'shortname', 'tag', 'status', 'channel_logo', 'channel_logo_slave', 'watermark', 'channel_url', 'channel_instr', 'channel_info', 'region_id','address',],
            MetaData::MODELS_NOT_NULL => ['id', 'name', 'shortname', 'tag', 'status', 'channel_logo', 'channel_url', 'channel_instr', 'channel_info',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'shortname' => Column::TYPE_VARCHAR,
                'tag' => Column::TYPE_VARCHAR,
                'status' => Column::TYPE_INTEGER,
                'channel_logo' => Column::TYPE_VARCHAR,
                'channel_logo_slave' => Column::TYPE_VARCHAR,
                'watermark' => Column::TYPE_VARCHAR,
                'channel_url' => Column::TYPE_VARCHAR,
                'channel_instr' => Column::TYPE_VARCHAR,
                'channel_info' => Column::TYPE_TEXT,
                'region_id' => Column::TYPE_INTEGER,
                'address' => Column::TYPE_VARCHAR,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'status', 'region_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'shortname' => Column::BIND_PARAM_STR,
                'tag' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
                'channel_logo' => Column::BIND_PARAM_STR,
                'channel_logo_slave' => Column::BIND_PARAM_STR,
                'watermark' => Column::BIND_PARAM_STR,
                'channel_url' => Column::BIND_PARAM_STR,
                'channel_instr' => Column::BIND_PARAM_STR,
                'channel_info' => Column::BIND_PARAM_STR,
                'region_id' => Column::BIND_PARAM_INT,
                'address' => Column::BIND_PARAM_STR,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'status' => '1',
                'region_id' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function listChannel($is_system = false) {
        $data = self::query()->execute()->toArray();
        $return = [];
        if (!empty($data)) {
            $return = array_refine($data, "id");
            $return[0] = $return[1];
            $return[0]['name'] = '系统';
            $return[0]['id'] = 0;
        }
        ksort($return);
        return $return;
    }

    /**
     * 创建新频道
     * @param array $data
     * @return bool
     */
    public function createChannel($data) {
        return $this->save($data);
    }

    /**
     * 删除频道
     */
    public static function deleteChannel($channel_id) {
        return Channel::findFirst($channel_id)->delete();
    }

    /**
     * 修改频道
     */
    public function modifyChannel($data) {
        if (isset($data['channel_logo']))
            $this->channel_logo = $data['channel_logo'];
        if (isset($data['channel_logo_slave']))
            $this->channel_logo = $data['channel_logo_slave'];
        if (isset($data['channel_instr']))
            $this->channel_instr = $data['channel_instr'];
        if (isset($data['channel_info']))
            $this->channel_instr = $data['channel_info'];
        return ($this->save()) ? 1 : 0;
    }

    /**
     * 通过ID取单一频道
     */
    public static function getOneChannel($channel_id) {
        $result = self::query()->andCondition('id', $channel_id)->first();
        return $result;
    }

    public static function getOneByTag($tag) {
        $result = self::query()->andCondition('tag', $tag)->first();
        return $result;
    }

    /**
     * 取所有频道
     */
    public static function findAll() {
        return self::query()->paginate(50, 'Pagination');
    }

    public static function makeValidators($inputs, $excluded_id = 0) {
        return Validator::make($inputs, [
            'name' => 'required|min:2|max:50',
            'tag' => 'required|max:10|unique:channel',
            'shortname' => 'required',
            'tag' => 'required|max:10',
            'channel_url' => 'required',
            'channel_instr' => 'required',
            'channel_info' => 'required',
            'status' => 'required'
        ], [
            'name.required' => '请填写频道名',
            'name.min' => '用户名不得小于 2 个字符',
            'name.max' => '用户名不得多于 50 个字符',
            'shortname.required' => '请填写短标识',
            'tag.required' => '请填写频道短标识',
            'tag.max' => '短标识不得多于 10 个字符',
            'tag.unique' => '短标识已存在',
            'channel_url.required' => '请填写Url',
            'channel_instr.required' => '请填写说明',
            'channel_info.required' => '请填写频道详情',
            'status.required' => '请填写状态'
        ]);
    }

    public static function editValidators($inputs) {
        return Validator::make($inputs, [
            'channel_instr' => 'required',
            'channel_info' => 'required',
        ], [
            'channel_instr.required' => '请填写简介',
            'channel_info.required' => '请填写详情',
        ]);
    }

    /*
     * 获取频道所有管理员
     */
    public static function findChannelAdmin($channel_id) {
        $managers = Admin::query()
            ->columns(array('Admin.name'))
            ->where("Admin.is_admin = 1 and Admin.channel_id =$channel_id")
            ->execute()
            ->toArray();
        $str = '';
        foreach ($managers as $manager) {
            if ($str) {
                $str = $str . '， ' . $manager['name'];
            } else {
                $str = $str . $manager['name'];
            }
        }
        return $str;
    }

    /*
     * 获取频道下的站点
     */
    public static function findChannelSites($channel_id) {
        $data = Site::query()
            ->where("channel_id = $channel_id")
            ->paginate(Site::PAGE_SIZE, 'Pagination');
        return $data;
    }
}
