<?php

namespace WscnApiVer2\Controllers;

use Eva\EvaEngine\Exception;


/**
 * @package
 * @category
 * @subpackage
 *
 * @SWG\Resource(
 *  apiVersion="0.2",
 *  swaggerVersion="1.2",
 *  resourcePath="/wiki",
 *  basePath="/v2"
 * )
 */
class WikiController extends ControllerBase
{
    /**
     *
     * @SWG\Api(
     *   path="/wiki/category",
     *   description="Wiki related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="POST",
     *       summary="create a category by names",
     *       notes="Returns: category id",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="categoryName",
     *           description="name of category",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="parentNames",
     *           description="parent category names",
     *           paramType="body",
     *           required=false,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function createCategoryAction()
    {
        $category = new \Eva\Wiki\Models\Category();
        $categoryId = $category->createCategoryByNames($this->request->getPost("categoryName"), preg_split('/\s+/', $this->request->getPost("parentNames")));
        return $this->response->setJsonContent(array('categoryId' => $categoryId));
    }

    /**
     *
     * @SWG\Api(
     *   path="/wiki/entry",
     *   description="Wiki related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="POST",
     *       summary="Create new post",
     *       notes="Returns a post based on ID",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="post json",
     *           description="Post info",
     *           paramType="body",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */
    public function createEntryAction()
    {
        $data = $this->request->getRawBody();

        if (!$data = json_decode($data, true)) {
            throw new Exception\InvalidArgumentException('Data not able to decode as JSON');
        }
        if(isset($data['categoryNames'])) {
            $data['categoryNames'] = preg_split('/\s+/', $data['categoryNames']);
        }
        if(isset($data['keywords'])) {
            $data['keywords'] = preg_split('/\n+/', $data['keywords']);

        }
        $entry = new \Eva\Wiki\Models\Entry();
        $form = new \Eva\Wiki\Forms\EntryForm();
        $form->setModel($entry);
        $form->addForm('text', 'Eva\Wiki\Forms\EntryTextForm');
        if (!$form->isFullValid($data)) {
            return $this->showInvalidMessages($form);
        }

        try {
            $entry = $form->save('createEntry');
        } catch (\Exception $e) {
            return $this->showException($e, $form->getModel()->getMessages());
        }
        return $this->response->setJsonContent(array('id'=>$entry->id));
    }
}