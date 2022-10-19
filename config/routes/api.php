<?php
return [
    [
        'path' => 'users',
        'controller' => 'user',
        'action' => 'add',
        'method' => 'POST',
    ],
    [
        'path' => 'users/:id',
        'controller' => 'user',
        'action' => 'edit',
        'method' => 'PUT',
    ],
    [
        'path' => 'users/:id',
        'controller' => 'user',
        'action' => 'deleteUser',
        'method' => 'DELETE',
    ],
    [
        'path' => 'users/delete',
        'controller' => 'user',
        'action' => 'deleteUsers',
        'method' => 'DELETE',
    ],
    [
        'path' => 'users/change-status',
        'controller' => 'user',
        'action' => 'changeStatus',
        'method' => 'POST',
    ],
];
