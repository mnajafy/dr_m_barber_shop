<?php
namespace app\controllers;
use core\web\Controller;
class DefaultController extends Controller {
    public function actionIndex() {
        return $this->render();
    }
}