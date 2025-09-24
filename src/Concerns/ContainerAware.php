<?php


declare(strict_types=1);

namespace Droath\PluginManager\Concerns;

use Psr\Container\ContainerInterface;
use Droath\PluginManager\Exceptions\PluginManagerRuntimeException;

trait ContainerAware
{
    /**
     * @var \Psr\Container\ContainerInterface|null
     */
    protected ?ContainerInterface $container = null;

    /**
     * @inheritDoc
     */
    public function getContainer(): ContainerInterface
    {
        if ($this->container === null) {
            throw new PluginManagerRuntimeException(
                'The plugin manager container has not been configured.'
            );
        }

        return $this->container;
    }

    /**
     * @inheritDoc
     */
    public function setContainer(ContainerInterface $container): void
    {
        $this->container = $container;
    }
}
