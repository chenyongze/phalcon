<?php
namespace Eva\EvaComment\Models;

use Eva\EvaComment\Entities\Comments;
use Eva\EvaComment\Entities\Threads;

use Eva\EvaEngine\Mvc\Model as BaseModel;

use InvalidArgumentException;

class CommentManager extends BaseModel
{
    public static $orderMapping = array(
        'id' => 'id ASC',
        '-id' => 'id DESC',
        'created_at' => 'createdAt ASC',
        '-created_at' => 'createdAt DESC',
    );

    const DEFAULT_SORT = '-created_at';

    /**
     * {@inheritdoc}
     */
    public function createComment(Threads $thread, Comments $parent = null)
    {
        $comment = new Comments;

        $comment->threadId = $thread->id;
        $comment->status  = $thread->defaultCommentStatus;

        if (null !== $parent) {
            $comment->parentId = $parent->id;
            $comment->rootId = $parent->rootId ? $parent->rootId : $parent->id;

            $comment->parentPath = $parent->parentPath ? $parent->parentPath.'/'.$parent->id : $parent->id;

            $comment->depth = $parent->depth+1;
        }

//        $event = new CommentEvent($comment);
//        $this->dispatcher->dispatch(Events::COMMENT_CREATE, $event);

        return $comment;
    }

    /**
     * {@inheritdoc}
     */
    public function saveComment(Comments $comment)
    {
        $thread = $comment->getThread();
        if (null === $thread) {
            throw new InvalidArgumentException('The comment must have a thread');
        }

        $comment->save();

        $threadManager = new ThreadManager();
        $threadManager->addCommentNumber($thread);

        return true;
    }

    public function removeComment(Comments $comment)
    {
        //todo 权限验证
        $comment->status = Comments::STATE_DELETED;
        $comment->save();
    }

    function findComments($query=array())
    {
//        $phql = 'SELECT * FROM Eva\EvaComment\Entities\Comments AS c ORDER BY c.createdAt DESC';

//        $manager = $this->getModelsManager();
//        $comments = $manager->createQuery($phql);

//        return $comments;

        $builder = $this->getModelsManager()->createBuilder();

        $builder->from('Eva\EvaComment\Entities\Comments');

        if (!empty($query['columns'])) {
            $builder->columns($query['columns']);
        }


        if (!empty($query['q'])) {
            $builder->andWhere('content LIKE :q:', array('q' => "%{$query['q']}%"));
        }

        if (!empty($query['status'])) {
            $builder->andWhere('status = :status:', array('status' => $query['status']));
        }else{
            $builder->andWhere('status != :status:', array('status' => Comments::STATE_DELETED));
        }

        if (!empty($query['uid'])) {
            $builder->andWhere('userId = :uid:', array('uid' => $query['uid']));
        }

        if (!empty($query['username'])) {
            $builder->andWhere('username LIKE :username:', array('username' => "%{$query['usernameli']}%"));
        }

        if (empty($query['order']) || empty(self::$orderMapping[$query['order']])) {
            $query['order'] = self::DEFAULT_SORT;
        }
        $order = self::$orderMapping[$query['order']];

        $builder->orderBy($order);

        return  $builder;
    }

    function findCommentById($id)
    {
        $comment = Comments::findFirstById($id);
        return $comment;
    }

    function updateCommentStatus(Comments $comment,$status)
    {
        $comment->status = $status;
        $comment->updatedAt = time();
        $comment->save();

        $comment->thread->lastEditAt = time();
        $comment->thread->save();

        return $comment;
    }


    function findCommentsByThread(Threads $thread, $sorter, $displayDepth)
    {

        $builder = $this->getModelsManager()->createBuilder();

        $builder->from('Eva\EvaComment\Entities\Comments');

        $builder->andWhere('status = "' . Comments::STATE_APPROVED . '"');
        $builder->andWhere('threadId = :threadId:', array('threadId' => $thread->id));

        if (empty($sorter) || empty(self::$orderMapping[$sorter])) {
            $sorter = self::DEFAULT_SORT;
        }
        $order = self::$orderMapping[$sorter];

        $builder->orderBy($order);

        return $builder;
    }

    function findCommentTreeByThread($thread, $sorter, $displayDepth)
    {
//        $phql = 'SELECT * FROM Eva\EvaComment\Entities\Comments AS c
//                WHERE c.threadId = :threadId: AND c.rootId = 0 AND c.status = "approved" ORDER BY c.createdAt DESC';
//
//        $manager = $this->getModelsManager();
//        $comments = $manager->executeQuery($phql, array('threadId' => $thread->id));
//
//        return $comments;
    }

    function filterContent(Comments $comment)
    {
        $phql = 'SELECT word FROM Eva\EvaComment\Entities\Filter AS f WHERE f.level = 2';

        $manager = $this->getModelsManager();
        $arr = $manager->executeQuery($phql);

        if (!empty($arr)) {
            foreach($arr as $v){
                if (stripos($comment->content,$v->word) !== false) {
                    $comment->status = Comments::STATE_PENDING;
                }
            }
        }
        return $comment;
    }
} 