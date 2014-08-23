<?php
return array(
    'EvaCommon' => array(
        'routesFrontend' => false,
        'routesBackend' => false,
    ),
    'EvaUser' => array(
        'routesFrontend' => false,
        'routesBackend' => false,
    ),
    'EvaBlog' => array(
        'routesFrontend' => false,
        'routesBackend' => false,
    ),
    'EvaComment' => array(
        'routesFrontend' => false,
        'routesBackend' => false,
    ),
    'EvaLivenews' => array(
        'routesFrontend' => false,
        'routesBackend' => false,
    ),
    'EvaFileSystem' => array(
        'routesFrontend' => false,
        'routesBackend' => false,
    ),
    'EvaPermission' => array(
        'routesFrontend' => false,
        'routesBackend' => false,
    ),
    'Wiki' => array(
        'routesFrontend' => false,
        'routesBackend' => false,
    ),
    'Wscn' => array(
        'className' => 'Wscn\Module',
        'path' => __DIR__ . '/../apps/Wscn/Module.php',
        'routesFrontend' => false, 
        'routesBackend' => false,
    ),
    'WscnApiVer1' => array(
        'className' => 'WscnApiVer1\Module',
        'path' => __DIR__ . '/../apps/WscnApiVer1/Module.php',
        'routesFrontend' => __DIR__ . '/../apps/WscnApiVer1/config/routes.frontend.php',
        'routesBackend' => __DIR__ . '/../apps/WscnApiVer1/config/routes.backend.php',
    ),
    'WscnApiVer2' => array(
        'className' => 'WscnApiVer2\Module',
        'path' => __DIR__ . '/../apps/WscnApiVer2/Module.php',
        'routesFrontend' => __DIR__ . '/../apps/WscnApiVer2/config/routes.frontend.php',
        'routesBackend' => __DIR__ . '/../apps/WscnApiVer2/config/routes.backend.php',
    ),
);
