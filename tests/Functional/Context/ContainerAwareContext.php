<?php

declare(strict_types=1);

namespace Tests\Functional\Context;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpKernel\KernelInterface;

class ContainerAwareContext
{
    private KernelInterface $kernel;

    public function __construct(KernelInterface $kernel)
    {
        $this->kernel = $kernel;
    }

    protected function getContainer(): ContainerInterface
    {
        return $this->getKernel()->getContainer();
    }

    public function getKernel(): KernelInterface
    {
        return $this->kernel;
    }
}
