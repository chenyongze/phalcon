<?php

namespace Eva\CounterRank\utils;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-9 19:38
// +----------------------------------------------------------------------
// + JSClientHandlerUtil.php js 客户端处理工具类
// +----------------------------------------------------------------------

use mr5\CounterRank\JSClientHandler;
use Phalcon\DI;

/**
 * js 客户端处理工具类
 * @package Eva\CounterRank\utils
 */
class JSClientHandlerUtil extends JSClientHandler
{
    public function __construct(DI $di) {
        $counterConfig = $di->getConfig()->counter;

        parent::__construct($counterConfig->redis_host, $counterConfig->redis_port, $counterConfig->redis_namespace, (array) $counterConfig->group_tokens);
    }
}