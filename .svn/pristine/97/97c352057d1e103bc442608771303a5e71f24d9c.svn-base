<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AuthElement extends Model {
    const PAGE_SIZE = 50;

    public function getSource() {
        return 'auth_element';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'controller', 'action', 'action_name', 'is_hide', 'is_system', 'belong', 'auth_hide',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['controller', 'action', 'action_name', 'is_hide', 'is_system', 'belong', 'auth_hide',],
            MetaData::MODELS_NOT_NULL => ['id', 'controller', 'action', 'action_name', 'is_hide', 'is_system', 'belong', 'auth_hide',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'controller' => Column::TYPE_VARCHAR,
                'action' => Column::TYPE_VARCHAR,
                'action_name' => Column::TYPE_VARCHAR,
                'is_hide' => Column::TYPE_INTEGER,
                'is_system' => Column::TYPE_INTEGER,
                'belong' => Column::TYPE_INTEGER,
                'auth_hide' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'is_hide', 'is_system', 'belong', 'auth_hide',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'controller' => Column::BIND_PARAM_STR,
                'action' => Column::BIND_PARAM_STR,
                'action_name' => Column::BIND_PARAM_STR,
                'is_hide' => Column::BIND_PARAM_INT,
                'is_system' => Column::BIND_PARAM_INT,
                'belong' => Column::BIND_PARAM_INT,
                'auth_hide' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'is_hide' => '0',
                'is_system' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [
                
            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }

    public static function getOne($elementid) {
        $parameters = array();
        $parameters['conditions'] = "id=".$elementid;
        return AuthElement::findFirst($parameters);
    }

    public static function getElementArr() {
        $elements = AuthElement::find();
        $elementarr = [];
        foreach ($elements as $e) {
            $elementarr[$e->id] = $e;
        }
        return $elementarr;
    }

    public static function makeValidator($input) {
        return Validator::make(
                        $input, [
                    'controller' => 'required',
                    'action' => 'required',
                    'action_name' => 'required',
                        ], [
                    'controller.required' => '控制器必填',
                    'action.required' => '操作必填',
                    'action_name.required' => '原子名称必填',
                        ]
        );
    }

    public static function deleteElement($data) {        
        $element = AuthElement::getOne((int)$data['id']);
        if('module'==$element->controller) {
            return false;
        }
        $module = AuthModule::getOne($data['moduleid']);
        $childsbefore = explode(',', $module->child);
        $childs = [];
        foreach($childsbefore as $elementid) {
            if($elementid==$data['id']) continue;
            $childs[] = $elementid;
        }
        $module->child = (implode(',', $childs))?implode(',', $childs):'0';
        $module->save();
        return $element->delete();
    }

    public function saveElement($data) {
        $element = AuthElement::findFirst(array('conditions'=>"controller='".$data['controller']."' and action='".$data['action']."'"));
        if($element) {
            $element->action_name = $data['action_name'];
            $element->is_hide = $data['is_hide'];
            $element->auth_hide = $data['auth_hide'];
            $element->is_system = $data['is_system'];
            $element->belong = $data['belong'];
            if(isset($data['moduleid'])) {
                $module = AuthModule::getOne($data['moduleid']);
                if(in_array($this->id, explode(',', $module->child))) {
                    $element->save();
                    $msg[] = Lang::_('success');
                }
                else {
                    $msg[] = Lang::_('exist');
                }                
            }
        }
        else {
            $this->controller = $data['controller'];
            $this->action = $data['action'];
            $this->action_name = $data['action_name'];
            $this->is_hide = $data['is_hide'];
            $this->is_system = $data['is_system'];
            $this->auth_hide = $data['auth_hide'];
            $this->belong = $data['belong'];
            if ($this->save()) {
                if(isset($data['moduleid'])) {
                    $module = AuthModule::getOne($data['moduleid']);
                    if($module->child==0) {
                        $module->child = $this->id;
                    }
                    else if(!in_array($this->id, explode(',', $module->child))) {
                        $module->child .= ','.$this->id;
                    }
                    $module->save();
                }
                $msg[] = Lang::_('success');
            } else {
                foreach ($this->getMessages() as $m) {
                    $msg[] = $m->getMessage();
                }
            }
        }
        return $msg;
    }

    public static function getDependenceElement($main_element_id) {
        $data = self::find(array('conditions'=> 'auth_hide=0 and belong='.$main_element_id))->toArray();

        $return = [];
        foreach ($data as $v){
            $return[$v['id']] = $v;
        }
        return $return;
    }


    public static function getAuthHiddenElement() {
        $data = self::find(array('conditions'=> 'auth_hide=1'))->toArray();

        $return = [];
        foreach ($data as $v){
            $return[$v['id']] = $v;
        }
        return $return;
    }

    public static function getAll() {
        $data = self::find(array('order'=> 'id asc,controller asc'))->toArray();

        $return = [];
        foreach ($data as $v){
            $return[$v['id']] = $v;
        }
        return $return;
    }

    public static function getElementList($elementids) {
        if($elementids=="") {
            return false;
        }
        return AuthElement::query()
                ->where('id in('.$elementids.')')
                ->orderBy('controller desc')
                ->paginate(1000, 'Pagination');
    }

    /**
     * 根据module表child列，按controller列排序
     * @param unknown $elementIds
     * @return boolean|unknown
     */
    public static function sortElementByModuleChild($elementIds) {
        if($elementIds == "") {
            return  false;
        }

        $data = self::query()
            ->where("id in (".$elementIds.")")
            ->orderBy('controller asc')
            ->execute()
            ->toArray();

        return $data;
    }


}