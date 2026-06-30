<?php

namespace Syedn\Helper\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;

abstract class BaseModel extends Model
{
    const DEFAULT_PAGINATE_PER_PAGE = 15;

    use HasFactory;

    /**
     * Define the allowed includes for Spatie QueryBuilder.
     * Must be overwritten by the child class.
     * * @return array
     */
    abstract protected function allowedIncludes(): array;

    /**
     * Define the allowed filters for Spatie QueryBuilder.
     * Must be overwritten by the child class.
     * * @return array
     */
    abstract protected function allowedFilters(): array;

    /**
     * Define the allowed sorts for Spatie QueryBuilder.
     * Must be overwritten by the child class.
     * * @return array
     */
    abstract protected function allowedSorts(): array;

    /**
     * Build a query builder instance for the model.
     */
    public static function queryBuilder(Builder|Model $queryModel): Builder
    {
        $model = new static();

        return QueryBuilder::for($queryModel)
            ->allowedIncludes($model->allowedIncludes())
            ->allowedFilters($model->allowedFilters())
            ->allowedSorts($model->allowedSorts())
            ->getEloquentBuilder();
    }

    /**
     * Build a paginated query builder instance.
     */
    public static function queryBuilderPaginate(
        Builder|Model $queryModel,
        $perPage = self::DEFAULT_PAGINATE_PER_PAGE,
        $columns = ['*'],
        $pageName = 'page',
        $page = null,
        $total = null
    ): LengthAwarePaginator {
        $model = new static();

        return QueryBuilder::for($queryModel)
            ->allowedIncludes($model->allowedIncludes())
            ->allowedFilters($model->allowedFilters())
            ->allowedSorts($model->allowedSorts())
            ->paginate($perPage, $columns, $pageName, $page, $total);
    }
}