<?php

namespace Syedn\Helper\Contracts;

interface BaseExceptionInterface
{
    /**
     * Get the exception message.
     */
    public function getMessage(): string;

    /**
     * Get the exception code.
     */
    public function getCode(): int;

    /**
     * Get the exception context data.
     */
    public function getContext(): array;

    /**
     * Set the exception context data.
     */
    public function setContext(array $context): self;

    /**
     * Get the exception as an array.
     */
    public function toArray(): array;

    /**
     * Get the exception as JSON.
     */
    public function toJson(): string;
}