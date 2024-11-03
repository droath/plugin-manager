<?php

declare(strict_types=1);

namespace Droath\PluginManager\Attributes;

/**
 * Define the plugin metadata attribute.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class PluginMetadata
{
    /**
     * Define the attribute constructor.
     *
     * @param string $id
     *   The plugin identifier.
     * @param string $label
     *   The plugin human-readable name.
     */
    public function __construct(
        protected string $id,
        protected string $label,
    ) {}
}
