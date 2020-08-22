<?php
namespace app\controllers;
use Framework;
use core\web\Controller;
use app\models\LoginForm;
class AuthController extends Controller {
    public function actionLogin() {
        $model = new LoginForm();
        if ($model->load(Framework::$app->request->post()) && $model->login()) {
            return $this->redirect(['home/index']);
        }
        return $this->render($model);
    }
    public function actionLogout() {
        Framework::$app->user->logout();
        return $this->redirect(['home/index']);
    }
}