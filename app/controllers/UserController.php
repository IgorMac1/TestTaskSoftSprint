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

        if ($user) {
            ApiResponse::response(200, ['user' => $user]);
        } else {
            ApiResponse::response(404, [], 'User Not Found');
        }
    }

    public function deleteUserAction()
    {

        $id = getRequestId();
        $user = $this->model->getUser($id);
        $result = $this->model->deleteUser($id);

        if ($result) {
            ApiResponse::response(200, ['user' => $user]);
        }else{
            ApiResponse::response(404, [], 'User Not Found');
        }
    }

    public function deleteUsersAction()
    {
        $params = getRequestData();
        $result = null;
        $user = [];
        if (empty($params['ids'])) {
            ApiResponse::response(417, [], 'Please provide users ids');
        }
        foreach ($params['ids'] as $id) {
            $user[] = $this->model->getUser($id);
            $result = $this->model->deleteUser($id);

        }

        if ($result) {
            ApiResponse::response(200, ['users' => $user]);
        }else{
            ApiResponse::response(404, [], 'User Not Found');
        }
    }

    public function changeStatusAction()
    {
        $params = getRequestData();
        $user = [];
        foreach ($params['ids'] as $id) {
            $user[] = $this->model->getUser($id);
        }

        $result = $this->model->changeUserStatus();


        if ($result) {
            ApiResponse::response(200, ['users' => $user]);
        } else {
            ApiResponse::response(404, [], 'User Not Found');
        }
    }
}



