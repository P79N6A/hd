<?php

use GenialCloud\Support\Task as BaseTask;

class Task extends BaseTask {

    protected function beforeRun() {
        $t = $this;
        DB::setCore(function() use ($t) {
            return $t->db;
        });
        Session::setCore(function() use ($t) {
            return $t->session;
        });
        $components = Config::get('components', []);
        foreach($components as $key => $component) {
            if(isset($component->alias)) {
                $alias = $component->alias;
                $alias::setCore(function() use ($t, $key) {
                    return $t->$key;
                });
            }
        }
    }

    public function error($msg) {
        $this->output($msg, 'error');
    }

    public function warning($msg) {
        $this->output($msg, 'warning');
    }

    public function notice($msg) {
        $this->output($msg, 'notice');
    }

    public function info($msg) {
        $this->output($msg, 'info');
    }

    public function debug($msg) {
        $this->output($msg, 'debug');
    }

    protected function output($msg, $type) {
        $color = '';
        switch($type) {
            case 'error':
                $color = '31;5m';
                break;
            case 'warning':
                $color = '33;4m';
                break;
            case 'notice':
                $color = '34m';
                break;
            case 'info':
                $color = '32m';
                break;
            case 'debug':
                $color = '2m';
                break;
        }
        $output = '['.strtoupper($type[0]).']['.date('Y-m-d H:i:s').']'.$msg;
        if($color) {
            $output = "\033[{$color}{$output}\033[0m";
        }
        echo $output, PHP_EOL;
    }

}