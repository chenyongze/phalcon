<?php

namespace Eva\CounterRank\View\Helpers;
// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-15 13:25
// +----------------------------------------------------------------------
// + CounterRank.php  JS 客户端 View Helper
// +----------------------------------------------------------------------
use Eva\CounterRank\Utils\CounterRankUtil;
use Eva\EvaEngine\Tag;

/**
 * JS 客户端 View Helper
 * @package Eva\CounterRank\View\Helpers
 */
class CounterRank extends Tag
{
    /**
     * @var \mr5\CounterRank\JSClientHandler
     */
    static private $jsHandler;
    /**
     * @var \Eva\CounterRank\Utils\CounterRankUtil
     */
    static private $counterUtil;

    /**
     * @var bool
     */
    static private $inited = false;

    static public function init()
    {
        if(!self::$inited) {
            self::$counterUtil = new CounterRankUtil();
            self::$jsHandler = self::$counterUtil->getJSClientHandler();
        }
    }

    /**
     * 生成 get 操作的 script 标签
     *
     * @param string $groupName 分组名
     * @param string $keys id，多个使用下划线区隔
     * @param string $callback js 回调函数，参数是类似 [{key1:value1}, {key2:value2} ... ] 的数组
     * @return string
     */
    static public function get($groupName, $keys, $callback = '')
    {
        self::init();

        $hash = self::generateHash($groupName, $keys);
        $query = '?hash=' . $hash . '&_t=' . time();
        if ($callback) {
            $query .= '&callback=' . $callback;
        }
        return <<<HTML
        <script src="/counter/client.js/{$groupName}/{$keys}/get{$query}"></script>
HTML;
    }

    /**
     * 生成 increase 操作的 script 标签
     *
     * @param string $groupName 分组名
     * @param string $keys id，多个使用下划线区隔
     * @param string $callback js 回调函数，参数是类似 [{key1:newValue1}, {key2:newValue2} ... ] 的数组
     * @return string
     */
    public function __invoke($groupName, $keys, $callback = '')
    {
        self::init();

        $hash = self::generateHash($groupName, $keys);
        $query = '?hash=' . $hash . '&_t=' . time();
        if ($callback) {
            $query .= '&callback=' . $callback;
        }
        return <<<HTML
        <script src="/counter/client.js/{$groupName}/{$keys}/increase{$query}"></script>
HTML;
    }

    /**
     * 生成 rank 操作的 script 标签
     *
     * @param $groupName
     * @param string $type
     * @param int $limit
     * @param string $callback
     * @return string
     */
    static public function rank($groupName, $type = 'desc', $limit = 10, $callback = '')
    {
        self::init();

        $hash = self::generateHash($groupName, '');

        $query = '?hash=' . $hash . '&_t=' . time();
        if ($callback) {
            $query .= '&callback=' . $callback;
        }
        $limit = intval($limit);

        $type = strtolower($type);
        if (!in_array($type, array('desc', 'asc'))) {
            $type = 'desc';
        }
        return <<<HTML
        <script src="/counter/client.js/{$groupName}/{$type}/{$limit}/rank{$query}"></script>
HTML;

    }

    /**
     * 生成服务器验证的 hash
     *
     * @param string $groupName
     * @param string $keys
     * @return string
     */
    static public function generateHash($groupName, $keys = '')
    {
        return md5(self::$counterUtil->getToken($groupName) . $groupName . $keys);
    }
}