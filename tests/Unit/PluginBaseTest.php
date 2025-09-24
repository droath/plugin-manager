<?php

declare(strict_types=1);

use Droath\PluginManager\Tests\Stubs\PluginBaseStub;

it('exposes the plugin definition and configuration', function () {
    $plugin = new PluginBaseStub(
        ['enabled' => true],
        [
            'id' => 'plugin_id',
            'label' => 'Plugin Label',
        ],
    );

    expect($plugin->getPluginId())->toBe('plugin_id')
        ->and($plugin->getPluginLabel())->toBe('Plugin Label')
        ->and($plugin->getConfiguration())->toBe(['enabled' => true]);
});
