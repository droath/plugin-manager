<?php

declare(strict_types=1);

namespace Droath\PluginManager\Tests\Stubs;

use Droath\PluginManager\Contracts\PluginInterface;

class FakePlugin implements PluginInterface
{
    /**
     * @param array<string, mixed> $configuration
     * @param array<string, mixed> $definition
     */
    public function __construct(
        private array $configuration,
        private array $definition
    ) {
    }

    public function getPluginId(): string
    {
        return $this->definition['id'] ?? '';
    }

    public function getPluginLabel(): string
    {
        return $this->definition['label'] ?? '';
    }

    /**
     * @return array<string, mixed>
     */
    public function getConfiguration(): array
    {
        return $this->configuration;
    }
}
