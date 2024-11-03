<?php

declare(strict_types=1);

namespace Droath\PluginManager\Contracts;

/**
 * Define the plugin manager interface.
 */
interface PluginManagerInterface
{
    /**
     * Disable the lifecycle caching.
     *
     * @return $this
     */
    public function disableCache(): static;

    /**
     * Create the plugin instance.
     *
     * @throws \Droath\PluginManager\Exceptions\PluginNotFoundException
     */
    public function createInstance(
        string $pluginId,
        array $configurations = []
    ): PluginInterface;

    /**
     * Get the plugin definition.
     *
     * @param string $pluginId
     *   The plugin identifier.
     *
     * @return array|null
     *   An array of the plugin definition metadata; otherwise null.
     */
    public function getDefinition(string $pluginId): ?array;

    /**
     * Get the plugin definitions.
     *
     * @return array
     *   An array of plugin definitions.
     */
    public function getDefinitions(): array;

    /**
     * Get the plugin definition options.
     *
     * @return array
     *   An array of the plugin options.
     */
    public function getDefinitionOptions(): array;
}
