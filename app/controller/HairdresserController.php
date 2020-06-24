<?php
namespace App\Controller;

use Core\Controller;

class HairdresserController extends Controller
{
    public function index()
    {
        $this->setTitle('hairdresser');
        return $this->render('hairdresser');
    }
}