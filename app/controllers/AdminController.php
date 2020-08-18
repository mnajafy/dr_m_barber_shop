<?php
namespace app\controllers;
use core\web\Controller;
use app\models\Test;
use Framework;
use Exception;
class AdminController extends Controller {
    //public $layout = 'admin';
    public function actionGalleryIndex() {
        $model = new Test();
        return $this->render($model);
    }
    public function actionGalleryView($id) {
        $model = $this->findGalley($id);
        return $this->render($model);
    }
    public function actionGalleryCreate() {
        $model = new Test();
        if ($model->load(Framework::$app->request->post()) && $model->save()) {
            return $this->redirect(['gallery-view', 'id' => $model->id]);
        }
        return $this->render($model);
    }
    public function actionGalleryUpdate($id) {
        $model = $this->findGalley($id);
        if ($model->load(Framework::$app->request->post()) && $model->save()) {
            return $this->redirect(['gallery-view', 'id' => $model->id]);
        }
        return $this->render($model);
    }
    public function actionGalleryDelete($id) {
        $model = $this->findGalley($id);
        $model->delete();
        return $this->redirect(['gallery-index']);
    }
    private function findGalley($id) {
        $model = Test::findOne($id);
        if ($model === null) {
            throw new Exception("The request page doesn't exist.");
        }
        return $model;
    }
}