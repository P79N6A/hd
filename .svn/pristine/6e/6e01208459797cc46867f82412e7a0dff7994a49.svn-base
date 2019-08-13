<?php

use Phalcon\Di;
use Phalcon\DiInterface;
use Phalcon\Mvc\Model as BaseModel;
use GenialCloud\Database\Criteria;

class Model extends BaseModel {

    CONST MAX_REDIS_TTL_DAY = 86400;
    CONST MAX_REDIS_TTL_WEEK =  604800;

    /**
     * @param DiInterface $dependencyInjector
     * @return \GenialCloud\Database\Criteria
     */
    public static function query(DiInterface $dependencyInjector = null) {

        /**
         * Use the global dependency injector if there is no one defined
         */
        if(!is_object($dependencyInjector)) {
            $dependencyInjector = Di::getDefault();
        }

        /**
         * Gets Criteria instance from DI container
         */
        if(is_a($dependencyInjector, 'DiInterface')) {
            $criteria = $dependencyInjector->get("GenialCloud\\Database\\Criteria");
        } else {
            $criteria = new Criteria;
            $criteria->setDI($dependencyInjector);
        }

        $criteria->setModelName(get_called_class());

        return $criteria;
    }

    /**
     * @param array|null $data
     * @param array|null $whiteList
     * @return int
     */
    public function saveGetId($data=null, $whiteList=null) {
        if($this->create($data, $whiteList)) {
            return (int) $this->getWriteConnection()->lastInsertId();
        }
        return 0;
    }

    /**
     * 查找错误或者抛错
     *
     * @param null $parameters
     * @return BaseModel
     * @throws \GenialCloud\Exceptions\HttpException
     */
    public static function findFirstOrFail($parameters = null) {
        $r = self::findFirst($parameters);
        if(!$r) {
            abort(404);
        }
        return $r;
    }

    /**
     * 自增字段值
     *
     * @param $column
     * @param int $step
     * @return bool
     */
    public function increment($column, $step=1) {
        $this->$column += $step;
        return $this->update(null, [$column]);
    }

}