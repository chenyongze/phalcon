<?php
namespace Eva\Wiki\Forms;

use Eva\EvaEngine\Form;

class EntryTextForm extends Form
{
    /**
     *
     * @var integer
     */
    public $postId;

    /**
     *
     * @var string
     */
    public $metaKeywords;

    /**
     *
     * @Type(TextArea)
     * @var string
     */
    public $metaDescription;

    /**
     *
     * @var string
     */
    public $toc;

    /**
     * @Type(TextArea)
     * @var string
     */
    public $content;

    protected $defaultModelClass = 'Eva\EvaBlog\Models\Text';
}
