<?php
namespace Eva\EvaComment\Forms;

use Eva\EvaEngine\Form;
use Phalcon\Forms\Element\Select;
use Eva\EvaComment\Models;

class ThreadForm extends Form
{

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
     * @Type(Select)
     * @Option(approved=Approved)
     * @Option(pending=Pending)
     * @var string
     */
    public $defaultCommentStatus;

    public function initialize($entity = null, $options = null)
    {
        $this->initializeFormAnnotations();
    }

}
