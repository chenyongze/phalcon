<?php

namespace Eva\Wiki\Controllers\Admin;

use Eva\Wiki\Models;
use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;
use Eva\EvaEngine\Exception;

class ProcessController extends AdminControllerBase implements JsonControllerInterface
{
    public function deleteAction()
    {
        if (!$this->request->isDelete()) {
            return $this->showErrorMessageAsJson(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $id = $this->dispatcher->getParam('id');
        $entry =  new Models\Entry();
        try {
            $entry->removeEntry($id);
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $entry->getMessages());
        }

        return $this->response->setJsonContent($entry);
    }

    public function statusAction()
    {
        if (!$this->request->isPut()) {
            return $this->showErrorMessageAsJson(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $id = $this->dispatcher->getParam('id');
        $entry =  Models\Entry::findFirst($id);
        try {
            $entry->status = $this->request->getPut('status');
            $entry->save();
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $entry->getMessages());
        }

        return $this->response->setJsonContent($entry);
    }



    public function batchAction()
    {
        if (!$this->request->isPut()) {
            return $this->showErrorMessageAsJson(405, 'ERR_REQUEST_METHOD_NOT_ALLOW');
        }

        $idArray = $this->request->getPut('id');
        if (!is_array($idArray) || count($idArray) < 1) {
            return $this->showErrorMessageAsJson(401, 'ERR_REQUEST_PARAMS_INCORRECT');
        }

        $status = $this->request->getPut('status');
        $entries = array();

        try {
            foreach ($idArray as $id) {
                $entry =  Models\Entry::findFirst($id);
                if ($entry) {
                    $entry->status = $status;
                    $entry->save();
                    $entries[] = $entry;
                }
            }
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $entry->getMessages());
        }

        return $this->response->setJsonContent($entries);
    }
}
