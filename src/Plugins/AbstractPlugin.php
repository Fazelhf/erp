<?php

declare(strict_types=1);

namespace Plugins;

abstract class AbstractPlugin implements PluginInterface
{
    public function install(): void {}

    public function uninstall(): void {}

    public function boot(): void {}

    public function providers(): array
    {
        return [];
    }

    public function routes(): array
    {
        return [];
    }
}
