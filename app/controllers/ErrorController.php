<?php
namespace app\controllers;
use core\web\Controller;
class ErrorController extends Controller {
    public function actionIndex($title = null, $file = null, $line = null, $message = null) {
        return $this->render([
            'title'   => $title,
            'file'    => $file,
            'line'    => $line,
            'message' => $message,
        ]);
    }
}