<?php

namespace Eva\EvaFileSystem\Controllers\Admin;

use Eva\EvaFileSystem\Models;
use Eva\EvaEngine\Mvc\Controller\JsonControllerInterface;

/**
* @resourceName("Media Upload")
* @resourceDescription("Media Upload")
*/
class UploadController extends ControllerBase implements JsonControllerInterface
{
    /**
    * @operationName("Upload Media")
    * @operationDescription("Upload Media")
    */
    public function indexAction()
    {
        if (!$this->request->isPost() || !$this->request->hasFiles()) {
            $this->response->setStatusCode('400', 'No Upload Files');
            return $this->response->setJsonContent(array(
                'errors' => array(
                    array(
                        'code' => 400,
                        'message' => 'ERR_FILE_NO_UPLOAD'
                    )
                ),
            ));
        }
        $upload = new Models\Upload();
        $fileinfo = array();
        try {
            $files = $this->request->getUploadedFiles();
            //Only allow upload the first file by force
            $file = $files[0];
            $file = $upload->upload($file);
            if ($file) {
                $fileinfo = $file->toArray();
                $fileinfo['localUrl'] = $file->getLocalUrl();
            }
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $upload->getMessages());
        }
        return $this->response->setJsonContent($fileinfo);
    }

    /**
    * @operationName("Upload Encoded Media")
    * @operationDescription("Upload Base64 encoded media (from Browser drag and drop)")
    */
    public function encodeAction()
    {
        if (!$this->request->isPost()) {
            $this->response->setStatusCode('400', 'No Upload Files');
            return $this->response->setJsonContent(array(
                'errors' => array(
                    array(
                        'code' => 400,
                        'message' => 'ERR_FILE_NO_UPLOAD'
                    )
                ),
            ));
        }
         $upload = new Models\Upload();
         $fileinfo = array();
        try {
            $file = $upload->uploadByEncodedData(
                $this->request->getPost('file'),
                $this->request->getPost('name'),
                $this->request->getPost('type')
            );
            if ($file) {
                $fileinfo = $file->toArray();
                $fileinfo['localUrl'] = $file->getLocalUrl();
            }
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $upload->getMessages());
        }
         return $this->response->setJsonContent($fileinfo);
    }
}
