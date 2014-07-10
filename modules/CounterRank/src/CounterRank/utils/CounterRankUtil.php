<?php

namespace Eva\CounterRank\utils;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-9 19:35
// +----------------------------------------------------------------------
// + CounterRank.php CounterRank 工具类
// +----------------------------------------------------------------------

use mr5\CounterRank\CounterRank;
use Phalcon\DI;

/**
 * CounterRank 工具类
 * @package Eva\CounterRank\utils
 */
class CounterRankUtil extends CounterRank
{

    public function __construct(DI $di, $groupName, $useFloat=false) {
        $counterConfig = $di->getConfig()->counter;

        parent::__construct(
            $counterConfig->redis_host,
            $counterConfig->redis_port,
            $counterConfig->redis_namespace,
            $groupName,
            $useFloat
        );
    }
} 