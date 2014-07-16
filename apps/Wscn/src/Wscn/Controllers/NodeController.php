<?php

namespace Wscn\Controllers;

use Eva\EvaBlog\Models\Post;
use Eva\EvaEngine\Exception;

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
        $this->view->setVar('post', $post);
    }
}
