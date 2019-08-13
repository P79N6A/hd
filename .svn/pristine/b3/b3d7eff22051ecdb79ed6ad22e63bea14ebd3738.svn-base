<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AppPopup extends Model {
    static $DATATYPE_URL = 1;
    static $DATATYPE_VIDEO = 2;
    static $VideoUnitDetailTypeLive = 0;      //普通直播
    static $VideoUnitDetailTypeVOD = 1;       //普通点播
    static $VideoUnitDetailTypeSubject = 2;   //主题
    static $VideoUnitDetailTypePanoLive = 5;  //全景直播
    static $VideoUnitDetailTypePanoVOD = 6;   //全景点播
    static $VideoUnitDetailTypeWeb = 3;       //H5网页
    static $VideoUnitDetailTypeCredit = 7;    //积分商城


    public function getSource() {
        return 'app_popup';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'app_id', 'type', 'version', 'title', 'thumb', 'poptype', 'popupdata', 'datatype', 'status', 'created_at',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['app_id', 'type', 'version', 'title', 'thumb', 'poptype', 'popupdata', 'datatype', 'status', 'created_at',],
            MetaData::MODELS_NOT_NULL => ['id', 'app_id', 'type', 'version', 'title', 'thumb', 'poptype', 'datatype', 'status', 'created_at',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'app_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
                'version' => Column::TYPE_VARCHAR,
                'title' => Column::TYPE_VARCHAR,
                'thumb' => Column::TYPE_VARCHAR,
                'poptype' => Column::TYPE_INTEGER,
                'popupdata' => Column::TYPE_VARCHAR,
                'datatype' => Column::TYPE_INTEGER,
                'status' => Column::TYPE_INTEGER,
                'created_at' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'app_id', 'type', 'poptype', 'datatype', 'status', 'created_at',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'app_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
                'version' => Column::BIND_PARAM_STR,
                'title' => Column::BIND_PARAM_STR,
                'thumb' => Column::BIND_PARAM_STR,
                'poptype' => Column::BIND_PARAM_INT,
                'popupdata' => Column::BIND_PARAM_STR,
                'datatype' => Column::BIND_PARAM_INT,
                'status' => Column::BIND_PARAM_INT,
                'created_at' => Column::BIND_PARAM_INT,
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

    public static function findAll($app_id, $type) {
        return AppPopup::query()->where("app_id = '{$app_id}' and type=" . $type)->order('id desc')->paginate(10, 'Pagination');
    }

    public static function findOne($data) {
        if ($data['poptype'] != 1) {
            $data['poptype'] = 2;
        }
        $query = self::query()
            ->andCondition('app_id', $data['app_id'])
            ->andCondition('type', $data['type'])
            ->andCondition('version', $data['version'])
            ->andCondition('poptype', $data['poptype']);
        if (isset($data['status'])) {
            $query->andCondition('status', $data['status']);
        }
        return $query->first();
    }

    public function changeStatus() {
        if ($this->status == 1) {
            $this->status = 0;
        } else {
            $this->status = 1;
        }
        return $this->save();
    }

    public function createAppPopup($data) {
        $this->assign($data);
        $this->status = 1;
        $this->created_at = time();
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public function updateAppPopup($data) {
        $this->assign($data);
        $this->status = 1;
        $this->created_at = time();
        if ($this->save()) {
            $messages[] = Lang::_('success');
        } else {
            foreach ($this->getMessages() as $m) {
                $messages[] = $m->getMessage();
            }
        }
        return $messages;
    }

    public static function makeValidators($inputs) {
        return Validator::make(
            $inputs, [
            'app_id' => 'required',
            'type' => 'required',
            'version' => 'required',
            'datatype' => 'required',
            'poptype' => 'required',
        ], [
            'app_id.required' => '请填写APP_ID',
            'type.required' => '请填写客户端类型',
            'version.required' => '请填写版本号',
            'datatype.required' => '请填写弹窗内容类型',
            'poptype.required' => '请填写弹出类型',
        ]);
    }

}