<?php

namespace Test\Elasticsearch;


use Elasticsearch\ClientBuilder;
use XsKit\ScoutElasticSearch\ClientBuilderFactory;
use Elasticsearch\Client;

class SearchTest extends \PHPUnit\Framework\TestCase
{
    public function testClientBuilderFactoryCreate()
    {

        $clientFactory = new ClientBuilderFactory;

        $client = $clientFactory->create();

        $this->assertInstanceOf(ClientBuilder::class, $client);
    }

    /**
     * @return Client
     */
    public function testHostNotReached()
    {
        $clientFactory = new ClientBuilderFactory();

        $client = $clientFactory->create()->setHosts(['http://47.102.116.99:8260'])->build();

        $this->assertNotEmpty($client->info());

        return $client;
    }

    /**
     * @depends testHostNotReached
     * @param Client $client
     */
    public function testSearch($client)
    {
        $res = $client->search([
            'index' => 'public_video_v5',
            'type' => 'video',
            'body' => [
                'suggest' => [
                    "title_suggest" => [
                        'text' => "点赞",
                        'term' => [
                            'field' => 'title',
                            'suggest_mode' => 'always'
                        ],
                    ]
                ]
            ]
        ]);

        $this->assertNotEmpty($res);

        var_dump($res);
    }
}