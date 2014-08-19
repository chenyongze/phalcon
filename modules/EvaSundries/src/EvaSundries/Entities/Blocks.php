<?php

namespace Eva\EvaSundries\Entities;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-18 15:19
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Model;

class Blocks extends Model
{
    protected $tableName = 'sundry_blocks';
    /**
     * @var int
     */
    public $id;
    /**
     * @var string
     */
    public $name;
    /**
     * @var string
     */
    public $content;
    /**
     * @var string
     */
    public $visibility = 'visible';
    /**
     * @var int
     */
    public $createdAt;
    /**
     * @var int
     */
    public $updatedAt;
}