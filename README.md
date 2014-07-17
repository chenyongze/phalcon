## 项目简介
wallstreetcn.com 新版

## 技术栈
* 语言：PHP >=5.3.10
* PHP框架：Phalcon 1.3.2 [http://phalconphp.com](http://phalconphp.com)
* 

## 环境要求

### PHP 设置
* 开启短标签 `short_open_tag: on`

### PHP 扩展
* Phalcon 1.3.2
* gearman

## 目录结构
```shell
|-- LICENSE.md
|-- LICENSE.txt
|-- README.md
|-- api
|   |-- explorer
|   `-- index.php
|-- apps
|   |-- Frontend
|   |-- WscnApiVer2
|   |-- WscnGold
|   `-- WscnWap
|-- cache
|   `-- schema
|-- composer.json
|-- composer.lock
|-- config
|   |-- config.default.php
|   |-- modules.api.php
|   |-- modules.evaengine.php
|   `-- modules.wscnwap.php
|-- index.html
|-- init_autoloader.php
|-- languages
|   |-- empty.csv
|   `-- zh_CN.csv
|-- logs
|   `-- 2014-06-27.log
|-- makefile
|-- modules
|   |-- EvaBlog
|   |-- EvaComment
|   |-- EvaCommon
|   |-- EvaEngine
|   |-- EvaFileSystem
|   |-- EvaOAuthClient
|   |-- EvaOAuthServer
|   |-- EvaPermission
|   `-- EvaUser
|-- public
|   |-- assets
|   |-- cache
|   |-- css
|   |-- favicon.ico
|   |-- img
|   |-- index.php
|   |-- js
|   |-- thumbnails
|   |-- tmp
|   |-- uploads
|   |-- vendor
|   `-- wscnwap
|-- sql
|   `-- engine.sql
|-- tests
|   |-- Bootstrap.php
|   |-- EvaEngineTest
|   |-- phpunit.xml.dist
|   `-- run.bat
|-- utilities
|   |-- aclscanner.php
|   |-- cli-config.php
|   |-- doctrine
|   `-- doctrine.bat
|-- vendor
|   |-- allovince
|   |-- autoload.php
|   |-- bin
|   |-- composer
|   |-- doctrine
|   |-- erusev
|   |-- imagine
|   |-- ircmaxell
|   |-- knplabs
|   |-- phalcon
|   |-- swiftmailer
|   |-- symfony
|   |-- zendframework
|   `-- zircote
`-- workers
    `-- sendmail.php
```
## 部署
