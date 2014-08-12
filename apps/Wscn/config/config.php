<?php

return array(
    'wscn' => array(
        'mobileRedirect' => false,
        'mobileRedirectCookieName' => 'mr',
        'mobileDomain' => 'm.wallstreetcn.com',
        'sidebar' => array(
            'quotes' => array(
                'SPX500' => array(
                    'symbol' => 'SPX500',
                    'title' => '标普500',
                    'keyword' => '标普',
                ),
                'XAUUSD' => array(
                    'symbol' => 'XAUUSD',
                    'title' => '黄金',
                    'keyword' => '黄金',
                ),
                'UKOil' => array(
                    'symbol' => 'UKOil',
                    'title' => '布油',
                    'keyword' => '石油',
                ),
                '000001' => array(
                    'symbol' => '000001',
                    'title' => '上证指数',
                    'keyword' => '上证',
                ),
                'EURUSD' => array(
                    'symbol' => 'EURUSD',
                    'title' => '欧元/美元',
                    'keyword' => '欧元',
                ),
                'USDJPY' => array(
                    'symbol' => 'USDJPY',
                    'title' => '美元日元',
                    'keyword' => '日元',
                ),
                'US10Year' => array(
                    'symbol' => 'US10Year',
                    'title' => '美债利率',
                    'keyword' => '美债',
                ),
            )
        ),
    ),
    'blog' => array(
        'postPath' => '/node/{{id}}',
        'postPreviewPath' => '',
    ),
    'user' => array(
        'registerUri' => '/register',
        'registerSuccessRedirectUri' => '/login',
        'registerFailedRedirectUri' => '/login',
        'loginUri' => '/admin',
        'loginSuccessRedirectUri' => '/mine/dashboard',
        'loginFailedRedirectUri' => '/login',
        'activeSuccessRedirectUri' => '/login',
        'activeFailedRedirectUri' => '/login',
        'resetSuccessRedirectUri' => '/login',
        'resetFailedRedirectUri' => '/login',
        'activeMailTemplate' => __DIR__ . '/../views/mails/active.phtml',
        'resetMailTemplate' => __DIR__ . '/../views/mails/reset.phtml',
        'changeEmailTemplate' => __DIR__ . '/../views/mails/change_email.phtml',
    ),
);
