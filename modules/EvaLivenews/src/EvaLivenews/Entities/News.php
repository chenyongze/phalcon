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
    public $status = 'published';

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
    public $icon;

    /**
     *
     * @var string
     */
     public $visibility = 'public';

    /**
     *
     * @var string
     */
    public $codeType = 'markdown';

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
    public $commentStatus = 'open';

    /**
     *
     * @var string
     */
    public $commentType = 'local';

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
     * @var integer
     */
     public $imageCount = 0;

    /**
     *
     * @var integer
     */
    public $videoId = 0;

    /**
     *
     * @var string
     */
    public $video;

    /**
     *
     * @var integer
     */
    public $videoCount = 0;

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

    public function getContentHtml()
    {
        if(!$this->content) {
            return '';
        }

        if ($this->codeType == 'markdown') {
            $parsedown = new \Parsedown();
            return $parsedown->text($this->content);
        } elseif ($this->codeType == 'json') {
            $data = json_decode($this->content);
            return preg_replace_callback('/{{(.+?)}}/', function($matches) use ($data) {
                return empty($data->$matches[1]) ? '' : $data->$matches[1];
            }, '{{title}} {{actual}}，预期{{forecast}}，前值{{previous}}');
        } else {
            return $this->content;
        }
    }

    public function initialize()
    {
        $this->belongsTo('userId', 'Eva\EvaUser\Entities\Users', 'id', array(
            'alias' => 'user'
        ));

        $this->hasOne('id', 'Eva\EvaLivenews\Entities\Texts', 'newsId', array(
            'alias' => 'text'
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
