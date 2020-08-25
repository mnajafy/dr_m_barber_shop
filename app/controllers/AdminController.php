<?php
namespace app\controllers;
use Exception;
use Framework;
use core\web\Controller;
use core\data\ActiveDataProvider;
use app\models\Test;
class AdminController extends Controller {
    //public $layout = 'admin';
    public function actionUsersIndex() {
        $query  = Test::find();
        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort'  => ['defaultOrder' => ['id' => SORT_ASC]],
            'pagination' => ['defaultPageSize' => 5]
        ]);
        return $this->render(['dataProvider' => $dataProvider]);
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