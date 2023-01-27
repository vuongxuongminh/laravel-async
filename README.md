<p align="center">
    <a href="https://github.com/laravel" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/958072" height="100px">
    </a>
    <h1 align="center">Laravel Async</h1>
    <br>
    <p align="center">
    <a href="https://packagist.org/packages/vxm/laravel-async"><img src="https://img.shields.io/packagist/v/vxm/laravel-async.svg?style=flat-square" alt="Latest version"></a>
    <a href="https://github.com/vuongxuongminh/laravel-async/actions/workflows/tests.yml"><img src="https://github.com/vuongxuongminh/laravel-async/actions/workflows/tests.yml/badge.svg?branch=main" alt="Build status"></a>
    <a href="https://codecov.io/github/vuongxuongminh/laravel-async"><img src="https://codecov.io/github/vuongxuongminh/laravel-async/branch/main/graph/badge.svg?token=8F1ZTFB2C3"/></a>
    <a href="https://packagist.org/packages/vxm/laravel-async"><img src="https://img.shields.io/packagist/dt/vxm/laravel-async.svg?style=flat-square" alt="Total download"></a>
    <a href="https://packagist.org/packages/vxm/laravel-async"><img src="https://img.shields.io/packagist/l/vxm/laravel-async.svg?style=flat-square" alt="License"></a>
    </p>
</p>

## About it

A package provide an easy way to run code asynchronous and parallel base
on [Spatie Async](https://github.com/spatie/async) wrapper for Laravel application.

## Installation

Require Laravel Async using [Composer](https://getcomposer.org):

```bash
composer require vxm/laravel-async
```

The package will automatically register itself.

You can publish the config-file (optional) with:

```php
php artisan vendor:publish --provider="VXM\Async\AsyncServiceProvider" --tag="config"
```

This is the contents of the published config file:

```php
return [
    /*
     * The PHP binary will be use in async processes.
     */
    'withBinary' => PHP_BINARY,

    /*
     * Maximum concurrency async processes.
     */
    'concurrency' => 20,

    /*
     * Async process timeout.
     */
    'timeout' => 15,

    /*
     * Sleep (micro-second) time when waiting async processes.
     */
    'sleepTime' => 50000,

    /*
     * Default output length of async processes.
     */
    'defaultOutputLength' => 1024 * 10,

    /*
     * An autoload script to boot composer autoload and Laravel application.
     * Default null meaning using an autoload of this package.
     */
    'autoload' => null,
];
```

## Usage

### Run async code

After install, now you can try run async code via `Async` facade:

```php
use VXM\Async\AsyncFacade as Async;

for ($i = 1; $i < 20; $i++) {
    Async::run(function () use ($i) {
        sleep(1);

        return $i;
    });
}

var_dump(implode(', ', Async::wait()));

// Output value may be like:
// string(65) "5, 2, 1, 14, 4, 6, 7, 8, 19, 16, 12, 18, 13, 3, 10, 9, 11, 17, 15"
```

An async job can be callable class, anonymous function or Laravel callback:

```php
use VXM\Async\AsyncFacade as Async;

// run with anonymous function:
Async::run(function() {
    // Do a thing
});

// run with class@method
Async::run('Your\AsyncJobs\Class@handle');

// call default `handle` method if method not set.
Async::run('Your\AsyncJobs\Class');
```

You can run multiple job one time and waiting until all done.

```php
use VXM\Async\AsyncFacade as Async;

Async::run('Your\AsyncJobs\Class@jobA');
Async::run('Your\AsyncJobs\Class@jobB');
Async::run('Your\AsyncJobs\Class@jobC');
Async::run('Your\AsyncJobs\Class@jobD');

// Another way:

Async::batchRun(
    'Your\AsyncJobs\Class@jobA',
    'Your\AsyncJobs\Class@jobB',
    'Your\AsyncJobs\Class@jobC',
    'Your\AsyncJobs\Class@jobD'
);

$results = Async::wait(); // result return from jobs above
```

### Event listeners

When creating asynchronous processes, you can add the following event hooks:

```php
use VXM\Async\AsyncFacade as Async;

Async::run(function () {

    return 123;
}, [
    'success' => function ($output) { 
        // `$output` of job in this case is `123`.
    },
    'timeout' => function () { 
        // A job took too long to finish.
    },
    'error' => function (\Throwable $exception) {
        // When an exception is thrown from job, it's caught and passed here.
    },
]);

// Another way:
Async::run('AsyncJobClass@handleMethod', [
    'success' => 'AsyncJobEventListener@handleSuccess',
    'timeout' => 'AsyncJobEventListener@handleTimeout',
    'error' => 'AsyncJobEventListener@handleError'
]);

Async::batchRun(
    ['AsyncJobClassA@handleMethod', ['success' => 'AsyncJobEventListenerA@handleSuccess']],
    ['AsyncJobClassB@handleMethod', ['success' => 'AsyncJobEventListenerB@handleSuccess']],
    ['AsyncJobClassC@handleMethod', ['success' => 'AsyncJobEventListenerC@handleSuccess']]
);
```

## Working with complex job

When working with complex job you may want to setup more before it run (ex: job depend on Eloquent model). This package
provide you an Artisan command `make:async-job` to generate a job template.
By default, all of the async jobs for your application are stored in the `app/AsyncJobs` directory.
If the `app/AsyncJobs` directory doesn't exist, it will be created. You may generate a new async job using the Artisan
CLI:

```php
php artisan make:async-job MyJob
```

After created it, you need to prepare your job structure, example:

```php
namespace App\AsyncJobs;

use App\MyModel;
use VXM\Async\Invocation;
use App\MyHandleDependency;
use Illuminate\Queue\SerializesModels;

class MyJob
{

    use Invocation;
    use SerializesModels;

    protected $model;
    
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(MyModel $model)
    {
        $this->model = $model;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(MyHandleDependency $dependency)
    {
        //
    }
}
```

In this example, note that we were able to pass an Eloquent model directly into the async job's constructor.
Because of the `SerializesModels` trait that the job is using, Eloquent models will be gracefully serialized and
unserialized when the job is processing.
If your async job accepts an Eloquent model in its constructor, only the identifier for the model will be serialized
onto the queue.
When the job is actually handled, the system will automatically re-retrieve the full model instance from the database.
It's all totally transparent to your application and prevents issues that can arise from serializing full Eloquent model
instances.

The `handle` method is called when the job is processed in async process. Note that we are able to type-hint
dependencies on the handle method of the job.
The Laravel service container automatically injects these dependencies.

If you would like to take total control over how the container injects dependencies into the handle method, you may use
the container's `bindMethod` method. The `bindMethod` method accepts a callback which receives the job and the
container. Within the callback, you are free to invoke the handle method however you wish.
Typically, you should call this method from a service provider:

```php
use App\AsyncJobs\MyJob;
use App\MyHandleDependency;

$this->app->bindMethod(MyJob::class.'@handle', function ($job, $app) {
    return $job->handle($app->make(MyHandleDependency::class));
});
```

Now run it asynchronously:

```php
use VXM\Async\AsyncFacade as Async;
use App\MyModel;
use App\AsyncJobs\MyJob;

$model = App\MyModel::find(1);

Async::run(new MyJob($model));

// or batch run with multiple models:

$model2 = App\MyModel::find(2);

Async::batchRun(
    new MyJob($model),
    new MyJob($model2)
);
```

## Compare with queue

You can feel this package look like queue and thing why not using queue?

Queue is a good choice for common async jobs. This package using in cases end-user need to get response in single
request but
it's a heavy things need to using several processes for calculation or IO heavy operations. And it no need to run a
queue listener.
