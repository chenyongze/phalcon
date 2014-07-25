<?php

return array(
    '/' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'index',
    ),
    '/livenews' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'livenews',
    ),
    '/news' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'news',
    ),
    '/news/(\d+)' =>  array(
        'module' => 'WscnMobile',
        'controller' => 'news',
        'action' => 'news',
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
);
