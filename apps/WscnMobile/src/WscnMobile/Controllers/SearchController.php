<?php

namespace WscnMobile\Controllers;

use Eva\EvaBlog\Models\Post;
use Eva\EvaEngine\Exception;

class SearchController extends ControllerBase
{
    public function indexAction()
    {
        $limit = $this->request->getQuery('limit', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 10 ? 10 : $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'status' => $this->request->getQuery('status', 'string', 'published'),
            'uid' => $this->request->getQuery('uid', 'int'),
            'cid' => $this->request->getQuery('cid', 'int'),
            'username' => $this->request->getQuery('username', 'string'),
            'order' => $order,
            'limit' => $limit,
            'page' => $this->request->getQuery('page', 'int', 1),
        );

        if ($query['cid']) {
            $this->view->setVar('category', Category::findFirst($query['cid']));
        }

        $post = new Post();
        $posts = $post->findPosts($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $posts,
            "limit"=> $limit,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);

        return $paginator;
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
