<?php
namespace Eva\EvaComment\Controllers\Admin;

use Eva\EvaComment\Models\ThreadManager;
use Eva\EvaComment\Forms\ThreadForm;
use Eva\EvaComment\Entities\Threads;

use Eva\EvaBlog\Models\Post;
use Eva\EvaEngine\Exception;

class ThreadController extends ControllerBase
{
    public function postAction()
    {
        $postId = $this->dispatcher->getParam('postId');
        $threadManager = new ThreadManager();
        $uniqueKey = 'post_'.$postId;
        $thread = $threadManager->findThreadByUniqueKey($uniqueKey);
        if (!$thread) {
            throw new Exception\ResourceNotFoundException('ERR_COMMENT_THREAD_NOT_FOUND');
        }

        $form = new ThreadForm();
        $form->setModel($thread);

        if ($this->request->isPost()) {
            $data = $this->request->getPost();

            if (!$form->isFullValid($data)) {
                return $this->displayInvalidMessages($form);
            }

            try {
                $form->save();
            } catch (\Exception $e) {
                return $this->displayException($e, $form->getModel()->getMessages());
            }
            $this->flashSession->success('SUCCESS_UPDATED');

            return $this->redirectHandler('/admin/thread/post/' . $postId);
        }

        $this->view->setVar('form', $form);

        $this->view->setVar('thread', $thread);
    }

}
