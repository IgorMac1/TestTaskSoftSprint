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
            foreach ($routes as $key => $route) {
                $route['path'] = 'api/' . $route['path'];
                $this->add($route);
            }
        } else {
            $routes = require 'config/routes/web.php';
            foreach ($routes as $val) {
                $this->add($val);
            }
        }
    }

    public function add($params)
    {
        $params['path'] = '#^' . $params['path'] . '$#';
        $this->routes[] = $params;
    }

    public function match()
    {
        $url = trim($_SERVER['REQUEST_URI'], '/');
        $url = preg_replace('/\d+/', ':id', $url);
        $requestMethod = $_SERVER['REQUEST_METHOD'];
        foreach ($this->routes as $params) {
            if (preg_match($params['path'], $url, $matches) && $requestMethod === $params['method']) {
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

//        header('Location: /') ;
    }
}