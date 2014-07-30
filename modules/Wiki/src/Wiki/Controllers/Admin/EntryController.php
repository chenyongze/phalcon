<?php
namespace Eva\Wiki\Controllers\Admin;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-11 15:33
// +----------------------------------------------------------------------
// + EntryController.php
// +----------------------------------------------------------------------
use Eva\EvaEngine\Exception\ResourceNotFoundException;
use Eva\Wiki\Forms;
use Eva\Wiki\Models;

class EntryController extends AdminControllerBase
{
    public function indexAction()
    {
//        return;
        $limit = $this->request->getQuery('per_page', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 10 ? 10 : $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'status' => $this->request->getQuery('status', 'string'),
            'uid' => $this->request->getQuery('uid', 'int'),
            'cid' => $this->request->getQuery('cid', 'int'),
            'username' => $this->request->getQuery('username', 'string'),
            'sourceName' => $this->request->getQuery('source_name', 'string'),
            'order' => $order,
            'limit' => $limit,
            'page' => $this->request->getQuery('page', 'int', 1),
        );
//
        $form = new Forms\FilterForm();
        $form->setValues($this->request->getQuery());
        $this->view->setVar('form', $form);
//

        $entry = new Models\Entry();
        $this->view->setVar('pager', $entry->listEntries($query, $limit));
    }

    public function createAction()
    {
        exit(p(json_encode($_POST)));
        $form = new Forms\EntryForm();
        $entry = new Models\Entry();
        $form->setModel($entry);
        $form->addForm('text', 'Eva\Wiki\Forms\EntryTextForm');
        $this->view->setVar('form', $form);
        $this->view->setVar('item', $entry);

        if (!$this->request->isPost()) {
            return false;
        }

        $data = $this->request->getPost();
        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessages($form);
        }

        try {
            $form->save('createEntry');
        } catch (\Exception $e) {
            return $this->showException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_ENTRY_CREATED');

        return $this->redirectHandler('/admin/wiki/edit/' . $form->getModel()->id);
    }
    public function editAction()
    {
        $this->view->changeRender('admin/entry/create');
        $entry = Models\Entry::findFirst($this->dispatcher->getParam('id'));
        if (!$entry) {
            throw new ResourceNotFoundException('ERR_BLOG_POST_NOT_FOUND');
        }

        $form = new Forms\EntryForm();
        $form->setModel($entry);
        $form->addForm('text', 'Eva\Wiki\Forms\EntryTextForm');
        $this->view->setVar('form', $form);
        $this->view->setVar('item', $entry);

        if (!$this->request->isPost()) {
            return false;
        }
        $data = $this->request->getPost();

        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessages($form);
        }

        try {
            $form->save('updateEntry');
        } catch (\Exception $e) {
            return $this->showException($e, $form->getModel()->getMessages());
        }
        $this->flashSession->success('SUCCESS_POST_UPDATED');

        return $this->redirectHandler('/admin/wiki/edit/' . $entry->id);
    }

}