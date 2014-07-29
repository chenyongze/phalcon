<?php

namespace Eva\Wiki\Models;

use Eva\EvaEngine\Paginator;
use Eva\Wiki\Entities;
use Eva\EvaFileSystem\Models\Upload as UploadModel;
use Eva\EvaEngine\Exception;
use Eva\Wiki\Forms\CategoryForm;
use Eva\Wiki\Utils\Pinyin;


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
            "limit" => 100,
            "page" => $currentPage
        ));

        $pager = $paginator->getPaginate();
        return $pager;
    }

    /**
     *
     * @param array $data
     */
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
        $pinyin = new Pinyin();
        $this->initial = substr($pinyin->transformUcwords($this->categoryName), 0, 1);
        if ($this->save()) {
            if ($data['parents']) {
                $cateCate = new CategoryCategory();
                $cateCate->forNewCategoryByParentId($this->id, $data['parents']);
            }
        }
    }

    /**
     * @param array $data
     */
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
        $pinyin = new Pinyin();
        $this->initial = substr($pinyin->transformUcwords($this->categoryName), 0, 1);
        if ($this->save()) {
            $parents = isset($data['parents']) ? $data['parents'] : array();

            $cateCate = new CategoryCategory();
            $cateCate->forFullUpdateCategoryByParentId($this->id, $parents);
        }
    }

    /**
     * 标记指定分类为根分类
     *
     * @param $categoryId
     */
    public function markRoot($categoryId)
    {
        $this->getModelsManager()->executeQuery('UPDATE Eva\Wiki\Entities\Categories SET isRoot=1 WHERE id=:categoryId:', array(
            'categoryId' => $categoryId
        ));
    }

    /**
     * 取消标记指定根分类
     *
     * @param $categoryId
     */
    public function unmarkRoot($categoryId)
    {
        $this->getModelsManager()->executeQuery('UPDATE Eva\Wiki\Entities\Categories SET isRoot=0 WHERE id=:categoryId:', array(
            'categoryId' => $categoryId
        ));
    }

    /**
     * 通过分类名、父分类名创建分类。自动检测分类名是否已经存在
     *
     * @param $categoryName
     * @param $parentNames
     * @return Category
     * @throws Exception\RuntimeException
     */
    public function createCategoryByNames($categoryName, array $parentNames)
    {
        $categoryName = addslashes($categoryName);
        $subCategory = self::getOrCreate($categoryName);
        if(!$subCategory) {
            return false;
        }
        $parentIds = array();
        foreach ($parentNames as $_parentName) {
            $_category = self::getOrCreate($_parentName);
            if ($_category->id > 0) {
                $parentIds[] = $_category->id;

            }
        }
        $cateCate = new CategoryCategory();
        $cateCate->forFullUpdateCategoryByParentId($subCategory->id, $parentIds);
        return $subCategory->id;
    }
    public static  function getOrCreate($categoryName)
    {
        $categoryDAO = new Category();
        $category = $categoryDAO->findFirst("categoryName='{$categoryName}'");
        $pinyin = new Pinyin();

        if(!$category) {
            $category->categoryName = $categoryName;
            $category->initial = substr($pinyin->transformUcwords($category->categoryName), 0, 1);

            $category->save();
        }
        return $category;

    }
}
