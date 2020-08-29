<?php
namespace core\validators;
use core\web\AssetBundle;
class ValidationAsset extends AssetBundle {
    public $js      = [
        'assets/libs/framework/framework.validation.js'
    ];
    public $depends = [
        'core\web\FrameworkAsset'
    ];
}