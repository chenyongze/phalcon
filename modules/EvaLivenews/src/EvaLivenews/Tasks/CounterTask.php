<?php

namespace Eva\EvaLivenews\Tasks;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-17 15:48
// +----------------------------------------------------------------------
// + CounterTask.php  实时新闻计数器相关任务
// +----------------------------------------------------------------------

use Eva\CounterRank\Utils\CounterRankUtil;
use Eva\EvaLivenews\Entities\News;
use mr5\CounterRank\CounterIterator;
use Eva\EvaEngine\Tasks\TaskBase;
use Phalcon\Mvc\Model\Query;

class CounterTask extends TaskBase
{
    public function mainAction($params)
    {
        $this->persistAction($params);
    }

    public function persistAction($params)
    {

        $counterRank = new CounterRankUtil();
        $counterRank = $counterRank->getCounterRank('livenews');
        $post = new News();
        $count = 0;
        $tableName = $post->getSource();
        foreach ($counterRank->getIterator(100, CounterIterator::PERSIST_WITH_DELETING) as $items) {
            $values = '';
            $count += count($items);
            $ids = '';
            foreach ($items as $post_id => $heat) {

                if ($ids != '') {
                    $ids .= ',';
                }
                $ids .= $post_id;
                $values .= " WHEN id={$post_id} THEN `viewCount`+{$heat} ";
//                    $values .= "({$post_id}, {$heat}, '', 'private', '', 0)";
            }
            $sql = <<<SQL
UPDATE {$tableName} SET `viewCount` = CASE
    {$values}
    ELSE `viewCount`
END
WHERE `id` IN({$ids})
SQL;
            $post->getWriteConnection()->execute($sql);
        }
        $this->output->writelnComment('Done! Persist ' . $count . ' items;');

    }
}
