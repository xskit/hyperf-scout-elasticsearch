<?php


namespace XsKit\ScoutElasticSearch;

use Elasticsearch\ClientBuilder;
use Hyperf\Guzzle\RingPHP\CoroutineHandler;
use Swoole\Coroutine;

/**
 * Class ElasticClient
 * @package XsKit\ElasticSearch
 */
class ClientBuilderFactory
{

    public function create()
    {
        $builder = ClientBuilder::create();
        if (Coroutine::getCid() > 0) {
            $builder->setHandler(new CoroutineHandler());
        }

        return $builder;
    }
}