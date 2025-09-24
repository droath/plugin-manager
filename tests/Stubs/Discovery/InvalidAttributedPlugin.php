<?php

declare(strict_types=1);

namespace Droath\PluginManager\Tests\Stubs\Discovery;

use Droath\PluginManager\Attributes\PluginMetadata;

#[PluginMetadata(id: 'invalid_plugin', label: 'Invalid Plugin')]
final class InvalidAttributedPlugin
{
}
