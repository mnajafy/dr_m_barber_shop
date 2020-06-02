<?php
namespace App\Controller;

use Core\Form;
use Core\Controller;

class HomeController extends Controller
{
    public function home()
    {
        $this->setTitle('home');
        return $this->render('index');
    }
}