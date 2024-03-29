<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use Async;

class ServiceExistInChildProcessTest extends TestCase
{
    public function testBaseServicesExist(): void
    {
        Async::run(function () {
            return app()->has('events') && app()->has('log') && app()->has('router');
        });

        $this->assertTrue(current(Async::wait()));
    }
}
