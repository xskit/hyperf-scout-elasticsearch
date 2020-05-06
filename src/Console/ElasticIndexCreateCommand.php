<?php


namespace XsKit\ScoutElasticSearch\Console;

use XsKit\ScoutElasticSearch\Console\Features\RequiresIndexConfiguratorArgument;
use XsKit\ScoutElasticSearch\ElasticClient;
use XsKit\ScoutElasticSearch\Payloads\IndexPayload;
use XsKit\Traits\ElasticSearch\Migratable;
use Psr\Container\ContainerInterface;
use Hyperf\Command\Command as HyperfCommand;
use Hyperf\Command\Annotation\Command;


class ElasticIndexCreateCommand extends HyperfCommand
{

    use RequiresIndexConfiguratorArgument;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('elastic:create-index');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Create an Elasticsearch index');
    }

    /**
     * Create an index.
     *
     * @return void
     */
    protected function createIndex()
    {
        $configurator = $this->getIndexConfigurator();

        $payload = (new IndexPayload($configurator))
            ->setIfNotEmpty('body.settings', $configurator->getSettings())
            ->get();

        ElasticClient::indices()
            ->create($payload);

        $this->info(sprintf(
            'The %s index was created!',
            $configurator->getName()
        ));
    }

    /**
     * Create an write alias.
     *
     * @return void
     */
    protected function createWriteAlias()
    {
        $configurator = $this->getIndexConfigurator();

        if (!in_array(Migratable::class, class_uses_recursive($configurator))) {
            return;
        }

        $payload = (new IndexPayload($configurator))
            ->set('name', $configurator->getWriteAlias())
            ->get();

        ElasticClient::indices()->putAlias($payload);

        $this->info(sprintf(
            'The %s alias for the %s index was created!',
            $configurator->getWriteAlias(),
            $configurator->getName()
        ));
    }

    /**
     * @inheritDoc
     */
    public function handle()
    {
        $this->createIndex();

        $this->createWriteAlias();
    }
}