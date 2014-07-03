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
);
