<?php
namespace App\Controllers;
use Core\Controller;
class HomeController extends Controller {
    public $layout = 'main';
    public function actionIndex() {

        return $this->render();
    }
}