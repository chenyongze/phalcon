<?php

namespace Eva\EvaPermission\Models;

use Eva\EvaPermission\Entities;
use Eva\EvaEngine\Exception;

class Operation extends Entities\Operations
{
    public function findOperations(array $query = array())
    {
        $itemQuery = $this->getDI()->getModelsManager()->createBuilder();

        $itemQuery->from(__CLASS__);

        $orderMapping = array(
            'id' => 'id ASC',
            '-id' => 'id DESC',
        );
        $order = 'id ASC';

        if (!empty($query['q'])) {
            $itemQuery->andWhere('resourceKey LIKE :q: OR name LIKE :q:', array('q' => "%{$query['q']}%"));
        }

        if (!empty($query['rid'])) {
            $itemQuery->andWhere('resourceId = :rid:', array('rid' => $query['rid']));
        }

        if (!empty($query['roleid'])) {
            $itemQuery->join('Eva\EvaPermission\Entities\RolesOperations', 'id = r.operationId', 'r')
            ->andWhere('r.roleId= :roleid:', array('roleid' => $query['roleid']));
        }

        $itemQuery->orderBy($order);
        return $itemQuery;
    }
}
