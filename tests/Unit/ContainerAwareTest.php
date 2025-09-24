<?php

declare(strict_types=1);

use Droath\PluginManager\Exceptions\PluginManagerRuntimeException;
use Droath\PluginManager\Tests\Stubs\ArrayDiscovery;
use Droath\PluginManager\Tests\Stubs\ContainerAwareTestPluginManager;
use Droath\PluginManager\Tests\Stubs\FakeContainer;

it('returns the configured container', function () {
    $manager = new ContainerAwareTestPluginManager(new ArrayDiscovery());
    $container = new FakeContainer(['id' => 'value']);
    $manager->setContainer($container);

    expect($manager->getContainer())->toBe($container);
});

it('throws when the container has not been configured', function () {
    $manager = new ContainerAwareTestPluginManager(new ArrayDiscovery());

    $manager->getContainer();
})->throws(PluginManagerRuntimeException::class);
