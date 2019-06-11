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
        Async::run(TestClass::class);
        $result = current(Async::wait());

        $this->assertEquals('ok!', $result);
    }

    public function testHandleError()
    {
        Async::run(TestClass::class . '@handleException');
        $result = current(Async::wait());

        $this->assertEquals(false, $result);
    }
}
