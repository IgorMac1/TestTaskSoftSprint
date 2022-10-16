<?php

namespace app\controllers;
use core\ApiResponse;
use core\Controller;

class UserController extends Controller
{
    public function indexAction()
    {
        $users = $this->model->getAllUsers();

        $this->view->render('main', ['users' => $users]);
    }

    public function addAction()
    {
        $newUser = null;

        if ($newUser) {
            ApiResponse::response(200, ['user' => $newUser]);
        } else {
            ApiResponse::response(417, [], 'form validation failed');
        }
    }

}