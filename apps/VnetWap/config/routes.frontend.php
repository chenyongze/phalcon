<?php

return array(
    '/' =>  array(
        'module' => 'VnetWap',
        'controller' => 'index',
    ),
    '/markets' =>  array(
        'module' => 'VnetWap',
        'controller' => 'markets',
    ),
    '/markets/(\w+)' =>  array(
        'module' => 'VnetWap',
        'controller' => 'markets',
        'action' => 'quote',
        'id' => 1 
    ),
    '/livenews' =>  array(
        'module' => 'VnetWap',
        'controller' => 'livenews',
    ),
    '/direct' =>  array(
        'module' => 'VnetWap',
        'controller' => 'index',
        'action' => 'live',
    ),
    '/recommend' =>  array(
        'module' => 'VnetWap',
        'controller' => 'index',
        'action' => 'recommend',
    ),
);
