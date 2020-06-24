<?php

namespace Core;

use App\Controller\ErrorController;

class UrlManager
{
    private $url;
    private $rules = [];
    private $matches = [];

    public function __construct($rules)
    {
        $this->url();
        $this->rules = $rules;
    }

    private function url()
    {
        if (isset($_GET['url'])) 
        {
            $this->url = trim($_GET['url'], '/');
        }
        else
        {
            $this->url = '/';
        }
    }

    public function run()
    {
        foreach ($this->rules as $name => $classAction) 
        {
            if($this->match($this->url, $name))
            {
                return $this->call($classAction, $this->matches);
            }
        }
        (new ErrorController())->urlManagerError( ROOT . $this->url );
    }

    private function match($url, $name)
    {
        $name = preg_replace('#{([\w]+)}#', '([^/]+)', $name);

        $regex = "#^$name$#i";
        
        if (!preg_match($regex, $url, $matches)) 
        {
            return false;
        }
        
        array_shift($matches);

        $this->matches = $matches;
        return true;
    }

    private function call($classAction, $matches)
    {
        if(is_callable($classAction))
        {
            $class = new $classAction[0]();
            $action = $classAction[1];
            return call_user_func_array([$class, $action], $matches);
        }
        (new ErrorController())->urlManagerError(implode('->', $classAction));
    }
}

?>