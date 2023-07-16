<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use Exception;

class EventTestClass
{
    public string|Exception $capture;

    public function success($result)
    {
        $this->capture = $result;
    }

    public function catch(Exception $exception)
    {
        $this->capture = $exception;
    }
}
