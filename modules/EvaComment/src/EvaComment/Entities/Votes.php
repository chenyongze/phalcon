<?php
namespace Eva\EvaComment\Entities;

use Eva\EvaEngine\Mvc\Model as BaseModel;

class Votes extends BaseModel
{
    const TYPE_UP = 'up';

    const TYPE_DOWN = 'down';

    protected $tableName = 'comment_votes';

    public static $defaultDump = array(
        'id',
        'commentId',
        'userId',
        'type',
        'createdAt',
    );

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var integer
     */
    public $commentId;

    /**
     *
     * @var integer
     */
    public $userId;

    /**
     *
     * @var string
     */
    public $type;

    /**
     *
     * @var integer
     */
    public $createdAt;

    public function initialize()
    {
        $this->belongsTo(
            'commentId',
            '\Eva\EvaComment\Entities\Comments',
            'id',
            array(
                'alias' => 'comment',
                'foreignKey' => true
            )
        );

    }

    public function onConstruct()
    {
        $this->createdAt = time();
    }
}
