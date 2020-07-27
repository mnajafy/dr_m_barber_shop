<?php
namespace App\Controllers;
use Core\Controller;
use App\Model\Gallery;
use App\Model\Category;
use Exception;
class GalleryController extends Controller {
    public function actionIndex($category = null) {
        $dataGallery  = Gallery::byCategory($category);
        $dataCategory = Category::all();
        return $this->render([
            'dataGallery'  => $dataGallery,
            'dataCategory' => $dataCategory,
        ]);
    }
    public function actionSingle(int $id) {
        $model = Gallery::one($id);
        if ($model == NULL) {
            throw new Exception('The request page not found!');
        }
        return $this->render($model);
    }
}