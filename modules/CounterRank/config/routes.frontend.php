<?php

return array(
    '/counter/client.js/(\w+)/([\d_]+)/(\w+)/get' => array(
        'module' => 'CounterRank',
        'controller' => 'Client',
        'action' => 'get',
        'group' => 1,
        'keys' => 2,
        'token' => 3
    ),
    '/counter/client.js/(\w+)/([\d_]+)/(\w+)/increase' => array(
        'module' => 'CounterRank',
        'controller' => 'Client',
        'action' => 'increase',
        'group' => 1,
        'keys' => 2,
        'token' => 3
    ),
    '/counter/client.js/(\w+)/([a-zA-Z]+)/(\d+)/(\w+)/rank' => array(
        'module' => 'CounterRank',
        'controller' => 'Client',
        'action' => 'rank',
        'group' => 1,
        'type' => 2,
        'limit' => 3,
        'token' => 4
    )
);
