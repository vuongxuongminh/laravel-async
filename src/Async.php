<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async;

use Closure;
use Spatie\Async\Pool;
use Illuminate\Support\Str;
use Spatie\Async\Process\Runnable;
use VXM\Async\Runtime\ParentRuntime;
use Illuminate\Support\Facades\Event;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since 1.0.0
 */
class Async
{

    /**
     * A pool manage async processes.
     *
     * @var Pool
     */
    private $pool;

    /**
     * Execute async job.
     *
     * @param callable|string|object $job need to execute.
     * @param array $events event. Have key is an event name, value is a callable triggered when event happen,
     * have three events `error`, `success`, `timeout`.
     * @return static
     */
    public function run($job, array $events = []): self
    {
        $process = $this->createProcess($this->makeJob($job));
        $this->addProcessListeners($events, $process);

        $process
            ->then($this->makeProcessListener('success', $process))
            ->catch($this->makeProcessListener('error', $process))
            ->timeout($this->makeProcessListener('timeout', $process));

        $this->getPool()->add($process);

        return $this;
    }

    /**
     * Wait until all async jobs done and return job results.
     *
     * @return array
     */
    public function wait()
    {
        $results = $this->getPool()->wait();
        $this->getPool(true); // change pool for next jobs

        return $results;
    }

    /**
     * Make async job.
     *
     * @param $job
     * @return mixed
     */
    protected function makeJob($job)
    {
        if (is_string($job)) {
            return $this->createClassJob($job);
        }

        return $job;
    }

    /**
     * Create class and method job.
     *
     * @param string $job
     * @return Closure
     */
    protected function createClassJob(string $job): Closure
    {
        [$class, $method] = Str::parseCallback($job, 'handle');

        return function () use ($class, $method) {
            return app()->call($class . '@' . $method);
        };
    }


    /**
     * Listen events of process given.
     *
     * @param array $events
     * @param Runnable $process
     */
    protected function addProcessListeners(array $events, Runnable $process): void
    {
        foreach ($events as $event => $callable) {
            Event::listen("async.{$event}_{$process->getId()}", $callable);
        }
    }

    /**
     * Make a base listener for integration with [[EventDispatcher]].
     *
     * @param string $event
     * @param Runnable $process
     * @return callable
     */
    protected function makeProcessListener(string $event, Runnable $process): callable
    {
        return function (...$args) use ($event, $process) {
            Event::dispatch("async.{$event}_{$process->getId()}", $args);
        };
    }

    /**
     * Create a new process for run a job.
     *
     * @param Closure $job need to execute.
     * @return Runnable process.
     */
    protected function createProcess($job): Runnable
    {
        return ParentRuntime::createProcess($job);
    }

    /**
     * Create a pool for handle jobs.
     *
     * @param bool $force
     * @return Pool
     */
    protected function getPool(bool $force = false): Pool
    {
        if (null === $this->pool || $force) {
            return $this->pool = app(Pool::class)
                ->autoload(config('async.autoload', __DIR__ . '/Runtime/RuntimeAutoload.php'))
                ->concurrency(config('async.concurrency'))
                ->sleepTime(config('async.sleepTime'))
                ->timeout(config('async.timeout'));
        }

        return $this->pool;
    }

}
