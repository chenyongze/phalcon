<?php

return array(
    'filesystem' => array(
        'default' => array(
            'adapter' => 'local',
            'options' => array(),
            'baseUrl' => '',  //Full http link
            'baseUrlForLocal' => '',  //Path is better
            'uploadPath' => __DIR__ . '/../uploads/',
            'uploadTmpPath' => __DIR__ . '/../uploads/',
            'uploadPathLevel' => 3,
            'allowExtensions' => 'jpg,png,jpeg,gif,rar,zip,7z,gz,doc,xls,docx,xlsx,ppt,pptx,pdf,mp3,mp4,wma',
            'minFileSize' => 1,
            'maxFileSize' => 1048576 * 20, //20MB
        ),
    ),
);
