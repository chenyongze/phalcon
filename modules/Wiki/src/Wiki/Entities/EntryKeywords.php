<?php

namespace Eva\Wiki\Entities;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-25 17:26
// +----------------------------------------------------------------------
// + EntryKeywords.php
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Model;

class EntryKeywords extends Model
{
    protected $tableName = 'wiki_entry_keywords';

    /**
     * @var int 所属词条 ID
     */
    public $entryId;

    /**
     * @var string 关键词
     */
    public $keyword;

    public function initialize()
    {

        $this->belongsTo('entryId', 'Eva\Wiki\Models\Entry', 'id',
            array('alias' => 'entry')
        );

        parent::initialize();
    }
} 