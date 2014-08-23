<?php
namespace Eva\EvaLivenews\Forms;

use Eva\EvaEngine\Form;
use Phalcon\Forms\Element\Check;
use Phalcon\Forms\Element\Radio;
use Eva\EvaLivenews\Models;

class NewsForm extends Form
{
    /**
     * @Type(Hidden)
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $title;

    /**
     *
     * @var string
     */
    public $status;

    /**
     *
     * @Type(Number)
     * @var string
     */
    public $importance;

    /**
     *
     * @var string
     */
    public $flag;

    /**
     *
     * @var string
     */
    public $icon;

    /**
     *
     * @var string
     */
    public $visibility;

    /**
     *
     * @Type(Hidden)
     * @var string
     */
    public $type;

    /**
     *
     * @Type(Hidden)
     * @var string
     */
    public $codeType;

    /**
     *
     * @var string
     */
    public $language;

    /**
     *
     * @var integer
     */
    public $parentId;

    /**
     *
     * @var string
     */
    public $slug;

    /**
     *
     * @var integer
     */
    public $sortOrder;

    /**
     *
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
     *
     * @var integer
     */
    public $updatedAt;

    /**
     *
     * @var integer
     */
    public $editorId;

    /**
     *
     * @var string
     */
    public $editorName;

    /**
     *
     * @var string
     */
    public $commentStatus;

    /**
     *
     * @var string
     */
    public $commentType;

    /**
     *
     * @var integer
     */
    public $commentCount;

    /**
     *
     * @var integer
     */
    public $count;

    /**
     *
     * @Type(Hidden)
     * @var integer
     */
    public $imageId;

    /**
     * @Type(Hidden)
     * @var string
     */
    public $image;

    /**
     *
     * @var integer
     */
    public $imageCount;

    /**
     *
     * @var integer
     */
    public $videoId;

    /**
     *
     * @var string
     */
    public $video;

    /**
     *
     * @var integer
     */
    public $videoCount;

    /**
     *
     * @Type(TextArea)
     * @var string
     */
    public $summary;

    /**
     *
     * @Validator("PresenceOf", message = "Please input content")
     * @Type(TextArea)
     * @var string
     */
    public $content;

    /**
     *
     * @var string
     */
    public $sourceName;

    /**
     *
     * @Type(Url)
     * @var string
     */
    public $sourceUrl;

    /**
     *
     * @var string
     */
    public $categorySet;

    protected $categories;

    public function getCategories()
    {
        if ($this->categories) {
            return $this->categories;
        }
        $category = new Models\Category();
        $categories = $category->find(array(
            "order" => "id DESC",
            "limit" => 100
        ));

        $post = $this->getModel();
        $values = array();
        if ($post && $post->categories) {
            foreach ($post->categories as $categoryitem) {
                $values[] = $categoryitem->id;
            }
        }
        foreach ($categories as $key => $item) {
            $check = new Check('categories[]', array(
                'value' => $item->id
            ));
            if (in_array($item->id, $values)) {
                $check->setDefault($item->id);
            }
            $check->setLabel($item->categoryName);
            $this->categories[] = $check;
        }

        return $this->categories;
    }

    public function getIcons()
    {
        $icons = array(
            'reminder' => 'Reminder',
            'rumor' => 'Rumor',
            'warning' => 'Warning',
        );
        
        $radios = array();
        $news = $this->getModel();
        foreach ($icons as $icon => $label) {
            $radio = new Radio('icon', array(
                'value' => $icon
            ));
            $radio->setLabel($label);
            $radio->setDefault($news->icon);
            $radios[$icon] = $radio;
        }
        return $this->radio = $radios;
    }

    public function initialize($entity = null, $options = null)
    {
    }
}
