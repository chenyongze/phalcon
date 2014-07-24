<?php

namespace Eva\Wiki\Entities;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-11 16:15
// +----------------------------------------------------------------------
// + Entry.php 词条实体
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Model;

class Entries extends Model
{
    protected $tableName = 'wiki_entries';

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
     * @var integer
     */
    public $count;

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

    public function initialize()
    {
        $this->hasMany(
            'categoryId',
            'Eva\Wiki\Entities\Categories',
            'id',
            array('alias' => 'categories')
        );

        $this->hasOne('id', 'Eva\Wiki\Entities\EntryTexts', 'entryId', array(
            'alias' => 'text'
        ));

        $this->belongsTo('userId', 'Eva\EvaUser\Entities\Users', 'id', array(
            'alias' => 'user'
        ));

        $this->hasMany(
            'id',
            'Eva\Wiki\Entities\CategoriesEntries',
            'entryId',
            array('alias' => 'categoriesEntries')
        );

        $this->hasManyToMany(
            'id',
            'Eva\Wiki\Entities\CategoriesEntries',
            'entryId',
            'categoryId',
            'Eva\Wiki\Entities\Categories',
            'id',
            array('alias' => 'categories')
        );

        parent::initialize();
    }
}
