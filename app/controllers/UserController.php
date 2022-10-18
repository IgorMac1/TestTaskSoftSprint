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



        $newUser = $this->model->addNewUser($_POST);

        if ($newUser) {
            ApiResponse::response(200, ['user' => ['name'=>$_POST['name'],'surname'=>$_POST['surname'],'status'=>$_POST['status']]]);

        } else {
            ApiResponse::response(417, [], 'form validation failed');
        }
    }


    public function editAction()
    {

    }


    public function setActiveAction()
    {
        $setActive = false;

        if ($_POST['action'] === 'Set active') {
            foreach ($_POST['id'] as $value) {
                $setActive = $this->model->setActive($value);
            }
        } elseif ($_POST['action'] === 'Set not active') {
            foreach ($_POST['id'] as $value) {
                $setActive = $this->model->setNotActive($value);
            }
        } elseif ($_POST['action'] === 'Delete') {
            foreach ($_POST['id'] as $value) {
                $setActive = $this->model->deleteUsers($value);
            }
        } else return false;


        if ($setActive) {
            ApiResponse::response(200, []);

        } else {
            ApiResponse::response(417, [], 'form validation failed');
        }

    }



}



