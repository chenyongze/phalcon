<?php

namespace Eva\Wiki\Entities;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-22 18:27
// +----------------------------------------------------------------------
// + CategoriesEntries.php 分类与词条的关系实体
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Model;

class CategoriesEntries extends Model
{
    protected $tableName = 'wiki_categories_entries';

    /**
     *
     * @var integer
     */
    public $categoryId;

    /**
     *
     * @var integer
     */
    public $entryId;



    public function initialize()
    {
        $this->belongsTo('categoryId', 'Eva\Wiki\Entities\Categories', 'id',
            array('alias' => 'category')
        );
        $this->belongsTo('entryId', 'Eva\Wiki\Entities\Entries', 'id',
            array('alias' => 'entry')
        );

        parent::initialize();
    }
}
