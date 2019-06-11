<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use VXM\Async\Invocation;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class TestClass
{

    use Invocation;

    public function handle()
    {
        return 'ok!';
    }

    public function handleException()
    {
        throw new TestException('ok!');
    }
}
