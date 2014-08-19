<?php
return array(
    'EvaCommon',
    'EvaUser',
    'EvaOAuthClient',
    'EvaOAuthServer',
    'EvaBlog',
    'EvaFileSystem',
    'EvaComment',
    'EvaPermission',
    'EvaLivenews',
    'CounterRank',
    'EvaSearch',
    'Wiki',
    'EvaSundries',
    //Load mobile module for checking controller / action whether exists, for mobile redirectiing
    'WscnMobile' => array(
        'className' => 'WscnMobile\Module',
        'path' => __DIR__ . '/../apps/WscnMobile/Module.php',
        'routesFrontend' => false,
        'routesBackend' => false,
    ),
    'Wscn' => array(
        'className' => 'Wscn\Module',
        'path' => __DIR__ . '/../apps/Wscn/Module.php'
    ),
);
