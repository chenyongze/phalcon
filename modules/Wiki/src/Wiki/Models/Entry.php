<?php

namespace Eva\Wiki\Models;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-11 16:17
// +----------------------------------------------------------------------
// + Entry.php
// +----------------------------------------------------------------------

use Eva\EvaEngine\Exception\RuntimeException;
use Eva\EvaFileSystem\Models\Upload;
use Eva\Wiki\Entities\Entries;
use Eva\Wiki\Entities\EntryKeywords;
use Eva\Wiki\Entities\EntryTexts;
use Eva\Wiki\Utils\Pinyin;

class Entry extends Entries
{
    public function getLinkedKeywords()
    {
        $_keywords = '';
        foreach ($this->keywords as $keyword) {
            if ($_keywords != '') {
                $_keywords .= ',';
            }
            $_keywords .= $keyword->keyword;
        }
        return $_keywords;
    }

    public function removeEntry($id)
    {
        $this->id = $id;
        //remove linked keywords
        if ($this->keywords) {
            $this->keywords->delete();
        }
        //remove categories relationship
        if ($this->categoriesEntries) {
            $this->categoriesEntries->delete();
        }
        $this->text->delete();
        $this->delete();
    }

    public function listEntries(array $query, $limit)
    {
        $entries = $this->findEntries($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $entries,
            "limit" => $limit,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $this->findEntries($query);

        return $paginator->getPaginate();
    }

    public function findEntries(array $query = array())
    {
        $itemQuery = $this->getDI()->getModelsManager()->createBuilder();

        $itemQuery->from(__CLASS__);

        $orderMapping = array(
            'id' => 'id ASC',
            '-id' => 'id DESC',
            'created_at' => 'createdAt ASC',
            '-created_at' => 'createdAt DESC'
        );

        if (!empty($query['columns'])) {
            $itemQuery->columns($query['columns']);
        }

        if (!empty($query['q'])) {
            $itemQuery->andWhere('title LIKE :q:', array('q' => "%{$query['q']}%"));
        }

        if (!empty($query['status'])) {
            $itemQuery->andWhere('status = :status:', array('status' => $query['status']));
        }

        if (!empty($query['sourceName'])) {
            $itemQuery->andWhere('sourceName = :sourceName:', array('sourceName' => $query['sourceName']));
        }

        if (!empty($query['uid'])) {
            $itemQuery->andWhere('userId = :uid:', array('uid' => $query['uid']));
        }

        if (!empty($query['cid'])) {
            $itemQuery->join('Eva\EvaBlog\Entities\CategoriesPosts', 'id = r.postId', 'r')
                ->andWhere('r.categoryId = :cid:', array('cid' => $query['cid']));
        }

        $order = 'createdAt DESC';
        if (!empty($query['order'])) {
            $orderArray = explode(',', $query['order']);
            if (count($orderArray) > 1) {
                $order = array();
                foreach ($orderArray as $subOrder) {
                    if ($subOrder && !empty($orderMapping[$subOrder])) {
                        $order[] = $orderMapping[$subOrder];
                    }
                }
            } else {
                $order = empty($orderMapping[$orderArray[0]]) ? array('createdAt DESC') : array($orderMapping[$query['order']]);
            }

            //Add default order as last order
            array_push($order, 'createdAt DESC');
            $order = array_unique($order);
            $order = implode(', ', $order);
        }
        $itemQuery->orderBy($order);
        return $itemQuery;
    }

    public function beforeValidationOnCreate()
    {
        $this->createdAt = $this->createdAt ? $this->createdAt : time();
        $this->userId = $this->userId ? $this->userId : 1;

    }

    public function beforeSave()
    {

        if ($this->getDI()->getRequest()->hasFiles()) {
            $upload = new Upload();
            $files = $this->getDI()->getRequest()->getUploadedFiles();
            if (!$files) {
                return;
            }
            $file = $files[0];
            $file = $upload->upload($file);
            if ($file) {
                $this->imageId = $file->id;
                $this->image = $file->getLocalUrl();
            }
        }
    }

    public function createEntry($data)
    {

        $textData = isset($data['text']) ? $data['text'] : array();
        $synonymies = isset($data['synonymies']) ? $data['synonymies'] : array();
        $categoryData = isset($data['categories']) ? $data['categories'] : array();
        $categoryNamesData = isset($data['categoryNames']) ? $data['categoryNames'] : array();
        if ($textData) {
            unset($data['text']);
            $text = new EntryTexts();
            $text->assign($textData);
            $this->text = $text;
        }

        $keywords = array();
        if ($synonymies) {
            unset($data['synonymies']);
            $synonymiesArray = is_array($synonymies) ? $synonymies : explode(',', $synonymies);
            $mainKeyword = new EntryKeywords();
            $mainKeyword->keyword = $data['title'];
            $keywords[] = $mainKeyword;
            foreach ($synonymiesArray as $_keyword) {
                $keyword = new EntryKeywords();
                $keyword->keyword = $_keyword;

                $keywords[] = $keyword;
            }
            if ($keywords) {
                $this->keywords = $keywords;
            }
        }

        $categories = array();
        if ($categoryData) {
            unset($data['categories']);
            foreach ($categoryData as $categoryId) {
                $category = Category::findFirst($categoryId);
                if ($category) {
                    $categories[] = $category;
                }
            }
        }
        // 通过分类名指定分类
        if($categoryNamesData) {
            unset($data['categoryNames']);
            foreach($categoryNamesData as $_categoryName) {
                $categories[] = Category::getOrCreate($_categoryName);
            }
        }
        if($categories) {
            $this->categories = $categories;
        }
        $this->assign($data);
        $pinyin = new Pinyin();
        $this->initial = substr($pinyin->transformUcwords($this->title), 0, 1);

        if (!$this->save()) {
            throw new RuntimeException('Create post failed');
        }

        return $this;
    }

    public function updateEntry($data)
    {
        $textData = isset($data['text']) ? $data['text'] : array();
        $synonymies = isset($data['synonymies']) ? $data['synonymies'] : array();
        $categoryData = isset($data['categories']) ? $data['categories'] : array();

        if ($textData) {
            unset($data['text']);
            $text = new EntryTexts();
            $text->assign($textData);
            $this->text = $text;
        }

        $keywords = array();
        if ($synonymies) {
            unset($data['synonymies']);
            $synonymiesArray = is_array($synonymies) ? $synonymies : explode(',', $synonymies);
            $synonymiesArray[] = $data['title'];
            $entryKeyword = new EntryKeyword();
            $entryKeyword->fullUpdateEntryKeywords($this->id, $synonymiesArray);
        }

        if ($categoryData) {
            unset($data['categories']);
            $cateEntry = new CategoryEntry();
            $cateEntry->fullUpdateCategoriesEntries($this->id, $categoryData);
        }


        $this->assign($data);
        $pinyin = new Pinyin();
        $this->initial = substr($pinyin->transformUcwords($this->title), 0, 1);
        if (!$this->save()) {
            throw new RuntimeException('Create post failed');
        }

        return $this;
    }
}