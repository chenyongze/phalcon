<?php

namespace Eva\Wiki\Models;


// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-28 14:48
// +----------------------------------------------------------------------
// + EntryKeywords.php
// +----------------------------------------------------------------------
use Eva\Wiki\Entities\EntryKeywords;

class EntryKeyword extends EntryKeywords
{
    public function fullUpdateEntryKeywords($entryId, $keywords)
    {
        $VALUES = '';
        $entryId = intval($entryId);
        if ($entryId <= 0) {
            return false;
        }
        array_walk($keywords, function (& $item) {
            $item = "'{$item}'";
        });
        foreach ($keywords as $_keyword) {
            $_keyword = trim($_keyword);
            if ($_keyword) {
                if ($VALUES != '') {
                    $VALUES .= ',';
                }
                $VALUES .= sprintf('(%d,%s)', $entryId, $_keyword);
            }
        }

        if ($VALUES) {
            $this->getWriteConnection()->execute('INSERT INTO ' . $this->getSource() . '(entryId, keyword) VALUES ' . $VALUES . ' ON DUPLICATE KEY UPDATE entryId=entryId;');
            // 全量更新时，删除 $keywords 中没有的关键词
            $this->getModelsManager()
                ->executeQuery(
                    sprintf('DELETE FROM Eva\Wiki\Entities\EntryKeywords  WHERE entryId=%d AND keyword NOT IN (%s)',
                        $entryId,
                        implode(',', $keywords)
                    )
                );
        } else {
            // $VALUES为空时，即表示删除所有关键词。
            $this->getModelsManager()
                ->executeQuery(
                    'DELETE FROM Eva\Wiki\Entities\EntryKeywords  WHERE entryId=:entryId:',
                    array(
                        'entryId' => $entryId
                    )
                );
        }
    }
} 