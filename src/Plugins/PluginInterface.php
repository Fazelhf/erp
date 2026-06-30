<?php

declare(strict_types=1);

namespace Plugins;

interface PluginInterface
{
    public function id(): string;

    public function name(): string;

    public function version(): string;

    public function description(): string;

    public function author(): string;

    /** Called on plugin install — run migrations, seed defaults. */
    public function install(): void;

    /** Called on plugin uninstall — reverse install. */
    public function uninstall(): void;

    /** Called on every boot if plugin is active. */
    public function boot(): void;

    /** List of module Service Providers this plugin registers. */
    public function providers(): array;

    /** List of routes files this plugin contributes. */
    public function routes(): array;
}
