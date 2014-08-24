<?php

namespace Eva\EvaFileSystem\Models;

use Eva\EvaFileSystem\Entities\Files;

class FileManager extends Files
{
    public static $defaultDump = array(
        'id',
        'title',
        'url' => 'getFullUrl',
        'localUrl' => 'getLocalUrl',
        'description',
        'fileExtension',
        'fileHash',
        'fileSize',
        'readableFileSize' => 'getReadableFileSize',
        'isImage',
        'imageWidth',
        'imageHeight',
        'sortOrder',
        'userId',
        'username',
        'createdAt',
    );

    public function findFiles(array $query = array())
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
            $itemQuery->andWhere('title LIKE :q:', array('q' => "%{$query['q']}%"));
        }

        if (!empty($query['status'])) {
            $itemQuery->andWhere('status = :status:', array('status' => $query['status']));
        }

        if (!empty($query['uid'])) {
            $itemQuery->andWhere('userId = :uid:', array('uid' => $query['uid']));
        }

        if (!empty($query['extension'])) {
            $itemQuery->andWhere('fileExtension = :extension:', array('extension' => $query['extension']));
        }

        if (!empty($query['image'])) {
            $itemQuery->andWhere('isImage = :image:', array('image' => $query['image']));
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
