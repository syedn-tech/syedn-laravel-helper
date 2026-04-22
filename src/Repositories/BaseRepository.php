<?php

namespace TalentCorpMalaysia\MYXpatsBackendLibrary\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Builder as EloquentBuilder;
use Illuminate\Database\Eloquent\Collection;
use Spatie\QueryBuilder\QueryBuilder;
use TalentCorpMalaysia\MYXpatsBackendLibrary\Contracts\BaseRepositoryInterface;

abstract class BaseRepository implements BaseRepositoryInterface
{
    /**
     * Constants
     */
    const DEFAULT_PAGINATE_PER_PAGE = 10;

    /**
     * Properties
     */
    protected $model;

    protected $includes;

    protected $filters;

    protected $sorts;

    protected $relationships;

    protected $withQueryBuilder = false;

    /**
     * Create a new instance of the repository.
     *
     * @param  Illuminate\Database\Eloquent\Model  $model
     * @param  array  $includes
     * @param  array  $filters
     * @param  array  $sorts
     */
    public function __construct($model, $includes = [], $filters = [], $sorts = [])
    {
        $this->model = $model;
        $this->includes = $includes;
        $this->filters = $filters;
        $this->sorts = $sorts;
    }

    /**
     * Use clean repository (without filters, includes and sort enabled)
     * Call this before call the actual functions
     *
     * @return BaseRepository
     */
    public function withQueryBuilder(): BaseRepository
    {
        $this->withQueryBuilder = true;
        return $this;
    }

    /**
     * Build a query builder instance for the model with the given includes, filters and sorts.
     */
    public function model(): QueryBuilder
    {
        $tags = (method_exists($this->model, 'getTaggableTableName') && request()->has('tags'))
            ? request('tags')
            : null;

        return QueryBuilder::for($this->model->query())
            ->allowedIncludes($this->includes)
            ->allowedFilters($this->filters)
            ->allowedSorts($this->sorts)
            ->when($tags != null, function (Builder $query) use ($tags) {
                $tags = (str_contains($tags, ',')) ? explode(',', $tags) : [$tags];

                return $query->withAllTags($tags);
            });
    }

    /**
     * Return the raw eloquent builder of the model
     */
    public function rawModel(): EloquentBuilder
    {
        return $this->model()->getEloquentBuilder();
    }

    /**
     * Retrieve all records from the model.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        $records = $this->model()->get();

        if (request('group_by')) {
            return $records->groupBy(request('group_by'));
        }

        return $records;
    }

    /**
     * Paginate the records of the model.
     *
     * @param  int  $perPage  The number of records to display per page. Defaults to self::DEFAULT_PAGINATE_PER_PAGE.
     */
    public function paginate($perPage = self::DEFAULT_PAGINATE_PER_PAGE, $descending = false): LengthAwarePaginator
    {
        return $this->model()->orderBy('created_at', $descending ? 'desc' : 'asc')->paginate($perPage);
    }

    /**
     * Paginate the records of the model if pagination is enabled, otherwise return all the records.
     */
    public function paginateOrAll(): LengthAwarePaginator|Collection
    {
        return $this->isPaginationEnabled() ? $this->paginate(request()->query('per_page')) : $this->all();
    }

    /**
     * Create a new record in the model.
     *
     * @param  array  $request  The data to create the new record with.
     * @return \Illuminate\Database\Eloquent\Model The newly created model instance.
     */
    public function create($request)
    {
        return $this->model->create($request);
    }

    /**
     * Retrieve the first record that matches the attributes, or create it if it doesn't exist.
     *
     * @param  array  $filterRequest  The attributes to search for in the model.
     * @param  array  $valueRequest  The attributes to set on the model if it is created.
     * @return \Illuminate\Database\Eloquent\Model The existing or newly created model instance.
     */
    public function firstOrCreate($filterRequest, $valueRequest)
    {
        return $this->model->firstOrCreate($filterRequest, $valueRequest);
    }

