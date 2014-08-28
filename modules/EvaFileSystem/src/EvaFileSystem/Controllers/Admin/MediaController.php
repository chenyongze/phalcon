<?php

namespace Eva\EvaFileSystem\Controllers\Admin;

use Eva\EvaFileSystem\Models;
use Eva\EvaFileSystem\Forms;

/**
* @resourceName("Media Managment")
* @resourceDescription("Media Managment")
*/
class MediaController extends ControllerBase
{
    /**
    * @operationName("Media List")
    * @operationDescription("Get Media list")
    */
    public function indexAction()
    {
        $limit = $this->request->getQuery('per_page', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 10 ? 10 : $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
           'q' => $this->request->getQuery('q', 'string'),
           'status' => $this->request->getQuery('status', 'string'),
           'uid' => $this->request->getQuery('uid', 'int'),
           'extension' => $this->request->getQuery('extension', 'string'),
           'image' => $this->request->getQuery('image', 'int'),
           'order' => $order,
           'limit' => $limit,
           'page' => $this->request->getQuery('page', 'int', 1),
        );
        $form = new Forms\FilterForm();
        $form->setValues($this->request->getQuery());
        $this->view->setVar('form', $form);
        $fileManager = new Models\FileManager();
        $medias = $fileManager->findFiles($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
           "builder" => $medias,
           "limit"=> $limit,
           "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();
        $this->view->setVar('pager', $pager);
    }

    /**
    * @operationName("Upload Files")
    * @operationDescription("Upload files by drag and drop")
    */
    public function uploadAction()
    {
    }
}
