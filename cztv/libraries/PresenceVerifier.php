<?php

class PresenceVerifier implements Illuminate\Validation\PresenceVerifierInterface {

    /**
     * Count the number of objects in a collection having the given value.
     *
     * @param  string  $collection
     * @param  string  $column
     * @param  string  $value
     * @param  int     $excludeId
     * @param  string  $idColumn
     * @param  array   $extra
     * @return int
     */
    public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = array()) {
        $sql = "SELECT COUNT(*) as `c` FROM `{$collection}` WHERE `{$column}`=".q($value);
        if(!$idColumn) {
            $idColumn = 'id';
        }
        if($excludeId) {
            $sql .= " AND `{$idColumn}` != ".q($excludeId);
        }
        foreach($extra as $c => $v) {
            $sql .= " AND `{$c}` = $v";
        }
        $sql .= ';';
        return DB::query($sql)->fetchAll()[0]['c'];
    }

    /**
     * Count the number of objects in a collection with the given values.
     *
     * @param  string  $collection
     * @param  string  $column
     * @param  array   $values
     * @param  array   $extra
     * @return int
     */
    public function getMultiCount($collection, $column, array $values, array $extra = array()) {
        throw new \Phalcon\Mvc\Model\Exception('PresenceVerifier::getMultiCount() not implemented.');
    }

}