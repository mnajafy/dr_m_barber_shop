<?php
namespace App\Controllers;
use Core\Controller;
class ErrorController extends Controller {
    public function actionIndex($title = null, $message = null) {
        return $this->render([
            'title'   => $title,
            'message' => $message,
        ]);
    }
}