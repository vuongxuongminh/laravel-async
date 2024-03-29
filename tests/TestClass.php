<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use VXM\Async\Invocation;

class TestClass
{
    use Invocation;

    public function handle(): string
    {
        return 'ok!';
    }

    public function handleException(): void
    {
        throw new TestException('ok!');
    }
}
