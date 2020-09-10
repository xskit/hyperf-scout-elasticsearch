<?php


namespace XsKit\ScoutElasticSearch\Payloads;

use XsKit\ScoutElasticSearch\Traits\Searchable;
use Exception;
use Hyperf\Database\Model\Model;

class TypePayload extends IndexPayload
{
    /**
     * @var Model
     */
    protected $model;

    /**
     * TypePayload constructor.
     * @param Model $model
     * @throws Exception
     */
    public function __construct(Model $model)
    {
        if (!in_array(Searchable::class, class_uses_recursive($model))) {
            throw new Exception(sprintf(
                'The %s model must use the %s trait.',
                get_class($model),
                Searchable::class
            ));
        }

        $this->model = $model;

        parent::__construct($model->getIndexConfigurator());

        $this->payload['type'] = $model->searchableAs();

        $this->protectedKeys[] = 'type';
    }
}