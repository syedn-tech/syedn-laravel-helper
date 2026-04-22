<?php

namespace Syedn\Helper\Contracts;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;

interface BaseRepositoryInterface
{
    public function model(): QueryBuilder;

    public function rawModel(): EloquentBuilder;

    public function all();

    public function paginate($perPage): LengthAwarePaginator;

    public function paginateOrAll(): LengthAwarePaginator|Collection;

    public function create($request);

    public function firstOrCreate($filterRequest, $valueRequest);

    public function updateOrCreate($filterRequest, $valueRequest);

    public function createMany($requests);

    public function find(int $id);

    public function findOrFail(int $id);

    public function findByColumn($value, string $column = 'id');

    public function findManyByColumn($value, string $column = 'id');

    public function update($request, $id);

    public function updateByColumn($value, $column, $request);

    public function updateManyByColumn($value, $column, $request);

    public function delete(int $id);
}
