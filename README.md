# Plugin Manager

A lightweight PHP toolkit for discovering, describing, and instantiating pluggable services. It couples attribute-based metadata with a flexible discovery pipeline so you can publish extensions without wiring every class manually.

## Features

- Attribute-first definitions: describe plugins with `#[PluginMetadata]` directly on the class.
- Namespace discovery: scan one or more namespaces for implementations that match your interface contract.
- Extensible managers: override the constructor of `DefaultPluginManager` to inject discovery and container wiring.
- Container integration: opt into PSR-11 containers for dependency-rich plugins.
- Testing utilities: reference stubbed discovery helpers to keep specs fast and focused.

## Installation

```bash
composer require droath/plugin-manager
```

The library targets PHP 8.1+ and expects Composer’s autoloader at runtime.

## Core Concepts

- **Plugin metadata attribute**: `Droath\PluginManager\Attributes\PluginMetadata` marks eligible classes and captures identity fields such as `id` and `label`.
- **Plugin contracts**: every plugin must implement `Droath\PluginManager\Contracts\PluginInterface`. The `Plugin\PluginBase` abstract class ships with sensible defaults.
- **Discovery**: `Discovery\NamespacePluginDiscovery` scans configured namespaces for attributed classes that implement one or more target interfaces.
- **Plugin manager**: `DefaultPluginManager` expects a discovery implementation via its constructor. Extend it and override `__construct()` to provide the discovery strategy (and optionally register a container).
- **Container awareness**: when a plugin implements `PluginContainerInjectionInterface` the manager must satisfy `PluginManagerContainerAwareInterface` (see `Concerns\ContainerAware`).

## Quick Start

### 1. Declare a plugin

```php
<?php

namespace App\Acme\Plugin;

use Droath\PluginManager\Attributes\PluginMetadata;
use Droath\PluginManager\Plugin\PluginBase;

#[PluginMetadata(id: 'hello_world', label: 'Hello World')]
final class HelloWorldPlugin extends PluginBase
{
    public function greet(): string
    {
        $name = $this->getConfiguration()['name'] ?? 'World';

        return sprintf('Hello %s!', $name);
    }
}
```

### 2. Create a manager by overriding `__construct()`

```php
<?php

namespace App\Acme\Plugin;

use Droath\PluginManager\Attributes\PluginMetadata;
use Droath\PluginManager\Concerns\ContainerAware;
use Droath\PluginManager\Contracts\PluginInterface;
use Droath\PluginManager\Contracts\PluginManagerContainerAwareInterface;
use Droath\PluginManager\Contracts\PluginDiscoveryInterface;
use Droath\PluginManager\DefaultPluginManager;
use Droath\PluginManager\Discovery\NamespacePluginDiscovery;
use Illuminate\Container\Container; // Replace with your PSR-11 container implementation

final class HelloPluginManager extends DefaultPluginManager implements PluginManagerContainerAwareInterface
{
    use ContainerAware;

    public function __construct(?PluginDiscoveryInterface $discovery = null)
    {
        $this->setContainer(Container::getInstance());

        parent::__construct($discovery ?? new NamespacePluginDiscovery(
            namespaces: ['App\Acme\Plugin'],
            pluginInterface: PluginInterface::class,
            pluginMetadataAttribute: PluginMetadata::class,
        ));
    }
}
```

Overriding the constructor lets you keep one canonical discovery definition while still injecting alternative discovery implementations (e.g., stubs) for tests.

### 3. Instantiate plugins

```php
$manager = new HelloPluginManager();

$plugin = $manager->createInstance('hello_world', [
    'name' => 'Developers',
]);

echo $plugin->greet(); // "Hello Developers!"
```

`createInstance()` fetches the definition from cached discovery results, merges any runtime configuration, and returns a fully constructed plugin object.

## Container-Aware Plugins

When a plugin needs services from your PSR-11 container, implement `PluginContainerInjectionInterface` and let the manager supply the container.

```php
<?php

namespace App\Acme\Plugin;

use Droath\PluginManager\Contracts\PluginContainerInjectionInterface;
use Droath\PluginManager\Plugin\PluginBase;
use Psr\Container\ContainerInterface;

final class ContainerBackedPlugin extends PluginBase implements PluginContainerInjectionInterface
{
    public function __construct(
        array $configuration,
        array $pluginDefinition,
    ) {
        parent::__construct($configuration, $pluginDefinition);
    }

    public static function create(
        ContainerInterface $container,
        array $configuration,
        array $pluginDefinition,
    ): static {
        return new static($configuration, $pluginDefinition, $container->get('service_id');
    }
}
```

Because `HelloPluginManager` implements `PluginManagerContainerAwareInterface` and calls `setContainer()` in its constructor, container-aware plugins receive the PSR-11 container automatically. If a plugin requests a container but the manager is not container-aware, `PluginManagerRuntimeException` is thrown.

## Definition Caching

`DefaultPluginManager` caches discovery results within the lifecycle of the manager. Use these helpers when definitions change at runtime:

- `disableCache()` — bypass the cache on the next lookup (useful when discovery inputs change).
- `resetCache()` — clear the cache while keeping caching enabled (helps after deployments or configuration changes).

Both methods return the manager instance for fluent chaining.

## Testing Helpers

Leverage an in-memory discovery implementation during testing so you can bypass namespace scans:

```php
use Droath\PluginManager\Contracts\PluginDiscoveryInterface;
use Droath\PluginManager\Discovery\PluginMetadata;
use Droath\PluginManager\Tests\Stubs\ArrayDiscovery;
use App\Acme\Plugin\HelloPluginManager;
use App\Acme\Plugin\HelloWorldPlugin;

$discovery = new ArrayDiscovery([
    PluginMetadata::make(HelloWorldPlugin::class, [
        'id' => 'hello_world',
        'label' => 'Hello World Plugin',
    ]),
]);

$manager = new HelloPluginManager($discovery);
```

The repository’s specs under `tests/Unit` show additional examples covering discovery filters, caching, and container-aware plugins.

## Development

- Static analysis: `composer analyze`
- Run the test suite: `composer test`
- Check coding standards: `vendor/bin/phpcs --standard=phpcs.xml src`


