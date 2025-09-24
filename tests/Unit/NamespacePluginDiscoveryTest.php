<?php

declare(strict_types=1);

use Droath\PluginManager\Attributes\PluginMetadata as PluginMetadataAttribute;
use Droath\PluginManager\Contracts\PluginInterface;
use Droath\PluginManager\Discovery\NamespacePluginDiscovery;
use Droath\PluginManager\Tests\Stubs\Discovery\AttributedPlugin;
use Droath\PluginManager\Tests\Stubs\Discovery\InvalidAttributedPlugin;
use Droath\PluginManager\Tests\Stubs\Discovery\NoAttributePlugin;

it('discovers attributed plugins that implement the configured interface', function () {
    $discovery = new NamespacePluginDiscovery(
        'Droath\\PluginManager\\Tests\\Stubs\\Discovery',
        PluginInterface::class,
        PluginMetadataAttribute::class,
    );

    $results = $discovery->find();

    expect($results)
        ->toHaveKey(AttributedPlugin::class)
        ->and($results)->not->toHaveKey(NoAttributePlugin::class)
        ->and($results)->not->toHaveKey(InvalidAttributedPlugin::class);

    $metadata = $results[AttributedPlugin::class];

    expect($metadata->classname)->toBe(AttributedPlugin::class)
        ->and($metadata->pluginDefinition)->toBe([
            'id' => 'discovered_plugin',
            'label' => 'Discovered Plugin',
        ]);
});
