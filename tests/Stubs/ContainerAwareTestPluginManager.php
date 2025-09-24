<?php

declare(strict_types=1);

namespace Droath\PluginManager\Tests\Stubs;

use Droath\PluginManager\Concerns\ContainerAware;
use Droath\PluginManager\Contracts\PluginManagerContainerAwareInterface;
use Droath\PluginManager\DefaultPluginManager;

final class ContainerAwareTestPluginManager extends DefaultPluginManager implements PluginManagerContainerAwareInterface
{
    use ContainerAware;
}
