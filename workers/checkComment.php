<?php
require __DIR__ . '/../init_autoloader.php';

require '/var/www/phalcon/modules/EvaComment/src/EvaComment/Components/Akismet.php';

use Eva\EvaComment\Components\Akismet;

$data = array();

$data = array('blog' => 'http://www.goldtoutiao.com',
    'user_ip' => '127.0.0.1',
    'user_agent' => 'Mozilla/5.0 (Windows; U; Windows NT 6.1; en-US; rv:1.9.2) Gecko/20100115 Firefox/3.6',
    'referrer' => '',
    'permalink' => 'http://www.goldtoutiao.com/post/ccO1wla9',
    'comment_type' => 'comment',
    'comment_author' => 'aaa',
    'comment_author_email' => 'test@test.com',
    'comment_author_url' => ''
    );

//$data['comment_content'] = '找小姐 电话：62312555';
$data['comment_content'] = '';




$akismet = new Akismet();
$response = $akismet->commentCheck($data);
p($response);