<?php

declare(strict_types=1);

namespace Droath\PluginManager\Contracts;

/**
 * Define the plugin discovery interface.
 */
interface PluginDiscoveryInterface
{
    /**
     * @return \Droath\PluginManager\Discovery\PluginMetadata[]
     */
    public function find(): array;

}
