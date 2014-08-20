#!/usr/bin/env php
<?php
//Send email by Gearman queue
require __DIR__ . '/../init_autoloader.php';

$appName = isset($argv[1]) ? $argv[1] : null;
$engine = new \Eva\EvaEngine\Engine(__DIR__ . '/..');
if($appName) {
    $engine->setAppName($appName);
}
$engine
    ->loadModules(include __DIR__ . '/../config/modules.' . $engine->getAppName() . '.php')
    ->bootstrap();

$newsManager = new \Eva\EvaLivenews\Models\NewsManager();

$newsManager->syncNewsToCache();

