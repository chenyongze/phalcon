<?php

namespace Eva\EvaBlog\Models;

use Eva\EvaBlog\Entities;

class Tag extends Entities\Tags
{

    public function findTags($query = null)
    {
        $itemQuery = $this->getDI()->getModelsManager()->createBuilder();

        //SELECT t.* FROM tags AS t LEFT JOIN thread_tag_map AS ttm ON t.id = ttm.tags_id GROUP BY t.id ORDER BY COUNT(t.id) DESC LIMIT 30
        $itemQuery
        ->from(__CLASS__)
        ->columns(array(
            'id', 'tagName', 'COUNT(id) AS tagCount'
        ))
        ->leftJoin('Eva\EvaBlog\Entities\TagsPosts', 'id = r.tagId', 'r')
        ->groupBy('id')
        ->orderBy('COUNT(id) DESC')
        ->limit(10);
        return $itemQuery;
    }
}
