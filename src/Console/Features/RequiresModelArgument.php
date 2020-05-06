<?php


namespace XsKit\ScoutElasticSearch\Console\Features;


use XsKit\ScoutElasticSearch\Traits\Searchable;
use Hyperf\Database\Model\Model;
use Symfony\Component\Console\Input\InputArgument;
use InvalidArgumentException;


trait RequiresModelArgument
{
    /**
     * Get the model.
     *
     * @return Model
     */
    protected function getModel()
    {
        $modelClass = trim($this->input->getArgument('model'));

        $modelInstance = new $modelClass;

        if (
            !($modelInstance instanceof Model) ||
            !in_array(Searchable::class, class_uses_recursive($modelClass))
        ) {
            throw new InvalidArgumentException(sprintf(
                'The %s class must extend %s and use the %s trait.',
                $modelClass,
                Model::class,
                Searchable::class
            ));
        }

        return $modelInstance;
    }


    /**
     * Get the arguments.
     *
     * @return array
     */
    protected function getArguments()
    {
        return [
            [
                'model',
                InputArgument::REQUIRED,
                'The model class',
            ],
        ];
    }
}