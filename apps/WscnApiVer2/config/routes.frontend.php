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
    'postlist' =>  array(
        'pattern' => '/v2/post',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'post',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'createpost' =>  array(
        'pattern' => '/v2/post',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'post',
            'action' => 'post',
        ),
        'httpMethods' => 'POST'
    ),
    'getpost' =>  array(
        'pattern' => '/v2/post/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'post',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'putpost' =>  array(
        'pattern' => '/v2/post/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'post',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'deletepost' =>  array(
        'pattern' => '/v2/post/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'post',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),
    'userlist' =>  array(
        'pattern' => '/v2/user',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'user',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'createuser' =>  array(
        'pattern' => '/v2/user',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'user',
            'action' => 'user',
        ),
        'httpMethods' => 'POST'
    ),
    'getuser' =>  array(
        'pattern' => '/v2/user/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'user',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'putuser' =>  array(
        'pattern' => '/v2/user/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'user',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'deleteuser' =>  array(
        'pattern' => '/v2/user/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'user',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),
    'medialist' =>  array(
        'pattern' => '/v2/media',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'createmedia' =>  array(
        'pattern' => '/v2/media',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'media',
        ),
        'httpMethods' => 'POST'
    ),
    'getmedia' =>  array(
        'pattern' => '/v2/media/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'putmedia' =>  array(
        'pattern' => '/v2/media/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'deletemedia' =>  array(
        'pattern' => '/v2/media/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'media',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),

    'livenewslist' =>  array(
        'pattern' => '/v2/livenews',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'createlivenews' =>  array(
        'pattern' => '/v2/livenews',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'post',
        ),
        'httpMethods' => 'POST'
    ),
    'getlivenews' =>  array(
        'pattern' => '/v2/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'get',
            'id' => 1,
        ),
        'httpMethods' => 'GET'
    ),
    'putlivenews' =>  array(
        'pattern' => '/v2/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'put',
            'id' => 1,
        ),
        'httpMethods' => 'PUT'
    ),
    'deletelivenews' =>  array(
        'pattern' => '/v2/livenews/(\d+)',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'livenews',
            'action' => 'delete',
            'id' => 1,
        ),
        'httpMethods' => 'DELETE'
    ),
    'wikiCrateCategory' =>  array(
        'pattern' => '/v2/wiki/category',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Wiki',
            'action' => 'createCategory',
        ),
        'httpMethods' => 'POST'
    ),
    'wikiCrateEntry' =>  array(
        'pattern' => '/v2/wiki/entry',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Wiki',
            'action' => 'createEntry',
        ),
        'httpMethods' => 'POST'
    ),
    'commentList' =>  array(
        'pattern' => '/v2/comment',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Comment',
            'action' => 'index',
        ),
        'httpMethods' => 'GET'
    ),
    'commentCounter' =>  array(
        'pattern' => '/v2/comment/counter',
        'paths' => array(
            'module' => 'WscnApiVer2',
            'controller' => 'Comment',
            'action' => 'counter',
        ),
        'httpMethods' => 'GET'
    ),
);
