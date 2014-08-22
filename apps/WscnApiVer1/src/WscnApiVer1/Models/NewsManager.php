<?php

namespace WscnApiVer1\Models;

use Eva\EvaLivenews\Entities;
use Eva\EvaUser\Models\Login as LoginModel;
use Eva\EvaFileSystem\Models\Upload as UploadModel;
use Eva\EvaEngine\Exception;

class NewsManager extends Entities\News
{

    public static $defaultDump = array(
        'id',
        'title',
        'createdAt',
        'content' => 'getContentHtml',
    );



    public function getLiveNewNoReadCount(array $query = array())
    {

        if (isset($query['type'])) {
            $phql = <<<QUERY
SELECT COUNT(*) AS total
FROM Eva\EvaLivenews\Entities\News
WHERE id > {$query['id']} AND type = {$query['type']}
AND status = '{$query['status']}'
QUERY;
        } else {
            $phql = <<<QUERY
SELECT COUNT(*) AS total
FROM Eva\EvaLivenews\Entities\News
WHERE id > {$query['id']}
AND status = '{$query['status']}'
QUERY;
        }


        $manager = $this->getModelsManager();
        $query = $manager->createQuery($phql);
        $results = $query->execute();

        //print_r($results->count());die;

        $count = 0;

        if ($results->count() > 0) {
            foreach ($results as $result) {
                $count = $result->total;
            }
        }

        return $count;

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

        if (!empty($query['type'])) {
            $itemQuery->andWhere('type = :type:', array('type' => $query['type']));
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
}
