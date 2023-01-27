<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async;

use Illuminate\Support\ServiceProvider as BaseServiceProvider;
use VXM\Async\Commands\JobMakeCommand;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class AsyncServiceProvider extends BaseServiceProvider
{
    /**
     * Package boot.
     */
    public function boot(): void
    {
        $this->publishConfigs();
    }

    /**
     * Publish async config files.
     */
    protected function publishConfigs(): void
    {
        $this->publishes([
            __DIR__ . '/../config/async.php' => config_path('async.php'),
        ], 'config');
    }

    public function register(): void
    {
        $this->mergeDefaultConfigs();
        $this->registerServices();
        $this->registerCommands();
    }

    /**
     * Merge default async config to config service.
     */
    protected function mergeDefaultConfigs(): void
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/async.php', 'async');
    }

    /**
     * Register package services.
     */
    protected function registerServices(): void
    {
        $this->app->singleton('command.async.make', JobMakeCommand::class);

        $this->app->singleton('async', function ($app) {
            return new Async($app['async.pool'], $app['events']);
        });

        $this->app->singleton('async.pool', function ($app) {
            $pool = new Pool();
            $config = $app['config']->get('async');
            $pool->autoload($config['autoload'] ?? __DIR__ . '/Runtime/RuntimeAutoload.php');
            $pool->concurrency($config['concurrency']);
            $pool->timeout($config['timeout']);
            $pool->sleepTime($config['sleepTime']);
            $pool->defaultOutputLength($config['defaultOutputLength']);
            $pool->withBinary($config['withBinary']);

            return $pool;
        });
    }

    /**
     * Register package commands.
     */
    protected function registerCommands(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands(['command.async.make']);
        }
    }
}
