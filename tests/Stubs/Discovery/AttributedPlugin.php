<?php

declare(strict_types=1);

namespace Droath\PluginManager\Tests\Stubs\Discovery;

use Droath\PluginManager\Attributes\PluginMetadata;
use Droath\PluginManager\Plugin\PluginBase;

#[PluginMetadata(id: 'discovered_plugin', label: 'Discovered Plugin')]
final class AttributedPlugin extends PluginBase
{
}
