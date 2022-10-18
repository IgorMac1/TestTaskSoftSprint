<?php
return [
    'users' => [
        'controller' => 'user',
        'action' => 'add',
        'method' => 'POST',
    ],
    'users/:id' => [
        'controller' => 'user',
        'action' => 'edit',
        'method' => 'PUT',
    ],

    'user/:id' => [
        'controller' => 'user',
        'action' => 'delete',
        'method' => 'POST',
    ],

    'setActive' => [
        'controller' => 'user',
        'action' => 'setActive',
        'method' => 'POST',
    ],



];
