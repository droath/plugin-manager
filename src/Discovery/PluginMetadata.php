<?php

declare(strict_types=1);

namespace Droath\PluginManager\Discovery;

/**
 * Create the plugin metadata instance.
 */
final readonly class PluginMetadata
{
    /**
     * @param string $classname
     * @param array $pluginDefinition
     */
    protected function __construct(
        public string $classname,
        public array $pluginDefinition
    ) {}

    /**
     * @param string $classname
     * @param array $pluginDefinition
     *
     * @return self
     */
    public static function make(
        string $classname,
        array $pluginDefinition
    ): self
    {
        return new self($classname, $pluginDefinition);
    }
}
