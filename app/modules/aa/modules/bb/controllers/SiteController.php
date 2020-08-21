<?php
namespace app\modules\aa\modules\bb\controllers;
class SiteController extends \core\web\Controller {
    public $defaultAction = 'view';
    public function actionView() {
        return $this->render();
    }
}