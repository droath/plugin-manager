<?php

namespace Droath\PluginManager\Contracts;

use Psr\Container\ContainerInterface;

interface PluginContainerInjectionInterface
{
    /**
     * @param \Psr\Container\ContainerInterface $container
     * @param array $configuration
     * @param array $pluginDefinitions
     *
     * @return static
     */
    public static function create(
        ContainerInterface $container,
        array $configuration,
        array $pluginDefinitions
    ): static;
}
