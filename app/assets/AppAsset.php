<?php
namespace app\assets;
use core\web\AssetBundle;
/**
 * AppAsset
 */
class AppAsset extends AssetBundle {
    public $css = [
        'assets/css/main.css',
        'assets/css/style.css',
        'assets/libs/fontawesome/css/fontawesome-all.min.css',
    ];
    public $js  = [
        'assets/js/jquery-3.5.1/jquery.js',
        'assets/libs/framework/framework.js',
        'assets/js/main.js',
    ];
}