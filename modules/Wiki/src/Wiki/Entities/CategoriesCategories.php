<?php

namespace Eva\Wiki\Entities;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-22 18:27
// +----------------------------------------------------------------------
// + CategoriesCategories.php 分类与分类的关系实体
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Model;

class CategoriesCategories extends Model
{
    protected $tableName = 'wiki_categories_categories';

    /**
     *
     * @var integer
     */
    public $categoryId;

    /**
     *
     * @var integer
     */
    public $parentId;

    /**
     *
     * @var integer
     */
    public $rootId = 0;

    public function initialize()
    {
        $this->belongsTo('categoryId', 'Eva\Wiki\Entities\Categories', 'id',
            array('alias' => 'category')
        );
        $this->belongsTo('parentId', 'Eva\Wiki\Entities\Categories', 'id',
            array('alias' => 'parent')
        );
        $this->belongsTo('rootId', 'Eva\Wiki\Entities\Categories', 'id',
            array('alias' => 'root')
        );

        parent::initialize();
    }
}
