<?php

namespace Eva\CounterRank\Events;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-11 10:48
// +----------------------------------------------------------------------
// + BlogListener.php
// +----------------------------------------------------------------------

use Eva\CounterRank\Utils\CounterRankUtil;

class BlogListener
{
    public function afterCreate($event, $post)
    {
        if(!$post->id) {
            return;
        }

        $counterRankUtil = new CounterRankUtil();
        $counterRankUtil->getCounterRank('posts')->create($post->id);
    }
} 