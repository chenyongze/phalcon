<?php

return array(
    'livenews' => array(
        'broadcastEnable' => true,
        'socketIoServer' => 'http://localhost:3000',
        'socketIoRedis' => array(
            'host' => 'localhost',
            'port' => 6379
        ),
    ),
);
