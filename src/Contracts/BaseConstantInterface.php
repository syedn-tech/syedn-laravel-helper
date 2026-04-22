<?php

namespace Syedn\Helper\Contracts;

interface BaseConstantInterface
{
    /**
     * Get all defined constants.
     */
    public static function getConstants(): array;

    /**
     * Get a constant value by key.
     */
    public static function getConstant(string $key): mixed;

    /**
     * Check if a constant exists.
     */
    public static function hasConstant(string $key): bool;

    /**
     * Get constant keys.
     */
    public static function getConstantKeys(): array;

    /**
     * Get constant values.
     */
    public static function getConstantValues(): array;
}