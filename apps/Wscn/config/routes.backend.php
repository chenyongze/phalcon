<?php

return array(
    '/admin/appoption' =>  array(
        'module' => 'Wscn',
        'controller' => 'Admin\Appoption',
    ),
    '/admin/appoption/:action(/(\d+))*' =>  array(
        'module' => 'Wscn',
        'controller' => 'Admin\Appoption',
        'action' => 1,
        'id' => 3,
    ),
);
