<?php

namespace Eva\EvaBlog\Entities;

class Connections extends \Eva\EvaEngine\Mvc\Model
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
    public $sourceId;

    /**
     *
     * @var integer
     */
    public $targetId;

    /**
     *
     * @var integer
     */
    public $priority = 0;

    /**
     *
     * @var string
     */
    public $detectedType = 'user';

    /**
     *
     * @var integer
     */
    public $createdAt;

    protected $tableName = 'blog_connections';
}
