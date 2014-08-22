<?php

namespace WscnApiVer1\Models;

use Eva\EvaBlog\Entities;
use Eva\EvaUser\Models\Login as LoginModel;
use Eva\EvaFileSystem\Models\Upload as UploadModel;
use Eva\EvaEngine\Exception;
use Eva\EvaEngine\Mvc\Model\Validator\Uniqueness;

class Post extends Entities\Posts
{

    public static $defaultDump = array(
        'id',
        'title',
        'codeType',
        'createdAt',
        'summary',
        'summaryHtml' => 'getSummaryHtml',
        'commentStatus',
        'sourceName',
        'sourceUrl',
        'url' => 'getUrl',
        'imageUrl' => 'getImageUrl',
        'content' => 'getContentHtml',
        'text' => array(
            'content',
        ),
        'tags' => array(
            'id',
            'tagName',
        ),
        'categories' => array(
            'id',
            'categoryName',
        ),
        'user' => array(
            'id',
            'username',
        ),
    );



    public function findPosts(array $query = array())
    {
        $itemQuery = $this->getDI()->getModelsManager()->createBuilder();

        $itemQuery->from(__CLASS__);

        if ($query['cid'] != 0) {
            $itemQuery->join('Eva\EvaBlog\Entities\CategoriesPosts', 'id = r.postId', 'r')
            ->andWhere('r.categoryId = :cid:', array('cid' => $query['cid']));
        }

        $order = 'createdAt DESC';

        $itemQuery->orderBy($order);
        return $itemQuery;
    }

    public function findRankTwodaysPosts(array $query = array())
    {
        $itemQuery = $this->getDI()->getModelsManager()->createBuilder();
        $itemQuery->from(__CLASS__);
        $itemQuery->andWhere('createdAt >= ' . strtotime('-60 days'));

        $order = 'count DESC';

        $itemQuery->orderBy($order);
        return $itemQuery;

    }

    public function getContentHtml()
    {
        if (empty($this->text->content)) {
            return '';
        }
        if ($this->codeType == 'markdown') {
            $parsedown = new \Parsedown();

            return $parsedown->text($this->text->content);
        }

        return $this->text->content;
    }

    public function getUrl()
    {
        $postUrl = $this->getDI()->get('config')->baseUri;
        $postPath = $this->getDI()->get('config')->blog->postPath;

        return $postUrl . sprintf($postPath, $this->slug);
    }

    public function getSummaryHtml()
    {
        if (!$this->summary) {
            return '';
        }

        if ($this->codeType == 'markdown') {
            $parsedown = new \Parsedown();

            return $parsedown->text($this->summary);
        } else {
            return $this->summary;
        }
    }

    public function getImageUrl()
    {
        if (!$this->image) {
            return '';
        }

        return $this->image;
    }
}
