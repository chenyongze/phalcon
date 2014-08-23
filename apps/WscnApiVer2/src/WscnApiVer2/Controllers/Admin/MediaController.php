<?php

namespace WscnApiVer2\Controllers\Admin;

use WscnApiVer2\Controllers\ControllerBase;
use Eva\EvaEngine\Mvc\Controller\TokenAuthorityControllerInterface;
use Swagger\Annotations as SWG;
use Eva\EvaFileSystem\Models;
use Eva\EvaFileSystem\Forms;
use Eva\EvaEngine\Exception;

/**
 * @package
 * @category
 * @subpackage
 *
 * @SWG\Resource(
 *  apiVersion="0.2",
 *  swaggerVersion="1.2",
 *  resourcePath="/AdminMedia",
 *  basePath="/v2"
 * )
 * @resourceName("文件管理API")
 * @resourceDescription("文件管理API")
 */
class MediaController extends ControllerBase implements TokenAuthorityControllerInterface
{
    /**
     *
     * @SWG\Api(
     *   path="/admin/media",
     *   description="Media manage API",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="Get media list",
     *       notes="Returns media list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="q",
     *           description="Keyword",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="status",
     *           description="Status, allow value : pending | published | deleted | draft",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="uid",
     *           description="User ID",
     *           paramType="query",
     *           required=false,
     *           type="integer"
     *         ),
     *         @SWG\Parameter(
     *           name="extension",
     *           description="File Extension",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="image",
     *           description="Only image files : 0/1",
     *           paramType="query",
     *           required=false,
     *           type="integer"
     *         ),
     *         @SWG\Parameter(
     *           name="order",
     *           description="Order, allow value : +-id, +-created_at, +-sortOrder default is -created_at",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="limit",
     *           description="Limit max:100 | min:3; default is 25",
     *           paramType="query",
     *           required=false,
     *           type="integer"
     *         )
     *       )
     *     )
     *   )
     * )
     * @operationName("文件列表")
     * @operationDescription("文件列表")
     */
    public function indexAction()
    {
        $limit = $this->request->getQuery('limit', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 3 ? 3 : $limit;
        $order = $this->request->getQuery('order', 'string', 'id');
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

        $fileManager = new Models\FileManager();
        $medias = $fileManager->findFiles($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $medias,
            "limit"=> $limit,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();

        $mediaArray = array();
        if ($pager->items) {
            foreach ($pager->items as $key => $media) {
                $mediaArray[] = $media->dump(Models\FileManager::$defaultDump);
            }
        }

        $data = array(
            'paginator' => $this->getApiPaginator($paginator),
            'results' => $mediaArray,
        );
        return $this->response->setJsonContent($data);
    }

    /**
    *
    * @SWG\Api(
    *   path="/admin/media/{mediaId}",
    *   description="Media related api",
    *   produces="['application/json']",
    *   @SWG\Operations(
    *     @SWG\Operation(
    *       method="GET",
    *       summary="Find media by ID",
     *       notes="Returns a media based on ID",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="mediaId",
     *           description="ID of media",
     *           paramType="path",
     *           required=true,
     *           type="integer"
     *         )
     *       )
     *     )
     *   )
     * )
     * @operationName("单个文件信息")
     * @operationDescription("单个文件信息")
     */
    public function getAction()
    {
        $id = $this->dispatcher->getParam('id');
        $mediaModel = new Models\FileManager();
        $media = $mediaModel->findFirst($id);
        if (!$media) {
            throw new Exception\ResourceNotFoundException('Request media not exist');
        }
        $media = $media->dump(Models\FileManager::$defaultDump);
        return $this->response->setJsonContent($media);
    }

    /**
     *
     * @SWG\Api(
     *   path="/admin/media/{mediaId}",
     *   description="Media related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="PUT",
     *       summary="Update media by ID",
     *       notes="Returns updated media",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="mediaId",
     *           description="ID of media",
     *           paramType="path",
     *           required=true,
     *           type="integer"
     *         )
     *       ),
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="mediaData",
     *           description="Media info",
     *           paramType="body",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     * @operationName("更新文件信息")
     * @operationDescription("更新文件信息")
     */
    public function putAction()
    {
        $id = $this->dispatcher->getParam('id');
        $data = $this->request->getRawBody();
        if (!$data) {
            throw new Exception\InvalidArgumentException('No data input');
        }
        if (!$data = json_decode($data, true)) {
            throw new Exception\InvalidArgumentException('Data not able to decode as JSON');
        }
        $media = Models\FileManager::findFirst($id);
        if (!$media) {
            throw new Exception\ResourceNotFoundException('Request media not exist');
        }
        try {
            $media->assign($data);
            $media->save();
            $data = $media->dump(Models\FileManager::$defaultDump);
            return $this->response->setJsonContent($data);
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $form->getModel()->getMessages());
        }
    }

     /**
     *
     * @SWG\Api(
     *   path="/admin/media",
     *   description="Media related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="POST",
     *       summary="Create new media",
     *       notes="Returns a media based on ID",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="upload",
     *           description="Media info",
     *           paramType="body",
     *           required=true,
     *           type="file"
     *         )
     *       )
     *     )
     *   )
     * )
     * @operationName("上传文件")
     * @operationDescription("上传文件")
     */
    public function postAction()
    {
        if (!$this->request->isPost() || !$this->request->hasFiles()) {
            throw new Exception\InvalidArgumentException('No data input');
        }

        $upload = new Models\Upload();
        try {
            $files = $this->request->getUploadedFiles();
            //Only allow upload the first file by force
            $file = $files[0];
            $file = $upload->upload($file);
            if ($file) {
                $data = $file->dump(Models\FileManager::$defaultDump);
                return $this->response->setJsonContent($data);
            }
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $upload->getMessages());
        }
    }

    /**
    *
     * @SWG\Api(
     *   path="/admin/media/{mediaId}",
     *   description="Media related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="DELETE",
     *       summary="Delete media by ID",
     *       notes="Returns deleted media",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="mediaId",
     *           description="ID of media",
     *           paramType="path",
     *           required=true,
     *           type="integer"
     *         )
     *       )
     *     )
     *   )
     * )
     * @operationName("删除文件")
     * @operationDescription("删除文件")
     */
    public function deleteAction()
    {
         $id = $this->dispatcher->getParam('id');
         $media = Models\FileManager::findFirst($id);
        if (!$media) {
            throw new Exception\ResourceNotFoundException('Request media not exist');
        }
         $mediainfo = $media->dump(Models\FileManager::$defaultDump);
        try {
            $media->delete($id);
            return $this->response->setJsonContent($mediainfo);
        } catch (\Exception $e) {
            return $this->showExceptionAsJson($e, $media->getMessages());
        }
    }
}
