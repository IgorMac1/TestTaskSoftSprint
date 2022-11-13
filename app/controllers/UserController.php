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
            ApiResponse::response(200,true, ['user' => $newUser]);
        } else {
            ApiResponse::response(417, [], 'DB error');
        }
    }

    public function editAction()
    {
        $user = $this->model->editUser();
        if ($user) {
            ApiResponse::response(200,true, ['user' => $user]);
        } elseif ($user === null) {
            ApiResponse::response(100,false, ['user' => null], 'not found user');
        }else{
            ApiResponse::response(417,false, [], 417,'DB error');
        }
    }

    public function deleteUserAction()
    {
        $id = getRequestId();
        $user = $this->model->getUser($id);
        $result = $this->model->deleteUser($id);
        if ($result) {
            ApiResponse::response(200,true, ['user' => $user]);
        }elseif ($result === null){
            ApiResponse::response(200,false, ['user' => null],100,'not found user');
        }else{
            ApiResponse::response(417,false, [], 417,'DB error');
        }
    }

    public function deleteUsersAction()
    {
        $params = getRequestData();

        $result = null;
        $user = [];

        $userNotFoundId = [];

        if (empty($params['ids'])) {
            ApiResponse::response(417,false, ['user' => null], 417,'Please provide users ids');
        }
        foreach ($params['ids'] as $id) {

            if (!$this->model->getUser($id)){
                $user[] = [$id => null];
                $userNotFoundId[] = $id;
            }elseif($this->model->getUser($id)){
                $user[] = $this->model->getUser($id);
            }
            $result = $this->model->deleteUser($id);
        }

        if (!empty($userNotFoundId)){
            ApiResponse::response(200,false, ['users' => $user],100, "not found user",$userNotFoundId);
        }elseif ($result){
            ApiResponse::response(200,true,['users' => $user]);
        }else {
            ApiResponse::response(417,false, [],417, 'DB error');
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
                $user[] = [$id => null];
                $userNotFoundId[]= $id;
            }elseif($this->model->getUser($id)){
                $user[] = $this->model->getUser($id);
            }
        }
        if (!empty($userNotFoundId)){
            ApiResponse::response(200,false, ['users' => $user], 100,"not found user",$userNotFoundId);
        }elseif ($result){
            ApiResponse::response(200,true, ['users' => $user]);
        }else {
            ApiResponse::response(417,false, [],417, 'DB error');
        }
    }
}



