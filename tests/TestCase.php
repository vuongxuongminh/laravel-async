<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;
use VXM\Async\AsyncFacade;
use VXM\Async\AsyncServiceProvider;

class TestCase extends BaseTestCase
{
    protected function getPackageProviders($app): array
    {
        return [AsyncServiceProvider::class];
    }

    protected function getPackageAliases($app): array
    {
        return [
            'Async' => AsyncFacade::class,
        ];
    }
}
