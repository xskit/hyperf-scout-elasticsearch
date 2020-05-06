<?php


namespace XsKit\ScoutElasticSearch\Console;


use Hyperf\Command\Command as HyperfCommand;
use Psr\Container\ContainerInterface;
use XsKit\ScoutElasticSearch\Console\Features\RequiresModelArgument;

class ElasticImportCommand extends HyperfCommand
{
    use RequiresModelArgument;

    /**
     * @var ContainerInterface
     */
    protected $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;

        parent::__construct('elastic:import');
    }

    public function configure()
    {
        parent::configure();
        $this->setDescription('Import the given model into the search index');
    }


    /**
     * @inheritDoc
     */
    public function handle()
    {
        $model = $this->getModel();

        $model::makeAllSearchable();

        $this->info('All [' . trim($this->input->getArgument('model')) . '] records have been imported.');
    }
}