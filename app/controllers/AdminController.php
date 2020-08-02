<?php
namespace App\Controllers;
use Core\Controller;
use App\Model\Test;
class AdminController extends Controller {
    //public $layout = 'admin';
    public function actionGalleryIndex() {
        $model = new Test();
        return $this->render($model);
    }
}