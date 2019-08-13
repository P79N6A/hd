<?php

class IndexController extends BaseController {

    public function indexAction() {
        View::disable();
        header('Content-Type: application/yaml');
        $this->readHeader();
        $this->readPaths();
        $this->readDefinitions();
        $this->readParameters();
    }

    protected function readHeader() {
        $this->readYaml('header.yaml');
    }

    protected function readPaths() {
        echo 'paths:', PHP_EOL;
        $this->readYamlDir('paths', '  ');
    }

    protected function readDefinitions() {
        echo 'definitions:', PHP_EOL;
        $this->readYamlDir('definitions', '  ');
    }

    protected function readParameters() {
        echo 'parameters:', PHP_EOL;
        $this->readYamlDir('parameters', '  ');
    }

    protected function readYamlDir($path, $line_pre = '') {
        $p = site_path('resources/').$path;
        if(is_dir($p)) {
            $r = opendir($p);
            if(!$r) {
                throw new \Phalcon\Exception('Can not open directory: '.$path);
            }
            while($i = readdir($r)) {
                if($i == '.' || $i == '..') {
                    continue;
                }
                if(is_dir($p.'/'.$i)) {
                    $this->readYamlDir($path.'/'.$i, $line_pre);
                } else {
                    $this->readYaml($path.'/'.$i, $line_pre);
                }
            }
        } else {
            throw new \Phalcon\Exception('Not a directory: '.$path);
        }
    }

    protected function readYaml($path, $line_pre = '') {
        $p =  site_path('resources/').$path;
        $f = fopen($p, 'r');
        if(!$f) {
            throw new \Phalcon\Exception('Can not read YAML file: '.$path);
        }
        while($line = rtrim(fgets($f))) {
            if($line) {
                echo $line_pre, $line, PHP_EOL;
            }
        }
        fclose($f);
    }

}