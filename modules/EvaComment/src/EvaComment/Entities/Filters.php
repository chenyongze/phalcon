<?php
namespace Eva\EvaComment\Entities;

use Eva\EvaEngine\Mvc\Model as BaseModel;

class Filters extends BaseModel
{
    protected $tableName = 'comment_filters';

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $word;

    /**
     *
     * @var integer
     */
    public $level;
}
