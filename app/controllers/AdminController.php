<?php
namespace App\Controllers;
use Core\Controller;
use App\Model\Test;
use Framework;
class AdminController extends Controller {
    //public $layout = 'admin';
    public function actionGalleryIndex() {
        $model = new Test();
        return $this->render($model);
    }
    public function actionGalleryCreate() {
        $model = new Test();
        if ($model->load(Framework::$app->request->post()) && $model->save()) {
            return $this->redirect(['index']);
        }
        return $this->render($model);
    }
}