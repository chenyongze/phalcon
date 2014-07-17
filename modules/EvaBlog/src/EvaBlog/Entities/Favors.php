<?php

namespace Eva\EvaBlog\Entities;

class Favors extends \Eva\EvaEngine\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $userId;

    /**
     *
     * @var integer
     */
    public $postId;

    /**
     *
     * @var integer
     */
    public $createdAt = 0;


    protected $tableName = 'blog_favors';
}
