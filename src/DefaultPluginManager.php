<?php

declare(strict_types=1);

namespace Droath\PluginManager;

use Droath\PluginManager\Contracts\PluginInterface;
use Droath\PluginManager\Contracts\PluginManagerInterface;
use Droath\PluginManager\Contracts\PluginDiscoveryInterface;
use Droath\PluginManager\Exceptions\PluginNotFoundException;
use Droath\PluginManager\Exceptions\PluginManagerRuntimeException;
use Droath\PluginManager\Contracts\PluginContainerInjectionInterface;
use Droath\PluginManager\Contracts\PluginManagerContainerAwareInterface;

/**
 * Define the base default plugin manager.
 */
abstract class DefaultPluginManager implements PluginManagerInterface
{
    /**
     * @var bool
     */
    protected bool $useCache = true;

    /**
     * @var array
     */
    protected array $definitions = [];

    /**
     * The plugin manager constructor.
     *
     * @param \Droath\PluginManager\Contracts\PluginDiscoveryInterface $discovery
     *   The discovery instance responsible in locating the manager plugins.
     */
    public function __construct(
        protected PluginDiscoveryInterface $discovery
    ) {}

    /**
     * @inheritDoc
     */
    public function disableCache(): static
    {
        $this->useCache = false;

        return $this->resetCache();
    }

    /**
     * @inheritDoc
     */
    public function createInstance(
        string $pluginId,
        array $configurations = []
    ): PluginInterface
    {
        $definition = $this->getDefinition($pluginId);

        if (! isset($definition)) {
            throw new PluginNotFoundException(
                sprintf('The plugin "%s" is not found.', $pluginId)
            );
        }
        $class = $definition['class'];

        if (! class_exists($class)) {
            throw new PluginManagerRuntimeException(
                sprintf('The plugin "%s" class does not exist.', $class)
            );
        }
        unset($definition['class']);

        if (is_subclass_of($class, PluginContainerInjectionInterface::class)) {
            if (! $this instanceof PluginManagerContainerAwareInterface) {
                throw new PluginManagerRuntimeException(
                    'The plugin manager must implement \Droath\PluginManager\Contracts\PluginManagerContainerAwareInterface.'
                );
            }

            return $class::create($this->getContainer(), $configurations, $definition);
        }

        return new $class($configurations, $definition);
    }

    /**
     * @inheritDoc
     */
    public function getDefinition(string $pluginId): ?array
    {
        return $this->getDefinitions()[$pluginId] ?? null;
    }

    /**
     * @inheritDoc
     */
    public function getDefinitions(): array
    {
        if (! $this->useCache || empty($this->definitions)) {
            /** @var \Droath\PluginManager\Discovery\PluginMetadata $metadata */
            foreach ($this->discovery->find() as $metadata) {
                $pluginDefinition = $metadata->pluginDefinition;

                if (! isset($pluginDefinition['id'])) {
                    continue;
                }
                $this->definitions[$pluginDefinition['id']] = [
                        'class' => $metadata->classname,
                    ] + $pluginDefinition;
            }
        }

        return $this->definitions;
    }

    /**
     * @inheritDoc
     */
    public function getDefinitionOptions(): array
    {
        $options = [];

        foreach ($this->getDefinitions() as $pluginId => $definition) {
            if (! isset($definition['label'])) {
                continue;
            }
            $options[$pluginId] = $definition['label'];
        }

        natsort($options);

        return $options;
    }

    /**
     * @inheritDoc
     */
    public function resetCache(): static
    {
        $this->definitions = [];

        return $this;
    }
}
