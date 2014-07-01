<?php

namespace Eva\EvaLivenews\Entities;

class Categories extends \Eva\EvaEngine\Mvc\Model
{
    protected $tableName = 'livenews_categories';

    /**
     *
     * @var integer
     */
    public $id;

    /**
     *
     * @var string
     */
    public $categoryName;

    /**
     *
     * @var string
     */
    public $slug;

    /**
     *
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
     * @var integer
     */
    public $imageId;

    /**
     *
     * @var string
     */
    public $image;

    public function initialize()
    {
        $this->hasManyToMany(
            'id',
            'Eva\EvaLivenews\Entities\CategoriesNews',
            'categoryId',
            'newsId',
            'Eva\EvaLivenews\Entities\News',
            'id',
            array('alias' => 'News')
        );

    }
}
