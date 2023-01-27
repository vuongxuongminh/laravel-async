<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async;

use Closure;
use Illuminate\Contracts\Events\Dispatcher as EventDispatcher;
use Illuminate\Support\Str;
use Spatie\Async\Process\Runnable;

/**
 * @author Vuong Minh <vuongxuongminh@gmail.com>
 * @since  1.0.0
 */
class Async
{
    public function __construct(
        protected Pool $pool,
        protected EventDispatcher $events,
    ) {
    }

    public function run(callable|string|object $job, array $events = [], int $outputLength = null): static
    {
        $process = $this->pool->add($this->makeJob($job), $outputLength);

        $this->addProcessListeners($events, $process);

        $process->then($this->makeProcessListener('success', $process));
        $process->catch($this->makeProcessListener('error', $process));
        $process->timeout($this->makeProcessListener('timeout', $process));

        return $this;
    }

    public function batchRun(...$jobs): static
    {
        foreach ($jobs as $job) {
            $events = [];
            $outputLength = null;

            if (is_array($job)) {
                if (count($job) === 2) {
                    [$job, $events] = $job;
                } else {
                    [$job, $events, $outputLength] = $job;
                }
            }

            $this->run($job, $events, $outputLength);
        }

        return $this;
    }

    public function wait(): array
    {
        $results = $this->pool->wait();
        $this->pool->flush();

        return $results;
    }

    protected function makeJob($job): mixed
    {
        if (is_string($job)) {
            return $this->createClassJob($job);
        }

        return $job;
    }

    protected function createClassJob(string $job): Closure
    {
        [$class, $method] = Str::parseCallback($job, 'handle');

        return function () use ($class, $method) {
            return app()->call($class . '@' . $method);
        };
    }

    protected function addProcessListeners(array $events, Runnable $process): void
    {
        foreach ($events as $event => $callable) {
            $this->events->listen("async.{$event}_{$process->getId()}", $callable);
        }
    }

    protected function makeProcessListener(string $event, Runnable $process): callable
    {
        return function (...$args) use ($event, $process) {
            $event = "async.{$event}_{$process->getId()}";
            $this->events->dispatch($event, $args);
            $this->events->forget($event);
        };
    }

    /**
     * Get current pool.
     *
     * @since 2.1.0
     */
    public function getPool(): Pool
    {
        return $this->pool;
    }
}
