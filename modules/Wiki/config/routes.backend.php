<?php

return array(

//    '/admin/category' =>  array(
//        'module' => 'EvaBlog',
//        'controller' => 'Admin\Category',
//    ),
//    '/admin/category/:action(/(\d+))*' =>  array(
//        'module' => 'EvaBlog',
//        'controller' => 'Admin\Category',
//        'action' => 1,
//        'id' => 3,
//    ),


    '/admin/wiki' =>  array(
        'module' => 'Wiki',
        'controller' => 'Admin\Entry',
        'action' => 'index'
    ),

    '/admin/wiki/:action' =>  array(
        'module' => 'Wiki',
        'controller' => 'Admin\Entry',
        'action' => 1
    ),
    '/admin/wiki/category' =>  array(
        'module' => 'Wiki',
        'controller' => 'Admin\Category'
    ),
    '/admin/wiki/category/:action(/(\d+))*' =>  array(
        'module' => 'Wiki',
        'controller' => 'Admin\Category',
        'action' => 1,
        'id' => 3,
    ),
//    '/admin/post/:action(/(\d+))*' =>  array(
//        'module' => 'EvaBlog',
//        'controller' => 'Admin\Post',
//        'action' => 1,
//        'id' => 3,
//    ),
//    '/admin/post/process/:action(/(\d+))*' =>  array(
//        'module' => 'EvaBlog',
//        'controller' => 'Admin\Process',
//        'action' => 1,
//        'id' => 3,
//    ),
);

