<?php

/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */
define('LARAVEL_START', microtime(true));

use Illuminate\Foundation\Application;

new class() {
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
            __DIR__ . '/../../../../autoload.php',
            __DIR__ . '/../../../autoload.php',
            __DIR__ . '/../../vendor/autoload.php',
            __DIR__ . '/../../../vendor/autoload.php',
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
     */
    protected function boot(Application $app): void
    {
        $app[Illuminate\Contracts\Console\Kernel::class]->bootstrap();
    }

    /**
     * Make an application and register services.
     */
    protected function makeApplication(): Application
    {
        if (! file_exists($basePath = $_SERVER['argv'][4] ?? null)) {
            throw new InvalidArgumentException('No application base path provided in child process.');
        }

        $app = new Application($basePath);

        $app->singleton(
            Illuminate\Contracts\Console\Kernel::class,
            class_exists(App\Console\Kernel::class) ? App\Console\Kernel::class : Illuminate\Foundation\Console\Kernel::class
        );
        $app->singleton(
            Illuminate\Contracts\Http\Kernel::class,
            class_exists(App\Http\Kernel::class) ? App\Http\Kernel::class : Illuminate\Foundation\Http\Kernel::class
        );
        $app->singleton(
            Illuminate\Contracts\Debug\ExceptionHandler::class,
            class_exists(App\Exceptions\Handler::class) ? App\Exceptions\Handler::class : Illuminate\Foundation\Exceptions\Handler::class
        );

        return $app;
    }
};
