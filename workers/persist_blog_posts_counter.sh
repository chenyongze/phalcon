#!/bin/sh
# 博客文章计数器持久化任务
###### S: 定位工作路径 ######
work_root=${0}
# aboslute path
if [[ ${work_root} == /* ]]
then
    work_root=`dirname ${work_root}`/../
else
    work_root=`dirname $(pwd)/${0}`/../
fi
cd ${work_root}
###### E: 定位工作路径 ######
./console.php  EvaBlog Counter:persist posts