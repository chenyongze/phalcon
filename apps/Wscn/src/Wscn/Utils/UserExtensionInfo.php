<?php

namespace Wscn\Utils;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-22 16:46
// +----------------------------------------------------------------------

/**
 * 解析 User 表的 extension 字段
 * Class UserExtensionInfo
 * @package Wscn\Utils
 */
class UserExtensionInfo
{
    /**
     * @var boolean 正在使用老密码
     */
    private $oldPasswordUsing;
    private $map = array(
        0 => 'oldPasswordUsed',
    );

    public function __construct()
    {

    }

    public function getExtensionStr()
    {
        $extensionStr = '';
        foreach ($this->map as $_k => $_v) {
            $extensionStr .= $this->$_k == 1 ? $this->$_k : 0;
        }
        return $extensionStr;
    }

    public function setExtensionStr($extension)
    {
        if (is_string($extension)) {
            foreach ($this->map as $_k => $_v) {
                $this->$_v = isset($extension[$_k]) ? $extension[$_k] == 1 : 0;
            }
        }
    }

    /**
     * @return boolean
     */
    public function getOldPasswordUsing()
    {
        return $this->oldPasswordUsing;
    }

    /**
     * @param boolean $oldPasswordUsing
     */
    public function setOldPasswordUsing($oldPasswordUsing)
    {
        $this->oldPasswordUsing = $oldPasswordUsing;
    }


}