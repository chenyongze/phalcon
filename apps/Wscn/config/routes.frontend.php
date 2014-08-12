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

    '/stars' =>  array(
        'module' => 'Wscn',
        'controller' => 'stars',
    ),

    'getstar' =>  array(
        'pattern' => '/stars/(\d+)',
        'paths' => array(
            'module' => 'Wscn',
            'controller' => 'stars',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'putstar' =>  array(
        'pattern' => '/stars/(\d+)',
        'paths' => array(
            'module' => 'Wscn',
            'controller' => 'stars',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'deletestar' =>  array(
        'pattern' => '/stars/(\d+)',
        'paths' => array(
            'module' => 'Wscn',
            'controller' => 'stars',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
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
    '/session/reset/(\w+)/(\w+)' => array(
        'module' => 'Wscn',
        'controller' => 'session',
        'action' => 'reset',
        'username' => 1,
        'code' => 2,
    ),
    '/session/verify/(\w+)/(\w+)' => array(
        'module' => 'Wscn',
        'controller' => 'session',
        'action' => 'verify',
        'username' => 1,
        'code' => 2,
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

    '/widget/:action' =>  array(
        'module' => 'Wscn',
        'controller' => 'widget',
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

    '/mine' => array(
        'module' => 'Wscn',
        'controller' => 'mine',
    ),
    '/mine/:action' => array(
        'module' => 'Wscn',
        'controller' => 'mine',
        'action' => 1,
    ),
);
