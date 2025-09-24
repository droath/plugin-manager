<?php

declare(strict_types=1);

use Droath\PluginManager\Discovery\PluginMetadata;
use Droath\PluginManager\Exceptions\PluginManagerRuntimeException;
use Droath\PluginManager\Exceptions\PluginNotFoundException;
use Droath\PluginManager\Tests\Stubs\ArrayDiscovery;
use Droath\PluginManager\Tests\Stubs\ContainerAwarePlugin;
use Droath\PluginManager\Tests\Stubs\ContainerAwareTestPluginManager;
use Droath\PluginManager\Tests\Stubs\FakeContainer;
use Droath\PluginManager\Tests\Stubs\FakePlugin;
use Droath\PluginManager\Tests\Stubs\SecondFakePlugin;
use Droath\PluginManager\Tests\Stubs\TestPluginManager;

it('throws when the plugin identifier is unknown', function () {
    $manager = new TestPluginManager(new ArrayDiscovery());

    $manager->createInstance('missing');
})->throws(PluginNotFoundException::class);

it('throws when the plugin class no longer exists', function () {
    $discovery = new ArrayDiscovery([
        PluginMetadata::make(
            'Droath\\PluginManager\\Tests\\Stubs\\MissingPlugin',
            [
                'id' => 'missing',
                'label' => 'Missing Plugin',
            ],
        ),
    ]);
    $manager = new TestPluginManager($discovery);

    $manager->createInstance('missing');
})->throws(PluginManagerRuntimeException::class);

it('creates plugin instances for valid definitions', function () {
    $discovery = new ArrayDiscovery([
        PluginMetadata::make(
            FakePlugin::class,
            [
                'id' => 'fake_plugin',
                'label' => 'Fake Plugin',
            ],
        ),
    ]);
    $manager = new TestPluginManager($discovery);

    $plugin = $manager->createInstance('fake_plugin', ['enabled' => true]);

    expect($plugin)
        ->toBeInstanceOf(FakePlugin::class)
        ->and($plugin->getPluginId())->toBe('fake_plugin')
        ->and($plugin->getConfiguration())->toBe(['enabled' => true]);
});

it('injects the container for container-aware plugins', function () {
    $discovery = new ArrayDiscovery([
        PluginMetadata::make(
            ContainerAwarePlugin::class,
            [
                'id' => 'aware_plugin',
                'label' => 'Aware Plugin',
            ],
        ),
    ]);
    $manager = new ContainerAwareTestPluginManager($discovery);
    $container = new FakeContainer(['service.id' => 'value']);
    $manager->setContainer($container);

    $plugin = $manager->createInstance('aware_plugin', ['enabled' => true]);

    expect($plugin)
        ->toBeInstanceOf(ContainerAwarePlugin::class)
        ->and($plugin->getContainer())->toBe($container)
        ->and($plugin->getConfiguration())->toBe(['enabled' => true]);
});

it('fails fast when a container-aware plugin is managed by a non container-aware manager', function () {
    $discovery = new ArrayDiscovery([
        PluginMetadata::make(
            ContainerAwarePlugin::class,
            [
                'id' => 'aware_plugin',
                'label' => 'Aware Plugin',
            ],
        ),
    ]);
    $manager = new TestPluginManager($discovery);

    $manager->createInstance('aware_plugin');
})->throws(PluginManagerRuntimeException::class);

it('returns sorted definition options containing labels', function () {
    $discovery = new ArrayDiscovery([
        PluginMetadata::make(
            FakePlugin::class,
            [
                'id' => 'b_plugin',
                'label' => 'Bravo Plugin',
            ],
        ),
        PluginMetadata::make(
            SecondFakePlugin::class,
            [
                'id' => 'a_plugin',
                'label' => 'Alpha Plugin',
            ],
        ),
        PluginMetadata::make(
            ContainerAwarePlugin::class,
            [
                'id' => 'no_label_plugin',
            ],
        ),
    ]);
    $manager = new TestPluginManager($discovery);

    expect($manager->getDefinitionOptions())->toBe([
        'a_plugin' => 'Alpha Plugin',
        'b_plugin' => 'Bravo Plugin',
    ]);
});

it('refreshes definitions when cache is disabled', function () {
    $discovery = new ArrayDiscovery([
        PluginMetadata::make(
            FakePlugin::class,
            [
                'id' => 'first_plugin',
                'label' => 'First Plugin',
            ],
        ),
    ]);
    $manager = new TestPluginManager($discovery);
    $manager->getDefinitions();

    $discovery->setMetadata([
        PluginMetadata::make(
            SecondFakePlugin::class,
            [
                'id' => 'second_plugin',
                'label' => 'Second Plugin',
            ],
        ),
    ]);

    $definitions = $manager->disableCache()->getDefinitions();

    expect($definitions)
        ->toHaveKey('second_plugin')
        ->and($definitions)->not->toHaveKey('first_plugin');
});

it('resets cached definitions while keeping caching enabled', function () {
    $discovery = new ArrayDiscovery([
        PluginMetadata::make(
            FakePlugin::class,
            [
                'id' => 'first_plugin',
                'label' => 'First Plugin',
            ],
        ),
    ]);
    $manager = new TestPluginManager($discovery);
    $manager->getDefinitions();

    $discovery->setMetadata([
        PluginMetadata::make(
            SecondFakePlugin::class,
            [
                'id' => 'second_plugin',
                'label' => 'Second Plugin',
            ],
        ),
    ]);

    expect($manager->getDefinitions())->toHaveKey('first_plugin');

    $definitions = $manager->resetCache()->getDefinitions();

    expect($definitions)
        ->toHaveKey('second_plugin')
        ->and($definitions)->not->toHaveKey('first_plugin');
});
