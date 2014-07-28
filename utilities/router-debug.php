<?php
require_once '../init_autoloader.php';
$router = unserialize(include '../config/_debug.evaengine.router.php');
$router->handle('/');
p('------------Matched Route');
p($router->getMatchedRoute());
p('------------Matched Namespace');
p($router->getNamespaceName());
p('------------Matched Module');
p($router->getModuleName());
p('------------Matched Controller');
p($router->getControllerName());
p('------------Matched Action');
p($router->getActionName());
p('------------Matched Params');
p($router->getParams());


