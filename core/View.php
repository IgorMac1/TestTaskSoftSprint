<?php

namespace core;

class View
{
    public $path;
    public $route;
//    public $layout = 'default';

    public function __construct($route)
    {
        $this->route = $route;
        $this->path = $route['controller'] . '/' . $route['action'];
    }

    public function render($template, $vars = [])
    {
        extract($vars);
        if (file_exists('app/view/' . $template . '.php')) {
//            ob_start();
            require 'app/view/' . $template . '.php';
//            $content = ob_get_clean();

//            require 'app/view/main/' . $this->layout . '.php';
        } else {
            echo 'view not found';
        }
    }

    public static function errorCode($code)
    {
        if (file_exists('app/view/errors/' . $code . '.php')) {
            http_response_code($code);
            require 'app/view/errors/' . $code . '.php';
            exit;
        }
    }

    public function redirect($url)
    {
        header('Location:' . $url);
        exit();
    }
}