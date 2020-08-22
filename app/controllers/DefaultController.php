<?php
namespace app\controllers;
use core\web\Controller;
class DefaultController extends Controller {
    public function actionError($exception) {
        return $this->render(['exception' => $exception]);
    }
    public function actionIndex() {
        return $this->render();
    }
}