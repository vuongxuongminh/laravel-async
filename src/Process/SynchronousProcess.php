<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Process;

use Spatie\Async\Process\SynchronousProcess as BaseSynchronousProcess;
use Throwable;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 * @deprecated https://github.com/spatie/async/pull/73 had solved. This class will be remove on version 2.
 */
class SynchronousProcess extends BaseSynchronousProcess
{
    /**
     * Hotfix: https://github.com/spatie/async/pull/73.
     *
     * @return Throwable
     */
    protected function resolveErrorOutput(): Throwable
    {
        return $this->getErrorOutput();
    }
}
