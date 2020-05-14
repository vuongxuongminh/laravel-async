<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace VXM\Async\Runtime;

use Spatie\Async\Pool;
use Spatie\Async\Process\ParallelProcess;
use Spatie\Async\Process\Runnable;
use Spatie\Async\Process\SynchronousProcess;
use Spatie\Async\Runtime\ParentRuntime as BaseParentRuntime;
use Symfony\Component\Process\Process;

/**
 * ParentRuntime support invoke console environment in child runtime mode.
 *
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class ParentRuntime extends BaseParentRuntime
{
    /**
     * {@inheritdoc}
     */
    public static function createProcess($task, ?int $outputLength = null, ?string $binary = 'php'): Runnable
    {
        if (! self::$isInitialised) {
            self::init();
        }

        if (! Pool::isSupported()) {
            return new SynchronousProcess($task, self::getId());
        }

        $process = new Process([
            $binary,
            self::$childProcessScript,
            self::$autoloader,
            self::encodeTask($task),
            $outputLength,
            base_path(),
        ]);

        return new ParallelProcess($process, self::getId());
    }
}
