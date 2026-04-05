<?php

namespace Debugger\Duk;

use Illuminate\Support\ServiceProvider;

class DukServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/duk.php', 'duk');

        $this->app->singleton(Duk::class, function () {
            return new Duk();
        });
    }

    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/duk.php' => config_path('duk.php'),
            ], 'duk-config');
        }

        Duk::configure(
            host: config('duk.host', 'localhost'),
            port: config('duk.port', 23517),
            enabled: (bool) config('duk.enabled', true),
        );
    }
}
