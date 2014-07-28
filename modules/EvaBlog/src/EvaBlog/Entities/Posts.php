<?php

namespace Eva\EvaBlog\Entities;

use Eva\EvaBlog\Entities\Texts;

class Posts extends \Eva\EvaEngine\Mvc\Model
{
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
    public $status = 'pending';

    /**
     *
     * @var string
     */
    public $flag;

    /**
     *
     * @var string
     */
    public $visibility = 'public';

    /**
     *
     * @var string
     */
    public $codeType = 'html';

    /**
     *
     * @var string
     */
    public $language;

    /**
     *
     * @var integer
     */
    public $parentId = 0;

    /**
     *
     * @var string
     */
    public $slug;

    /**
     *
     * @var integer
     */
    public $sortOrder = 0;

    /**
     *
     * @var integer
     */
    public $createdAt;

    /**
     *
     * @var integer
     */
    public $userId = 0;

    /**
     *
     * @var string
     */
    public $username;

    /**
     *
     * @var integer
     */
    public $updatedAt = 0;

    /**
     *
     * @var integer
     */
    public $editorId = 0;

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
    public $commentCount = 0;

    /**
     *
     * @var integer
     */
    public $count = 0;

    /**
     *
     * @var integer
     */
    public $imageId = 0;

    /**
     *
     * @var string
     */
    public $image;

    /**
     *
     * @var string
     */
    public $summary;

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
     * @var decimal
     */
    public $voteScore = 0;

    protected $tableName = 'blog_posts';

    public function initialize()
    {
        $this->hasOne('id', 'Eva\EvaBlog\Entities\Texts', 'postId', array(
            'alias' => 'text'
        ));

        $this->belongsTo('userId', 'Eva\EvaUser\Entities\Users', 'id', array(
            'alias' => 'user'
        ));

        $this->hasMany(
            'id',
            'Eva\EvaBlog\Entities\CategoriesPosts',
            'postId',
            array('alias' => 'categoriesPosts')
        );

        $this->hasManyToMany(
            'id',
            'Eva\EvaBlog\Entities\CategoriesPosts',
            'postId',
            'categoryId',
            'Eva\EvaBlog\Entities\Categories',
            'id',
            array('alias' => 'categories')
        );

        $this->hasMany(
            'id',
            'Eva\EvaBlog\Entities\TagsPosts',
            'postId',
            array('alias' => 'tagsPosts')
        );

        $this->hasManyToMany(
            'id',
            'Eva\EvaBlog\Entities\TagsPosts',
            'postId',
            'tagId',
            'Eva\EvaBlog\Entities\Tags',
            'id',
            array('alias' => 'tags')
        );

        $this->hasManyToMany(
            'id',
            'Eva\EvaBlog\Entities\Connections',
            'sourceId',
            'targetId',
            'Eva\EvaBlog\Entities\Posts',
            'id',
            array('alias' => 'connections')
        );


        $this->hasOne('imageId', 'Eva\EvaFileSystem\Entities\Files', 'id', array(
            'alias' => 'thumbnail'
        ));

        parent::initialize();
    }
}
