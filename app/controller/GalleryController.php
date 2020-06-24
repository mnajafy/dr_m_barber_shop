<?php
namespace App\Controller;

use Core\Controller;
use App\Model\Gallery;
use App\Model\Category;

class GalleryController extends Controller
{
    public function index()
    {
        $value = (isset($_GET['category'])) ? $_GET['category'] : null;
        $dataGallery = Gallery::byCategory($value);
        $dataCategory = Category::all('category', Category::class);

        $this->setTitle('Gallery');
        return $this->render('gallery/index', compact('dataGallery', 'dataCategory'));
    }

    public function single($id)
    {
        $data = Gallery::oneGallery('imgs', $id, Gallery::class);
        $this->setTitle('single');
        return $this->render('gallery/single', compact('data'));
    }
}