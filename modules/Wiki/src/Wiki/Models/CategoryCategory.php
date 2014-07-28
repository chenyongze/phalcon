<?php

namespace Eva\Wiki\Models;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-22 18:27
// +----------------------------------------------------------------------
// + CategoryCategory.php 分类与分类的模型
// +----------------------------------------------------------------------

use Eva\EvaEngine\Mvc\Model;
use Eva\Wiki\Entities\CategoriesCategories;

class CategoryCategory extends CategoriesCategories
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

            $category = new Category();
            $category->unmarkRoot($categoryId);

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
        $category = new Category();

        if ($VALUES) {
            $this->getWriteConnection()->execute('INSERT INTO ' . $this->getSource() . '(categoryId, parentId) VALUES ' . $VALUES . ' ON DUPLICATE KEY UPDATE categoryId=categoryId;');
            // 全量更新时，删除 $parents 中没有的 id
            $this->getModelsManager()
                ->executeQuery(
                    sprintf('DELETE FROM Eva\Wiki\Entities\CategoriesCategories  WHERE categoryId=%d AND parentId NOT IN (%s)',
                        $categoryId,
                        implode(',', $parents)
                    )
                );
            $category->unmarkRoot($categoryId);
        } else {
            // $VALUES为空时，即表示解除所有分类父子关系。
            $this->getModelsManager()
                ->executeQuery(
                    'DELETE FROM Eva\Wiki\Entities\CategoriesCategories  WHERE categoryId=:categoryId:',
                    array(
                        'categoryId' => $categoryId
                    )

                );
            $category->markRoot($categoryId);
        }
    }
}
