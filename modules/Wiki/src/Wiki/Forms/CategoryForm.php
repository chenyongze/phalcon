<?php
namespace Eva\Wiki\Forms;

use Eva\EvaEngine\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Select;
use Eva\Wiki\Models;

class CategoryForm extends Form
{
    /**
     * @Type(Hidden)
     * @var integer
     */
    public $id;

    /**
     * @Validator("PresenceOf", message = "Please input category name")
     * @var string
     */
    public $categoryName;

    /**
     *
     * @var string
     */
//    public $slug;

    /**
     * @Type(TextArea)
     * @var string
     */
    public $description;

    /**
     *
     * @var integer
     */
    public $parentId;

    /**
     *
     * @var integer
     */
    public $rootId;

    /**
     *
     * @var integer
     */
    public $sortOrder;

    /**
     *
     * @var integer
     */
    public $createdAt;

    /**
     *
     * @var integer
     */
    public $count;

    /**
     *
     * @var integer
     */
    public $leftId;

    /**
     *
     * @var integer
     */
    public $rightId;

    /**
     *
     * @Type(Hidden)
     * @var integer
     */
    public $imageId=0;

    /**
     *
     * @Type(Hidden)
     * @var string
     */
    public $image='';

    public  $parents;

    protected $defaultModelClass = 'Eva\Wiki\Models\Category';

    public function initialize($entity = null, $options = null)
    {
        $select = new Select('parentId');
        $category = new Models\Category();

        $categories = $category->find(array(
            "order" => "id DESC",
            "limit" => 100
        ));
        $categoryArray = array('None');
        foreach ($categories as $key => $item) {
            $categoryArray[$item->id] = $item->categoryName;
        }
        $select->setOptions($categoryArray);
        $this->add($select);
    }
    public function getParents()
    {
        if ($this->parents) {
            return $this->parents;
        }
        $category = new Models\Category();
        $categories = $category->find(array(
            "limit" => 100
        ));

        $category = $this->getModel();
        if ($category->parents) {
            $values = array();
            foreach ($category->parents as $_cate) {
                $values[] = $_cate->id;
            }

            foreach ($categories as $key => $item) {
                $check = new Check('parents[]', array(
                    'value' => $item->id
                ));
                if (in_array($item->id, $values)) {
                    $check->setDefault($item->id);
                }
                $check->setLabel($item->categoryName);
                $this->parents[] = $check;
            }
        }



        return $this->parents;
    }
}
