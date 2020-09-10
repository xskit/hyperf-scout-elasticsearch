<?php

return [
    'driver' => 'default',

    'soft_delete' => false, // 是否软删除

    'prefix' => '',

    'default' => [
        'host' => [
            'localhost:9500'
        ],

        'max_connections' => 500,

        // 索引方式
        'indexer' => 'single',

        // 批量处理的块数量
        'chunk' => [
            'searchable' => 500,
            'unsearchable' => 500,
        ],
        'update_mapping' => true, // 是否自动更新 mapping

        'document_refresh' => false, // 可用选：false (默认)、true 、wait_for
    ]

];