<?php

namespace Wscn\Controllers;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-12 14:14
// +----------------------------------------------------------------------
// + SearchController.php
// +----------------------------------------------------------------------


use Eva\EvaEngine\View\PurePaginator;

class SearchController extends ControllerBase
{
    public function indexAction()
    {
        $keyword = trim($this->request->getQuery("q"));
        if (!$keyword) {
            return;
        }
        $client = new \Elasticsearch\Client(array(
            'hosts' => $this->getDI()->getConfig()->EvaSearch->elasticsearch->servers->toArray()
        ));
        $searchParams['index'] = 'wallstreetcn';
        $type = $this->request->getQuery('type');
        $type = in_array($type, array('article', 'wiki', 'livenews')) ? $type : 'article';
        $searchParams['type'] = $type;
        $searchParams['size'] = 15;
        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $page = $page > 0 ? $page : 1;
        $searchParams['from'] = ($page - 1) * $searchParams['size'];
//        p($searchParams);

        $searchParams['body']['query']['multi_match'] = array(
            'query' => $keyword,
            "fields" => array("title", "content"),
            "tie_breaker" => 0.3
        );

//        "query": {
//        "bool": {
//            "must": [
//                {
//                    "wildcard": {
//                    "content": "美元*"
//                    }
//                }
//            ],
//            "must_not": [],
//            "should": []
//        }
//    },
//        $searchParams['body']['fields'] = array(
//            '_parent',
//            '_source'
//        );
//        $searchParams['body']['query']['bool'] = array(
//            "must" => array(
//                array(
//                    'wildcard' => array(
//                        "content" => $keyword . '*'
//                    )
//                )
//            ),
//            "must_not" => array(),
//            'should' => array()
//        );
        $searchParams['body']['highlight'] = array(

            "fields" => array(
                "title" => array(
                    "type" => "plain"
                ),
                "content" => array(
                    "fragment_size" => 50,
                    "number_of_fragments" => 3,
                    "type" => "plain"
                ),

            )
        );
        $searchParams['body']['filter'] = array(
            "bool" => array(
                "should" => array(
                    "term" => array(
                        "status" => "published"
                    )
                )
            )
        );
        $searchParams['body']['sort'] = array(
            '_score' => array(
                'order' => 'desc'
            ),
            'createdAt' => array(
                'order' => 'desc',
                'mode' => 'avg'
            )
        );
//        "sort" : [
//      {"price" : {"order" : "asc", "mode" : "avg"}}
//   ]

        $ret = $client->search($searchParams);
        $this->view->setVar('hits', $ret['hits']);
        $pager = new PurePaginator($searchParams['size'], $ret['hits']['total'], $ret['hits']['hits']);
        $this->view->setVar('pager', $pager);
        $this->view->setVar('keyword', $keyword);

    }

    public function opensearchxmlAction()
    {
        $this->view->disable();
        $host = $this->getDI()->getConfig()->baseUri;
        header('Content-Type:text/xml; charset=utf-8;');
        echo <<<XML
<OpenSearchDescription xmlns="http://a9.com/-/spec/opensearch/1.1/"
                       xmlns:moz="http://www.mozilla.org/2006/browser/search/">
  <ShortName>华尔街见闻</ShortName>
  <Description>华尔街见闻搜索</Description>
  <InputEncoding>UTF-8</InputEncoding>
  <Image width="16" height="16" type="image/x-icon">{$host}/favicon.ico</Image>
  <Url type="text/html" method="get" template="{$host}/search?q={searchTerms}&amp;ref=opensearch"/>
  <moz:SearchForm>{$host}/search</moz:SearchForm>
</OpenSearchDescription>
XML;

    }
} 