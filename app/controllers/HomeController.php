<?php
namespace App\Controllers;
use Core\Controller;
class HomeController extends Controller {
    public function actionIndex() {

        return $this->render();
    }
}