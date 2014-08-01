<?php

namespace Wscn\Controllers;

use Eva\EvaEngine\Exception;
use Eva\Wiki\Models\Entry;
use Eva\Wiki\Models\EntryKeyword;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-30 18:30
// +----------------------------------------------------------------------
// + WikiController.php
// +----------------------------------------------------------------------

class WikiController extends ControllerBase
{
    public function wikiAction()
    {
        $keyword = $this->dispatcher->getParam('keyword');
        $entry = null;
        $keyword = EntryKeyword::findFirst("keyword='{$keyword}'");
        if ($keyword) {
            $entry = $keyword->entry;
        }
        if (!$entry || $entry->status != 'published') {
            throw new Exception\ResourceNotFoundException('Request entry not found');
        }

        $this->view->setVar('keyword', $keyword);
    }
} 