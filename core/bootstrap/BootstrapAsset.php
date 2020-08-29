<?php
namespace core\bootstrap;
use core\web\AssetBundle;
class BootstrapAsset extends AssetBundle {
    public $css = [
        'assets/libs/bootstrap/css/bootstrap.min.css'
    ];
    public $js = [
        'assets/libs/bootstrap/js/bootstrap.min.js'
    ];
    public $depends = [
        'core\jquery\JqueryAsset'
    ];
}