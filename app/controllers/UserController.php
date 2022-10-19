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
        $result = $this->model->deleteUser($id);

        if ($result) {
            ApiResponse::response(200, []);
        }

        ApiResponse::response(404, [], 'User Not Found');
    }

    public function deleteUsersAction()
    {
        $params = getRequestData();
        $result = null;

        if (empty($params['ids'])) {
            ApiResponse::response(417, [], 'Please provide users ids');
        }
        foreach ($params['ids'] as $id) {
            $result = $this->model->deleteUser($id);
        }

        if ($result) {
            ApiResponse::response(200, []);
        }

        ApiResponse::response(417, [], 'DB error');
    }


    public function changeStatusAction()
    {
        $result = $this->model->changeUserStatus();

        if (empty($result['message'])) {
            ApiResponse::response(200, $result);
        } else {
            ApiResponse::response(417, [], $result['message']);
        }

    }



}



