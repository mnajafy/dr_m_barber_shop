<?php
namespace app\controllers;
use core\web\Controller;
use app\models\Test;
use Framework;
use Exception;
class AdminController extends Controller {
    //public $layout = 'admin';
    public function actionUsersIndex() {
        $models = Test::find()->all();
        return $this->render(['models' => $models]);
    }
    public function actionUsersView($id) {
        $model = $this->findGalley($id);
        return $this->render($model);
    }
    public function actionUsersCreate() {
        $model = new Test();
        if ($model->load(Framework::$app->request->post()) && $model->save()) {
            return $this->redirect(['users-view', 'id' => $model->id]);
        }
        return $this->render($model);
    }
    public function actionUsersUpdate($id) {
        $model = $this->findGalley($id);
        if ($model->load(Framework::$app->request->post()) && $model->save()) {
            return $this->redirect(['users-view', 'id' => $model->id]);
        }
        return $this->render($model);
    }
    public function actionUsersDelete($id) {
        $model = $this->findGalley($id);
        $model->delete();
        return $this->redirect(['users-index']);
    }
    private function findGalley($id) {
        $model = Test::findOne($id);
        if ($model === null) {
            throw new Exception("The request page doesn't exist.");
        }
        return $model;
    }
}