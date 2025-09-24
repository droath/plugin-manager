<?php

declare(strict_types=1);

namespace Droath\PluginManager\Tests\Stubs;

use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;

final class FakeContainer implements ContainerInterface
{
    /**
     * @param array<string, mixed> $services
     */
    public function __construct(private array $services = [])
    {
    }

    public function get(string $id): mixed
    {
        if (! $this->has($id)) {
            throw new class(sprintf('Service "%s" was not found.', $id)) extends \Exception implements NotFoundExceptionInterface {
            };
        }

        return $this->services[$id];
    }

    public function has(string $id): bool
    {
        return array_key_exists($id, $this->services);
    }
}
