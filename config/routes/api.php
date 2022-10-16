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
];
