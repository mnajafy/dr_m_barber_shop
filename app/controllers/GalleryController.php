<?php
namespace App\Controllers;
use Core\Controller;
use App\Model\Gallery;
use App\Model\Category;
class GalleryController extends Controller {
    public function actionIndex($category = null) {
        $dataGallery  = Gallery::byCategory($category);
        $dataCategory = Category::all();
        return $this->render([
            'dataGallery'  => $dataGallery,
            'dataCategory' => $dataCategory,
        ]);
    }
    public function actionSingle($id) {
        $model = Gallery::one($id);
        return $this->render([
            'model' => $model
        ]);
    }
}