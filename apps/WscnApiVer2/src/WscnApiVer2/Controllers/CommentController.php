<?php

namespace WscnApiVer2\Controllers;

use Swagger\Annotations as SWG;
use Eva\EvaComment\Models;
use Eva\EvaComment\Entities;
use Eva\EvaComment\Forms;
use Eva\EvaEngine\Exception;

/**
 * @package
 * @category
 * @subpackage
 *
 * @SWG\Resource(
 *  apiVersion="0.2",
 *  swaggerVersion="1.2",
 *  resourcePath="/comment",
 *  basePath="/v2"
 * )
 */
class CommentController extends ControllerBase
{
    /**
     *
     * @SWG\Api(
     *   path="/comment",
     *   description="Comment related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="Get comment list",
     *       notes="Returns comment list",
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
     */
    public function indexAction()
    {
        $limit = $this->request->getQuery('limit', 'int', 25);
        $limit = $limit > 100 ? 100 : $limit;
        $limit = $limit < 3 ? 3 : $limit;
        $order = $this->request->getQuery('order', 'string', '-created_at');
        $query = array(
            'q' => $this->request->getQuery('q', 'string'),
            'status' => $this->request->getQuery('status', 'string'),
            'uid' => $this->request->getQuery('uid', 'int'),
//            'cid' => $this->request->getQuery('cid', 'int'),
            'username' => $this->request->getQuery('username', 'string'),
            'order' => $order,
            'limit' => $limit,
            'page' => $this->request->getQuery('page', 'int', 1),
        );

        $form = new Forms\FilterForm();
        $form->setValues($this->request->getQuery());

        $commentManger = new Models\CommentManager();
        $comments = $commentManger->findComments($query);
        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $comments,
            "limit"=> $limit,
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();

        $commentArray = array();
        if ($pager->items) {
            foreach ($pager->items as $key => $comment) {
                $commentArray[] = $comment->dump(array(
                    'id',
                    'threadId',
                    'title',
                    'codeType',
                    'content',
                    'status',
                    'createdAt',
                ));
            }
        }

        $data = array(
            'paginator' => $this->getApiPaginator($paginator),
            'results' => $commentArray,
        );
        return $this->response->setJsonContent($data);
    }

    /**
    *
    * @SWG\Api(
        *   path="/comment/{commentId}",
        *   description="Comment related api",
        *   produces="['application/json']",
        *   @SWG\Operations(
            *     @SWG\Operation(
                *       method="GET",
                *       summary="Find comment by ID",
     *       notes="Returns a comment based on ID",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="commentId",
     *           description="ID of comment",
     *           paramType="path",
     *           required=true,
     *           type="integer"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function getAction()
    {
        $id = $this->dispatcher->getParam('id');
        $commentManger = new Models\CommentManager();
        $comment = $commentManger->findCommentById($id);
        if (!$comment) {
            throw new Exception\ResourceNotFoundException('Request commment not exist');
        }
        $comment = $comment->dump(Entities\Comments::$defaultDump);
        return $this->response->setJsonContent($comment);
    }

    /**
     *
     * @SWG\Api(
     *   path="/comment/{commentId}",
     *   description="Comment related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="PUT",
     *       summary="Update comment by ID",
     *       notes="Returns updated comment",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="commentId",
     *           description="ID of comment",
     *           paramType="path",
     *           required=true,
     *           type="integer"
     *         )
     *       ),
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="commentData",
     *           description="Comment info",
     *           paramType="body",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
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

         $commentManger = new Models\CommentManager();
         $comment = $commentManger->findCommentById($id);
         if(!$comment){
             throw new Exception\ResourceNotFoundException('Request comment not exist');
         }

        $form = new Forms\CommentForm();
        $form->setModel($comment);


        if (!$form->isFullValid($data)) {
            return $this->displayJsonInvalidMessages($form);
        }

        try {
            $form->save();
            $data = $comment->dump(Entities\Comments::$defaultDump);
            return $this->response->setJsonContent($data);
        } catch (\Exception $e) {
            return $this->displayExceptionForJson($e, $form->getModel()->getMessages());
        }
     }

     /**
     *
     * @SWG\Api(
     *   path="/comment",
     *   description="Comment related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="POST",
     *       summary="Create new comment",
     *       notes="Returns a comment based on ID",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="comment json",
     *           description="Comment info",
     *           paramType="body",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function postAction()
    {
        $data = $this->request->getRawBody();
        if (!$data) {
            throw new Exception\InvalidArgumentException('No data input');
        }
        if (!$data = json_decode($data, true)) {
            throw new Exception\InvalidArgumentException('Data not able to decode as JSON');
        }

        $form = new Forms\CommentForm();
        $comment = new Entities\Comments();
        $form->setModel($comment);

        if (!$form->isFullValid($data)) {
            return $this->displayJsonInvalidMessages($form);
        }

        try {
            $form->save();
            $data = $comment->dump(Entities\Comments::$defaultDump);
            return $this->response->setJsonContent($data);
        } catch (\Exception $e) {
            return $this->displayExceptionForJson($e, $form->getModel()->getMessages());
        }
    }

    /**
    *
     * @SWG\Api(
     *   path="/comment/{commentId}",
     *   description="Comment related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="DELETE",
     *       summary="Delete comment by ID",
     *       notes="Returns deleted comment",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="commentId",
     *           description="ID of comment",
     *           paramType="path",
     *           required=true,
     *           type="integer"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function deleteAction()
    {
        $id = $this->dispatcher->getParam('id');

        $commentManger = new Models\CommentManager();
        $comment = $commentManger->findCommentById($id);
        if (!$comment) {
            throw new Exception\ResourceNotFoundException('Request comment not exist');
        }
        $commentInfo = $comment->dump(Entities\Comments::$defaultDump);
        try {
            $commentManger->removeComment($comment);
            return $this->response->setJsonContent($commentInfo);
        } catch (\Exception $e) {
            return $this->displayExceptionForJson($e, $comment->getMessages());
        }
    }
}
