<?php

namespace WscnMobile\Controllers;

use Eva\EvaEngine\View\PurePaginator;
use Eva\EvaBlog\Models\Tag;
use Eva\EvaBlog\Models\Post;
use Eva\EvaEngine\Exception;

class SearchController extends ControllerBase
{
    public function indexAction()
    {
        $keyword = trim($this->request->getQuery("q"));
        if (!$keyword) {
            $tag = new Tag();
            $tags = $tag->getPopularTags(30);
            $this->view->setVar('tags', $tags);
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

        $searchParams['body']['query']['multi_match'] = array(
            'query' => $keyword,
            "fields" => array("title", "content"),
            "tie_breaker" => 0.3
        );

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
            'createdAt' => array(
                'order' => 'desc',
            ),
            '_score' => array(
                'order' => 'desc'
            ),
        );

        $ret = $client->search($searchParams);
        $this->view->setVar('hits', $ret['hits']);
        $pager = new PurePaginator($searchParams['size'], $ret['hits']['total'], $ret['hits']['hits']);
        $this->view->setVar('pager', $pager);
        $this->view->setVar('keyword', $keyword);

    }

    public function suggestionAction()
    {
        $searchUrl = 'http://nssug.baidu.com/su?&sugParams&prod=baike&ie=uft-8';
        header('Content-Type: application/json');
        if(!empty($_GET['wd'])) {
            $content = file_get_contents($searchUrl . '&wd=' . urlencode($_GET['wd']) . '&cb=' . urlencode($_GET['cb']));
            echo iconv('GB2312', 'UTF-8//IGNORE', $content);
            exit;
        }
    }
}
