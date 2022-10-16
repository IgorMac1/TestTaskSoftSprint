<?php

namespace core;

class Router
{
    protected $routes = [];
    protected $params = [];

    function __construct()
    {
        if (strpos($_SERVER['REQUEST_URI'], '/api/') === 0) {
            $routes = require 'config/routes/api.php';
            foreach ($routes as $key => $val) {
                $this->add('api/' . $key, $val);
            }
            // check request method
        } else {
            $routes = require 'config/routes/web.php';
            foreach ($routes as $key => $val) {
                $this->add($key, $val);
            }
        }
    }

    public function add($route, $params)
    {
        $route = '#^' . $route . '$#';
        $this->routes[$route] = $params;
    }

    public function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                $this->params = $params;
                return true;
            }
        }
        return false;
    }

    public function run()
    {
        if ($this->match()) {
            $path = 'app\controllers\\' . ucfirst($this->params['controller']) . 'Controller';

            if (class_exists($path)) {
                $action = $this->params['action'] . 'Action';
                if (method_exists($path, $action)) {
                    $controller = new $path($this->params);
                    $controller->$action();
                    return;
                }
            }
        }

        header('Location: /') ;
    }
}