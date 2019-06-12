<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use Async;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class ClassJobTest extends TestCase
{
    public function testHandleSuccess()
    {
        Async::run(TestClass::class, [
            'success' => 'VXM\Async\Tests\EventTestClass@success',
        ]);

        Async::run(new TestClass, [
            'success' => 'VXM\Async\Tests\EventTestClass@success',
        ]);

        Async::run(function () {
            return 'ok!';
        }, [
            'success' => 'VXM\Async\Tests\EventTestClass@success',
        ]);

        foreach (Async::wait() as $result) {
            $this->assertEquals('ok!', $result);
        }
    }

    public function testHandleError()
    {
        Async::run(TestClass::class.'@handleException', [
            'error' => 'VXM\Async\Tests\EventTestClass@catch',
        ]);

        Async::run(function () {
            throw new TestException('ok!');
        }, [
            'error' => 'VXM\Async\Tests\EventTestClass@catch',
        ]);

        foreach (Async::wait() as $result) {
            $this->assertNull($result);
        }
    }
}
