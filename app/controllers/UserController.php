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
        $newUser = $this->model->addNewUser();

        if ($newUser) {
            ApiResponse::response(200, ['user' => $newUser]);
        } else {
            ApiResponse::response(417, [], 'DB error');
        }
    }

    public function editAction()
    {
        $user = $this->model->editUser();
        $id = getRequestId();
        if ($user) {
            ApiResponse::response(200, ['user' => $user]);
        } elseif ($user === null) {
            ApiResponse::response(200, [], 'User Not Found',false,$id);
        }else{
            ApiResponse::response(417, [], 'DB error');
        }
    }

    public function deleteUserAction()
    {
        $id = getRequestId();
        $user = $this->model->getUser($id);
        $result = $this->model->deleteUser($id);
        $userNotFoundId = [];
        if ($result) {
            ApiResponse::response(200, ['user' => $user]);
        }elseif ($result === null){
            $userNotFoundId[] = $id;
            ApiResponse::response(200, [], 'User Not Found',false,$userNotFoundId);
        }else{
            ApiResponse::response(417, [], 'DB error');
        }
    }

    public function deleteUsersAction()
    {
        $params = getRequestData();

        $result = null;
        $user = [];

        $userNotFoundId = [];

        if (empty($params['ids'])) {
            ApiResponse::response(417, [], 'Please provide users ids');
        }
        foreach ($params['ids'] as $id) {
            $user[] = $this->model->getUser($id);
            if (!$this->model->getUser($id)){
                $userNotFoundId[] = $id;
            }
            $result = $this->model->deleteUser($id);
        }

        if (!empty($userNotFoundId)){
            ApiResponse::response(200, ['users' => $user], "User(s) Not Found",false,$userNotFoundId);
        }elseif ($result){
            ApiResponse::response(200, ['users' => $user]);
        }else {
            ApiResponse::response(417, [], 'DB error');
        }
    }

    public function changeStatusAction()
    {
        $params = getRequestData();
        $userNotFoundId = [];
        $user = [];
        $result = $this->model->changeUserStatus();
        foreach ($params['ids'] as $id) {
            if (!$this->model->getUser($id)){
                $userNotFoundId[]= $id;
            }
            $user[] = $this->model->getUser($id);
        }


        if (!empty($userNotFoundId)){
            ApiResponse::response(200, ['users' => $user], "User(s) Not Found",false,$userNotFoundId);
        }elseif ($result){
            ApiResponse::response(200, ['users' => $user]);
        }else {
            ApiResponse::response(417, [], 'DB error');
        }
    }
}



