<?php

require_once APP_PATH . 'libraries/Smarty/Smarty.class.php';

class MemcacheSmartyResource extends Smarty_Resource_Custom {

    protected $domainId;

    public function __construct($domain_id) {
        $this->domainId = $domain_id;
    }

    /**
     * 从数据库中获取一个模板内容及修改时间。
     *
     * @param string $name 模板名称
     * @param string $source 引用的模板资源
     * @param integer $mtime 引用的模板修改时间戳
     * @return void
     */
    protected function fetch($name, &$source, &$mtime) {
//        $row = MemcacheIO::get('smarty:'.$this->domainId.':'.$name);
        $row = Templates::query()
            ->andWhere('domain_id = :domain_id: AND path = :path: AND status = 1', ['path' => $name, 'domain_id' => $this->domainId])
            ->first();
        if ($row) {
            $row = $row->toArray();
            $source = $row['content'];
            $mtime = $row['updated_at'];
        } else {
            $source = null;
            $mtime = null;
        }
    }

}