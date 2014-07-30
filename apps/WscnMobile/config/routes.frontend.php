<?php

return array(
    '/' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'index',
    ),
    '/node/(\d+)' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'news',
        'action' => 'news',
        'id' => 1 
    ),
    '/markets' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'markets',
    ),
    '/markets/(\w+)' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'markets',
        'action' => 'quote',
        'id' => 1 
    ),
    '/livenews' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'livenews',
    ),
    '/livenews/(\d+)' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'livenews',
        'action' => 'livenews',
        'id' => 1 
    ),
    '/calendar' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'calendar',
    ),
    '/search' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'search',
    ),
    '/search/suggestion' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'search',
        'action' => 'suggestion',
    ),
    '/register' => array(
        'module' => 'WscnMobile',
        'controller' => 'register',
    ),
    '/register/:action' => array(
        'module' => 'WscnMobile',
        'controller' => 'register',
        'action' => 1,
    ),
    '/login' => array(
        'module' => 'WscnMobile',
        'controller' => 'login',
    ),
    '/login/:action' => array(
        'module' => 'WscnMobile',
        'controller' => 'login',
        'action' => 1,
    ),
    '/logout' => array(
        'module' => 'WscnMobile',
        'controller' => 'logout',
    ),
    '/session/:action' => array(
        'module' => 'WscnMobile',
        'controller' => 'session',
        'action' => 1,
    ),
);
