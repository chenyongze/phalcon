<?php
namespace Eva\EvaComment\Components;

use Phalcon\Mvc\User\Component as BaseComponent;
use Eva\EvaComment\Entities\Comments;

class Filter extends BaseComponent{
    public function filterContent(Comments $comment){
        if (!is_string($check_text)) {
            return $check_text;
        }
        $sql = new SQL();
        $sql->query("select word from filter where level = 2");
        $arr = $sql->fetch_all();
        if (!empty($arr)) {
            foreach($arr as $v){
                if (strstr($check_text,$v['word'])) {
                    $check_text= $this->replaceChar($check_text,$v['word']);
                }
            }
        }
        return $check_text;
    }

    private function replaceChar($checkText = '',$filter = ''){

        if(empty($checkText) || empty($filter)) {
            return $checkText;
        }
        $len = mb_strlen($filter,'gbk');
        $par = '';
        for($i = 0;$i<$len;$i++){
            $par .= '*';
        }
        $checkText = str_replace($filter,$par,$checkText);
        return $checkText;

    }
}
