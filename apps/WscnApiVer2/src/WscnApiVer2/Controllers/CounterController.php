<?php
// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-3 下午5:30
// +----------------------------------------------------------------------
// + Counter.php
// +----------------------------------------------------------------------

namespace WscnApiVer2\Controllers;



/**
 * @package
 * @category
 * @subpackage
 *
 * @SWG\Resource(
 *  apiVersion="0.2",
 *  swaggerVersion="1.2",
 *  resourcePath="/counter",
 *  basePath="/"
 * )
 */
class CounterController extends ControllerBase
{
    /**
     *
     * @SWG\Api(
     *   path="/counter/{group}/{items}",
     *   description="counter related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="get counted items",
     *       notes="Returns: items list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="group",
     *           description="需要递增的键所在的分组名，可以对同一组内的 items 排序",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="items",
     *           description="item keys, 如文章 id。多个使用-分割",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */

    public function getAction() {
        $dispatcher = $this->dispatcher;
        $group = $dispatcher->getParam('group');
        $items = $dispatcher->getParam('items');

        var_dump($group);
        var_dump($items);
    }
    /**
     *
     * @SWG\Api(
     *   path="/counter/{group}/top10",
     *   description="counter related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="get top 10 of the specified group",
     *       notes="Returns top 10 counted number list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="group",
     *           description="需要递增的键所在的分组名，可以对同一组内的 items 排序",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */

    public function top10() {

    }
    /**
     *
     * @SWG\Api(
     *   path="/counter/{group}/{limit}/sort/{type}",
     *   description="counter related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="sort a group",
     *       notes="Returns top 10 counted number list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="group",
     *           description="需要递增的键所在的分组名，可以对同一组内的 items 排序",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="limit",
     *           description="限制条数",
     *           paramType="path",
     *           required=true,
     *           type="int"
     *         ),
     *         @SWG\Parameter(
     *           name="type",
     *           description="排序方式，默认为 asc ，可选 desc",
     *           paramType="path",
     *           required=false,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */

    public function sort() {

    }
    /**
     *
     * @SWG\Api(
     *   path="/counter/{group}/{items}/increase",
     *   description="counter related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="PUT",
     *       summary="increase given items ",
     *       notes="",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="group",
     *           description="需要递增的键所在的分组名，可以对同一组内的 items 排序",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="items",
     *           description="item keys, 如文章 id。多个使用-分割",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */

    public function increase() {

    }
    /**
     *
     * @SWG\Api(
     *   path="/counter/{group}/{items}/decrease",
     *   description="counter related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="PUT",
     *       summary="decrease given items ",
     *       notes="",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="group",
     *           description="需要递减的键所在的分组名，可以对同一组内的 items 排序",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="items",
     *           description="item keys, 如文章 id。多个使用-分割",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */

    public function decrease() {

    }
    /**
     *
     * @SWG\Api(
     *   path="/counter/{group}/{items}",
     *   description="counter related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="DELETE",
     *       summary="delete items ",
     *       notes="",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="group",
     *           description="需要删除的键所在的分组名",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="items",
     *           description="item keys, 如文章 id。多个使用-分割",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */

    public function delete() {

    }
    /**
     *
     * @SWG\Api(
     *   path="/counter/{group}/{items}",
     *   description="counter related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="POST",
     *       summary="add items",
     *       notes="Returns:",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="group",
     *           description="需要递增的键所在的分组名，可以对同一组内的 items 排序",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="items",
     *           description="item keys, 如文章 id。多个使用-分割",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */

    public function create() {

    }
    /**
     *
     * @SWG\Api(
     *   path="/counter/{group}",
     *   description="counter related api",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="DELETE",
     *       summary="delete group ",
     *       notes="",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="group",
     *           description="需要删除的分组名",
     *           paramType="path",
     *           required=true,
     *           type="string"
     *         )
     *       )
     *     )
     *   )
     * )
     */

    public function deleteGroup() {

    }
}