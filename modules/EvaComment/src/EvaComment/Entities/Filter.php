<?php
namespace Eva\EvaComment\Entities;

use Eva\EvaEngine\Mvc\Model as BaseModel;

class Filter extends BaseModel
{
    protected $tableName = 'comment_filter';

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $level;
}
