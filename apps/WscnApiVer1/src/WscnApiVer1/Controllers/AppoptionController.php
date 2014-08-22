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
        if ($option) {
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

    /**
     *
     * @SWG\Api(
     *   path="/ios_gold_version.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="ios gold升级版本",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function iosGoldVersionAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/ios_live_version.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="ios live升级版本",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function iosLiveVersionAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/ios_pro_version.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="ios pro升级版本",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function iosProVersionAction()
    {
        return $this->showApi();
    }


    /**
     *
     * @SWG\Api(
     *   path="/app_activity_android.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="android活动",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function activityAndroidAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/app_activity_android_new.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="android活动 new",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function activityAndroidNewAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/app_activity_ios.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="ios活动",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function activityIosAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/app_activity_ios_pro.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="ios pro活动",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function activityIosProAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/app_more_gallery.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="更多页面幻灯片",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function moreGalleryAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/app_recommend.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="app推荐列表",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function recommendAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/app_splash_ad.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="app闪屏",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function splashAdAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/refresh_frequence.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="刷新频率",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function refreshFrequenceAction()
    {
        return $this->showApi();
    }

    /**
     *
     * @SWG\Api(
     *   path="/topnews9.json",
     *   description="App相关接口",
     *   produces="['application/json']",
     *   @SWG\Operations(
     *     @SWG\Operation(
     *       method="GET",
     *       summary="topnews9",
     *       notes="",
     *       @SWG\Parameters(
     *       )
     *     )
     *   )
     * )
     */
    public function topnews9Action()
    {
        return $this->showApi();
    }
}
