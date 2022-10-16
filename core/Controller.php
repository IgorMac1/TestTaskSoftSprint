<?php

namespace core;
abstract class Controller
{
    public $route;
    public $view;
    public $acl;

    public function __construct($route)
    {
        $this->route = $route;
        $this->view = new View($route);
        $this->model = $this->loadModel($route['controller']);
    }

    public function loadModel($name)
    {
        $path = 'app\model\\' . ucfirst($name);
        if (class_exists($path)) {
            return new $path;
        }
    }
}