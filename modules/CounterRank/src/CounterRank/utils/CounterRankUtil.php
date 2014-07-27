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
     * 通过分组名获取 token
     * @param string $groupName
     * @return string
     */
    public function getToken($groupName) {
        return $this->counterConfig->group_tokens[$groupName]->token;
    }
    /**
     * 获取 CounterRank 实例
     *
     * @param string    $groupName  分组名
     * @param bool      $useFloat   是否使用浮点数，默认不使用
     * @return CounterRank
     */
    public function getCounterRank($groupName, $useFloat = false)
    {
        if(!isset(self::$counterRankInstances[$groupName])) {
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
        $tokens = array();
        foreach($this->counterConfig->group_tokens->toArray() as $groupName=>$config) {
            $tokens[$groupName] = $config['token'];
        }
        if(self::$jsClientHandler == null) {
            self::$jsClientHandler = new JSClientHandler(
                $this->counterConfig->redis_host,
                $this->counterConfig->redis_port,
                $this->counterConfig->redis_namespace,
                $tokens
            );
            self::$jsClientHandler->setTokenVerifier(function ($operation, $userToken, $token, $group, $keys) {
                $str = $token.$group;
                if($keys) {
                    $str .= $keys;
                }
                return md5($str) == $userToken;
            });
            self::$jsClientHandler->getCounterRankInstance()->setFixMiss(function($key, CounterRank $counterRank) {
                $counterRank->create($key, 0);

                return true;
            });
        }
        return self::$jsClientHandler;
    }
    public function persist(\Closure $func) {

    }
}