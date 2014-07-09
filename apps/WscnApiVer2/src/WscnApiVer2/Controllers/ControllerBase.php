<?php

namespace WscnApiVer2\Controllers;

use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;

class ControllerBase extends \Eva\EvaEngine\Mvc\Controller\ControllerBase implements JsonControllerInterface
{
    protected $cacheKey;

    protected $expired = 60;

    public function beforeExecuteRoute($dispatcher)
    {
        //Only cache get
        if(!$this->request->isGet()) {
            return true;
        }
        $this->cacheKey = $cacheKey = md5($this->request->getURI());
        $cache = $this->getDI()->getApiCache();
        if($data = $cache->get($cacheKey)) {
            $this->response->setContent($data);
            parent::afterExecuteRoute($dispatcher);
            $this->response->send();
            return false;
        }
        return true;
    }

    public function afterExecuteRoute($dispatcher)
    {
        if(!$this->request->isGet()) {
            return parent::afterExecuteRoute($dispatcher);
        }

        if(!$cacheKey = $this->cacheKey) {
            return parent::afterExecuteRoute($dispatcher);
        }
        $cache = $this->getDI()->getApiCache();
        $cache->save($cacheKey, $this->response->getContent(), $this->expired);
        return parent::afterExecuteRoute($dispatcher);
    }


    public function toFullUrl($paramsOrUrl, $params = null)
    {
        $path = is_array($paramsOrUrl) ? $this->router->getRewriteUri() : $paramsOrUrl;
        $url = clone $this->url;
        $url->setBaseUri($this->getDI()->get('config')->apiUri);
        $params = is_array($paramsOrUrl) ? $paramsOrUrl : $params;

        return $url->get($path, $params);
    }

    public function getApiPaginator(\Phalcon\Paginator\AdapterInterface $paginator)
    {
        $pager = $paginator->getPaginate();
        if ($pager->total_pages <= 1) {
            return null;
        }
        $query = $pager->query;

        return array(
            'total' => $pager->total_items,
            'previous' => $this->toFullUrl(array_merge($query, array('page' => $pager->before))),
            'next' => $this->toFullUrl(array_merge($query, array('page' => $pager->next))),
            'last' => $this->toFullUrl(array_merge($query, array('page' => $pager->last))),
        );
    }
}
