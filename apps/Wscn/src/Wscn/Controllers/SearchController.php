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


use Eva\EvaBlog\Models\PostSearcher;
use Eva\EvaBlog\Models\Tag;

class SearchController extends ControllerBase
{
    public function indexAction()
    {
        $keyword = trim($this->request->getQuery("q"));
        $postSearcher = new PostSearcher();
        $pager = $postSearcher->searchPosts(
            array(
                'q' => $keyword,
                'highlight' => true,
                'order' => '-created_at',
                'status'=>'published'
            )
        );
        $this->view->setVar('pager', $pager);
        $this->view->setVar('keyword', $keyword);

        $tag = new Tag();
        $tags = $tag->getPopularTags(6);
        $this->view->setVar('tags', $tags);
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
