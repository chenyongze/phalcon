<?php

return array(
    '/thread' =>  array(
        'module' => 'EvaComment',
        'controller' => 'thread',
    ),


    '/thread/(\w+)/comments' => array(
        'module' => 'EvaComment',
        'controller' => 'thread',
        'action' => 'getThreadComments',
        'uniqueKey' => 1,
    ),

    '/thread/(\w+)/comments/save' => array(
        'module' => 'EvaComment',
        'controller' => 'thread',
        'action' => 'postThreadComments',
        'threadKey' => 1,
    ),

    '/thread/(\w+)/comments/new' => array(
        'module' => 'EvaComment',
        'controller' => 'thread',
        'action' => 'newThreadComments',
        'threadKey' => 1,
    ),

    '/thread/:action' =>  array(
        'module' => 'EvaComment',
        'controller' => 'thread',
        'action' => 1,
    ),

    '/comment/(\d+)/up' => array(
        'module' => 'EvaComment',
        'controller' => 'vote',
        'action' => 'up',
        'commentId' => 1,
    ),

    '/comment/(\d+)/down' => array(
        'module' => 'EvaComment',
        'controller' => 'vote',
        'action' => 'down',
        'commentId' => 1,
    ),

    '/thread/user/votes' => array(
        'module' => 'EvaComment',
        'controller' => 'vote',
        'action' => 'userVotes',
    ),


);

