## Hyperf 架构 Elasticsearch 组件

Elasticsearch version >= 6 < 7


### 功能

## 配置
> 发布配置文件
```shell
php bin/hyperf vendor:publish xskit/hyperf-scout-elasticsearch
```

### 命令行工具

# 索引配置
```shell
php bin/hyperf elastic:create-index "App\MyIndexConfigurator"
```

### 执行该命令后，您将找到该文件 `MyModel.php` 
```php
<?php

namespace App;

use XsKit\ScoutElastic\Searchable;
use Illuminate\Database\Eloquent\Model;

class MyModel extends Model
{
    use Searchable;

    protected $indexConfigurator = MyIndexConfigurator::class;

    protected $searchRules = [
        //
    ];

    // Here you can specify a mapping for model fields
    protected $mapping = [
        'properties' => [
            'title' => [
                'type' => 'text',
                // Also you can configure multi-fields, more details you can find here https://www.elastic.co/guide/en/elasticsearch/reference/current/multi-fields.html
                'fields' => [
                    'raw' => [
                        'type' => 'keyword',
                    ]
                ]
            ],
        ]
    ];
}

```


### 索引 mapping
```sh
php bin/hyperf elastic:update-mapping MyModel
```

## 使用
基本搜索使用示例:
```php
// set query string
App\MyModel::search('phone')
    // specify columns to select
    ->select(['title', 'price'])
    // filter 
    ->where('color', 'red')
    // sort
    ->orderBy('price', 'asc')
    // collapse by field
    ->collapse('brand')
    // set offset
    ->from(0)
    // set limit
    ->take(10)
    // get results
    ->get();
```
如果你只需要一个查询匹配的数量，使用计数方法:
```php
App\MyModel::search('phone') 
    ->count();
```

> 稍后更新...