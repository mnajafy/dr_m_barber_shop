<?php
class Autoloader {
    static function register() {
        spl_autoload_register([__CLASS__, 'autoload']);
    }
    static function autoload($name) {
        $filename = str_replace('\\', '/', $name) . '.php';
        if (file_exists($filename)) {
            require $filename;
        }
    }
}

require 'Framework.php';
Autoloader::register();