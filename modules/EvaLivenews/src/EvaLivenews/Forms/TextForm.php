<?php
namespace Eva\EvaLivenews\Forms;

use Eva\EvaEngine\Form;

class TextForm extends Form
{
    /**
     *
     * @var integer
     */
    public $newsId;

    /**
     *
     * @Type(TextArea)
     * @var string
     */
    public $contentExtra;

    /**
     *
     * @Type(TextArea)
     * @var string
     */
    public $contentFollowup;

    /**
     *
     * @Type(TextArea)
     * @var string
     */
    public $contentAnalysis;

    protected $defaultModelClass = 'Eva\EvaLivenews\Models\Text';
}
