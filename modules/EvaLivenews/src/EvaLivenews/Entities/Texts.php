<?php

namespace Eva\EvaLivenews\Entities;

class Texts extends \Eva\EvaEngine\Mvc\Model
{

    /**
     *
     * @var integer
     */
    public $newsId;

    /**
     *
     * @var string
     */
    public $contentExtra;

    /**
     *
     * @var string
     */
    public $contentFollowup;

    /**
     *
     * @var string
     */
    public $contentAnalysis;

    protected $tableName = 'livenews_texts';

    public function initialize()
    {
        $this->belongsTo('newsId', 'Eva\EvaLivenews\Entities\News', 'id', array(
            'alias' => 'News'
        ));
        parent::initialize();
    }
}
