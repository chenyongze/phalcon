<?php
namespace Eva\Wiki\Forms;

use Eva\EvaEngine\Form;

class EntryTextForm extends Form
{
    /**
     *
     * @var integer
     */
    public $entryId;

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

    protected $defaultModelClass = 'Eva\Wiki\Models\EntryText';
}