    /**
     * Update an existing record that matches the attributes, or create it if it doesn't exist.
     *
     * @param  array  $filterRequest  The attributes to search for in the model.
     * @param  array  $valueRequest  The attributes to set on the model if it is created or updated.
     * @return \Illuminate\Database\Eloquent\Model The existing or newly created model instance.
     */
    public function updateOrCreate($filterRequest, $valueRequest)
    {
        return $this->model->updateOrCreate($filterRequest, $valueRequest);
    }

    /**
     * Insert multiple records into the database.
     *
     * @param  array  $requests
     * @return bool
     */
    public function createMany($requests)
    {
        return $this->model->insert($requests);
    }

    /**
     * Find a record by its id.
     *
     * @param  int  $id  The id of the record to find.
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function find(int $id)
    {
        if ($this->withQueryBuilder) {
            return $this->model()->find($id);
        }

        return $this->model->find($id);
    }

    /**
     * Find a record by its id or throw an exception if not found.
     *
     * @param  int  $id  The id of the record to find.
     * @return \Illuminate\Database\Eloquent\Model
     *
     * @throws \Illuminate\Database\Eloquent\ModelNotFoundException
     */
    public function findOrFail(int $id)
    {
        return $this->model()->findOrFail($id);
    }

    /**
     * Find a record by a specified column and value.
     *
     * @param  mixed  $value  The value of the column to search for.
     * @param  string  $column  The column to search. Defaults to 'id'.
     * @return \Illuminate\Database\Eloquent\Model|null The first matching model instance or null if none found.
     */
    public function findByColumn($value, string $column = 'id')
    {
        return $this->model()->where($column, $value)->first();
    }

    /**
     * Find multiple records by a specified column and value.
     *
     * @param  mixed  $value  The value of the column to search for.
     * @param  string  $column  The column to search. Defaults to 'id'.
     * @return \Illuminate\Database\Eloquent\Collection The matching model instances.
     */
    public function findManyByColumn($value, string $column = 'id')
    {
        return $this->model()->where($column, $value)->get();
    }

    /**
     * Update a record by its id.
     *
     * @param  int  $id  The id of the record to update.
     * @param  array  $request  The data to update the record with.
     * @return \Illuminate\Database\Eloquent\Model The updated model instance.
     */
    public function update($request, $id)
    {
        /**
         * Find the record
         */
        $record = $this->find($id);

        /**
         * Update
         */
        $record->update($request);

        /**
         * return updated record
         */
        return $record;
    }

    /**
     * Update a record by a specified column and value.
     *
     * @param  mixed  $value  The value of the column to search for.
     * @param  string  $column  The column to search. Defaults to 'id'.
     * @param  array  $request  The data to update the record with.
     * @return \Illuminate\Database\Eloquent\Model The updated model instance.
     */
    public function updateByColumn($value, $column, $request)
    {
        /**
         * Find the record
         */
        $record = $this->findByColumn($value, $column);

        /**
         * Update
         */
        $record->update($request);

        /**
         * return updated record
         */
        return $record;
    }

    /**
     * Update multiple records by a specified column and value.
     *
     * @param  mixed  $value  The value of the column to search for.
     * @param  string  $column  The column to search. Defaults to 'id'.
     * @param  array  $request  The data to update the records with.
     * @return \Illuminate\Database\Eloquent\Collection The collection of updated model instances.
     */
    public function updateManyByColumn($value, $column, $request)
    {
        /**
         * Find the record
         */
        $records = $this->findManyByColumn($value, $column);

        /**
         * Iterate over records to update
         */
        foreach ($records as $record) {
            $record->update($request);
        }

        /**
         * return updated record
         */
        return $records;
    }

    /**
     * Delete a record by its id.
     *
     * @param  int  $id  The id of the record to delete.
     * @return \Illuminate\Database\Eloquent\Model The deleted model instance.
     */
    public function delete(int $id)
    {
        /**
         * Find the record
         */
        $record = $this->find($id);

        /**
         * Delete the record
         */
        $record->delete();

        /**
         * Return deleted record
         */
        return $record;
    }

    /**
     * Checks if pagination is enabled.
     *
     * This method checks if page and/or per_page query parameters are set.
     * If either of them is set, pagination is enabled.
     *
     * @return bool
     */
    private function isPaginationEnabled()
    {
        return (bool) (request()->query('page') || request()->query('per_page'));
    }
}
