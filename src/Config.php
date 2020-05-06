<?php


namespace XsKit\ScoutElasticSearch;

use Hyperf\Config\Annotation\Value;
use Hyperf\Utils\Arr;

/**
 * Class Config
 * @package XsKit\ScoutElasticSearch
 */
class Config
{
    /**
     * @Value("scout_elasticsearch")
     * @var array
     */
    protected $config;


    public function driver()
    {
        return Arr::get($this->config, 'driver', 'default');
    }

    /**
     * 最大连接数
     * @return int
     */
    public function maxConnections(): int
    {
        return $this->getValue('max_connections');
    }

    public function host(): array
    {
        return $this->getValue('host');
    }

    public function indexer(): string
    {
        return $this->getValue('indexer');
    }

    public function queue(): bool
    {
        return $this->getValue('queue');
    }

    public function chunk(): array
    {
        return $this->getValue('chunk');
    }

    public function updateMapping(): bool
    {
        return $this->getValue('update_mapping');
    }

    public function documentRefresh()
    {
        return $this->getValue('document_refresh');
    }


    protected function getValue($key)
    {
        return Arr::get($this->config, $this->driver() . '.' . $key);
    }
}