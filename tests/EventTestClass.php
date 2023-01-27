<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use Exception;

class EventTestClass extends TestCase
{
    public function success($result)
    {
        $this->assertStringContainsString('ok!', $result);
    }

    public function catch(Exception $exception)
    {
        $this->assertStringContainsString('ok!', $exception->getMessage());
    }
}
