<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class Advert extends Model {
    const PAGE_SIZE = 50;

    const CHECKED = 1;
    const UNCHECKED = 0;

    const ADVERT_TYPE_IMAGES = 1;//图片，链接
    const ADVERT_TYPE_TEXT = 2;//文字，链接
    const ADVERT_TYPE_CODE = 3;//代码，第三方广告


    /**
     * 类型 map  ******* 新增类型常量的时候需要修改 *******
     * @var array
     */
    protected static $typeMaps = [
        self::ADVERT_TYPE_IMAGES => 'images',
        self::ADVERT_TYPE_TEXT => 'text',
        self::ADVERT_TYPE_CODE => 'code'
    ];

    public function getSource() {
        return 'advert';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'name', 'spaceid', 'type', 'setting', 'startdate', 'enddate', 'addtime', 'duration', 'clicks', 'listorder', 'status',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'name', 'spaceid', 'type', 'setting', 'startdate', 'enddate', 'addtime', 'duration', 'clicks', 'listorder', 'status',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'name', 'spaceid', 'type', 'setting', 'startdate', 'enddate', 'addtime', 'duration', 'clicks', 'listorder', 'status',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'name' => Column::TYPE_VARCHAR,
                'spaceid' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_VARCHAR,
                'setting' => Column::TYPE_TEXT,
                'startdate' => Column::TYPE_INTEGER,
                'enddate' => Column::TYPE_INTEGER,
                'addtime' => Column::TYPE_INTEGER,
                'duration' => Column::TYPE_INTEGER,
                'hits' => Column::TYPE_INTEGER,
                'clicks' => Column::TYPE_INTEGER,
                'listorder' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'spaceid', 'startdate', 'enddate', 'addtime', 'duration', 'clicks', 'listorder', 'status',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'name' => Column::BIND_PARAM_STR,
                'spaceid' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_STR,
                'setting' => Column::BIND_PARAM_STR,
                'startdate' => Column::BIND_PARAM_INT,
                'enddate' => Column::BIND_PARAM_INT,
                'addtime' => Column::BIND_PARAM_INT,
                'duration' => Column::BIND_PARAM_INT,
                'hits' => Column::BIND_PARAM_INT,
                'clicks' => Column::BIND_PARAM_INT,
                'listorder' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'spaceid' => '0',
                'startdate' => '0',
                'enddate' => '0',
                'addtime' => '0',
                'duration' => '0',
                'hits' => '0',
                'clicks' => '0',
                'listorder' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function makeValidator($input) {
        $validator = Validator::make(
            $input, [
            'name' => "required|unique:advert",
        ], [
                'name.required' => '名称不能为空',
                'name.unique' => '广告已存在',
            ]
        );
        return $validator;
    }

    public static function editValidator($input) {//编辑时验证
        $validator = Validator::make(
            $input, [
            'name' => "required",
        ], [
                'name.required' => '名称不能为空',
            ]
        );
        return $validator;
    }

    public static function findAll($spaceid) {

        $query = self::query()
            ->columns(array('Advert.*', 'AdvertSpace.name', 'AdvertSpace.type'))
            ->leftJoin('AdvertSpace', 'AdvertSpace.id=Advert.spaceid')
            ->andwhere('Advert.channel_id=' . Session::get('user')->channel_id . ' and Advert.spaceid=' . $spaceid);

        return $query->orderBy('Advert.listorder asc, Advert.id desc')
            ->paginate(Advert::PAGE_SIZE, 'Pagination');
    }

    public static function getAdvertBySpaceid($spaceid) {
        return Advert::query()
            ->where("spaceid={$spaceid}")
            ->paginate(50, 'Pagination');
    }

    public static function getOne($id) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $id;
        $data = Advert::findFirst($parameters);
        return $data;
    }

    public static function getAdvert($id) {
        $parameters = array();
        $parameters['conditions'] = "spaceid=" . $id;
        $data = Advert::find($parameters)->toArray();

        return !empty($data) ? true : false;
    }

    public function modifyAdvert($data) {
        if (isset($data['channel_id']))
            $this->channel_id = intval($data['channel_id']);
        if (isset($data['name']))
            $this->name = $data['name'];
        if (isset($data['type']))
            $this->type = $data['type'];
        if (isset($data['duration']))
            $this->duration = $data['duration'];
        if (isset($data['startdate']))
            $this->startdate = strtotime($data['startdate']);
        if (empty($data['enddate']))
            $this->enddate = 0;
        else
            $this->enddate = strtotime($data['enddate']);

        if (!empty($data['code'])) {
            $arr[] = array('code' => $data['code']);
        } else if (!empty($data['imageurl'])) {
            foreach ($data['imageurl'] as $k => $v) {
                $arr[] = array('linkurl' => $data['image_linkurl_' . $k], 'imageurl' => $v, 'alt' => $data['image_alt_' . $k]);
            }
        } else {
            $arr[] = array('linkurl' => $data['image_linkurl_0'], 'imageurl' => $data['imageurl_0'], 'alt' => $data['image_alt_0']);
        }
        $this->setting = json_encode($arr);
        if ($this->update()) {
            $msg[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $msg[] = $m->getMessage();
            }
        }
        return $msg;
    }

    public function createAdvert($data) {
        $this->assign($data);
        $this->channel_id = isset($data['channel_id']) ? $data['channel_id'] : Session::get('user')->channel_id;
        $this->spaceid = $data['spaceid'];
        if (!empty($data['code'])) {
            $arr[] = array('code' => $data['code']);
        } else if (!empty($data['imageurl'])) {
            foreach ($data['imageurl'] as $k => $v) {
                $arr[] = array('linkurl' => $data['image_linkurl_' . $k], 'imageurl' => $v, 'alt' => $data['image_alt_' . $k]);
            }
        } else {
            $arr[] = array('linkurl' => $data['image_linkurl_0'], 'imageurl' => $data['imageurl_0'], 'alt' => $data['image_alt_0']);
        }
        $this->setting = json_encode($arr);
        $this->addtime = time();
        $this->clicks = 1;
        $this->listorder = 0;
        $this->status = 1;
        $messages = [];
        if (isset($data['startdate']))
            $this->startdate = strtotime($data['startdate']);
        if (empty($data['enddate']))
            $this->enddate = 0;
        else
            $this->enddate = strtotime($data['enddate']);
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public static function updateOrder($id, $listorder) {
        $advert = Advert::getOne($id);
        $advert->listorder = $listorder;
        return ($advert->save()) ? true : false;
    }

    public function changeStatus($status) {
        $this->status = $status;
        return $this->save();
    }

    public static function getTypeName($type) {
        if ($type == 0) return "";
        return self::$typeMaps[$type];
    }

    public static function createJs($id) {
        $now = time();
        $query = [];
        $space = AdvertSpace::getOne($id);
        if(AdvertSpace::ADVERT_SAPCE_TYPE_COUPLET==$space->type) {
        $query = self::query()
            ->columns(array('Advert.*', 'AdvertSpace.*'))
            ->leftJoin('AdvertSpace', 'AdvertSpace.id=Advert.spaceid')
            ->andwhere('Advert.channel_id=' . Session::get('user')->channel_id . ' and Advert.spaceid=' . $id . ' and Advert.status = 1 and AdvertSpace.status = 1 ')
            ->orderBy('Advert.listorder asc, Advert.id desc')
            ->execute();
        }
        else {
            $query = self::query()
                ->columns(array('Advert.*', 'AdvertSpace.*'))
                ->leftJoin('AdvertSpace', 'AdvertSpace.id=Advert.spaceid')
                ->andwhere('Advert.channel_id=' . Session::get('user')->channel_id . ' and Advert.spaceid=' . $id . ' and Advert.status = 1 and AdvertSpace.status = 1 ')
                ->orderBy('Advert.listorder asc, Advert.id desc')
                ->first();
        }
        if ($query)
            return $query->toArray();
    }

}