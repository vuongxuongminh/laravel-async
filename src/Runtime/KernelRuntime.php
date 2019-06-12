<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Runtime;

use Illuminate\Foundation\Console\Kernel;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class KernelRuntime extends Kernel
{
    /**
     * @inheritDoc
     */
    protected $commandsLoaded = true;
}
