<?php

namespace Eva\Wiki\Models;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-28 14:56
// +----------------------------------------------------------------------
// + CategoryEntry.php
// +----------------------------------------------------------------------

use Eva\Wiki\Entities\CategoriesEntries;

class CategoryEntry extends CategoriesEntries
{
    public function fullUpdateCategoriesEntries($entryId, $categories)
    {
        $VALUES = '';
        $entryId = intval($entryId);
        if ($entryId <= 0) {
            return false;
        }
        foreach ($categories as $_category) {
            $_category = intval($_category);
            if ($_category > 0) {
                if ($VALUES != '') {
                    $VALUES .= ',';
                }
                $VALUES .= sprintf('(%d,%d)', $entryId, $_category);
            }
        }

        if ($VALUES) {
            $this->getWriteConnection()->execute('INSERT INTO ' . $this->getSource() . '(entryId, categoryId) VALUES ' . $VALUES . ' ON DUPLICATE KEY UPDATE entryId=entryId;');
            // 全量更新时，删除 $keywords 中没有的关键词
            $this->getModelsManager()
                ->executeQuery(
                    sprintf('DELETE FROM Eva\Wiki\Entities\CategoriesEntries  WHERE entryId=%d AND categoryId NOT IN (%s)',
                        $entryId,
                        implode(',', $categories)
                    )
                );
        } else {
            // $VALUES为空时，即表示删除所有关键词。
            $this->getModelsManager()
                ->executeQuery(
                    'DELETE FROM Eva\Wiki\Entities\CategoriesEntries  WHERE entryId=:entryId:',
                    array(
                        'entryId' => $entryId
                    )

                );
        }
    }
}