<?php

namespace Syedn\Helper\Contracts;

interface BaseModelInterface
{
    /**
     * Get the model's unique identifier.
     */
    public function getKey();

    /**
     * Get the table associated with the model.
     */
    public function getTable(): string;

    /**
     * Get the fillable attributes for the model.
     */
    public function getFillable(): array;

    /**
     * Create a new instance of the model.
     */
    public static function create(array $attributes = []): static;

    /**
     * Update the model in the database.
     */
    public function update(array $attributes = []): bool;

    /**
     * Delete the model from the database.
     */
    public function delete(): ?bool;

    /**
     * Find a model by its primary key.
     */
    public static function find($id, array $columns = ['*']): ?static;

    /**
     * Find a model by its primary key or throw an exception.
     */
    public static function findOrFail($id, array $columns = ['*']): static;
}