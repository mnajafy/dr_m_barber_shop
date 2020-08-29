<?php
namespace core\web;
class FrameworkAsset extends AssetBundle {
    public $js = [
        'assets/libs/framework/framework.js'
    ];
    public $depends = [
        'core\jquery\JqueryAsset'
    ];
}