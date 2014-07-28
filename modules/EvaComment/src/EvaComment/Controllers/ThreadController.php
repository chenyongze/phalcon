<?php
namespace Eva\EvaComment\Controllers;

use Eva\EvaComment\Entities\Comments;
use Eva\EvaComment\Entities\Threads;

use Eva\EvaComment\Models\ThreadManager;
use Eva\EvaComment\Models\CommentManager;

use Eva\EvaEngine\Mvc\Controller\ControllerBase;
use Eva\EvaBlog\Forms;

use Gregwar\Captcha\CaptchaBuilder;


class ThreadController extends ControllerBase
{
    const VIEW_FLAT = 'flat';
    const VIEW_TREE = 'tree';

    public function initialize()
    {
//        $this->view->setModuleLayout('WscnGold', '/views/layouts/default');
        $this->view->setModuleViewsDir('EvaComment', '/views');
        $this->view->setModulePartialsDir('EvaComment', '/views');
    }

    /**
     * Get the comments of a thread. Creates a new thread if none exists.
     *
     * @param string $uniqueKey Id of the thread
     *
     */
    public function getThreadCommentsAction($uniqueKey)
    {

        $displayDepth = $this->request->getQuery('displayDepth');
        $sorter = $this->request->getQuery('sorter','string',CommentManager::DEFAULT_SORT);
        $currentPage = $this->request->getQuery('page', 'int', 1);

        $threadManager = new ThreadManager();

        $thread = $threadManager->findThreadByUniqueKey($uniqueKey);

        // We're now sure it is no duplicate id, so create the thread
        if (null === $thread) {
            // Decode the permalink for cleaner storage (it is encoded on the client side)
            $permalink = urldecode($this->request->getQuery('permalink'));

            $thread = new Threads();
            $thread->uniqueKey = $uniqueKey;
            $thread->permalink = $permalink;

            //todo validate
            if ($thread->save() == false) {

                foreach ($thread->getMessages() as $message) {
                    echo $message, "\n";
                }
                exit;
//                throw new \Exception('Save failed');
            }
        }

        $cacheKey = $uniqueKey.'###'.$thread->numComments.$thread->lastEditAt;

//        $this->view->cache(
//            array(
//                "lifetime" => 86400,
//                "key"      => $cacheKey,
//            )
//        );

//        $viewMode = $this->dispatcher->getParam('view');

        $commentManager = new CommentManager();

        $viewMode = 'tree';

        switch ($viewMode) {
            case self::VIEW_FLAT:
                $comments = $commentManager->findCommentsByThread($thread, $sorter, $displayDepth);

                // We need nodes for the api to return a consistent response, not an array of comments
//                $comments = array_map(function($comment) {
//                        return array('comment' => $comment, 'children' => array());
//                    },
//                    $comments
//                );
                break;
            case self::VIEW_TREE:

            default:
                $comments = $commentManager->findCommentsByThread($thread, $sorter, $displayDepth);
                break;
        }

        $this->view->pick('thread/thread');

        $query = array(
            'page' => $currentPage,
        );
        $limit = 10;
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $comments,
            "limit"=> $limit,
            "page" => $currentPage
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();

        $this->view->setVars(
            array(
                'pager' => $pager,
                'comments' => $pager->items,
                'displayDepth' => $displayDepth,
                'sorter' => $sorter,
                'thread' => $thread,
            )
        );
    }

    /**
     * Creates a new Comment for the Thread from the submitted data.
     *
     * @param string $uniqueKey The id of the thread
     *
     */
    public function postThreadCommentsAction($uniqueKey)
    {
        $threadManager = new ThreadManager();
        $thread = $threadManager->findThreadByUniqueKey($uniqueKey);
        if (!$thread) {
            throw new \Exception(sprintf('Thread with identifier of "%s" does not exist', $threadKey));
        }

//        if (!$thread->isCommentable()) {
//            throw new \Exception(sprintf('Thread "%s" is not commentable', $uniqueKey));
//        }


        $parentId = $this->request->getQuery('parentId');
        $parent = $this->getValidCommentParent($thread, $parentId);

        $content = $this->request->getPost('content');
        $username = $this->request->getPost('username');
        $commentManager = new CommentManager();
        $comment = $commentManager->createComment($thread, $parent);

//        if ($form->isValid()) {
        $comment->content = $content;
        if(!empty($username)) $comment->username = $username;
        $commentManager->filterContent($comment);  //政治敏感词过滤
        if ($commentManager->saveComment($comment) !== false) {
            $errors = $comment->getMessages();
            p($errors);
//                return $this->getViewHandler()->handle($this->onCreateCommentSuccess($form, $id, $parent));
        }

        $this->view->pick('thread/comment');

        $this->view->setVars(
            array(
                'comment' => $comment,
                'thread' => $thread,
            )
        );

    }

    /**
     * Presents the form to use to create a new Comment for a Thread.
     *
     * @param string $uniqueKey
     *
     */
    public function newThreadCommentsAction($uniqueKey)
    {
        $threadManager = new ThreadManager();
        $thread = $threadManager->findThreadByUniqueKey($uniqueKey);
        if (!$thread) {
            throw new \Exception(sprintf('Thread with identifier of "%s" does not exist', $uniqueKey));
        }

        $commentManager = new CommentManager();
        $comment = $commentManager->createComment($thread);

        $parent = $this->getValidCommentParent($thread,$parentId = $this->request->getQuery('parentId'));

        $this->view->setVars(
            array(
                'comment' => $comment,
                'parent' => $parent,
                'thread' => $thread,
            )
        );
    }

    public function captchaAction()
    {
        $builder = new CaptchaBuilder;
        $builder->build();

        if($builder->testPhrase($userInput)) {
            // instructions if user phrase is good
        }
        else {
            // user phrase is wrong
        }

        header('Content-type: image/jpeg');
        $builder->output();
    }

    /**
     * Checks if a comment belongs to a thread. Returns the comment if it does.
     *
     * @param ThreadInterface $thread Thread object
     * @param mixed $commentId Id of the comment.
     *
     * @return CommentInterface|null The comment.
     */
    private function getValidCommentParent($thread, $commentId)
    {
        if (null !== $commentId) {
            $commentManager = new CommentManager();
            $comment = $commentManager->findCommentById($commentId);
            if (!$comment) {
                exit('Parent comment with identifier "%s" does not exist');
//                throw new NotFoundHttpException(sprintf('Parent comment with identifier "%s" does not exist', $commentId));
            }

//            if ($comment->getThread() !== $thread) {
//                exit('Parent comment is not a comment of the given thread.');
////                throw new NotFoundHttpException('Parent comment is not a comment of the given thread.');
//            }

            return $comment;
        }
    }

}
