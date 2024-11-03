<?php

declare(strict_types=1);

namespace Droath\PluginManager\Discovery;

use Composer\Autoload\ClassLoader;
use Kcs\ClassFinder\Finder\ComposerFinder;
use Droath\PluginManager\Contracts\PluginDiscoveryInterface;

/**
 * Define the namespace plugin discovery implementation.
 */
final class NamespacePluginDiscovery implements PluginDiscoveryInterface
{
    /**
     * @var \Composer\Autoload\ClassLoader|null
     */
    protected ClassLoader|null $classLoader = null;

    /**
     * @param string|array $namespaces
     *   An array or string containing allowed namespaces.
     * @param string|array $pluginInterface
     *   The class name of the plugin interfaces that are required.
     * @param string $pluginMetadataAttribute
     *   The plugin metadata attribute class name that is required.
     */
    public function __construct(
        protected string|array $namespaces,
        protected string|array $pluginInterface,
        protected string $pluginMetadataAttribute
    ) {}

    /**
     * @inheritDoc
     */
    public function find(): array
    {
        $definitions = [];

        /** @var \ReflectionClass $reflection */
        foreach ($this->getIterator() as $classname => $reflection) {
            foreach ($reflection->getAttributes() as $attribute) {
                if (
                    $attribute->getTarget() !== \Attribute::TARGET_CLASS
                    || $attribute->getName() !== $this->pluginMetadataAttribute
                ) {
                    continue;
                }
                $definitions[$classname] = PluginMetadata::make(
                    $classname,
                    $attribute->getArguments()
                );
            }
        }

        return $definitions;
    }

    /**
     * Set a custom class loader for plugin discovery.
     *
     * @param \Composer\Autoload\ClassLoader $classLoader
     *   A composer class loader.
     *
     * @return $this
     */
    public function setClassloader(ClassLoader $classLoader): NamespacePluginDiscovery
    {
        $this->classLoader = $classLoader;

        return $this;
    }

    /**
     * @return \Iterator
     */
    protected function getIterator(): \Iterator
    {
        return (new ComposerFinder($this->classLoader))
            ->inNamespace($this->namespaces)
            ->withAttribute($this->pluginMetadataAttribute)
            ->implementationOf($this->pluginInterface)
            ->getIterator();
    }
}
