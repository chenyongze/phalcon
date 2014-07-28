<?php
namespace Eva\EvaComment\Forms;

use Eva\EvaEngine\Form;
use Phalcon\Forms\Element\Select;
use Eva\EvaComment\Models;

class CommentForm extends Form
{

    /**
     * @Type(Hidden)
     * @var integer
     */
    public $id;

    /**
     *
     * @Validator("PresenceOf", message = "Please input comment content")
     * @var string
     */
    public $content;

    /**
     * @Type(Select)
     * @Option("All Status")
     * @Option(approved=Approved)
     * @Option(pending=Pending)
     * @Option(spam=Spam)
     * @Option(ham=Ham)
     * @Option(dangerous=Dangerous)
     * @Option(deleted=Deleted)
     * @var string
     */
    public $status;

    /**
     *
     * @Type(Hidden)
     * @var string
     */
    public $codeType;

    /**
     *
     * @var integer
     */
    public $parentId;

    /**
     * @Type(Hidden)
     * @var integer
     */
    public $createdAt;

    /**
     *
     * @Type(Hidden)
     * @var integer
     */
    public $userId;

    /**
     *
     * @var string
     */
    public $username;

    /**
     * @Type(Hidden)
     * @var integer
     */
    public $updatedAt;


    public function initialize($entity = null, $options = null)
    {
        $this->initializeFormAnnotations();
    }

}
