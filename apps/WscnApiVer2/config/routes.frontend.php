<?php

return array(
    '/' =>  array(
        'module' => 'WscnApiVer2',
        'controller' => 'index',
        'action' => 'index'
    ),
    '/v2/resources/(\w+)' =>  array(
        'module' => 'WscnApiVer2',
        'controller' => 'index',
        'action' => 'resource',
        'id' => 1,
    ),
    '/v2/resources' =>  array(
        'module' => 'WscnApiVer2',
        'controller' => 'index',
        'action' => 'resources',
    ),

    //Admin posts
    'adminPostList' =>  array(
        'pattern' => '/v2/admin/posts',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'posts',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'adminCreatePost' =>  array(
        'pattern' => '/v2/admin/posts',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'posts',
            'action' => 'posts',
        ),
        'httpMethods' => 'POST'
    ),
    'adminGetPost' =>  array(
        'pattern' => '/v2/admin/posts/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'posts',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'adminPutpost' =>  array(
        'pattern' => '/v2/admin/posts/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'posts',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'adminDeletePost' =>  array(
        'pattern' => '/v2/admin/posts/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'posts',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),

    //Admin posts
    'adminUserList' =>  array(
        'pattern' => '/v2/admin/users',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'users',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'adminCreateUser' =>  array(
        'pattern' => '/v2/admin/users',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'users',
            'action' => 'post',
        ),
        'httpMethods' => 'POST'
    ),
    'adminGetUser' =>  array(
        'pattern' => '/v2/admin/users/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'users',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'adminPutUser' =>  array(
        'pattern' => '/v2/admin/users/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'users',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'adminDeleteUser' =>  array(
        'pattern' => '/v2/admin/users/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'user',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),

    //Medias
    'medialist' =>  array(
        'pattern' => '/v2/admin/media',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'createmedia' =>  array(
        'pattern' => '/v2/admin/media',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'media',
        ),
        'httpMethods' => 'POST'
    ),
    'getmedia' =>  array(
        'pattern' => '/v2/admin/media/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'putmedia' =>  array(
        'pattern' => '/v2/admin/media/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'deletemedia' =>  array(
        'pattern' => '/v2/admin/media/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),

    'livenewslist' =>  array(
        'pattern' => '/v2/admin/livenews',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'livenewsrealtime' =>  array(
        'pattern' => '/v2/admin/livenews/realtime',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'realtime',
        ),
        'httpMethods' => 'GET'
    ),
    'createlivenews' =>  array(
        'pattern' => '/v2/admin/livenews',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'post',
        ),
        'httpMethods' => 'POST'
    ),
    'getlivenews' =>  array(
        'pattern' => '/v2/admin/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'putlivenews' =>  array(
        'pattern' => '/v2/admin/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'deletelivenews' =>  array(
        'pattern' => '/v2/admin/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),
    'wikiCrateCategory' =>  array(
        'pattern' => '/v2/admin/wiki/category',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Wiki',
            'action' => 'createCategory',
        ),
        'httpMethods' => 'POST'
    ),
    'wikiCrateEntry' =>  array(
        'pattern' => '/v2/admin/wiki/entry',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Wiki',
            'action' => 'createEntry',
        ),
        'httpMethods' => 'POST'
    ),
    'commentList' =>  array(
        'pattern' => '/v2/admin/comment',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Comment',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'commentCounter' =>  array(
        'pattern' => '/v2/admin/comment/counter',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Comment',
            'action' => 'counter',
        ),
        'httpMethods' => 'GET'
    ),
);
