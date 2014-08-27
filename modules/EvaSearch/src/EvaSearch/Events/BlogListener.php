<?php

namespace Eva\EvaSearch\Events;


// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-27 17:55
// +----------------------------------------------------------------------
// + BlogListener.php
// +----------------------------------------------------------------------
use Eva\EvaBlog\Models\Post;
use Eva\EvaEngine\IoC;
use Eva\EvaSearch\Utils\ElasticsearchUtil;
use Phalcon\Logger;

class BlogListener
{
    public function afterSave($event, Post $post)
    {
        if (!$post->id) {
            return;
        }
        $postArr = $post->toArray();
        $postArr['tagNames'] = $post->getTagString();

        $categoryIds = '';
        $postArr['content'] = strip_tags($post->text->content);
        $postArr['summary'] = strip_tags($post->summary);
        if ($post->categories) {
            foreach ($post->categories as $category) {
                if ($categoryIds != '') {
                    $categoryIds .= ',';
                }
                $categoryIds .= $category->id;
            }
        }
        $postArr['categoryIds'] = $categoryIds;
        $postArr['upVote'] = $postArr['upVote'] ? $postArr['upVote'] : 0;
        $es = new ElasticsearchUtil();
        $es->index('wallstreetcn', 'article', $postArr, $post->id);
//        (json_encode($post->categories));
    }
} 