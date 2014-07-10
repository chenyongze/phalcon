<?php

namespace Eva\CounterRank\Controllers;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-9 16:03
// +----------------------------------------------------------------------
// + CounterClientController.php 计数器 JS 客户端控制器
// +----------------------------------------------------------------------

use Eva\CounterRank\utils\JSClientHandlerUtil;
use mr5\CounterRank\JSClientHandler;
use Phalcon\Mvc\Controller;

/**
 * 计数器 JS 客户端控制器
 * @package Wscn\Controllers
 */
class ClientController extends Controller
{
    /**
     * @var JSClientHandler
     */
    private $jsClientHandler = null;
    private function _getParam($key)
    {
        return $this->dispatcher->getParam($key);
    }
    public function initialize()
    {
        $this->view->disable();

        $this->jsClientHandler = new JSClientHandlerUtil($this->getDI());
    }
    public function getAction()
    {
        $this->jsClientHandler->handleGet(
            $this->_getParam('token'),
            $this->_getParam('group'),
            $this->_getParam('keys'),
            $this->request->getQuery('callback')
        );
    }
    public function increaseAction()
    {
        $this->jsClientHandler->handleIncrease(
            $this->_getParam('token'),
            $this->_getParam('group'),
            $this->_getParam('keys'),
            $this->request->getQuery('callback')
        );
    }
    public function rankAction()
    {
        $this->jsClientHandler->handleRank(
            $this->_getParam('token'),
            $this->_getParam('group'),
            $this->_getParam('type'),
            $this->_getParam('limit'),
            $this->request->getQuery('callback')
        );

    }
}