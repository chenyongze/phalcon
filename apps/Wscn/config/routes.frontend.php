<?php

return array(
    '/' =>  array(
        'module' => 'Wscn',
        'controller' => 'index',
        'action' => 'index'
    ),
    '/node' =>  array(
        'module' => 'Wscn',
        'controller' => 'node',
    ),
    '/node/(\d+)' =>  array(
        'module' => 'Wscn',
        'controller' => 'node',
        'action' => 'node',
        'id' => 1,
    ),
    '/news' =>  array(
        'module' => 'Wscn',
        'controller' => 'news',
    ),
    '/news/:action(/(\d+))*' =>  array(
        'module' => 'Wscn',
        'controller' => 'news',
        'action' => 1,
        'id' => 3,
    ),


    '/livenews' =>  array(
        'module' => 'Wscn',
        'controller' => 'livenews',
    ),
    '/livenews/:action(/(\d+))*' =>  array(
        'module' => 'Wscn',
        'controller' => 'livenews',
        'action' => 1,
        'id' => 3,
    ),

    '/register' => array(
        'module' => 'Wscn',
        'controller' => 'register',
    ),
    '/register/:action' => array(
        'module' => 'Wscn',
        'controller' => 'register',
        'action' => 1,
    ),
    '/login' => array(
        'module' => 'Wscn',
        'controller' => 'login',
    ),
    '/login/:action' => array(
        'module' => 'Wscn',
        'controller' => 'login',
        'action' => 1,
    ),
    '/logout' => array(
        'module' => 'Wscn',
        'controller' => 'logout',
    ),
    '/session/:action' => array(
        'module' => 'Wscn',
        'controller' => 'session',
        'action' => 1,
    ),

    '/auth/:action/(\w+)/(oauth1|oauth2)*' =>  array(
        'module' => 'Wscn',
        'controller' => 'auth',
        'action' => 1,
        'service' => 2,
        'auth' => 3,
    ),

    '/auth/:action' =>  array(
        'module' => 'Wscn',
        'controller' => 'auth',
        'action' => 1,
    ),

    '/test/:action(/(\d+))*' =>  array(
        'module' => 'Wscn',
        'controller' => 'test',
        'action' => 1,
        'id' => 3,
    ),
    '/wiki/(.+)' =>  array(
        'module' => 'Wscn',
        'controller' => 'wiki',
        'action' => 'wiki',
        'keyword' => 1,
    ),

    '/me' =>  array(
        'module' => 'Wscn',
        'controller' => 'me',
        'action' => 'index'
    ),
);
