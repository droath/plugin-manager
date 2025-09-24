<?php

declare(strict_types=1);

namespace Droath\PluginManager\Tests\Stubs;

use Droath\PluginManager\Plugin\PluginBase;
use Droath\PluginManager\Contracts\PluginContainerInjectionInterface;
use Psr\Container\ContainerInterface;

final class ContainerAwarePlugin extends PluginBase implements PluginContainerInjectionInterface
{
    public function __construct(
        array $configuration,
        array $pluginDefinition,
        private ContainerInterface $container
    ) {
        parent::__construct($configuration, $pluginDefinition);
    }

    public static function create(
        ContainerInterface $container,
        array $configuration,
        array $pluginDefinitions
    ): static {
        return new static($configuration, $pluginDefinitions, $container);
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }
}
