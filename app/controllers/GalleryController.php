<?php
namespace app\controllers;
use Exception;
use core\web\Controller;
use app\models\Gallery;
use app\models\Category;
class GalleryController extends Controller {
    public function actionIndex($category = null) {
        $dataGallery  = Gallery::byCategory($category);
        $dataCategory = Category::find()->all();
        return $this->render([
            'dataGallery'  => $dataGallery,
            'dataCategory' => $dataCategory,
        ]);
    }
    public function actionSingle(int $id) {
        $model = Gallery::findOne($id);
        if ($model == NULL) {
            throw new Exception('The request page not found!');
        }
        return $this->render($model);
    }
}