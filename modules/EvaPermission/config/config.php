<?php
return array(
    'permission' => array(
        'disableAll' => false,
        'superusers' => array(
            1,
        ),
        'superkeys' => array(
        ),
        'keyLevels' => array(
            'basic' => array(
                'minutelyRate' => 60,
                'hourlyRate' => 4000,
                'dailyRate' => 5000,
            ),
            'starter' => array(
                'minutelyRate' => 100,
                'hourlyRate' => 10000,
                'dailyRate' => 30000,
            ),
            'business' => array(
                'minutelyRate' => 60,
                'hourlyRate' => 20000,
                'dailyRate' => 100000,
            ),
            'unlimited' => array(
                'minutelyRate' => 300,
                'hourlyRate' => 30000,
                'dailyRate' => 1000000,
            ),
            'extreme' => array(
                'minutelyRate' => 300,
                'hourlyRate' => 40000,
                'dailyRate' => 0,
            ),
            'blocked' => array(
                'minutelyRate' => '-1',
                'hourlyRate' => '-1',
                'dailyRate' => '-1',
            ),
        ),
        'error' => array(
            'module' => '',
            'controller' => '',
            'action' => '',
            'params' => '',
        ),
        'acl' => array(
            'adapter' => 'Memory',
        )
    ),
);
