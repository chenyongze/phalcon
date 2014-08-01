<?php

namespace Eva\Wiki\Utils;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-1 02:25
// +----------------------------------------------------------------------
// + KeywordsFilter.php 关键词过滤工具
// +----------------------------------------------------------------------

use Eva\Wiki\Models\EntryKeyword;

class KeywordsFilter
{
    /**
     * @var array 所有的关键词
     */
    private static $keywords = array();
    /**
     * @var array 每个关键词的首字
     */
    private static $firstCharCheck = array();
    /**
     * @var array 所有关键子中包含的所有字
     */
    private static $allCharCheck = array();
    /**
     * @var KeywordsFilter 单例模式唯一实例
     */
    private static $instance;
    /**
     * @var int 最长关键词的长度
     */
    private static $maxLength = 0;
    /**
     * @var string 文本编码
     */
    private static $encoding = 'utf-8';

    private function __construct()
    {
        $this->initialize();
    }

    private function initialize()
    {
        $keywords = EntryKeyword::find();
        foreach ($keywords as $keyword) {
            if (!isset(self::$keywords[$keyword->keyword])) {
                $keywordLength = mb_strlen($keyword->keyword, self::$encoding);
                self::$keywords[$keyword->keyword] = true;
                self::$maxLength = max(self::$maxLength, $keywordLength);
                self::$firstCharCheck[self::mbStrGet($keyword->keyword, 0)] = true;
                for ($i = 0; $i < $keywordLength; $i++) {
                    self::$allCharCheck[self::mbStrGet($keyword->keyword, $i)] = true;
                }
            }
        }



    }

    /**
     * 执行替换操作
     *
     * @param string    $text           文本
     * @param callable  $replaceFunc    回调函数，传入检测到的关键词，闭包内需返回新的字符串。
     * @param array     $blackList      黑名单词数组，在黑名单中的词会被忽略。
     * @param boolean   $htmlSafe       是否考虑 html 标签，当传入 true 时，html 标签本身(包含属性)不会被替换，默认是 true
     *
     * @return string
     */
    public function replace($text, \Closure $replaceFunc, array $blackList, $htmlSafe=false)
    {
        if (!is_string($text) || !$text) {
            return $text;
        }
        $newText = '';
//        $text = strip_tags($text);

        $textLength = mb_strlen($text, 'utf-8');
        $i = 0;
        while ($i < $textLength) {
            $_tmpStr = self::mbStrGet($text, $i);
            $newText .= $_tmpStr;
            // 尝试扫描到在关键词开头数组中的字
            // stop scanning when the character in 'all character' array
            if (!isset(self::$firstCharCheck[$_tmpStr])) {
                do {
                    $_tmpStr = self::mbStrGet($text, ++$i);
                    $newText .= $_tmpStr;
                } while ($i < $textLength - 1 && !isset(self::$firstCharCheck[$_tmpStr]));
            }

//            exit($_tmpStr);
            // 一旦扫描到在关键词开头数组中的字，则尝试扫描接下来的字是否都在所有字列表中，直至最长关键词的长度。
            //
            for ($j = 1; $j <= min(self::$maxLength, $textLength - $i); $j++) {
//                $_tmpStr = self::mbStrGet($text, $i + $j - 1);
//                $newText .= $_tmpStr;
//                var_dump($_tmpStr);
                // 判断这些字是否都在所有字列表中，直到匹配不到或者超出最长关键字长度
                if (!isset(self::$allCharCheck[$_tmpStr])) {
                    break;
                }

                $sub = mb_substr($text, $i, $j, self::$encoding);

                // 判断 $sub 段是否真的是一个关键词
                if (!in_array($sub, $blackList) && isset(self::$keywords[$sub])) {
                    // 在新文本中去除关键词这段字
                    $newText = mb_substr($newText, 0, 0-$j, self::$encoding);

                    // 执行替换操作把新的字符串附加到新文本
                    $newText .= call_user_func($replaceFunc, $sub);
                }
            }
            $i++;
        }

        return $newText;
    }

    /**
     * html 标签检测
     *
     * @param $newText
     * @param $text
     * @param $textLength
     * @param $i
     * @param $_tmpStr
     *
     * @return boolean
     */
    private static  function htmlTagCheck(& $newText, & $text, $textLength, & $i, $_tmpStr)
    {
        if($_tmpStr == '<') {
            do {
                $_tmpStr = self::mbStrGet($text, ++$i);
                $newText .= $_tmpStr;
            } while ($i < $textLength - 1 && $_tmpStr != '>');
        }
        return true;
    }
    /**
     * 获取一段宽字符串文本中的第 $index 个字
     *
     * @param string    $text       要检索的宽字符串
     * @param int       $index      下标，从 0 开始
     *
     * @return string
     */
    private static function mbStrGet(& $text, $index)
    {
        return mb_substr($text, $index, 1, self::$encoding);
    }

    /**
     * 单例模式获取实例
     *
     * @return KeywordsFilter
     */
    public static function getInstance()
    {
        if (!self::$instance) {
            self::$instance = new KeywordsFilter();
        }
        return self::$instance;
    }
}