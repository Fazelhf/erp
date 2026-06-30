<?php

declare(strict_types=1);

namespace Plugins;

use Illuminate\Contracts\Container\Container;

final class PluginRegistry
{
    /** @var array<string, PluginInterface> */
    private array $plugins = [];

    public function __construct(private readonly Container $container) {}

    public function register(string $pluginClass): void
    {
        /** @var PluginInterface $plugin */
        $plugin = $this->container->make($pluginClass);

        $this->plugins[$plugin->id()] = $plugin;
    }

    public function boot(): void
    {
        foreach ($this->plugins as $plugin) {
            $plugin->boot();

            foreach ($plugin->providers() as $providerClass) {
                $this->container->make($providerClass)->boot();
            }
        }
    }

    public function all(): array
    {
        return $this->plugins;
    }

    public function get(string $id): ?PluginInterface
    {
        return $this->plugins[$id] ?? null;
    }

    public function install(string $id): void
    {
        $this->plugins[$id]?->install();
    }

    public function uninstall(string $id): void
    {
        $this->plugins[$id]?->uninstall();
    }
}
