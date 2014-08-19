<?php

namespace Eva\EvaSundries\Models;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-18 15:23
// +----------------------------------------------------------------------

use Eva\EvaEngine\Paginator;
use Eva\EvaSundries\Entities\Blocks;

class Block extends Blocks
{
    public function beforeValidationOnCreate()
    {
        $this->updatedAt = $this->createdAt = $this->createdAt ? $this->createdAt : time();
    }

    public function beforeValidationOnUpdate()
    {
        $this->updatedAt = $this->updatedAt ? $this->updatedAt : time();
    }

    public function findBlocks($query)
    {
        $builder = $this->getDI()->getModelsManager()->createBuilder();
        $builder->from(__CLASS__);
        $builder->orderBy('createdAt DESC');
        $paginator = new Paginator(array(
            "builder" => $builder,
            "limit" => isset($query['limit']) ? $query['limit'] : 25,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        return $pager;
    }
}