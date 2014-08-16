<?php

namespace Eva\EvaSearch\Utils;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-15 15:11
// +----------------------------------------------------------------------
// + ElasticsearchUtil.php
// +----------------------------------------------------------------------

use Eva\EvaEngine\IoC;

class ElasticsearchUtil
{
    protected $elasticsearch;

    public function __construct()
    {
        $this->elasticsearch = new \Elasticsearch\Client(
            array('hosts' => IoC::get('config')->EvaSearch->elasticsearch->servers->toArray())
        );
    }

    public function index($index, $type, $data, $id = null)
    {

        return $this->elasticsearch->index(
            array(
                'index' => $index,
                'type' => $type,
                'body' => $data,
                'id' => $id
            )
        );
    }

    public function search($index, $type, $keyword, $searchInFields = array("title", "content"), $page = 1, $size = 15)
    {

        $searchParams['index'] = $index;
        $searchParams['type'] = $type;
        $searchParams['size'] = $size;
//        $page = isset($_REQUEST['page']) ? intval($_REQUEST['page']) : 1;
        $page = $page > 0 ? $page : 1;
        $searchParams['from'] = ($page - 1) * $searchParams['size'];
        $searchParams['body']['query']['multi_match'] = array(
            'query' => $keyword,
            "fields" => $searchInFields,
            "tie_breaker" => 0.3
        );
        $searchParams['body']['highlight'] = array(
            "fields" => array(
                "content" => array(
                    "fragment_size" => 50,
                    "number_of_fragments" => 2,
                    "type" => "plain"
                ),
                "title" => array(
                    "type" => "plain"
                )
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

        return $this->elasticsearch->search($searchParams);
    }
} 