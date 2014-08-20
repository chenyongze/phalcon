<?php
namespace Wscn\Forms;

use Eva\EvaEngine\Form;
use Phalcon\Forms\Element\Select;
use Eva\EvaBlog\Models;

class AppoptionForm extends Form
{
    /**
     *
     * @Type(Hidden)
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $endpoint;

    /**
     *
     * @var string
     */
    public $description;

    /**
     *
     * @Type(TextArea)
     * @var string
     */
    public $data;

    /**
     *
     * @Type(Hidden)
     * @var integer
     */
    public $createdAt;

    /**
     *
     * @var integer
     */
    public $updatedAt;

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

    public function initialize($entity = null, $options = null)
    {
    }
}
