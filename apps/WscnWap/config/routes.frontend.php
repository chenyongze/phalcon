<?php

return array(
    '/' =>  array(
        'module' => 'WscnWap',
        'controller' => 'index',
    ),
    '/livenews' =>  array(
        'module' => 'WscnWap',
        'controller' => 'index',
        'action' => 'livenews',
    ),
    '/node/(\d+)' =>  array(
        'module' => 'WscnWap',
        'controller' => 'index',
        'action' => 'node',
        'id' => 1 
    ),
);
