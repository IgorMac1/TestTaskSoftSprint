<?php

namespace app\controllers;

use core\Controller;


class AccountController extends Controller
{
    public function loginAction()
    {
        if (!empty($_POST)) {
            $test = $this->model->getUser($_POST['email'], $_POST['pass']);
            if (empty($test)) {
                echo '<div class="wrong-pass"><h3>Wrong Email,or Pass</h3></h1></div>';
                $this->view->render('login');
                die();
            }
            $_SESSION['autorize']['id'] = $test['id'];
            $_SESSION['name'] = $test['name'];
            $_SESSION['email'] = $test['email'];
            $this->view->redirect('/user');

        }
        $this->view->render('login');
    }

    public function registerAction()
    {
        $country = $this->model->getCountry();
        if (isset($_SESSION['autorize']['id'])){
            $this->view->redirect('/user');
        }
        if (!empty($_POST) && $_POST['pass'] === $_POST['confirmPass'] && isset($_POST['agree']) &&
            $this->model->checkUniqueEmail($_POST['email'])['email'] != $_POST['email'] &&
            $this->model->checkUniqueLogin($_POST['login'])['login'] != $_POST['login']) {
            $this->model->addNewUser($_POST);
            $this->view->redirect('/login');
        } elseif (!empty($_POST) && $_POST['pass'] !== $_POST['confirmPass']) {
            echo '<div class="wrong-pass"><h3>Wrong Pass</h3></h1></div>';
            $this->view->render('Registration', ['country' => $country]);
        } elseif (!empty($_POST) && $this->model->checkUniqueEmail($_POST['email'])['email'] === $_POST['email'] ){
            echo '<div class="wrong-pass"><h3>email ' . $_POST['email'] . ' already exists </h3></h1></div>';
            $this->view->render('Registration', ['country' => $country]);
        }elseif (!empty($_POST) && $this->model->checkUniquelogin($_POST['login'])['login'] === $_POST['login'] ){
            echo '<div class="wrong-pass"><h3>login ' . $_POST['login'] . ' already exists </h3></h1></div>';
            $this->view->render('Registration', ['country' => $country]);
        }
        else {
            $this->view->render('Registration', ['country' => $country]);
        }
    }
    public function userAction()
    {
        if (isset($_POST['logOut']) && $_POST['logOut'] === 'Log Out') {
            $_SESSION = [];
            $this->view->redirect('/');
        }
        $this->view->render('userPage');
    }
}
