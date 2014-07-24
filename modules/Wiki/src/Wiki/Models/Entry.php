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

use Eva\Wiki\Entities\Entries;

class Entry extends Entries
{
    public function getLinkedKeywords()
    {
        return '';
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
            if(count($orderArray) > 1) {
                $order = array();
                foreach($orderArray as $subOrder) {
                    if($subOrder && !empty($orderMapping[$subOrder])) {
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
    public function createEntry($data)
    {
        $textData = isset($data['text']) ? $data['text'] : array();
        $tagData = isset($data['tags']) ? $data['tags'] : array();
        $categoryData = isset($data['categories']) ? $data['categories'] : array();

        if($textData) {
            unset($data['text']);
            $text = new Text();
            $text->assign($textData);
            $this->text = $text;
        }

        $tags = array();
        if ($tagData) {
            unset($data['tags']);
            $tagArray = is_array($tagData) ? $tagData : explode(',', $tagData);
            foreach ($tagArray as $tagName) {
                $tag = new Tag();
                $tag->tagName = $tagName;
                $tags[] = $tag;
            }
            if ($tags) {
                $this->tags = $tags;
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
            $this->categories = $categories;
        }


        $this->assign($data);
        if (!$this->save()) {
            throw new Exception\RuntimeException('Create post failed');
        }

        return $this;
    }
}