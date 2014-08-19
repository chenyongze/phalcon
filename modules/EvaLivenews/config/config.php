<?php

return array(
    'livenews' => array(
        'broadcastEnable' => false,
        'socketIoServer' => 'http://localhost:3000',
        'socketIoRedis' => array(
            'host' => 'localhost',
            'port' => 6379
        ),
        'redisEnable' => true,
        'redisSize' => 5,
    ),
);
