<?php

namespace Eva\EvaLivenews\Entities;

class News extends \Eva\EvaEngine\Mvc\Model
{
    protected $tableName = 'livenews_news';

    /**
     *
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
     * @var integer
     */
    public $importance = 1;

    /**
     *
     * @var string
     */
    public $flag;

    /**
     *
     * @var string
     */
    public $visibility;

    /**
     *
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
     * @var integer
     */
    public $createdAt;

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
     * @var integer
     */
    public $imageId;

    /**
     *
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
     * @var string
     */
    public $summary;

    /**
     *
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
     * @var string
     */
    public $sourceUrl;

    /**
     *
     * @var string
     */
    public $categorySet;


    public function initialize()
    {
        $this->belongsTo('userId', 'Eva\EvaUser\Entities\Users', 'id', array(
            'alias' => 'user'
        ));

        $this->hasMany(
            'id',
            'Eva\EvaLivenews\Entities\CategoriesNews',
            'newsId',
            array('alias' => 'categoriesNews')
        );

        $this->hasManyToMany(
            'id',
            'Eva\EvaLivenews\Entities\CategoriesNews',
            'newsId',
            'categoryId',
            'Eva\EvaLivenews\Entities\Categories',
            'id',
            array('alias' => 'categories')
        );

        parent::initialize();
    }
}
