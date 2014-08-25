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
            'controller' => 'Admin\Posts',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'adminCreatePost' =>  array(
        'pattern' => '/v2/admin/posts',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Posts',
            'action' => 'posts',
        ),
        'httpMethods' => 'POST'
    ),
    'adminGetPost' =>  array(
        'pattern' => '/v2/admin/posts/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Posts',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'adminPutpost' =>  array(
        'pattern' => '/v2/admin/posts/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Posts',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'adminDeletePost' =>  array(
        'pattern' => '/v2/admin/posts/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Posts',
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
            'controller' => 'Admin\Users',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'adminCreateUser' =>  array(
        'pattern' => '/v2/admin/users',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Users',
            'action' => 'post',
        ),
        'httpMethods' => 'POST'
    ),
    'adminGetUser' =>  array(
        'pattern' => '/v2/admin/users/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Users',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'adminPutUser' =>  array(
        'pattern' => '/v2/admin/users/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Users',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'adminDeleteUser' =>  array(
        'pattern' => '/v2/admin/users/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Users',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),

    //Admin Media
    'adminMediaList' =>  array(
        'pattern' => '/v2/admin/media',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Media',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'adminCreateMedia' =>  array(
        'pattern' => '/v2/admin/media',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Media',
            'action' => 'post',
        ),
        'httpMethods' => 'POST'
    ),
    'adminGetMedia' =>  array(
        'pattern' => '/v2/admin/media/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Media',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'adminPutMedia' =>  array(
        'pattern' => '/v2/admin/media/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Media',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'adminDeleteMedia' =>  array(
        'pattern' => '/v2/admin/media/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Media',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),

    //Admin Media
    'adminLivenewsList' =>  array(
        'pattern' => '/v2/admin/livenews',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Livenews',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'adminCreateLivenews' =>  array(
        'pattern' => '/v2/admin/livenews',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Livenews',
            'action' => 'post',
        ),
        'httpMethods' => 'POST'
    ),
    'adminGetLivenews' =>  array(
        'pattern' => '/v2/admin/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Livenews',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'adminPutLivenews' =>  array(
        'pattern' => '/v2/admin/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Livenews',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'adminDeletelivenews' =>  array(
        'pattern' => '/v2/admin/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Admin\Livenews',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),

    //User livenews
    'livenewsList' =>  array(
        'pattern' => '/v2/livenews',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'getLivenews' =>  array(
        'pattern' => '/v2/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'livenewsRealtime' =>  array(
        'pattern' => '/v2/livenews/realtime',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'realtime',
        ),
        'httpMethods' => 'GET'
    ),

    //User posts
    'postList' =>  array(
        'pattern' => '/v2/posts',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'posts',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'postSearch' =>  array(
        'pattern' => '/v2/posts/search',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'posts',
            'action' => 'search',
        ),
        'httpMethods' => 'GET'
    ),
    'getPost' =>  array(
        'pattern' => '/v2/posts/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'posts',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),

    //Login API
    'login' =>  array(
        'pattern' => '/v2/login',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'login',
        ),
        'httpMethods' => 'POST'
    ),


    //Wiki
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
