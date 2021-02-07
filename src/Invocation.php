<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async;

use RuntimeException;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
trait Invocation
{
    /**
     * Handle method name.
     *
     * @var string
     */
    protected $handleMethod = 'handle';

    /**
     * Call this class.
     */
    public function __invoke()
    {
        if (! method_exists($this, $this->handleMethod)) {
            throw new RuntimeException(sprintf('`handle` method must be define in (%s)', __CLASS__));
        }

        return app()->call([$this, $this->handleMethod]);
    }
}
