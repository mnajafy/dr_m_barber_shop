<?php

namespace Core;

use Error\ErrorController;

class Controller
{
    private $title = "Hello world !";
    public $assetsPath = 'app/assets/';
    protected $viewPath = ROOT . 'app/view/';

    protected function render($view, $vars = [])
    {
        $urlPath = is_array($view) ? $view[0] . $view[1] . 'View.phtml': $this->viewPath . $view . 'View.phtml';
        
        if(file_exists($urlPath)):
            ob_start();
            extract($vars);
            require $urlPath;
            $content = ob_get_clean();
            require $this->viewPath . 'layout' . 'View.phtml';
        else :
            (new ErrorController())->viewError('Error : file dont exist!');
        endif;
    }

    protected function redirectTo($path)
    {

    }

    public function link($path)
    {
        return ROOT . $path;
    }

    protected function setTitle($title)
    {
        $this->title = $title;
    }

    public function getTitle()
    {
        return $this->title;
    }

    public function createForm($type, $entity)
    {

    }
}

?>