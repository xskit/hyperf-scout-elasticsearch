<?php


namespace XsKit\ScoutElasticSearch;


class ConfigProvider
{

    public function __invoke(): array
    {
        return [
            'dependencies' => [],
            'annotations' => [
                'scan' => [
                    'paths' => [
                        __DIR__,
                    ],
                ],
            ],
            'commands' => [
                \XsKit\ScoutElasticSearch\Console\ElasticUpdateMappingCommand::class,
                \XsKit\ScoutElasticSearch\Console\ElasticIndexCreateCommand::class,
                \XsKit\ScoutElasticSearch\Console\ElasticImportCommand::class,
            ],
            'listeners' => [],
            'publish' => [
                [
                    'id' => 'config',
                    'description' => 'elasticsearch connect config',
                    'source' => __DIR__ . '/../publish/scout_elasticsearch.php',
                    'destination' => BASE_PATH . '/config/autoload/scout_elasticsearch.php',
                ]
            ]

        ];
    }
}