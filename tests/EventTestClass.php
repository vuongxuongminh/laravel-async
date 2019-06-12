<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use Exception;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class EventTestClass extends TestCase
{
    public function success($result)
    {
        $this->assertEquals('ok!', $result);
    }

    public function catch(Exception $exception)
    {
        $this->assertEquals('ok!', $exception->getMessage());
    }
}
