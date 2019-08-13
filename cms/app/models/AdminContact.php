<?php

use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class AdminContact extends Model {

    public function getSource() {
        return 'admin_contact';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                 'id', 'admin_id', 'contact_id', 'contact_static',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['admin_id', 'contact_id', 'contact_static',],
            MetaData::MODELS_NOT_NULL => ['id','admin_id', 'contact_id', 'contact_static',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'admin_id' => Column::TYPE_INTEGER,
                'contact_id' => Column::TYPE_INTEGER,
                'contact_static' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'admin_id', 'contact_id', 'contact_static',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'admin_id' => Column::BIND_PARAM_INT,
                'contact_id' => Column::BIND_PARAM_INT,
                'contact_static' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'admin_id' => '0', 'contact_id' => '0', 'contact_static' => '0'
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    
    /**
     * 判断数据是否已经存在
     */
    public static function isExistData($adminId,$operateId) {
    	$query = self::query()
    	->Where('admin_id=' .$adminId)
    	->andWhere('contact_id=' .$operateId)
    	->first();
    	return $query;
    }
    
    /**
     * 保存数据
     */
    public function saveContactData($contactValue) {
    	$this->admin_id = $contactValue["admin_id"];
    	$this->contact_id = $contactValue["contact_id"];
    	$this->contact_static = $contactValue["contact_static"];
    	$result = $this->save();
    	if($result) {
    		$messages[] = Lang::_('success');
    	} else {
    		foreach ($this->getMessages() as $m) {
    			$messages[] = $m->getMessage();
    		}
    	}
    	return $messages;
    }

    /**
     * 更新数据
     * @param unknown $data
     * @return boolean
     */
    public static function updateContactData($data) {
    	return $data->update() ? true :false;
    }
    
    /**
     * 根据admin_id 查询
     * @param unknown $id
     * @return unknown
     */
    public static function findById($adminId) {
    	$query = self::query()
    	->Where('admin_id=' .$adminId)
    	->andWhere('contact_static = 1')
    	->execute()
    	->toArray();
    	return $query;
    }
    
}