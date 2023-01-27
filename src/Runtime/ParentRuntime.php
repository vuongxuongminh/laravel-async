<?php
/**
 * @link https://github.com/vuongxuongminh/yii2-async
 * @copyright Copyright (c) 2019 Vuong Xuong Minh
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 */

namespace VXM\Async\Runtime;

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
    public static function createProcess($task, ?int $outputLength = null, ?string $binary = 'php'): Runnable
    {
        $runnable = parent::createProcess($task, $outputLength, $binary);

        if ($runnable instanceof SynchronousProcess) {
            return $runnable;
        }

        $process = new Process([
            $binary,
            static::$childProcessScript,
            static::$autoloader,
            static::encodeTask($task),
            $outputLength,
            base_path(),
        ]);

        return ParallelProcess::create($process, self::getId());
    }
}
