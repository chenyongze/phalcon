<?php

return array(
    '/admin/livenews/news' =>  array(
        'module' => 'EvaLivenews',
        'controller' => 'Admin\News',
    ),
    '/admin/livenews/news/:action(/(\d+))*' =>  array(
        'module' => 'EvaLivenews',
        'controller' => 'Admin\News',
        'action' => 1,
        'id' => 3,
    ),
    '/admin/livenews/data' =>  array(
        'module' => 'EvaLivenews',
        'controller' => 'Admin\Data',
    ),
    '/admin/livenews/data/:action(/(\d+))*' =>  array(
        'module' => 'EvaLivenews',
        'controller' => 'Admin\Data',
        'action' => 1,
        'id' => 3,
    ),
    '/admin/livenews/category' =>  array(
        'module' => 'EvaLivenews',
        'controller' => 'Admin\Category',
    ),
    '/admin/livenews/category/:action(/(\d+))*' =>  array(
        'module' => 'EvaLivenews',
        'controller' => 'Admin\Category',
        'action' => 1,
        'id' => 3,
    ),
    '/admin/livenews/process/:action(/(\d+)*((/(\w+)/(\d+))*))*' =>  array(
        'module' => 'EvaLivenews',
        'controller' => 'Admin\Process',
        'action' => 1,
        'id' => 3,
        'subaction' => 6,
        'subid' => 7,
    ),
);
