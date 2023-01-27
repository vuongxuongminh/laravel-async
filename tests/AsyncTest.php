<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use Async;

use VXM\Async\Pool;

class AsyncTest extends TestCase
{
    public function testAsync()
    {
        $current = time();

        for ($i = 0; $i < 10; ++$i) {
            Async::run(function () use ($i) {
                sleep(1);

                return $i;
            });
        }

        $results = Async::wait();

        if (Pool::isSupported()) {
            $this->assertEquals(1, time() - $current);
        } else {
            $this->assertEquals(10, time() - $current);
        }

        sort($results);

        $this->assertEquals(range(0, 9), $results);
    }
}
