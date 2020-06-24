<?php

namespace Core;

class App
{
    private $url;

    public function __construct()
    {
        $rules = require 'app/config/rules.php';
        require 'core\Autoloader.php';
        \Autoloader::register();
        $this->url = new UrlManager($rules);
    }

    public function load()
    {
        $this->url->run();
    }
}

?>