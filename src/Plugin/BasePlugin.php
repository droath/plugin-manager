<?php

declare(strict_types=1);

namespace Droath\PluginManager\Plugin;

use Droath\PluginManager\Contracts\PluginInterface;

/**
 * Define the abstract base plugin.
 */
abstract class BasePlugin implements PluginInterface
{
    /**
     * The base plugin contractor.
     *
     * @param array $configuration
     *   An array of plugin configuration.
     * @param array $pluginDefinition
     *   An array of plugin definition.
     */
    public function __construct(
        protected array $configuration,
        protected array $pluginDefinition
    ) {}

    /**
     * @inheritDoc
     */
    public function getPluginId(): string
    {
        return $this->pluginDefinition['id'];
    }

    /**
     * @inheritDoc
     */
    public function getPluginLabel(): string
    {
        return $this->pluginDefinition['label'];
    }

    /**
     * @inheritDoc
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
