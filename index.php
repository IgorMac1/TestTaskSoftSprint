<?php
use core\Router;
include_once 'config/functions.php';
spl_autoload_register(function ($class){
    $path = str_replace('\\','/',$class.'.php');
    if (file_exists($path)){
        require $path;
    }
});
session_start();


$router = new Router();
$router->run();


