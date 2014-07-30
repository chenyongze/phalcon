<?php

namespace Eva\Wiki\Utils;

/**
*
* 汉字转拼音类
* @Author : Kin
* @Date   : 2014-03-16
* @Email  : Mr.kin@foxmail.com
*
*/

class Pinyin
{
    //utf-8中国汉字集合
    private $chineseCharacters;
    //编码
    private $charset = 'utf-8';

    public function __construct()
    {
        if (empty($this->chineseCharacters)) {
          $this->chineseCharacters = file_get_contents(__DIR__ . '/dict.dat');
        }
    }

    /*
    * 转成带有声调的汉语拼音
    * param $inputChar String  需要转换的汉字
    * param $delimiter  String   转换之后拼音之间分隔符
    * param $outsideIgnore  Boolean     是否忽略非汉字内容
    */
    public function transformWithTone($inputChar, $delimiter=' ', $outsideIgnore = false)
    {
        $inputLen = mb_strlen($inputChar, $this->charset);
        $chineseCharacters = $this->chineseCharacters;

        $outputChar = '';
        for ($i = 0; $i < $inputLen; $i++) {
            $word = mb_substr($inputChar, $i, 1, $this->charset);
            $matches = array();
            if (preg_match('/^[\x{4e00}-\x{9fa5}]$/u', $word) && preg_match('/\,'.preg_quote($word).'(.*?)\,/', $chineseCharacters, $matches)) {
                $outputChar .= $matches[1].$delimiter;
            } elseif (!$outsideIgnore) {
                $outputChar .= $word;
            }
        }
        return $outputChar ? $outputChar : $inputChar;
    }

    /*
    * 转成带无声调的汉语拼音
    * param $inputChar String  需要转换的汉字
    * param $delimiter  String   转换之后拼音之间分隔符
    * param $outsideIgnore  Boolean     是否忽略非汉字内容
    */
    public function transformWithoutTone($inputChar, $delimiter=' ', $outsideIgnore=true)
    {
        $charWithTone = $this->transformWithTone($inputChar, $delimiter, $outsideIgnore);

        $charWithoutTone = str_replace(
            array('ā', 'á', 'ǎ', 'à', 'ō', 'ó', 'ǒ', 'ò', 'ē', 'é', 'ě', 'è', 'ī', 'í', 'ǐ', 'ì', 'ū', 'ú', 'ǔ', 'ù', 'ǖ', 'ǘ', 'ǚ', 'ǜ', 'ü'), 
            array('a', 'a', 'a', 'a', 'o', 'o', 'o', 'o', 'e', 'e', 'e', 'e', 'i', 'i', 'i', 'i', 'u', 'u', 'u', 'u', 'v', 'v', 'v', 'v', 'v'),
            $charWithTone
        );

        return $charWithoutTone;

    }

    /*
    * 转成汉语拼音首字母
    * param $inputChar String  需要转换的汉字
    * param $delimiter  String   转换之后拼音之间分隔符
    */
    public function transformUcwords($inputChar, $delimiter='')
    {
        $charWithoutTone = ucwords($this->transformWithoutTone($inputChar, ' ', true));
        $ucwords = preg_replace('/[^A-Z]/', '', $charWithoutTone);
        if (!empty($delimiter)) {
            $ucwords = implode($delimiter, str_split($ucwords));
        }
        return $ucwords;
    }
}
