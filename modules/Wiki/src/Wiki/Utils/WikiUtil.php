<?php

namespace Eva\Wiki\Utils;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-7-30 16:18
// +----------------------------------------------------------------------
// + WikiUtil.php
// +----------------------------------------------------------------------

use Eva\Wiki\Models\EntryKeyword;

class WikiUtil
{
    /**
     * 给出一段文本，为其中的 wiki 关键词加上链接
     *
     * @param string $text 需要高亮的文本
     * @param array $blackList 黑名单，黑名单中的词不会被高亮
     * @return string
     */
    public static function highlight($text, array $blackList = array())
    {
        $entryKeyword = new  EntryKeyword();
        $text = addslashes($text);
        $text = preg_replace('/\n+/', ' ', $text);
        $text = trim($text);
        $params = '';
        if ($blackList) {
            $blackList = array_unique($blackList);
            array_walk(
                $blackList,
                function (& $_keyword) {
                    $_keyword = "'{$_keyword}'";
                }
            );
            $params = 'keyword NOT IN (' . implode(',', $blackList) . ')';
        }

        $_keywods = $entryKeyword->find($params);
        $searches = array();
        foreach ($_keywods as $keywod) {
        //$searches[]  = '/(?<=>)([^<]*)('.preg_quote($keywod->keyword, '/').')/';
            $searches[] = '/(' . preg_quote($keywod->keyword, '/') . ')/';

        }
        // $text =  preg_replace($searches, '$1<a href="/wiki/$2" target="_blank" class="wiki-highlighted-keyword">$2</a>', $text, 1);
        $text = preg_replace(
            $searches,
            '<a href="/wiki/$1" target="_blank" class="wiki-highlighted-keyword" title="点击查看关于「$1」的解释">$1</a>',
            $text,
            1
        );
        // $text = preg_replace('/　+/', '', $text);
        $text = stripslashes($text);
        return $text;
    }

    /**
     * 从词条解释文本的头部截取一部分内容
     *
     * @param string $text 被截取的文本
     * @param int @num 需要截取的字符数
     */
    public static function intercept($text, $num = 80)
    {
        return mb_substr(
            strip_tags(str_replace(array("\r\n", "\r", "\n", "&nbsp;"), "", $text)),
            0,
            $num,
            "utf-8"
        )."......";
    }
} 