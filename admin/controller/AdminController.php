<?php
namespace Admin\Controller;
class AdminController {
    private $title      = "Hello world !";
    public $assetsPath  = 'http://localhost/dr_m_barber_shop/app/assets/';
    protected $viewPath = ROOT . 'app/view/';
    protected function render($view, $vars = []) {
        $urlPath = $this->viewPath . $view . 'View.phtml';
        if (file_exists($urlPath)):
            ob_start();
            extract($vars);
            require $urlPath;
            $content = ob_get_clean();
            require $this->viewPath . 'layoutView.phtml';
        else :
            (new ErrorController())->viewError('Error : file dont exist!');
        endif;
    }
    protected function redirectTo($path) {
        
    }
    public function link($path = null) {
        return 'http://localhost/dr_m_barber_shop/' . $path;
    }
    protected function setTitle($title) {
        $this->title = $title;
    }
    public function getTitle() {
        return $this->title;
    }
}
?>