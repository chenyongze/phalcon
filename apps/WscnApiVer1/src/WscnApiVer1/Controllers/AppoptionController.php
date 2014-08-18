<?php

namespace WscnApiVer1\Controllers;

use Wscn\Entities\Appoptions;
use Eva\EvaEngine\Exception;

/**
 * @package
 * @category
 * @subpackage
 *
 * @SWG\Resource(
 *  apiVersion="0.2",
 *  swaggerVersion="1.2",
 *  resourcePath="/app",
 *  basePath="/apiv1"
 * )
 */
class AppoptionController extends ControllerBase
{
    protected function showApi()
    {
        $paths = $this->router->getMatchedRoute()->getPaths();
        $endpoint = $paths['action'];
        $appoption = new Appoptions();
        $option = $appoption->findFirst(array(
            "conditions" => "version = 1 AND endpoint = :endpoint:",
            "bind"       => array(
                'endpoint' => $endpoint
            )
        ));
        if($option) {
            return $this->response->setContent($option->data);
        } else {
            throw new Exception\ResourceNotFoundException('Request resource not found');
        }
    }

    /**
     *
     * @SWG\Api(
     *   path="/android_version.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="安卓版本",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function androidVersionAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/ios_version.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="iOS版本",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function iosVersionAction()
    {
        return $this->showApi();
    }

    // ios gold升级版本
    public function iosGoldVersionAction()
    {
        include_once  $this->path . 'ios_gold_version.php';
        return $this->response->setJsonContent($json);
    }

    // ios live升级版本
    public function iosLiveVersionAction()
    {
        include_once  $this->path . 'ios_live_version.php';
        return $this->response->setJsonContent($json);
    }

    // android活动
    public function activityAndroidAction()
    {
        include_once  $this->path . 'activity_android.php';
        return $this->response->setJsonContent($json);
    }

    // ios活动
    public function activityIosAction()
    {
        include_once  $this->path . 'activity_ios.php';
        return $this->response->setJsonContent($json);
    }

    // ios pro活动
    public function activityIosProAction()
    {
        include_once  $this->path . 'activity_ios_pro.php';
        return $this->response->setJsonContent($json);
    }

    // 更多页面幻灯片
    public function moreGalleryAction()
    {
        include_once  $this->path . 'more_gallery.php';
        return $this->response->setJsonContent($json);
    }

    // app推荐列表
    public function recommendAction()
    {
        include_once  $this->path . 'app_recommend.php';
        return $this->response->setJsonContent($json);
    }

    // app闪屏
    public function splashAdAction()
    {
        include_once  $this->path . 'splash_ad.php';
        return $this->response->setJsonContent($json);
    }

    // 刷新频率
    public function refreshFrequenceAction()
    {
        include_once  $this->path . 'refresh_frequence.php';
        return $this->response->setJsonContent($json);
    }

    // topnews9
    public function topnews9Action()
    {
        include_once  $this->path . 'topnews9.php';
        return $this->response->setJsonContent($json);
    }
}
