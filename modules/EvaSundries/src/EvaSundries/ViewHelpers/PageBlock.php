<?php

namespace Eva\EvaSundries\ViewHelpers;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-19 16:47
// +----------------------------------------------------------------------

use Eva\EvaSundries\Models\Block;

class PageBlock
{
    public function __invoke($name)
    {
        $block = Block::findFirst("name='{$name}'");
        $content = '';

        if($block && $block->visibility == 'visible') {
            $content = $block->content;
        }
        return $content;
    }
}