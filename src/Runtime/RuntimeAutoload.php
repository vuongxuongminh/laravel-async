<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */
define('LARAVEL_START', microtime(true));

use Illuminate\Contracts\Console\Kernel as KernelContract;
use Illuminate\Foundation\Application;
use VXM\Async\Runtime\KernelRuntime;

new class {
    /**
     *  Turn the light on.
     */
    public function __construct()
    {
        $this->registerComposerAutoload();
        $app = $this->makeApplication();
        $this->boot($app);
    }

    /**
     * Find and load Composer autoload.
     */
    protected function registerComposerAutoload(): void
    {
        $autoloadFiles = [
            __DIR__.'/../../../../autoload.php',
            __DIR__.'/../../../autoload.php',
            __DIR__.'/../../vendor/autoload.php',
            __DIR__.'/../../../vendor/autoload.php',
        ];

        $autoloadFile = current(array_filter($autoloadFiles, function (string $path) {
            return file_exists($path);
        }));

        if (false === $autoloadFile) {
            throw new RuntimeException('Composer autoload not found!');
        }

        require $autoloadFile;
    }

    /**
     * Boot an application.
     *
     * @param Application $app
     */
    protected function boot(Application $app): void
    {
        $app[KernelContract::class]->bootstrap();
    }

    /**
     * Make an application and register services.
     *
     * @return Application
     */
    protected function makeApplication(): Application
    {
        if (! file_exists($basePath = $_SERVER['argv'][4] ?? null)) {
            throw new InvalidArgumentException('No application base path provided in child process.');
        }

        $app = new Application($basePath);
        $app->singleton(KernelContract::class, KernelRuntime::class);

        return $app;
    }
};
