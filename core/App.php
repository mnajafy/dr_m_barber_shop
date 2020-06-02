<?php

namespace Core;

class App
{
    private $config = [];
    private $url;

    public function __construct($config)
    {
        require 'core\Autoloader.php';
        \Autoloader::register();
        $this->config = $config;
        $this->url = new UrlManager($config['urlManager']['rules']);
    }

    public function load()
    {
        $this->url->run();
    }
}

?>