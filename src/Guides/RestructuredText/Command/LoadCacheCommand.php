<?php

declare(strict_types=1);

namespace phpDocumentor\Guides\RestructuredText\Command;

use phpDocumentor\Guides\RestructuredText\Kernel;

final class LoadCacheCommand
{
    /** @var string */
    private $cacheDirectory;

    /** @var Kernel */
    private $kernel;

    public function __construct(Kernel $kernel, string $cacheDirectory)
    {
        $this->kernel = $kernel;
        $this->cacheDirectory = $cacheDirectory;
    }

    public function getCacheDirectory(): string
    {
        return $this->cacheDirectory;
    }

    public function getKernel(): Kernel
    {
        return $this->kernel;
    }
}
