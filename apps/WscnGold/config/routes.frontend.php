<?php

return array(
    '/' =>  array(
        'module' => 'WscnGold',
        'controller' => 'index',
        'action' => 'index'
    ),
    '/news' =>  array(
        'module' => 'WscnGold',
        'controller' => 'news',
    ),
    '/news/:action(/(\d+))*' =>  array(
        'module' => 'WscnGold',
        'controller' => 'news',
        'action' => 1,
        'id' => 3,
    ),
    '/post/([\w-_]+)' =>  array(
        'module' => 'WscnGold',
        'controller' => 'post',
        'action' => 'article',
        'id' => 1,
    ),
    '/livenews' =>  array(
        'module' => 'WscnGold',
        'controller' => 'livenews',
    ),
    '/calendar' =>  array(
        'module' => 'WscnGold',
        'controller' => 'calendar',
    ),
    '/market' =>  array(
        'module' => 'WscnGold',
        'controller' => 'market',
    ),
    '/tutorial' =>  array(
        'module' => 'WscnGold',
        'controller' => 'index',
        'action' => 'tutorial',
    ),
    '/tutorial/([\w-_]+)' =>  array(
        'module' => 'WscnGold',
        'controller' => 'index',
        'action' => 'tutorial',
        'id' => 1
    ),
    '/gold/:action' =>  array(
        'module' => 'WscnGold',
        'controller' => 'index',
        'action' => 1
    ),
    '/techanalysis' =>  array(
        'module' => 'WscnGold',
        'controller' => 'techanalysis',
    ),
    '/data/techanalysis/(\w+)/(\w+)' =>  array(
        'module' => 'WscnGold',
        'controller' => 'techanalysis',
        'action' => 'quote',
        'symbol' => 1,
        'period' => 2,
    ),
    '/techanalysis/:action(/(\w+))*' =>  array(
        'module' => 'WscnGold',
        'controller' => 'techanalysis',
        'symbol' => 1,
        'period' => 3,
    ),
);
