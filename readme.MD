## Hyperf 架构 Elasticsearch 组件
### 功能


### 配置
> 发布配置文件
```shell
php bin/hyperf vendor:publish xskit/hyperf-scout-elasticsearch
```

### 命令行工具
```shell script
# 创建ES 索引
php bin/hyperf elastic:create-index "App\MyIndexConfigurator"

# 更新索引 mapping
php bin/hyperf elastic:update-mapping

```


### 