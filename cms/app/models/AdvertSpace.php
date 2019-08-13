<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AdvertSpace extends Model {

    const PAGE_SIZE = 50;

    const ADVERT_SAPCE_TYPE_BANNER = 1;//矩形横幅
    const ADVERT_SAPCE_TYPE_FIXURE = 2;//固定位置
    const ADVERT_SAPCE_TYPE_FLOAT = 3;//漂浮移动
    const ADVERT_SAPCE_TYPE_COUPLET = 4;//对联广告
    const ADVERT_SAPCE_TYPE_TEXT = 5;//文字广告
    const ADVERT_SAPCE_TYPE_CODE = 6;//广告代码，第三方广告
    const ADVERT_SAPCE_TYPE_APPSTART = 7;//APP开机广告

    /**
     * 类型 map  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $typeMaps = [
        self::ADVERT_SAPCE_TYPE_BANNER => '矩形横幅',
        self::ADVERT_SAPCE_TYPE_FIXURE => '固定位置',
        self::ADVERT_SAPCE_TYPE_FLOAT => '漂浮移动',
        self::ADVERT_SAPCE_TYPE_COUPLET => '对联广告',
        self::ADVERT_SAPCE_TYPE_TEXT => '文字广告',
        self::ADVERT_SAPCE_TYPE_CODE => '广告代码',
        self::ADVERT_SAPCE_TYPE_APPSTART => 'APP开机广告',
    ];

    protected static $typeCodes = [
        self::ADVERT_SAPCE_TYPE_BANNER => 'banner',
        self::ADVERT_SAPCE_TYPE_FIXURE => 'fixure',
        self::ADVERT_SAPCE_TYPE_FLOAT => 'float',
        self::ADVERT_SAPCE_TYPE_COUPLET => 'couplet',
        self::ADVERT_SAPCE_TYPE_TEXT => 'text',
    ];

    public function getSource() {
        return 'advert_space';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'type', 'path', 'width', 'height', 'setting', 'description', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'type', 'path', 'width', 'height', 'setting', 'description', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'type', 'width', 'height', 'setting', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_CHAR,
                'type' => Column::TYPE_CHAR,
                'path' => Column::TYPE_CHAR,
                'width' => Column::TYPE_INTEGER,
                'height' => Column::TYPE_INTEGER,
                'setting' => Column::TYPE_CHAR,
                'description' => Column::TYPE_CHAR,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'width', 'height', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'type' => Column::BIND_PARAM_STR,
                'path' => Column::BIND_PARAM_STR,
                'width' => Column::BIND_PARAM_INT,
                'height' => Column::BIND_PARAM_INT,
                'setting' => Column::BIND_PARAM_STR,
                'description' => Column::BIND_PARAM_STR,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'channel_id' => '0',
                'width' => '0',
                'height' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function findAll() {
        return self::query()->where('channel_id=' . Session::get('user')->channel_id)->paginate(self::PAGE_SIZE, 'Pagination');
    }

    public static function getSpaceByType($channel_id, $type) {
        return self::query()->where('status=1 and type=' . $type . ' and channel_id=' . $channel_id)->first();;
    }

    public static function makeValidator($input) {
        $validator = Validator::make(
            $input, [
            'name' => "required|unique:advert_space",
        ], [
                'name.required' => '广告位名称不能为空',
                'name.unique' => '广告位已存在',
            ]
        );
        return $validator;
    }

    public static function editValidator($input) {
        $validator = Validator::make(
            $input, [
            'name' => "required",
        ], [
                'name.required' => '广告位名称不能为空',
            ]
        );
        return $validator;
    }

    //获取广告位类型表
    public static function typeList() {
        return self::$typeMaps;
    }

    public static function getTypeName($type) {
        if ($type == 0) return "";
        return self::$typeMaps[$type];
    }

    public static function getTypeCode($type) {
        if ($type == 0) return "";
        return self::$typeCodes[$type];
    }

    public static function getOne($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        return AdvertSpace::findFirst($parameters);
    }

    public function modifySpace($data) {
        if (isset($data['channel_id']))
            $this->channel_id = intval($data['channel_id']);
        if (isset($data['name']))
            $this->name = $data['name'];
        if (isset($data['type']))
            $this->type = $data['type'];
        if (isset($data['width']))
            $this->width = $data['width'];
        if (isset($data['height']))
            $this->height = $data['height'];
        if (isset($data['description']))
            $this->description = $data['description'];
        if (isset($data['intype']))
            $this->intype = $data['intype'];
        $this->setting = json_encode(array('paddleft' => $data['paddleft'], 'paddtop' => $data['paddtop']));

        if ($this->update()) {
            $msg[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $msg[] = $m->getMessage();
            }
        }
        return $msg;

    }

    public function createSpace($data) {
        $this->assign($data);
        $this->channel_id = Session::get('user')->channel_id;
        $arr = array('paddleft' => $data['paddleft'], 'paddtop' => $data['paddtop']);
        $this->setting = json_encode($arr);
        $this->status = 1;
        if (isset($data['name']))
            $this->name = $data['name'];
        if (isset($data['type']))
            $this->type = $data['type'];
        if (isset($data['width']))
            $this->width = $data['width'];
        if (isset($data['height']))
            $this->height = $data['height'];
        if (isset($data['description']))
            $this->description = $data['description'];
        if (isset($data['intype']))
            $this->intype = $data['intype'];

        $messages = [];
        if ($this->save()) {
            if ($this->type != 'code') {
                $this->path = 'space_js/' . $this->id . '.js';
                $this->save();
            }
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    /**
     * 审核通过
     * @param int $id
     * @return bool
     */
    public static function lock($id) {
        $data = AdvertSpace::findFirst((int)$id);
        if ($data) {
            $data->status = $data->status == 1 ? 0 : 1;
            return $data->save();
        }
        return false;
    }

    public static function search($data, $channel_id) {
        $keyword = $data['keyword'];
        $type = $data['type'] ?: '';
        $query = AdvertSpace::query()->andWhere("AdvertSpace.channel_id='{$channel_id}'");
        if ($keyword) {
            $query = $query->andWhere("AdvertSpace.name like '%$keyword%'");
        }
        if ($type) {
            $query = $query->andWhere("AdvertSpace.type = '{$type}'");
        }
        return $query->paginate(self::PAGE_SIZE, 'Pagination');
    }
}