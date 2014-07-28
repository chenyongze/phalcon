<?php
namespace Eva\EvaComment\Entities;

use Eva\EvaEngine\Mvc\Model as BaseModel;

class Comments extends BaseModel
{
    const STATE_APPROVED = 'approved';

    const STATE_DELETED = 'deleted';

    const STATE_SPAM = 'spam';   //灌水评论

    const STATE_HAM = 'ham';     //不文明评论

    const STATE_DANGEROUS = 'dangerous';  //政治敏感评论

    const STATE_PENDING = 'pending';



    protected $tableName = 'comment_comments';

    public static $defaultDump = array(
        'id',
        'threadId',
        'codeType',
        'content',
        'status',
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
    public $threadId;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @var string
     */
    public $codeType;

    /**
     *
     * @var string
     */
    public $content;

    /**
     *
     * @var integer
     */
    public $rootId;

    /**
     *
     * @var integer
     */
    public $parentId;

    /**
     *
     * @var string
     */
    public $parentPath;

    /**
     *
     * @var integer
     */
    public $depth;

    /**
     *
     * @var integer
     */
    public $numReply;

    /**
     *
     * @var integer
     */
    public $userId;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var string
     */
    public $email;

    /**
     *
     * @var string
     */
    public $userSite;

    /**
     *
     * @var string
     */
    public $userType;

    /**
     *
     * @var string
     */
    public $sourceName;

    /**
     *
     * @var string
     */
    public $sourceUrl;

    /**
     *
     * @var integer
     */
    public $updatedAt;

    /**
     *
     * @var integer
     */
    public $createdAt;

    public $childrenComments;

    public function getChildrenComments()
    {
        $phql = 'SELECT * FROM Eva\EvaComment\Entities\Comments AS c WHERE c.rootId = :rootId: AND c.status = :status:';

        $manager = $this->getModelsManager();
        $comments = $manager->executeQuery($phql,array('rootId'=>$this->id,'status'=>SELF::STATE_APPROVED));
        return $this->childrenComments = $comments;
    }

    public function initialize()
    {
        $this->belongsTo(
            'threadId',
            '\Eva\EvaComment\Entities\Threads',
            'id',
            array(
                'alias' => 'thread',
                'foreignKey' => true
            )
        );

    }

    public function onConstruct()
    {
        $this->codeType = 'TEXT';
        $this->username = 'anonymous';
        $this->numReply = 0;
        $this->parentId = 0;
        $this->rootId = 0;
        $this->parentPath = '';
        $this->depth = 0;
        $this->status = SELF::STATE_APPROVED;
        $this->createdAt = time();
        $this->updatedAt = time();
    }
}
