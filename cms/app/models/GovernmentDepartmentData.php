<?php
/**
 * 部门媒资模板类
 * 对应数据表government_department_data
 */
use Phalcon\Mvc\Model\MetaData;
use Phalcon\Db\Column;

class GovernmentDepartmentData extends Model {

    public function getSource() {
        return 'government_department_data';
    }

    public function metaData() {
        return [
            MetaData::MODELS_ATTRIBUTES => [
                'id', 'data_id', 'government_department_id',
            ],
            MetaData::MODELS_PRIMARY_KEY => ['id',],
            MetaData::MODELS_NON_PRIMARY_KEY => ['data_id', 'government_department_id',],
            MetaData::MODELS_NOT_NULL => ['id', 'data_id', 'government_department_id',],
            MetaData::MODELS_DATA_TYPES => [
                'id' => Column::TYPE_INTEGER,
                'data_id' => Column::TYPE_INTEGER,
                'government_department_id' => Column::TYPE_INTEGER,
            ],
            MetaData::MODELS_DATA_TYPES_NUMERIC => [
                'id', 'data_id', 'government_department_id',
            ],
            MetaData::MODELS_DATA_TYPES_BIND => [
                'id' => Column::BIND_PARAM_INT,
                'data_id' => Column::BIND_PARAM_INT,
                'government_department_id' => Column::BIND_PARAM_INT,
            ],
            MetaData::MODELS_DEFAULT_VALUES => [
                'government_department_id' => '0',
            ],
            MetaData::MODELS_EMPTY_STRING_VALUES => [

            ],
            MetaData::MODELS_AUTOMATIC_DEFAULT_INSERT => [],
            MetaData::MODELS_AUTOMATIC_DEFAULT_UPDATE => [],
            MetaData::MODELS_IDENTITY_COLUMN => 'id',
        ];
    }
    
    
    
    
    

    /**
     * 保存部门数据
     * @param unknown $data 界面数据，包含部门信息
     * @param unknown $data_id 媒资id
     * @return boolean
     */
    public function createGovernmentDepartmentData($data, $data_id) {
        $this->data_id = $data_id;
        if (array_key_exists('government_id', $data)) {
            $this->government_department_id = $data['government_id'] ?: 0;
        }
        return ($this->save()) ? true : false;
    }

    /**
     * 根据媒资id获取
     * @param unknown $id
     * @return unknown
     */
    public static function getOne($id) {
        $parameters = array();
        $parameters['conditions'] = "data_id=" . $id;
        $data = self::findFirst($parameters);
        return $data;
    }


    /**
     * 更新部门数据
     * @param unknown $data 界面数据，包含部门信息
     * @param unknown $data_id 媒资id
     * @return boolean
     */
    public function updateGovernmentDepartmentData($datas, $id) {
       
    	$data = self::getOne($id);
    	if($data&&isset($datas['government_id'])&&$datas['government_id']) {
	        $this->id = $data->id;
	        $this->data_id = $id;
	        $this->government_department_id = $datas['government_id'];
	        return ($this->update()) ? true : false;
    	}else {
    		return $this->createGovernmentDepartmentData($datas,$id);
    	}
    }

    /**
     * 从部门媒资关系表中根据媒资id查询部门id
     * @param unknown $data_id 媒资id
     */
    public static function fetchGovernmentDepartmentId($data_id) {
        return self::query()->andwhere('data_id = ' . $data_id)->execute()->toArray();
    }


    /**
     * 保存部门选择数据
     * @param unknown $data 界面输入数据，包含部门信息
     * @param unknown $data_id 媒资id
     * @return boolean
     */
    public function saveGovernmentDepartment($data,$data_id) {
    	if(!$this->createGovernmentDepartmentData($data, $data_id)) {
    		return  false;
    	}
    	return  true;
    }
    
    /**
     * 更新部门选择数据
     * @param unknown $data 界面输入数据，包含部门信息
     * @param unknown $data_id 媒资id
     * @return boolean
     */
    public function updateGovernmentDepartment($data,$data_id) {
    	if(!$this->updateGovernmentDepartmentData($data, $data_id)) {
    		return  false;
    	}
    	return  true;
    }

    /**
     * 数据更新后的操做
     */
    public function afterSave(){
        //修改缓存时间
        $channel_id = Session::get('user')->channel_id;
        $last_modified_key = "dept/getdept:";
        F::_clearCache($last_modified_key, $channel_id);
    }
}