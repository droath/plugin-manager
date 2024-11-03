<?php

declare(strict_types=1);

namespace Droath\PluginManager\Contracts;

/**
 * Define the plugin interface.
 */
interface PluginInterface
{
    /**
     * Get the plugin identifier.
     *
     * @return string
     */
    public function getPluginId(): string;

    /**
     * Get the plugin human-readable label.
     *
     * @return string
     */
    public function getPluginLabel(): string;

    /**
     * Get the plugin configuration.
     *
     * @return array<string, string>
     */
    public function getConfiguration(): array;
}
