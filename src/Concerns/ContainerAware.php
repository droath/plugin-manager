<?php

declare(strict_types=1);

namespace Droath\PluginManager\Concerns;

use Psr\Container\ContainerInterface;

trait ContainerAware
{
    /**
     * @var \Psr\Container\ContainerInterface|null
     */
    protected ?ContainerInterface $container = null;

    /**
     * @inheritDoc
     */
    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}
