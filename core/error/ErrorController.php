<?php
namespace Core\Error;

use Core\Controller;

class ErrorController extends Controller
{
    public function urlManagerError(array $msgErr)
    {
        return $this->render([ROOT, 'core/error/index'], compact('msgErr'));
    }
}
?>