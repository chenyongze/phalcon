<?php

namespace Wscn\Controllers;

use Eva\EvaBlog\Models\Post;
use Eva\EvaBlog\Models\Tag;
use Eva\EvaEngine\Exception;
use Eva\Wiki\Utils\WikiUtil;

class NodeController extends ControllerBase
{
    public function indexAction()
    {
        return $this->response->redirect('/news');
    }

    public function nodeAction()
    {
        $id = $this->dispatcher->getParam('id');
        if (is_numeric($id)) {
            $post = Post::findFirst($id);
        } else {
            $post = Post::findFirstBySlug($id);
        }
        if(!$post || $post->status != 'published') {
            throw new Exception\ResourceNotFoundException('Request post not found');
        }
        if($this->getDI()->getModuleManager()->hasModule('Wiki')) {
            $post->text->content = WikiUtil::highlight($post->text->content);
        }

        $posts = null;

        if ($post->connections->count() < 1 && $post->tags->count() > 0) {
            $tag = new Tag();
            $post->connections = $tag->getRelatedPosts($post->id, 3);
        }
        $this->view->setVar('post', $post);
    }
}
