<?php
namespace core\widgets;
use core\web\AssetBundle;
class ActiveFormAsset extends AssetBundle {
    public $js = [
        'assets/libs/framework/framework.activeForm.js'
    ];
    public $depends = [
        'core\web\FrameworkAsset'
    ];
}