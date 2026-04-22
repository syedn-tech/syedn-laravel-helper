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

    protected $includes;

    protected $filters;

    protected $sorts;

    /**
     * Build a query builder instance for the model with the given includes, filters and sorts.
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public static function queryBuilder(Builder|Model $queryModel): Builder
    {
        $model = new static();

        return QueryBuilder::for($queryModel)
            ->allowedIncludes($model->includes ?? [])
            ->allowedFilters($model->filters ?? [])
            ->allowedSorts($model->sorts ?? [])
            ->getEloquentBuilder();
    }

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
            ->allowedIncludes($model->includes ?? [])
            ->allowedFilters($model->filters ?? [])
            ->allowedSorts($model->sorts ?? [])
            ->paginate(
                $perPage,
                $columns,
                $pageName,
                $page,
                $total
            );
    }
}
