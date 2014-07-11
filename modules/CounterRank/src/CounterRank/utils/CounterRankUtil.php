<?php

namespace Eva\CounterRank\Utils;

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
use mr5\CounterRank\JSClientHandler;
use Phalcon\DI;
use Phalcon\Mvc\User\Component;

/**
 * CounterRank 工具类
 * @package Eva\CounterRank\utils
 */
class CounterRankUtil extends Component
{
    protected $counterConfig = null;
    protected static  $counterRankInstances = array();
    protected static $jsClientHandler = null;
    public function __construct()
    {
        $this->counterConfig = $this->getDI()->getConfig()->counter;
    }

    /**
     * 获取 CounterRank 实例
     *
     * @param $groupName
     * @param bool $useFloat
     * @return CounterRank
     */
    public function getCounterRank($groupName, $useFloat = false)
    {
        if(!self::$counterRankInstances[$groupName]) {
            self::$counterRankInstances[$groupName] = new CounterRank(
                $this->counterConfig->redis_host,
                $this->counterConfig->redis_port,
                $this->counterConfig->redis_namespace,
                $groupName,
                $useFloat
            );
        }
        return self::$counterRankInstances[$groupName];
    }

    /**
     * 获取 JSClientHandler实例
     *
     * @return JSClientHandler
     */
    public function getJSClientHandler()
    {
        if(self::$jsClientHandler == null) {
            self::$jsClientHandler = new JSClientHandler(
                $this->counterConfig->redis_host,
                $this->counterConfig->redis_port,
                $this->counterConfig->redis_namespace,
                (array) $this->counterConfig->group_tokens
            );
        }
        return self::$jsClientHandler;
    }
} 