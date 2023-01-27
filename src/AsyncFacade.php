<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async;

use Illuminate\Support\Facades\Facade;

/**
 * @method static Async run(callable|string $job, array $events = [], int $outputLength = null)
 * @method static array wait()
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class AsyncFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return 'async';
    }
}
