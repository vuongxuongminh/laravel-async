<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async;

use Illuminate\Console\GeneratorCommand;
use Illuminate\Contracts\Support\DeferrableProvider;
use Illuminate\Support\ServiceProvider as BaseServiceProvider;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class ServiceProvider extends BaseServiceProvider implements DeferrableProvider
{
    /**
     * Services provided by this package.
     *
     * @var array
     */
    public $singletons = [
        'async' => Async::class,
        'command.async.make' => GeneratorCommand::class
    ];

    /**
     * Boot
     */
    public function boot(): void
    {
        $this->publishConfigs();
    }

    /**
     * @inheritDoc
     */
    public function register(): void
    {
        $this->mergeDefaultConfigs();
        $this->registerCommands();
    }

    /**
     * Publish async config files.
     */
    protected function publishConfigs(): void
    {
        $this->publishes([
            __DIR__ . '/../config/async.php' => config_path('async.php')
        ], 'config');
    }

    /**
     * Merge default async config to config service.
     */
    protected function mergeDefaultConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/async.php', 'async');
    }

    /**
     * Register command to an artisan.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(['command.async.make']);
        }
    }

    /**
     * @inheritDoc
     */
    public function provides()
    {
        return ['async', 'command.async.make'];
    }

}
