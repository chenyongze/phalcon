<?php

namespace Eva\EvaSearch\Tasks;

// +----------------------------------------------------------------------
// | [phalcon]
// +----------------------------------------------------------------------
// | Author: Mr.5 <mr5.simple@gmail.com>
// +----------------------------------------------------------------------
// + Datetime: 14-8-15 10:28
// +----------------------------------------------------------------------

use Eva\EvaBlog\Models\Post;
use Eva\EvaBlog\Models\PostIterator;
use Eva\EvaEngine\IoC;
use Eva\EvaEngine\Tasks\TaskBase;
use Eva\EvaSearch\Utils\ElasticsearchUtil;

/**
 * 索引任务
 *
 * Class IndexTask
 * @package Eva\EvaSearch\Tasks
 */
class IndexTask extends TaskBase
{
    public function articleAction()
    {
        $posts = new PostIterator();
        $esUtil = new ElasticsearchUtil();

        foreach ($posts as $postArr) {

            foreach ($postArr as $_k => $post) {
                $post['content'] = strip_tags($post['content']);
                $post['summary'] = strip_tags($post['summary']);
                $ret = $esUtil->index('wallstreetcn', 'article', $post, $post['id']);
                $this->output->writeln('<comment>[INFO]</comment> ' . date('Y-m-d H:i') .'  <comment>Response:</comment>  '. json_encode($ret));
                unset($post);
            }

        }
    }
}