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

$worker = $engine->getDI()->getWorker();
$worker->addFunction('sendmailAsync', 'sendmailAsync');

$logger = new Phalcon\Logger\Adapter\File($engine->getDI()->getConfig()->logger->path . 'worker_sendmail.log');
$logger->info(sprintf("Sendmail worker started by appname %s", $engine->getAppName()));

//MySQL interactive_timeout after 8 hours
function refreshDbConnect()
{
    global $engine;
    $di = $engine->getDI();
    $di->getDbMaster()->connect();
    $di->getDbSlave()->connect();
}

function sendmailAsync($job)
{
    global $engine;
    global $logger;
    $jobString = $job->workload();
    $logger->info(sprintf("Start sending mail by %s", $jobString));
    echo sprintf("Start sending mail by %s \n", $jobString);
    try {
        $work = json_decode($jobString, true);
        if ($work) {
            refreshDbConnect();
            $class = new $work['class'];
            call_user_func_array(array($class, $work['method']), $work['parameters']);
            $logger->info(sprintf("Mail sent to %s", $jobString));
            echo sprintf("Mail sent by %s \n", $jobString);
        } else {
            $logger->error(sprintf("Mail sent parameters error by %s \n", $jobString));
            echo sprintf("Mail sent error %s \n", $jobString);
        }
    } catch (\Exception $e) {
        echo sprintf("Exception catched %s, see log for details \n", $jobString);
        $logger->debug($e);
    }
}

while ($worker->work());
