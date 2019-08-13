<?php

/**
 * Created by PhpStorm.
 * User: yantengwei
 * Date: 16/1/20
 * Time: 下午2:00
 */
trait BackendLogger {
    public function initialize() {
        if (!isset($this->notBackendLog)) {
            $this->keepSnapshots(true);
        }
    }

    public function afterSave() {
        if (!isset($this->notBackendLog)) {
            $old = $this->getSnapshotData();
            if ($old == null) {
                $type = BackendLogs::INSERT;
                $arr_new['id'] = $this->id;
                $this->remark($arr_new, $type);
            } else {
                $type = BackendLogs::UPDATE;
                $arr_old['id'] = $old['id'];
                $this->remark($arr_old, $type);
            }
            BackendLogs::toLog();
        }
    }

    public function afterDelete() {
        if (!isset($this->notBackendLog)) {
            $old = $this->getSnapshotData();
            $type = BackendLogs::DELETE;
            $this->remark($old, $type);
            BackendLogs::toLog();
        }
    }

    public function remark($old, $type) {
        $GLOBALS["backend_logs_type"] = $type;
        $GLOBALS["backend_logs_remark"][$this->getSource()] = $old;
    }
}