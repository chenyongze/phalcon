<?php

namespace Eva\Wiki\Models;

use Eva\EvaEngine\Paginator;
use Eva\Wiki\Entities;
use Eva\EvaFileSystem\Models\Upload as UploadModel;
use Eva\EvaEngine\Exception;

class Category extends Entities\Categories
{
    /**
     * 获取父分类名
     *
     * @param string $separator 分隔符
     * @return string 指定分隔符的字符串表示
     */
    public function getParentCategoryNames($separator = ', ')
    {
        $parentCategoryNames = '';
        if ($this->parents) {
            $_parentCategoryNames = array();
            foreach ($this->parents as $_parentCate) {
                $_parentCategoryNames[] = $_parentCate->categoryName;
            }
            $parentCategoryNames = implode($separator, $_parentCategoryNames);
        }

        return $parentCategoryNames;
    }

    /**
     * 获取儿子分类名
     *
     * @param string $separator 分隔符
     * @return string 指定分隔符的字符串表示
     */
    public function getChildCategoryNames($separator = ', ')
    {
        $childCategoryNames = '';
        if ($this->children) {
            $_childCategoryNames = array();
            foreach ($this->children as $_childCate) {
                $_childCategoryNames[] = $_childCate->categoryName;
            }
            $childCategoryNames = implode($separator, $_childCategoryNames);
        }

        return $childCategoryNames;

    }

    public function beforeValidationOnCreate()
    {
        $this->createdAt = time();
    }


    /**
     * 列出所有分类
     *
     * @param int $limit 每页条数
     * @param int $currentPage 当前页码
     * @return \stdClass
     */
    public function listCategories($limit = 10, $currentPage = 1)
    {
        $limit = $limit > 50 ? 50 : $limit;
        $limit = $limit < 10 ? 10 : $limit;

        $items = $this->getDI()->getModelsManager()->createBuilder()
            ->from('Eva\Wiki\Models\Category');

        $paginator = new Paginator(array(
            "builder" => $items,
            "limit" => 500,
            "page" => $currentPage
        ));

        $pager = $paginator->getPaginate();
        return $pager;
    }

    public function beforeUpdate()
    {
        /*
        //not allow set self to parent
        if (!$this->parentId || $this->parentId == $this->id) {
            $this->parentId = 0;
            $this->rootId = 0;
        } else {
            $parentCategory = self::findFirst($this->parentId);
            if ($parentCategory) {
                if (
                    $parentCategory->rootId == $this->id  //not allow move to child node
                    || $parentCategory->rootId == $this->rootId
                ) {
                    throw new Exception\InvalidArgumentException('ERR_BLOG_CATEGORY_NOT_ALLOW_MOVE');

                } else {
                    if ($parentCategory->parentId) {
                        $this->rootId = $parentCategory->rootId;
                    } else {
                        $this->rootId = $parentCategory->id;
                    }
                }

            } else {
                $this->rootId = 0;
            }
        }
        */
    }

    public function createCategory(array $data)
    {


        if ($this->getDI()->getRequest()->hasFiles()) {
            $upload = new UploadModel();
            $files = $this->getDI()->getRequest()->getUploadedFiles();
            if (!$files) {
                return;
            }
            $file = $files[0];
            $file = $upload->upload($file);
            if ($file) {
                $this->imageId = $file->id;
                $this->image = $file->getFullUrl();
            }
        }

        if ($this->save()) {
            if ($data['parents']) {
                $cateCate = new CategoriesCategories();
                $cateCate->forNewCategoryByParentId($this->id, $data['parents']);
            }
        }
    }

    public function updateCategory(array $data)
    {
        if ($this->getDI()->getRequest()->hasFiles()) {
            $upload = new UploadModel();
            $files = $this->getDI()->getRequest()->getUploadedFiles();
            if (!$files) {
                return;
            }
            $file = $files[0];
            $file = $upload->upload($file);
            if ($file) {
                $this->imageId = $file->id;
                $this->image = $file->getFullUrl();
            }
        }

        if ($this->save()) {
            if ($data['parents']) {
                $cateCate = new CategoriesCategories();
                $cateCate->forFullUpdateCategoryByParentId($this->id, $data['parents']);
            }
        }
    }
}
