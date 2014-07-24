<?php

namespace Eva\Wiki\Entities;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-22 18:27
// +----------------------------------------------------------------------
// + Categories.php 分类实体
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Model;

class Categories extends Model
{
    protected $tableName = 'wiki_categories';

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
    public $description;


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
    public $imageId=0;

    /**
     *
     * @var string
     */
    public $image='';


    public function initialize()
    {

        $this->hasManyToMany(
            'id',
            'Eva\Wiki\Entities\CategoriesCategories',
            'categoryId',
            'parentId',
            'Eva\Wiki\Entities\Categories',
            'id',
            array('alias' => 'parents')
        );
        $this->hasManyToMany(
            'id',
            'Eva\Wiki\Entities\CategoriesCategories',
            'parentId',
            'parentId',
            'Eva\Wiki\Entities\Categories',
            'id',
            array('alias' => 'children')
        );
        $this->belongsTo(
            'rootId',
            'Eva\Wiki\Entities\Categories',
            'id',
            array('alias' => 'root')
        );

        parent::initialize();
    }

}