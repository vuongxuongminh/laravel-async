<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async;

use Spatie\Async\Pool as BasePool;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class Pool extends BasePool
{
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
}
