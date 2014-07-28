<?php

namespace Eva\Wiki\Entities;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-22 15:37
// +----------------------------------------------------------------------
// + EntryText.php 词条正文实体
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Model;

class EntryTexts extends Model
{
    protected $tableName = 'wiki_entry_texts';

    /**
     *
     * @var integer
     */
    public $entryId;

    /**
     *
     * @var string
     */
    public $metaKeywords;

    /**
     *
     * @var string
     */
    public $metaDescription;

    /**
     *
     * @var string
     */
    public $toc;

    /**
     *
     * @var string
     */
    public $content;

    public function initialize()
    {
        $this->belongsTo('postId', 'Eva\Wiki\Entities\Entries', 'id', array(
            'alias' => 'Entry'
        ));

        parent::initialize();
    }
} 