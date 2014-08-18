<?php

namespace WscnApiVer1\Controllers;

use Swagger\Annotations as SWG;
use WscnApiVer1\Models;
use Eva\EvaBlog\Entities;
use Eva\EvaBlog\Forms;
use Eva\EvaEngine\Exception;

/**
 * @package
 * @category
 * @subpackage
 *
 * @SWG\Resource(
 *  apiVersion="0.2",
 *  swaggerVersion="1.2",
 *  resourcePath="/post",
 *  basePath="/apiv1"
 * )
 */
class NodeController extends ControllerBase
{
    /**
     *
     * @SWG\Api(
     *   path="/news-list.json",
     *   description="文章列表API",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="Get post list",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="tid",
     *           description="Tag id",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="spid",
     *           description="特殊 id",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *         @SWG\Parameter(
     *           name="page",
     *           description="翻页",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *       )
     *     )
     *   )
     * )
     */
    public function indexAction()
    {

        $tid = $this->request->getQuery('tid', 'int', 0);

        // 老分类id 新分类id 对应关系
        $tidMapping = array(
            3119 => 11, // 编辑推荐
            7349 => 15, // 欧洲
            7350 => 16, // 美国
            7351 => 17, // 中国
            4    => 19, // 经济
            7353 => 20, // 央行
            48   => 21, // 市场
            7354 => 22, // 公司
        );

        if (isset($tidMapping[$tid])) {
            $tid = $tidMapping[$tid];
        } else {
            $tid = 0;
        }

        // 推荐
        $spid = $this->request->getQuery('spid', 'int', 0);
        if ($spid == 3119) {
            $tid = $tidMapping[$spid];
        }


        $query = array(
            'type'   => 'list',
            'status' => 'published',
            'cid'    => $tid,
            'limit'  => 20,
            'page'   => $this->request->getQuery('page', 'int', 0) + 1, // 老版本第一页为0
        );

        $json = $this->getPostListData($query);
        /*
        // jsonp处理
        $callback = $this->request->getQuery('callback', 'string', '');
        if (!empty($callback)) {
            return $callback . '(' . $json . ');';
        }
        */
        return $json;
    }

    // 最热
    public function ranktwodaysAction()
    {

        $query = array(
            'type'   => 'listRankTwodays',
            'status' => 'published',
            'limit'  => 20,
            'page'   => $this->request->getQuery('page', 'int', 0) + 1, // 老版本第一页为0
        );

        return $this->getPostListData($query);
    }

    public function topnewsAction()
    {
        $query = array(
            'type'   => 'list',
            'status' => 'published',
            'cid'    => 6, // 头条
            'limit'  => 5,
        );

        return $this->getPostListData($query);
    }

    public function getAction()
    {
        $id = $this->dispatcher->getParam('id');

        $postModel = new Models\Post();
        $post = $postModel->findFirst($id);
        if (!$post) {
            throw new Exception\ResourceNotFoundException('Request post not exist');
        }
        $tempPost = $post->dump(Models\Post::$defaultDump);
        //print_r($tempPost);die;
        $post = array(
            'nid'        => $tempPost['id'],
            'title'      => $tempPost['title'],
            'created'    => $tempPost['createdAt'],
            'changed'    => $tempPost['createdAt'],
            'name'       => $tempPost['username'],
            'body'       =>  array(
                'und' => array(0 => array('safe_value' => $tempPost['content'], 'value' => $tempPost['content'], 'safe_summary' => $tempPost['summary'], 'summary' => $tempPost['summary']))
            ),
        );

        $upload = array();
        if (!empty($tempPost['imageUrl'])) {
            $upload = array(
                'und' => array(0 => array('filename' => $tempPost['imageUrl']))
            );
        }

        $post['upload'] = $upload;


        $related = array();
        // 相关文章未做

        $post['related'] = $related;

        return $this->response->setJsonContent($post);

    }



    public function liveAction()
    {
        echo 333;die;
    }




    private function getPostListData($query)
    {
        $post = new Models\Post();

        if ($query['type'] == 'list') {
            $posts = $post->findPosts($query);
        } else if ($query['type'] == 'listRankTwodays') {
            $posts = $post->findRankTwodaysPosts($query);
        }

        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $posts,
            "limit"=> $query['limit'],
            "page" => $query['page']
        ));
        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();


        $postArray = array();
        if ($pager->items) {
            foreach ($pager->items as $key => $post) {
                $tempArray = $post->dump(array(
                    'id',
                    'title',
                    'createdAt',
                    'imageUrl' => 'getImageUrl',

                ));
                // 对应老字段
                $postArray[] = array(
                    'nid'                                => $tempArray['id'],
                    'node_title'                         => $tempArray['title'],
                    'node_created'                       => $tempArray['createdAt'],
                    'file_managed_file_usage_uri'        => $tempArray['imageUrl'],
                    'file_managed_field_data_upload_uri' => $tempArray['imageUrl'],
                );
            }
        }

        return $this->response->setJsonContent($postArray);
    }
}
