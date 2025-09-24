<?php

declare(strict_types=1);

namespace Droath\PluginManager\Tests\Stubs;

use Droath\PluginManager\Contracts\PluginDiscoveryInterface;
use Droath\PluginManager\Discovery\PluginMetadata;

/**
 * Simple discovery stub backed by an in-memory list of metadata.
 */
final class ArrayDiscovery implements PluginDiscoveryInterface
{
    /**
     * @var PluginMetadata[]
     */
    private array $metadata;

    /**
     * @param PluginMetadata[] $metadata
     */
    public function __construct(array $metadata = [])
    {
        $this->metadata = $metadata;
    }

    /**
     * @param PluginMetadata[] $metadata
     */
    public function setMetadata(array $metadata): void
    {
        $this->metadata = $metadata;
    }

    public function find(): array
    {
        return $this->metadata;
    }
}
