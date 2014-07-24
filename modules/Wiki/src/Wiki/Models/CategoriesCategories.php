<?php

namespace Eva\Wiki\Models;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-22 18:27
// +----------------------------------------------------------------------
// + CategoriesCategories.php 分类与分类的模型
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Model;

class CategoriesCategories extends \Eva\Wiki\Entities\CategoriesCategories
{
    public function forNewCategoryByParentId($categoryId, $parents)
    {
        $VALUES = '';
        $categoryId = intval($categoryId);
        if ($categoryId <= 0) {
            return false;
        }
        foreach ($parents as $_parent) {
            $_parent = intval($_parent);
            if ($_parent > 0) {
                if ($VALUES != '') {
                    $VALUES .= ',';
                }
                $VALUES .= sprintf('(%d,%d)', $categoryId, $_parent);
            }
        }
        if ($VALUES) {
            $realTableName = $this->getPrefix() . $this->tableName;
            $this->getWriteConnection()->execute('INSERT INTO ' . $realTableName . '(categoryId, parentId) VALUES ' . $VALUES);

        }
    }

    public function forFullUpdateCategoryByParentId($categoryId, $parents)
    {
        $VALUES = '';
        $categoryId = intval($categoryId);
        if ($categoryId <= 0) {
            return false;
        }
        foreach ($parents as $_parent) {
            $_parent = intval($_parent);
            if ($_parent > 0) {
                if ($VALUES != '') {
                    $VALUES .= ',';
                }
                $VALUES .= sprintf('(%d,%d)', $categoryId, $_parent);
            }
        }
        if ($VALUES) {
            $realTableName = $this->getPrefix() . $this->tableName;
            $this->getWriteConnection()->execute('INSERT INTO ' . $realTableName . '(categoryId, parentId) VALUES ' . $VALUES . ' ON DUPLICATE KEY UPDATE categoryId=categoryId;');

            $this->getModelsManager()
                ->executeQuery(
                    sprintf('DELETE FROM Eva\Wiki\Entities\CategoriesCategories  WHERE categoryId=%d AND parentId NOT IN (%s)',
                        $categoryId,
                        implode(',', $parents)
                    )
                );

        }
    }
}
