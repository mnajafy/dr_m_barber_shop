<?php
namespace app\modules\aa;
class Module extends \core\web\Module {
    public $defaultRoute = 'default';
    public $_modules = [
        'bb' => ['class' => 'app\modules\aa\modules\bb\Module']
    ];
    public function init() {
        parent::init();
        //$this->setLayoutPath(__DIR__ . DIRECTORY_SEPARATOR . 'layouts');
    }
}