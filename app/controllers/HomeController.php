<?php
namespace app\controllers;
use core\web\Controller;
class HomeController extends Controller {
    public $layout = 'main';
    public function actionIndex() {

        return $this->render([]);
    }
}