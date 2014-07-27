<?php
namespace Eva\EvaPermission\Forms;

use Eva\EvaEngine\Form;
use Phalcon\Forms\Element\Select;
use Eva\EvaPermission\Entities;

class OperationFilterForm extends Form
{
    /**
    * @Type(Hidden)
    * @var integer
    */
    public $rid;

    /**
    *
    * @var string
    */
    public $q;

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

    protected $roleid;

    public function addRole()
    {
        if ($this->roleid) {
            return $this->roleid;
        }

        $roles = Entities\Roles::find();
        $options = array('All Roles');
        if ($roles) {
            foreach ($roles as $role) {
                $options[$role->id] = $role->roleKey . ' | ' . $role->name;
            }
        }
        $element = new Select('roleid', $options);
        $this->add($element);

        return $this->roleid = $element;
    }

    public function initialize($entity = null, $options = null)
    {
        $this->initializeFormAnnotations();
        $this->addRole();
    }
}
