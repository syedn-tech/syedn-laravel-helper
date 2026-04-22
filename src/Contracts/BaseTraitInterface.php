<?php

namespace Syedn\Helper\Contracts;

interface BaseTraitInterface
{
    /**
     * Initialize the trait when it's used.
     */
    public function initializeTrait(): void;

    /**
     * Get trait-specific configuration.
     */
    public function getTraitConfig(): array;

    /**
     * Apply trait-specific logic.
     */
    public function applyTraitLogic($data = null);
}