<?php

declare(strict_types=1);

namespace Syedn\Helper\Contracts;

interface BaseTestInterface
{
    public function refreshDatabase(): void;
    
}