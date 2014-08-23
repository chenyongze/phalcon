#!/usr/bin/env php
<?php
//Find all available resources and insert them to db
require __DIR__ . '/../init_autoloader.php';

$appName = isset($argv[1]) ? $argv[1] : null;
$engine = new \Eva\EvaEngine\Engine(__DIR__ . '/..');
if ($appName) {
    $engine->setAppName($appName);
}
$engine
->loadModules(include __DIR__ . '/../config/modules.' . $engine->getAppName() . '.php')
->bootstrap();

$modules = $engine->getDI()->getModuleManager()->getModules();
try {
    foreach ($modules as $module) {
        $scanner = new \Eva\EvaPermission\Utils\Scanner($module['dir']);
        $scanner->scan();
        $controllers = $scanner->getControllers();
        foreach ($controllers as $controller) {
            $scanner->process($controller);
        }
    }
} catch (\Exception $e) {
    print($e->getMessage());
}
