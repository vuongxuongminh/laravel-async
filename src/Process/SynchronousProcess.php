<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Process;

use Throwable;
use Spatie\Async\Process\SynchronousProcess as BaseSynchronousProcess;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
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
