<?php

return array(
    '/counter/client.js/(\w+)/([\d_]+)/get' => array(
        'module' => 'CounterRank',
        'controller' => 'Client',
        'action' => 'get',
        'group' => 1,
        'keys' => 2,
    ),
    '/counter/client.js/(\w+)/([\d_]+)/increase' => array(
        'module' => 'CounterRank',
        'controller' => 'Client',
        'action' => 'increase',
        'group' => 1,
        'keys' => 2,
    ),
    '/counter/client.js/(\w+)/([a-zA-Z]+)/(\d+)/rank' => array(
        'module' => 'CounterRank',
        'controller' => 'Client',
        'action' => 'rank',
        'group' => 1,
        'type' => 2,
        'limit' => 3,
    )
);
