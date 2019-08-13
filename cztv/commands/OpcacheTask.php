<?php

class OpcacheTask extends Task {

    public function clearAction() {
        if (function_exists('opcache_reset')) {
            opcache_reset();
            echo "Done.";
        } else {
            echo "The Zend Extension Opcache is not valid now.";
        }
        echo PHP_EOL;
    }

}