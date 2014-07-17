<?php

namespace Eva\CounterRank\Tasks;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-16 11:16
// +----------------------------------------------------------------------
// + PersistDataTask.php
// +----------------------------------------------------------------------
use Eva\EvaEngine\Tasks\TaskBase;

class PersistDataTask extends TaskBase
{
    public function mainAction()
    {
        $this->output->writeln('counter persist start...');

    }
} 