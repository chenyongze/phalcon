<?php
namespace Eva\EvaComment\Models;

use Eva\EvaComment\Entities\Comments;
use Eva\EvaComment\Entities\Threads;
use Eva\EvaComment\Entities\Votes;

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

    public function findComments($query=array())
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

        $builder->andWhere('rootId = 0');

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

    public function findCommentById($id)
    {
        $comment = Comments::findFirstById($id);
        return $comment;
    }


    public function updateCommentStatus(Comments $comment,$status)
    {
        $comment->status = $status;
        $comment->updatedAt = time();
        $comment->save();

        $comment->thread->lastEditAt = time();
        $comment->thread->save();

        return $comment;
    }

    public function findCommentsByUser($user)
    {
        $builder = $this->getModelsManager()->createBuilder();

        $builder->from('Eva\EvaComment\Entities\Comments');

        $builder->andWhere('userId = '.$user->id);
        $builder->andWhere('status = "' . Comments::STATE_APPROVED . '"');
        return $builder;
    }


    public function findCommentsByThread(Threads $thread, $sorter, $displayDepth)
    {

        $builder = $this->getModelsManager()->createBuilder();

        $builder->from('Eva\EvaComment\Entities\Comments');

        $builder->andWhere('rootId = 0');
        $builder->andWhere('status = "' . Comments::STATE_APPROVED . '"');
        $builder->andWhere('threadId = :threadId:', array('threadId' => $thread->id));

        if (empty($sorter) || empty(self::$orderMapping[$sorter])) {
            $sorter = self::DEFAULT_SORT;
        }
        $order = self::$orderMapping[$sorter];

        $builder->orderBy($order);

        return $builder;
    }

    public function findCommentTreeByThread($thread, $sorter, $displayDepth)
    {
//        $phql = 'SELECT * FROM Eva\EvaComment\Entities\Comments AS c
//                WHERE c.threadId = :threadId: AND c.rootId = 0 AND c.status = "approved" ORDER BY c.createdAt DESC';
//
//        $manager = $this->getModelsManager();
//        $comments = $manager->executeQuery($phql, array('threadId' => $thread->id));
//
//        return $comments;
    }

    public function filterContent(Comments $comment)
    {
        $phql = 'SELECT word FROM Eva\EvaComment\Entities\Filters AS f WHERE f.level = 2';

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

    public function findVotesByUserId($userId,$commentIds = null)
    {
        $builder = $this->getModelsManager()->createBuilder();

        $builder->from('Eva\EvaComment\Entities\Votes');

        $builder->andWhere('userId = :userId:',array('userId'=>$userId));
        if(is_array($commentIds)){
            $builder->inWhere('commentId', $commentIds);
        }

        $votes = $builder->getQuery()->execute();
        return $votes;
    }

    public function createVote($comment,$userId,$action)
    {
        $vote = new Votes;

        $vote->commentId = $comment->id;
        $vote->userId = $userId;
        $vote->action = $action;

        return $vote;
    }

    public function saveVote(Votes $vote,Comments $comment)
    {
        $vote->save();
        switch($vote->action) {
            case Votes::TYPE_UP:
                $this->addUpVote($comment);
                break;
            case Votes::TYPE_DOWN:
                $this->addDownVote($comment);
                break;
        }
    }

    public function removeVote(Votes $vote,Comments $comment)
    {
        $vote->remove();
        switch($vote->action) {
            case Votes::TYPE_UP:
                $this->removeUpVote($comment);
                break;
            case Votes::TYPE_DOWN:
                $this->removeDownVote($comment);
                break;
        }
    }



    public function addUpVote(Comments $comment)
    {
        $phql = "UPDATE Eva\EvaComment\Entities\Comments SET upVotes=upVotes+1 WHERE id = :id:";

        $manager = $this->getModelsManager();
        $data = $manager->executeQuery($phql, array('id'=>$comment->id));
        return $data;
    }

    public function removeUpVote(Comments $comment)
    {
        $phql = "UPDATE Eva\EvaComment\Entities\Comments SET upVotes=upVotes-1 WHERE id = :id:";

        $manager = $this->getModelsManager();
        $data = $manager->executeQuery($phql, array('id'=>$comment->id));
        return $data;
    }


    public function addDownVote(Comments $comment)
    {
        $phql = "UPDATE Eva\EvaComment\Entities\Comments SET downVotes=downVotes+1 WHERE id = :id:";

        $manager = $this->getModelsManager();
        $data = $manager->executeQuery($phql, array('id'=>$comment->id));
        return $data;
    }

    public function removeDownVote(Comments $comment)
    {
        $phql = "UPDATE Eva\EvaComment\Entities\Comments SET downVotes=downVotes-1 WHERE id = :id:";

        $manager = $this->getModelsManager();
        $data = $manager->executeQuery($phql, array('id'=>$comment->id));
        return $data;
    }




} 