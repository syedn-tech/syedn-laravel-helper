<?php

namespace Syedn\Helper\Models;

use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Pagination\LengthAwarePaginator;
use Spatie\QueryBuilder\QueryBuilder;
use Illuminate\Auth\Authenticatable;
use Illuminate\Auth\MustVerifyEmail;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Foundation\Auth\Access\Authorizable;

abstract class BaseAuthenticatableModel extends Model implements
    AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    const DEFAULT_PAGINATE_PER_PAGE = 15;

    use HasFactory, Authenticatable, Authorizable, CanResetPassword, MustVerifyEmail;

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
