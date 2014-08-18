<?php

namespace Eva\EvaComment\Entities;

use Eva\EvaEngine\Mvc\Model as BaseModel;

use Eva\EvaBlog\Models\Post;

class Threads extends BaseModel
{
    protected $tableName = 'comment_threads';

    const DEFAULT_COMMENT_STATUS = 'approved';

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $uniqueKey;

    /**
     *
     * @var string
     */
    public $permalink;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $defaultCommentStatus;

    /**
     *
     * @var string
     */
    public $numComments;

    /**
     *
     * @var integer
     */
    public $lastCommentAt;

    /**
     *
     * @var string
     */
    public $channel;


    /**
     *
     * @var integer
     */
    public $lastEditAt;

    public function onConstruct()
    {
        $this->title = 'undefined';
        $this->isCommentAble = '';
        $this->numComments = 0;
        $this->lastCommentAt = time();
        $this->lastEditAt = time();
        $this->channel = 0;
        $this->defaultCommentStatus = self::DEFAULT_COMMENT_STATUS;
    }

    public function isCommentable()
    {
        return $this->isCommentAble;
    }

    public function getTitle()
    {
        $postId = str_replace('post_','',$this->uniqueKey);
//        p($postId);
        $post = Post::findFirst($postId);
//        p($post);
        if($post){
            return $post->title;
        }

        return $this->title;
    }

}
