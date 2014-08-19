<?php

namespace Eva\EvaLivenews\Events;

use Eva\EvaEngine\Exception;
use SocketIO\Emitter;
use Eva\EvaLivenews\Models\NewsManager;

class LivenewsListener
{
    public function afterCreate($event, $news)
    {
        if(!$news->id) {
            return;
        }
        $config = $news->getDI()->getConfig();
        $newsString = '';
        if($config->livenews->broadcastEnable) {
            $emitter = new Emitter(array(
                'host' => $config->livenews->socketIoRedis->host,
                'port' => $config->livenews->socketIoRedis->port, 
            ));
            $newsString = json_encode($news->dump(
                NewsManager::$simpleDump
            ));
            $emitter->emit('livenews:create', $newsString);
        }

        if($news->status === 'published' && $config->livenews->redisEnable) {
            $newsString = $newsString ?: json_encode($news->dump(
                NewsManager::$simpleDump
            ));
            $redis = $news->getDI()->getFastCache();
            $count = $redis->zCount('livenews');
            $redis->zAdd('livenews', (int) $news->id, $newsString);
        }
    }
}
