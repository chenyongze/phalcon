<?php

return array(
    '/admin/comment' =>  array(
        'module' => 'EvaComment',
        'controller' => 'Admin\Comment',
    ),
//    '/admin/comment' =>  array(
//        'module' => 'EvaComment',
//        'controller' => 'Admin\Comment',
//    ),
    '/admin/comment/process/:action(/(\d+))*' =>  array(
        'module' => 'EvaComment',
        'controller' => 'Admin\Process',
        'action' => 1,
        'id' => 3,
    ),
    '/admin/thread/post/(\d+)*' =>  array(
        'module' => 'EvaComment',
        'controller' => 'Admin\Thread',
        'action' => 'post',
        'postId' => 1,
    ),
);

