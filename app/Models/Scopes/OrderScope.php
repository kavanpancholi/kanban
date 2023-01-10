<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;

class OrderScope implements Scope
{
    /**
     * The column to order by.
     *
     * @var string
     */
    protected $column;

    /**
     * The direction of the sort.
     *
     * @var string
     */
    protected $direction;

    /**
     * OrderScope constructor.
     *
     * @param $column
     * @param string $direction
     */
    public function __construct($column, $direction = 'asc')
    {
        $this->column = $column;
        $this->direction = $direction;
    }

    /**
     * Apply the scope to a given Eloquent query builder.
     */
    public function apply(Builder $builder, Model $model)
    {
        $builder->orderBy($this->column, $this->direction);
    }
}
