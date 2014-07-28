<?php
namespace Eva\EvaFileSystem\Forms;

use Eva\EvaEngine\Form;
use Phalcon\Forms\Element\Select;
use Eva\EvaFileSystem\Models;

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
    * @Option(deleted=Deleted)
    * @Option(draft=Draft)
    * @Option(pending=Pending)
    * @Option(published=Published)
    * @var string
    */
    public $status;

    /**
    *
    * @var string
    */
    public $username;

    /**
    *
    * @var string
    */
    public $extension;

    /**
    *
    * @Type(Select)
    * @Option("All Files")
    * @Option("1" : "ImageOnly")
    * @var string
    */
    public $image;

    public function initialize($entity = null, $options = null)
    {
        $this->initializeFormAnnotations();
    }
}
