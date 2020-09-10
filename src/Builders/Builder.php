<?php


namespace XsKit\ScoutElasticSearch\Builders;


use Hyperf\Utils\Traits\Macroable;
use XsKit\ScoutElasticSearch\ElasticEngine;
use Hyperf\Database\Model\Collection;
use Hyperf\Database\Model\Model;
use Hyperf\Paginator\LengthAwarePaginator;
use Hyperf\Paginator\Paginator;

class Builder
{

    use Macroable;

    public $model;

    /**
     * The query expression.
     *
     * @var string
     */
    public $query;

    /**
     * Optional callback before model query execution.
     *
     * @var \Closure|null
     */
    public $queryCallback;

    /**
     * The custom index specified for the search.
     *
     * @var string
     */
    public $index;

    /**
     * The "where" constraints added to the query.
     *
     * @var array
     */
    public $wheres = [];

    /**
     * 可选 回调之前的搜索执行
     *
     * @var string
     */
    public $callback;

    /**
     * @var int
     */
    public $limit;

    /**
     * 应用于搜索的排序
     * @var array
     */
    public $orders = [];


    /**
     * Specify a custom index to perform this search on.
     *
     * @param string $index
     * @return $this
     */
    public function within($index)
    {
        $this->index = $index;

        return $this;
    }

    /**
     * Add a constraint to the search query.
     *
     * @param string $field
     * @param mixed $value
     * @return $this
     */
    public function where($field, $value)
    {
        $this->wheres[$field] = $value;

        return $this;
    }


    /**
     * Set the "limit" for the search query.
     *
     * @param int $limit
     * @return $this
     */
    public function take($limit)
    {
        $this->limit = $limit;

        return $this;
    }

    /**
     * Add an "order" for the search query.
     *
     * @param string $column
     * @param string $direction
     * @return $this
     */
    public function orderBy($column, $direction = 'asc')
    {
        $this->orders[] = [
            'column' => $column,
            'direction' => strtolower($direction) == 'asc' ? 'asc' : 'desc',
        ];

        return $this;
    }

    /**
     * Apply the callback's query changes if the given "value" is true.
     *
     * @param mixed $value
     * @param callable $callback
     * @param callable $default
     * @return mixed
     */
    public function when($value, $callback, $default = null)
    {
        if ($value) {
            return $callback($this, $value) ?: $this;
        } elseif ($default) {
            return $default($this, $value) ?: $this;
        }

        return $this;
    }

    /**
     * Pass the query to a given callback.
     *
     * @param \Closure $callback
     * @return $this
     */
    public function tap($callback)
    {
        return $this->when(true, $callback);
    }

    /**
     * Set the callback that should have an opportunity to modify the database query.
     *
     * @param callable $callback
     * @return $this
     */
    public function query($callback)
    {
        $this->queryCallback = $callback;

        return $this;
    }


    /**
     * Get the raw results of the search.
     *
     * @return mixed
     */
    public function raw()
    {
        return $this->engine()->search($this);
    }

    /**
     * Get the keys of search results.
     *
     * @return \Illuminate\Support\Collection
     */
    public function keys()
    {
        return $this->engine()->keys($this);
    }

    /**
     * Get the first result from the search.
     *
     * @return Model
     */
    public function first()
    {
        return $this->get()->first();
    }

    /**
     * Get the results of the search.
     *
     * @return Collection
     */
    public function get()
    {
        return $this->engine()->get($this);
    }

    /**
     * Paginate the given query into a simple paginator.
     *
     * @param int $perPage
     * @param string $pageName
     * @param int|null $page
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function paginate($perPage = null, $pageName = 'page', $page = null)
    {
        $engine = $this->engine();

        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->model->getPerPage();

        $results = $this->model->newCollection($engine->map(
            $this, $rawResults = $engine->paginate($this, $perPage, $page), $this->model
        )->all());

        $paginator = (new LengthAwarePaginator($results, $engine->getTotalCount($rawResults), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]));

        return $paginator;
    }

    /**
     * Paginate the given query into a simple paginator with raw data.
     *
     * @param int $perPage
     * @param string $pageName
     * @param int|null $page
     * @return LengthAwarePaginator
     */
    public function paginateRaw($perPage = null, $pageName = 'page', $page = null)
    {
        $engine = $this->engine();

        $page = $page ?: Paginator::resolveCurrentPage($pageName);

        $perPage = $perPage ?: $this->model->getPerPage();

        $results = $engine->paginate($this, $perPage, $page);

        $paginator = (new LengthAwarePaginator($results, $engine->getTotalCount($results), $perPage, $page, [
            'path' => Paginator::resolveCurrentPath(),
            'pageName' => $pageName,
        ]));

        return $paginator->appends('query', $this->query);
    }

    /**
     * @return ElasticEngine
     */
    protected function engine()
    {
        return $this->model->searchableUsing();
    }
}