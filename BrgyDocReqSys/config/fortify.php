<?php

use Laravel\Fortify\Features;

return [
    'guard' => 'web',
    'passwords' => 'users',
    'username' => 'email',
    'email' => 'email',
    'home' => '/dashboard',
    'middleware' => ['web'],
    'limiters' => [
        'login' => 'login',
    ],
    'features' => [
        Features::registration(),
        Features::resetPasswords(),
    ],
];
