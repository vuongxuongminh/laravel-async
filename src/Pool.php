<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async;

use Spatie\Async\Pool as BasePool;
use Spatie\Async\Process\Runnable;
use VXM\Async\Runtime\ParentRuntime;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class Pool extends BasePool
{
    /**
     * Default processes output length.
     */
    protected int $defaultOutputLength = 10240;

    /**
     * Flush the pool.
     */
    public function flush(): void
    {
        $this->inProgress = [];
        $this->timeouts = [];
        $this->finished = [];
        $this->queue = [];
        $this->failed = [];
        $this->results = [];
    }

    public function add($process, int $outputLength = null): Runnable
    {
        $outputLength = $outputLength ?? $this->defaultOutputLength;

        if (is_callable($process)) {
            $process = ParentRuntime::createProcess(
                $process,
                $outputLength,
                $this->binary
            );
        }

        return parent::add($process, $outputLength);
    }

    /**
     * Set default output length of child processes.
     *
     * @since 2.1.0
     */
    public function defaultOutputLength(int $defaultOutputLength): void
    {
        $this->defaultOutputLength = $defaultOutputLength;
    }
}
