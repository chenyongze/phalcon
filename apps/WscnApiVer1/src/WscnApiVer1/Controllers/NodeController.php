<?php

namespace WscnApiVer1\Controllers;

use Swagger\Annotations as SWG;
use WscnApiVer1\Models;
use WscnApiVer1\NewsManager;
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

    // 实时新闻老分类id 新分类id 对应关系
    public $categoryLiveNews = array(
        '9494' => 4,
        '9500' => 2,
        '9497' => 3,
        '9495' => 1,
        '9496' => 5,
        '9499' => 8,
        '9501' => 8,
        '9498' => 6,
        '9493' => 2,
        '9503' => 7,
        '9502' => 4,
        '1294' => 3,
    );

    // 实时新闻地区分类 对应关系
    public $locationLiveNews = array(
        '9488' => 19,
        '9479' => 9,
        '9490' => 19,
        '9484' => 19,
        '9482' => 15,
        '9486' => 19,
        '9485' => 19,
        '9491' => 19,
        '9480' => 12,
        '9478' => 11,
        '9492' => 14,
        '9487' => 16,
        '9477' => 10,
        '9483' => 13,
        '9489' => 19,
        '9481' => 9,
    );

    /**
     *
     * @SWG\Api(
     *   path="/news-list.json",
     *   description="文章列表API",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="文章列表API",
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

        return $json;
    }


    /**
     *
     * @SWG\Api(
     *   path="/rank-twodays-list.json",
     *   description="最热API (两天排行榜)",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="最热API (两天排行榜)",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
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


    /**
     *
     * @SWG\Api(
     *   path="/topnews-list.json",
     *   description="头条幻灯片",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="头条幻灯片",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
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


    /**
     *
     * @SWG\Api(
         *   path="/node/{nid}.json",
     *   description="文章详情API （新闻、实时新闻同一个）",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="文章详情API （新闻、实时新闻同一个）",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="nid",
     *           description="新闻 id",
     *           paramType="path",
     *           required=true,
     *           type="int"
     *         ),
     *       )
     *     )
     *   )
     * )
     */
    public function getAction()
    {

        $id = $this->dispatcher->getParam('id');

        $postModel = new Models\Post();
        $post = $postModel->findFirst($id);

        // 新闻
        if ($post) {
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

        // 实时新闻
        $postModel = new Models\NewsManager();
        $post = $postModel->findFirst($id);
        if ($post) {
            $tempPost = $post->dump(Models\NewsManager::$defaultDump);
            $post = array(
                'nid'        => $tempPost['id'],
                'title'      => $tempPost['title'],
                'created'    => $tempPost['createdAt'],
                'changed'    => $tempPost['createdAt'],
                'name'       => $tempPost['username'],
                'body'       =>  array(
                    'und' => array(0 => array('safe_value' => $tempPost['content'], 'value' => $tempPost['content'], 'safe_summary' => $tempPost['content'], 'summary' => $tempPost['content']))
                ),
            );

            return $this->response->setJsonContent($post);
        }

        return $this->response->setJsonContent(array());
    }


    /**
     *
     * @SWG\Api(
     *   path="/livenews-count-gold.json",
     *   description="实时新闻未读数量",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="实时新闻未读数量",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="nid",
     *           description="新闻 id",
     *           paramType="query",
     *           required=false,
     *           type="int"
     *         ),
     *       )
     *     )
     *   )
     * )
     */
    public function liveNewsCountAction()
    {
        $nid = $this->request->getQuery('nid', 'int', 0);

        // 单项选择 field_icon
         $fieldIcon = $this->request->getQuery('field_icon');

         $iconNewsArray = array('rumor', 'warning', 'news');
         //$iconDataArray = array('alert', 'calendar', 'chart', 'chart_pie', 'download');

         $type = '';
        if (!empty($fieldIcon)) {
            $type = in_array($fieldIcon, $iconNewsArray) ? 'news' : 'data';
        }


        $query = array(
            'status' => 'published',
            'id'     => $nid,
            'type'   => $type,
        );

        $news = new Models\NewsManager();
        $count = $news->getLiveNewNoReadCount($query);

        return $this->response->setJsonContent(array(array('count' => $count)));
    }

    /**
     *
     * @SWG\Api(
     *   path="/livenews-count-gold.json",
     *   description="实时新闻黄金未读数量",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="实时新闻黄金未读数量",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="nid",
     *           description="新闻 id",
     *           paramType="query",
     *           required=false,
     *           type="int"
     *         ),
     *       )
     *     )
     *   )
     * )
     */
    public function liveNewsCountGoldAction()
    {
        $nid = $this->request->getQuery('nid', 'int', 0);

        $query = array(
            'status' => 'published',
            'id'     => $nid,
        );

        $news = new Models\NewsManager();
        $count = $news->getLiveNewNoReadCount($query);

        return $this->response->setJsonContent(array(array('count' => $count)));
    }


    /**
     *
     * @SWG\Api(
     *   path="/livenews-list-v2.json",
     *   description="实时新闻列表",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="实时新闻列表",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="tid[]",
     *           description="分类id",
     *           paramType="query",
     *           required=false,
     *           type="int"
     *         ),
     *       )
     *     )
     *   )
     * )
     */
    public function liveAction()
    {
        $tid = $this->request->getQuery('tid');

        $cid = '';
        if (is_array($tid) && !empty($tid)) {
            $cidArray = array();
            foreach ($tid as $val) {
                if (isset($this->categoryLiveNews[$val])) {
                    $cidArray[] = $this->categoryLiveNews[$val];
                }
            }
            $cid = implode(',', $cidArray);
        }

        $query = array(
            'status' => 'published',
            'cid'    => $cid,
            'limit'  => 30,
            'page'   => $this->request->getQuery('page', 'int', 0) + 1, // 老版本第一页为0
        );

        return $this->getLiveNewsListData($query);
    }

    /**
     *
     * @SWG\Api(
     *   path="/livenews.json",
     *   description="实时新闻最新3条",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="实时新闻最新3条"",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function liveNewsAction()
    {

        $query = array(
            'status' => 'published',
            'limit'  => 3,
        );

        return $this->getLiveNewsListData($query);
    }


    /**
     *
     * @SWG\Api(
     *   path="/livenews-list-gold.json",
     *   description="实时新闻黄金列表",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="实时新闻黄金列表",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="field_location_tid",
     *           description="地区分类id",
     *           paramType="query",
     *           required=false,
     *           type="int"
     *         ),
     *         @SWG\Parameter(
     *           name="tid[]",
     *           description="分类id",
     *           paramType="query",
     *           required=false,
     *           type="int"
     *         ),
     *         @SWG\Parameter(
     *           name="field_icon",
     *           description="icon 分类",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *       )
     *     )
     *   )
     * )
     */
    public function liveNewsListAction()
    {
        // 地区分类单项选择
        // field_location_tid
        $locationTid = $this->request->getQuery('field_location_tid');

        // 多项选择 tid
        $categoryTid = $this->request->getQuery('tid');

        $cidArray = array();

        if (!empty($locationTid) && isset($this->locationLiveNews[$locationTid])) {
            $cidArray[] = $this->locationLiveNews[$locationTid];
        }

        if (is_array($categoryTid) && !empty($categoryTid)) {
            foreach ($categoryTid as $val) {
                if (isset($this->categoryLiveNews[$val])) {
                    $cidArray[] = $this->categoryLiveNews[$val];
                }
            }
        }

        $cid = '';
        if (!empty($cidArray)) {
            $cid = implode(',', $cidArray);
        }

        // 单项选择 field_icon
         $fieldIcon = $this->request->getQuery('field_icon');

         $iconNewsArray = array('rumor', 'warning', 'news');
         $iconDataArray = array('alert', 'calendar', 'chart', 'chart_pie', 'download');

         $type = '';
        if (!empty($fieldIcon)) {
            $type = in_array($fieldIcon, $iconNewsArray) ? 'news' : 'data';
        }


        $query = array(
            'status' => 'published',
            'cid'    => $cid,
            'limit'  => 30,
            'type'   => $type,
            'page'   => $this->request->getQuery('page', 'int', 0) + 1, // 老版本第一页为0
        );

        return $this->getLiveNewsListData($query);
    }


    /**
     *
     * @SWG\Api(
     *   path="/livenews-gold.json",
     *   description="黄金实时新闻最新3条",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="黄金实时新闻最新3条",
     *       notes="Returns post list",
     *       @SWG\Parameters(
     *         @SWG\Parameter(
     *           name="field_icon",
     *           description="icon 分类",
     *           paramType="query",
     *           required=false,
     *           type="string"
     *         ),
     *       )
     *     )
     *   )
     * )
     */
    public function liveNewsGoldAction()
    {
        // 单项选择 field_icon
         $fieldIcon = $this->request->getQuery('field_icon');

         $iconNewsArray = array('rumor', 'warning', 'news');
         $iconDataArray = array('alert', 'calendar', 'chart', 'chart_pie', 'download');

         $type = '';
        if (!empty($fieldIcon)) {
            $type = in_array($fieldIcon, $iconNewsArray) ? 'news' : 'data';
        }


        $query = array(
            'status' => 'published',
            'limit'  => 3,
            'type'   => $type,
            'page'   => $this->request->getQuery('page', 'int', 0) + 1, // 老版本第一页为0
        );

        return $this->getLiveNewsListData($query);
    }


    private function getLiveNewsListData($query)
    {
        $news = new Models\NewsManager();

        $newsList = $news->findNews($query);

        $paginator = new \Eva\EvaEngine\Paginator(array(
            "builder" => $newsList,
            "limit"=> $query['limit'],
            "page" => $query['page']
        ));

        $paginator->setQuery($query);
        $pager = $paginator->getPaginate();

        $newsArray = array();
        if ($pager->items) {
            foreach ($pager->items as $key => $post) {
                $tempArray = $post->dump(array(
                    'id',
                    'title',
                    'createdAt',
                    'importance',
                    //'type',
                ));

                $tempArray['node_icon'] = '';
                /*
                if ($tempArray['type'] == 'data') {
                    $tempArray['node_icon'] = '柱状';
                }
                */
                $tempArray['node_color']  = '';
                $tempArray['node_format'] = '';

                if ($tempArray['importance'] == 1) {
                } elseif ($tempArray['importance'] == 2) {
                    $tempArray['node_color']  = '红色';
                } elseif ($tempArray['importance'] == 3) {
                    $tempArray['node_color']  = '红色';
                    $tempArray['node_format'] = '加粗';
                }


                // 对应老字段
                $newsArray[] = array(
                    'nid'          => $tempArray['id'],
                    'node_title'   => $tempArray['title'],
                    'node_created' => $tempArray['createdAt'],
                    'node_content' => $tempArray['title'],
                    'node_icon'    => $tempArray['node_color'],
                    'node_format'  => $tempArray['node_format'],
                    'node_color'   => $tempArray['node_color'],
                );
            }
        }

        return $this->response->setJsonContent($newsArray);
    }


    private function getPostListData($query)
    {
        $post = new Models\Post();

        if ($query['type'] == 'list') {
            $posts = $post->findPosts($query);
        } elseif ($query['type'] == 'listRankTwodays') {
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
