<?php
namespace Eva\EvaComment\Forms;

use Eva\EvaEngine\Form;
use Phalcon\Forms\Element\Select;
use Eva\EvaComment\Models;

class FilterForm extends Form
{
    /**
    * @Type(Hidden)
    * @var integer
    */
    public $uid;

    /**
    *
    * @var string
    */
    public $q;

    /**
    *
    * @Type(Select)
    * @Option("25":"25")
    * @Option("10":"10")
    * @Option("50":"50")
    * @Option("100":"100")
    * @var string
    */
    public $per_page;

    /**
    *
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
    * @var string
    */
    public $username;

    public function initialize($entity = null, $options = null)
    {
        $this->initializeFormAnnotations();
    }

}
