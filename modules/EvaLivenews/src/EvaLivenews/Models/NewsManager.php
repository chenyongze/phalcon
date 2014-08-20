<?php

namespace Eva\EvaLivenews\Models;

use Eva\EvaLivenews\Entities;
use Eva\EvaUser\Models\Login as LoginModel;
use Eva\EvaFileSystem\Models\Upload as UploadModel;
use Eva\EvaEngine\Exception;


class NewsManager extends Entities\News
{
    public static $defaultDump = array(
        'id',
        'title',
        'codeType',
        'importance',
        'createdAt',
        'contentHtml' => 'getContentHtml',
        'data' => 'getData',
        'commentStatus',
        'sourceName',
        'sourceUrl',
        'categories' => array(
            'id',
            'categoryName',
        ),
        'user' => array(
            'id',
            'username',
        ),
        'text' => array(
            'contentExtra',
            'contentFollowup',
            'contentAnalysis',
        ),
    );

    public static $simpleDump = array(
        'id',
        'title',
        'codeType',
        'importance',
        'createdAt',
        'contentHtml' => 'getContentHtml',
        'data' => 'getData',
        'commentStatus',
        'sourceName',
        'sourceUrl',
        'userId',
        'categorySet',
    );

    public function beforeValidationOnCreate()
    {
        $this->createdAt = $this->createdAt ? $this->createdAt : time();
        if (!$this->slug) {
            $this->slug = \Phalcon\Text::random(\Phalcon\Text::RANDOM_ALNUM, 8);
        }
        if (!$this->title) {
            $this->title = \Eva\EvaEngine\Text\Substring::substrCn(strip_tags($this->getContentHtml()), 100);
        }
    }

    public function beforeValidationOnUpdate()
    {
    }


    public function beforeCreate()
    {
        $user = new LoginModel();
        if ($userinfo = $user->isUserLoggedIn()) {
            $this->userId = $this->userId ? $this->userId : $userinfo['id'];
            $this->username = $this->username ? $this->username : $userinfo['username'];
        }
    }

    public function beforeUpdate()
    {
        $user = new LoginModel();
        if ($userinfo = $user->isUserLoggedIn()) {
            $this->editorId = $userinfo['id'];
            $this->editorName = $userinfo['username'];
        }
        $this->updatedAt = time();
    }

    public function beforeSave()
    {
        //Data importance will overwrite news importance
        if ($data = $this->getData()) {
            $this->importance = $data->importance;
        }

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
                $this->image = $file->getLocalUrl();
            }
        }
    }

    public function validation()
    {
        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    public function findNews(array $query = array())
    {
        $itemQuery = $this->getDI()->getModelsManager()->createBuilder();

        $itemQuery->from(__CLASS__);

        $orderMapping = array(
            'id' => 'id ASC',
            '-id' => 'id DESC',
            'created_at' => 'createdAt ASC',
            '-created_at' => 'createdAt DESC',
            'sort_order' => 'sortOrder ASC',
            '-sort_order' => 'sortOrder DESC',
        );

        if (!empty($query['columns'])) {
            $itemQuery->columns($query['columns']);
        }

        if (!empty($query['q'])) {
            $itemQuery->andWhere('content LIKE :q:', array('q' => "%{$query['q']}%"));
        }

        if (!empty($query['status'])) {
            $itemQuery->andWhere('status = :status:', array('status' => $query['status']));
        }

        if (!empty($query['codeType'])) {
            $itemQuery->andWhere('codeType = :codeType:', array('codeType' => $query['codeType']));
        }

        if (!empty($query['uid'])) {
            $itemQuery->andWhere('userId = :uid:', array('uid' => $query['uid']));
        }

        if (!empty($query['cid'])) {
            $cidArray = explode(',', $query['cid']);
            $setArray = array();
            $valueArray = array();
            foreach ($cidArray as $key => $cid) {
                $setArray[] = "FIND_IN_SET(:cid_$key:, categorySet)";
                $valueArray["cid_$key"] = $cid;
            }

            $itemQuery->andWhere(implode(' OR ', $setArray), $valueArray);
            /*
            $itemQuery->join('Eva\EvaLivenews\Entities\CategoriesNews', 'id = r.newsId', 'r')
            ->andWhere('r.categoryId = :cid:', array('cid' => $query['cid']));
            */
        }

        if (!empty($query['limit'])) {
            $itemQuery->limit($query['limit']);
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

    public function createNews(array $data)
    {
        $this->getDI()->getEventsManager()->fire('livenews:beforeCreate', $this);

        $categoryData = isset($data['categories']) ? $data['categories'] : array();
        $textData = isset($data['text']) ? $data['text'] : array();

        if ($textData) {
            unset($data['text']);
            $text = new Text();
            $text->assign($textData);
            $this->text = $text;
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
            $this->categorySet = implode(',', $categoryData);
            $this->categories = $categories;
        }


        $this->assign($data);
        if (!$this->save()) {
            throw new Exception\RuntimeException('Create news failed');
        }

        $this->getDI()->getEventsManager()->fire('livenews:afterCreate', $this);

        return $this;
    }

    public function updateNews($data)
    {
        $this->getDI()->getEventsManager()->fire('livenews:beforeUpdate', $this);
        $categoryData = isset($data['categories']) ? $data['categories'] : array();
        $textData = isset($data['text']) ? $data['text'] : array();

        if ($textData) {
            unset($data['text']);
            $text = new Text();
            $text->assign($textData);
            $this->text = $text;
        }

        //remove old relations
        if ($this->categoriesNews) {
            $this->categoriesNews->delete();
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
            $this->categorySet = implode(',', $categoryData);
            $this->categories = $categories;
        }

        $this->assign($data);
        if (!$this->save()) {
            throw new Exception\RuntimeException('Update news failed');
        }

        $this->getDI()->getEventsManager()->fire('livenews:afterUpdate', $this);
        return $this;
    }

    public function removeNews($id)
    {
        $this->getDI()->getEventsManager()->fire('livenews:beforeRemove', $this);
        $this->id = $id;
        //remove old relations
        if ($this->categoriesNews) {
            $this->categoriesNews->delete();
        }
        $this->delete();
        $this->getDI()->getEventsManager()->fire('livenews:afterRemove', $this);
        return $this;
    }

    public function syncNewsToCache($limit = null)
    {
        $config = $this->getDI()->getConfig();
        $limit = $limit ?: $config->livenews->realtimeCacheSize;
        $newsArray = $this->findNews(array(
            'status' => 'published',
            'limit' => $limit,
            'order' => '-created_at',
        ))->getQuery()->execute();

        foreach ($newsArray as $news) {
            if ($news->status === 'published' && $config->livenews->realtimeCacheEnable) {
                $newsString = json_encode($news->dump(
                    NewsManager::$simpleDump
                ));
                $redis = $news->getDI()->getFastCache();
                $redis->zAdd('livenews', (int) $news->id, $newsString);
                $size = $redis->zSize('livenews');
                if ($size > $config->livenews->realtimeCacheEnable) {
                    //remove lowest rank
                    $redis->zRemRangeByRank('livenews', 0, 0);
                }
            }
        }
    }
}
