<?php

namespace GenialCloud\Database;

use GenialCloud\Support\Parcel;

class Criteria extends \Phalcon\Mvc\Model\Criteria {

    /**
     * @param $size
     * @param null $page
     * @return \GenialCloud\Support\Parcel
     * @throws \Phalcon\Mvc\Model\Exception
     */
    public function paginate($size, $pagination_class='\GenialCloud\Helper\Pagination', $page=null) {
        if(!is_null($page)) {
            $page = (int) $page;
            $page = $page > 0? $page: 1;
        } else {
            if(isset($_REQUEST['page'])) {
                $page = (int)$_REQUEST['page'];
                if($page < 1) {
                    $page = 1;
                }
            } else {
                $page = 1;
            }
        }
        $model = $this->getModelName();
        if(!is_string($model)) {
            throw new \Phalcon\Mvc\Model\Exception("Model name must be string");
        }
        $count = $model::count($this->getParams());
        $models = [];
        if($page <= ceil($count/$size)) {
            $this->_params['limit'] = $size;
            $this->_params['offset'] = ($page - 1) * $size;
            $models = $model::find($this->getParams());
        }
        /**
         * @var $pagination \GenialCloud\Helper\Pagination
         */
        $pagination = new $pagination_class([
            'pageSize' => $size,
            'itemCount' => $count,
            'currentPage' => $page,
        ]);
        $parcel = Parcel::init(compact('models', 'count', 'pagination'));
        return $parcel;
    }

    /*
     * Redis 分页
     *
     * */
    public function redisPaginate($size,$count,$data_list,$pagination_class='\GenialCloud\Helper\Pagination', $page=null)
    {
        if(!is_null($page)) {
            $page = (int) $page;
            $page = $page > 0? $page: 1;
        } else {
            if(isset($_REQUEST['page'])) {
                $page = (int)$_REQUEST['page'];
                if($page < 1) {
                    $page = 1;
                }
            } else {
                $page = 1;
            }
        }
        $count = intval($count);
        $pagination = new $pagination_class([
            'pageSize' => $size,
            'itemCount' => $count,
            'currentPage' => $page,
        ]);
        $models = $data_list;
        $parcel = Parcel::init(compact('models','count','pagination'));
        return $parcel;
    }



    /**
     * @param $column
     * @param $operation
     * @param null $value
     * @return \GenialCloud\Database\Criteria
     */
    public function andCondition($column, $operation, $value=null) {
        list($column, $operation, $value) = $this->processCondition($column, $operation, $value);
        return $this->andWhere("{$column} {$operation} :{$column}:", [$column => $value]);
    }

    /**
     * @param $column
     * @param $operation
     * @param null $value
     * @return \GenialCloud\Database\Criteria
     */
    public function orCondition($column, $operation, $value=null) {
        list($column, $operation, $value) = $this->processCondition($column, $operation, $value);
        return $this->orWhere("{$column} {$operation} :{$column}:", [$column => $value]);
    }

    /**
     * @param $column
     * @param $operation
     * @param null $value
     * @return array
     */
    protected function processCondition($column, $operation, $value=null) {
        if($value===null) {
            $value = $operation;
            $operation = '=';
        }
        return [$column, $operation, $value];
    }

    /**
     * @return \Phalcon\Mvc\ModelInterface
     */
    public function first() {
        return $this->execute()->getFirst();
    }

}