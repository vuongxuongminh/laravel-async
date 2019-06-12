<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async;

use Closure;
use Illuminate\Support\Str;
use Spatie\Async\Process\Runnable;
use VXM\Async\Runtime\ParentRuntime;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since  1.0.0
 */
class Async
{
    /**
     * A pool manage async processes.
     *
     * @var Pool
     */
    protected $pool;

    /**
     * Event dispatcher manage async events.
     *
     * @var EventDispatcher
     */
    protected $events;

    /**
     * Create a new Async instance.
     *
     * @param \VXM\Async\Pool $pool
     * @param \Illuminate\Contracts\Events\Dispatcher $events
     */
    public function __construct(Pool $pool, EventDispatcher $events)
    {
        $this->pool = $pool;
        $this->events = $events;
    }

    /**
     * Execute async job.
     *
     * @param callable|string|object $job need to execute.
     * @param array $events event. Have key is an event name, value is a callable triggered when event
     *                                       happen, have three events `error`, `success`, `timeout`.
     *
     * @return static
     */
    public function run($job, array $events = []): self
    {
        $process = $this->createProcess($this->makeJob($job));
        $process->then($this->makeProcessListener('success', $process));
        $process->catch($this->makeProcessListener('error', $process));
        $process->timeout($this->makeProcessListener('timeout', $process));
        $this->addProcessListeners($events, $process);
        $this->pool->add($process);

        return $this;
    }

    /**
     * Wait until all async jobs done and return job results.
     *
     * @return array
     */
    public function wait()
    {
        $results = $this->pool->wait();
        $this->pool->flush();

        return $results;
    }

    /**
     * Make async job.
     *
     * @param $job
     *
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
     *
     * @return Closure
     */
    protected function createClassJob(string $job): Closure
    {
        [$class, $method] = Str::parseCallback($job, 'handle');

        return function () use ($class, $method) {
            return app()->call($class.'@'.$method);
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
            $this->events->listen("async.{$event}_{$process->getId()}", $callable);
        }
    }

    /**
     * Make a base listener for integration with [[EventDispatcher]].
     *
     * @param string $event
     * @param Runnable $process
     *
     * @return callable
     */
    protected function makeProcessListener(string $event, Runnable $process): callable
    {
        return function (...$args) use ($event, $process) {
            $event = "async.{$event}_{$process->getId()}";
            $this->events->dispatch($event, $args);
            $this->events->forget($event);
        };
    }

    /**
     * Create a new process for run a job.
     *
     * @param Closure $job need to execute.
     *
     * @return Runnable process.
     */
    protected function createProcess($job): Runnable
    {
        return ParentRuntime::createProcess($job);
    }
}
