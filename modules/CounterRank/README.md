## 简介
CounterRank 模块是一个计数器模块，支持全量及增量数据统计。基于 [Counter-Rank](https://github.com/mr5/counter-rank) Composer 组件。

## 配置
#### 基础配置
[config/config.php](https://github.com/AlloVince/phalcon/tree/master/modules/CounterRank/config/config.php) 中配置了 redis 连接信息、命名空间、分组 tokens
```php
return array(
    'counter' => array(
        'redis_host' => 'localhost',
        'redis_port' => 6379,
        // 命名空间，建议使用 app name
        'redis_namespace' => 'wscn',
        // 分组 tokens ，键是分组名，分组是一组元素的集合，只能对同分组内的数据进行排序。值为 token 值。未列出的分组不支持通过 http 客户端访问。
        'group_tokens' => array(
            'posts' => '8186b38865c422e581881b1e1d2d9740',
            'comments' => '4e1c2a79dcdd4e6e73bbdeb3f48d3d76'
        )
    )
);
```
#### 路由配置
[`config/routes.frontend.php`](https://github.com/AlloVince/phalcon/tree/master/modules/CounterRank/config/routes.frontend.php) 配置文件定义了 get、increase、 rank 三个操作的 HTTP 访问 URL，如需要修改，则需要同时修改 [`src/CounterRank/View/Helpers/CounterRank.php`](https://github.com/AlloVince/phalcon/tree/master/modules/CounterRank/src/CounterRank/View/Helpers/CounterRank.php) 中的 URL

## 使用

#### 后端使用

模块定义了一个 Util 类 [CounterRankUtil](https://github.com/AlloVince/phalcon/blob/master/modules/CounterRank/src/CounterRank/utils/CounterRankUtil.php)。

CounterRank 的使用请参考 [CounterRank.php](https://github.com/mr5/counter-rank/blob/master/lib/mr5/CounterRank/CounterRank.php) 中的注释，内有 example 。项目 [GitHub](https://github.com/mr5/counter-rank) 首页也有例子。

#### 前端使用
```php
<?=
    // 第三个参数是 js 回调函数
    $this->tag->CounterRank('posts', $item->id, 'setPostHeat')
?>
```


----------


上面的代码会生成一个 script 标签，对 posts 分组内的 $item->id 这个 id 进行递增，并将递增后的结果传递给 `setPostHeat` JS 函数。详细说明，请参考 [ViewHelper](https://github.com/AlloVince/phalcon/tree/master/modules/CounterRank/src/CounterRank/View/Helpers/CounterRank.php)。

> 文档待完善