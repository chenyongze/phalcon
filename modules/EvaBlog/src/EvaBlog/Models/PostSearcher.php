<?php

namespace Eva\EvaBlog\Models;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-25 18:13
// +----------------------------------------------------------------------

use Elasticsearch\Client;
use Eva\EvaEngine\View\PurePaginator;

class PostSearcher extends Post
{
    /**
     * @var Client
     */
    private $es_client;

    private $es_config;

    public function initialize()
    {
        parent::initialize();
        $this->es_config = $this->getDI()->getConfig()->EvaSearch->elasticsearch->toArray();
        $this->es_client = new Client(array(
            'hosts' => $this->es_config['servers']
        ));
    }

    public function searchPosts(array $query = array())
    {
        $searchParams = array();
        $searchParams['index'] = $this->es_config['index_name'];
        $searchParams['type'] = 'article';
        $searchParams['size'] = isset($query['limit']) && intval($query['limit']) > 0 ? intval($query['limit']) : 15;
        $page = isset($query['page']) && intval($query['page']) > 0 ? intval($query['page']) : 1;
        $searchParams['from'] = ($page - 1) * $searchParams['size'];

        $orderMapping = array(
            'id' => array(
                'id' => array('order' => 'asc')
            ),
            '-id' => array(
                'id' => array('order' => 'desc')
            ),
            'created_at' => array(
                'createdAt' => array('order' => 'asc')
            ),
            '-created_at' => array(
                'createdAt' => array(
                    'order' => 'desc',
                ),
            ),
            'sort_order' => array(
                'sortOrder' => array(
                    'order' => 'asc',
                ),
            ),
            '-sort_order' => array(
                'sortOrder' => array(
                    'order' => 'desc',
                ),
            ),
            'count' => array(
                'count' => array(
                    'order' => 'asc',
                ),
            ),
            '-count' => array(
                'count' => array(
                    'order' => 'desc',
                ),
            ),
        );

        if (!empty($query['columns'])) {
//            $itemQuery->columns($query['columns']);
        }


//        if (!empty($query['id'])) {
//            $idArray = explode(',', $query['id']);
//            $itemQuery->inWhere('id', $idArray);
//        }

        $filters = array();
        if (!empty($query['status'])) {
            $filters[]['term'] = array(
                'status' => $query['status']
            );
//            $itemQuery->andWhere('status = :status:', array('status' => $query['status']));
        }

        if (!empty($query['has_image'])) {
            $filters[]['range'] = array(
                'imageId' => array('from' => 1)
            );
        }

        if (!empty($query['sourceName'])) {
            $filters[]['term'] = array(
                'sourceName' => $query['sourceName']
            );
        }

        if (!empty($query['uid'])) {
            $filters[]['term'] = array(
                'uid' => $query['uid']
            );
        }

        if (!empty($query['cid'])) {
            $filters[]['term'] = array(
                'categoryId' => $query['cid']
            );
        }

        if (!empty($query['tags'])) {
            $filters[]['term'] = array(
                'tagNames' => $query['tags']
            );
        }
        $sort = array();
        if (!empty($query['order']) && $orderMapping[$query['order']]) {
            $sort = array_merge($sort, $orderMapping[$query['order']]);
        }
        $sort = array_merge(
            $sort,
            array(
                '_score' => array(
                    'order' => 'desc'
                )
            )
        );
        $searchParams['body']['sort'] = $sort;
        if ($filters) {
            $searchParams['body']['filter']['and']['filters'] = $filters;
        }
        if (isset($query['highlight']) && $query['highlight']) {
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
        }
        $keyword = isset($query['q']) && count(trim($query['q'])) > 0 ? trim($query['q']) : false;
        if ($keyword) {
            $searchParams['body']['query']['multi_match'] = array(
                'query' => $query['q'],
                "fields" => array("title", "content"),
                "tie_breaker" => 0.3
            );
        }
        $ret = $this->es_client->search($searchParams);
        $pager = new PurePaginator($searchParams['size'], $ret['hits']['total'], $ret['hits']['hits']);
        return $pager;
    }

    public function getRelatedPosts($id)
    {
        $searchParams['index'] = $this->es_config['index_name'];
        $searchParams['type'] = 'article';
        $searchParams['size'] = 3;
        $searchParams['from'] = 0;
        $searchParams['fields'] = array(
            'id', 'title', 'createdAt'
        );

        $searchParams['body']['query']['more_like_this'] = array(
            'fields' => array('title', 'content'),
            'ids' => array($id)
        );
//        $searchParams['body']['sort'] = array(
//            'createdAt' => array(
//                'order' => 'desc'
//            )
//        );
        $ret = $this->es_client->search($searchParams);
        $posts = array();
        foreach($ret['hits']['hits'] as $hit) {
            $posts[] = $hit['fields'];
        }
        return $posts;
//        dd($ret);
    }
} 