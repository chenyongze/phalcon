<?php

return array(
    '/apiv1' =>  array(
        'module' => 'WscnApiVer1',
        'controller' => 'index',
        'action' => 'index'
    ),
    '/apiv1/resources/(\w+)' =>  array(
        'module' => 'WscnApiVer1',
        'controller' => 'index',
        'action' => 'resource',
        'id' => 1,
    ),
    '/apiv1/resources' =>  array(
        'module' => 'WscnApiVer1',
        'controller' => 'index',
        'action' => 'resources',
    ),
    'postlistv1' =>  array(
        'pattern' => '/apiv1/news-list.json(p)*',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'post',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),

    'ranktwodayspostlist' =>  array(
        'pattern' => '/apiv1/rank-twodays-list.json(p)*',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'post',
            'action' => 'ranktwodays',
        ),
        'httpMethods' => 'GET'
    ),

    'getpostv1' =>  array(
        'pattern' => '/apiv1/node/(\d+).json(p)*',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'post',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),

    'topnewslist' =>  array(
        'pattern' => '/apiv1/topnews-list.json(p)*',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'post',
            'action' => 'topnews',
        ),
        'httpMethods' => 'GET'
    ),


    'postlivelist' =>  array(
        'pattern' => '/apiv1/livenews-list-v2.json(p)*',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'post',
            'action' => 'live',
        ),
        'httpMethods' => 'GET'
    ),

    'androidversion' =>  array(
        'pattern' => '/apiv1/android_version.json(p)*',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'androidVersion',
        ),
        'httpMethods' => 'GET'
    ),

    'iosversion' =>  array(
        'pattern' => '/apiv1/ios_version.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'iosVersion',
        ),
        'httpMethods' => 'GET'
    ),

    'iosgoldversion' =>  array(
        'pattern' => '/apiv1/ios_gold_version.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'iosGoldVersion',
        ),
        'httpMethods' => 'GET'
    ),

    'iosliveversion' =>  array(
        'pattern' => '/apiv1/ios_live_version.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'iosLiveVersion',
        ),
        'httpMethods' => 'GET'
    ),

    'activityandroid' =>  array(
        'pattern' => '/apiv1/app_activity_android.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'activityAndroid',
        ),
        'httpMethods' => 'GET'
    ),

    'activityios' =>  array(
        'pattern' => '/apiv1/app_activity_ios.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'activityIos',
        ),
        'httpMethods' => 'GET'
    ),

    'activityiospro' =>  array(
        'pattern' => '/apiv1/app_activity_ios_pro.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'activityIosPro',
        ),
        'httpMethods' => 'GET'
    ),

    'moregallery' =>  array(
        'pattern' => '/apiv1/app_more_gallery.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'moreGallery',
        ),
        'httpMethods' => 'GET'
    ),

    'apprecommend' =>  array(
        'pattern' => '/apiv1/app_recommend.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'recommend',
        ),
        'httpMethods' => 'GET'
    ),

    'splashad' =>  array(
        'pattern' => '/apiv1/app_splash_ad.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'splashAd',
        ),
        'httpMethods' => 'GET'
    ),

    'refreshfrequence' =>  array(
        'pattern' => '/apiv1/refresh_frequence.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'refreshFrequence',
        ),
        'httpMethods' => 'GET'
    ),

    'topnews9' =>  array(
        'pattern' => '/apiv1/topnews9.json',
        'paths' => array(
            'module' => 'WscnApiVer1',
            'controller' => 'appoption',
            'action' => 'topnews9',
        ),
        'httpMethods' => 'GET'
    ),

);
