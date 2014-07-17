<?php

namespace Eva\EvaBlog\Tasks;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-17 15:48
// +----------------------------------------------------------------------
// + CounterTask.php  计数器相关任务
// +----------------------------------------------------------------------

use Eva\CounterRank\Utils\CounterRankUtil;
use Eva\EvaBlog\Models\Post;
use mr5\CounterRank\CounterRank;
use Eva\EvaEngine\Tasks\TaskBase;
use Phalcon\Db\Adapter\Cacheable\Mysql;
use Phalcon\Mvc\Model\Query;

class CounterTask extends TaskBase
{
    public function mainAction($params)
    {
        $this->persistAction($params);
    }
    public function persistAction($params)
    {
        if(empty($params)) {
            $this->output->writelnError('[Error]: Group Name is null');

        } else {
            $counterRank = new CounterRankUtil();
            $counterRank = $counterRank->getCounterRank($params[0]);
            $post = new Post();
            $count = 0;
            $counterRank->persistHelper(
                function(array $items) use ($post, & $count) {
                    $values = '';
                    $count += count($items);
                    foreach($items AS $post_id=>$heat) {
                        if($values != '') {
                            $values .= ',';
                        }
                        $values .= "({$post_id}, {$heat}, '', 'private', '', 0)";
                    }

                    $post->getWriteConnection()->execute('INSERT INTO eva_blog_posts(id, count, title, visibility, slug, createdAt) VALUES  '.$values.' ON DUPLICATE KEY UPDATE count=count+values(`count`);');
                },
                CounterRank::PERSIST_WITH_DELETING
            );
            $this->output->writelnComment('Done! Persist '.$count.' items;');
        }
    }
} 