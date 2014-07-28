<?php

namespace Eva\EvaPermission\Controllers\Admin;

use Eva\EvaPermission\Entities;
use Eva\EvaPermission\Forms;
use Eva\EvaPermission\Models;
use Eva\EvaEngine\Exception;

class OperationController extends ControllerBase
{
    /**
    * Index action
    */
    public function indexAction()
    {
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'rid' => $this->request->getQuery('rid', 'int'),
            'roleid' => $this->request->getQuery('roleid', 'int'),
            'limit' => 1000,
            'page' => $this->request->getQuery('page', 'int', 1),
        );

        $form = new Forms\OperationFilterForm();
        $form->setValues($this->request->getQuery());
        $this->view->setVar('form', $form);

        $operation = new Models\Operation();
        $operations = $operation->findOperations($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $operations,
            "limit"=> $query['limit'],
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);
        $this->view->setVar('roles', Entities\Roles::find());
    }
}
