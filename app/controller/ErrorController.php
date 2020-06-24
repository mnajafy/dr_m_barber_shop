<?php
namespace App\Controller;

use Core\Controller;

class ErrorController extends Controller
{
    public function urlManagerError($msgErr = null)
    {
        $this->setTitle('error');
        return $this->render('error', compact('msgErr'));
    }

    public function viewError($msgErr = null)
    {
        $this->setTitle('error view');
        return $this->render('error', compact('msgErr'));
    }
}
?>