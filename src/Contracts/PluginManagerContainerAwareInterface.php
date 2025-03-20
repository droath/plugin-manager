<?php

declare(strict_types=1);

namespace Droath\PluginManager\Contracts;

use Psr\Container\ContainerInterface;

interface PluginManagerContainerAwareInterface
{
    /**
     * @return \Psr\Container\ContainerInterface
     */
    public function getContainer(): ContainerInterface;

    /**
     * @param \Psr\Container\ContainerInterface $container
     *
     * @return void
     */
    public function setContainer(ContainerInterface $container): void;
}
