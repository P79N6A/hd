<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AuthAssignYear extends Model {

    public function getSource() {
        return 'auth_assign';
    }

    public function onConstruct() {
        //使用年会数据库链接
        $this->setConnectionService('db_year');
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'channel_id', 'user_id', 'element_id', 'type',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['channel_id', 'user_id', 'element_id', 'type',],
            MetaData::MODELS_NOT_NULL => ['id', 'channel_id', 'user_id', 'element_id', 'type',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'channel_id' => Column::TYPE_INTEGER,
                'user_id' => Column::TYPE_INTEGER,
                'element_id' => Column::TYPE_INTEGER,
                'type' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'channel_id', 'user_id', 'element_id', 'type',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'channel_id' => Column::BIND_PARAM_INT,
                'user_id' => Column::BIND_PARAM_INT,
                'element_id' => Column::BIND_PARAM_INT,
                'type' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'type' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    static function getRoleId($user) {        
        $assignrole = self::find(array('conditions' => "type = 1 AND channel_id= {$user->channel_id} AND user_id ={$user->id}"));
        $roleids = [];
        foreach ($assignrole as $e) {
            $roleids[] = $e->element_id;
        }
        return $roleids;
    }

    static function getAuth($user) {
        $data = self::query()
                        ->where("channel_id= {$user->channel_id} AND user_id ={$user->id}")
                        ->execute()->toArray();
        $elements = [];
        $roleElements = [];
        if (!empty($data)) {
            foreach ($data as $v) {
                if ($v['type'] == 1) {
                    $elementId = AuthRole::findFirst($v['element_id']);
                    $element = !empty($elementId) ? explode(",", $elementId->element) : [];
                    $roleElements = array_merge($roleElements, $element);
                } else {
                    $element = [$v['element_id']];
                }
                $elements = array_merge($elements, $element);
            }
        }
        return [$elements,$roleElements];
    }

    public static function getOne($assignid) {
        $parameters = array();
        $parameters['conditions'] = "id=" . $assignid;
        return self::findFirst($parameters);
    }
    
    public static function getAssignRole($admin_id){
        return self::findFirst([
            'channel_id'=>Session::get('user')->channel_id,
            'user_id'=>$admin_id,
            'type'=>1
        ]);
    }
    
    public static function resetRole($admin, $roleId) {
        self::query()
                ->andCondition('user_id', $admin->id)
                ->andCondition('type', 1)
                ->execute()
                ->delete();
        $model = new self;
        return $model->save([
                    'channel_id' => $admin->channel_id,
                    'user_id' => $admin->id,
                    'element_id' => $roleId,
                    'type' => 1,
        ]);
    }
    
    public static function resetElement($admin, $element) {
        self::query()
                ->andCondition('user_id', $admin->id)
                ->andCondition('type', 0)
                ->execute()
                ->delete();
        if (!empty($element)) {
            foreach ($element as $v) {
                $model = new self;
                $model->save([
                    'channel_id' => $admin->channel_id,
                    'user_id' => $admin->id,
                    'element_id' => (int) $v,
                    'type' => 0,
                ]);
            }
        }
        return true;
    }

}
