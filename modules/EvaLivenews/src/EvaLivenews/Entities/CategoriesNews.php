<?php

namespace Eva\EvaLivenews\Entities;

class CategoriesNews extends \Eva\EvaEngine\Mvc\Model
{
    protected $tableName = 'livenews_categories_news';

    /**
     *
     * @var integer
     */
    public $categoryId;

    /**
     *
     * @var integer
     */
    public $newsId;

    public function initialize()
    {
        $this->belongsTo('categoryId', 'Eva\EvaLivenews\Entities\Categories', 'id',
            array('alias' => 'category')
        );
        $this->belongsTo('newsId', 'Eva\EvaLivenews\Entities\News', 'id',
            array('alias' => 'news')
        );
    }
}
