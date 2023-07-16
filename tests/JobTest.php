<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

use Async;
use PHPUnit\Framework\Attributes\DataProvider;
use VXM\Async\Pool;

class JobTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->forgetInstance(EventTestClass::class);
        $this->app->singleton(EventTestClass::class);
    }

    #[DataProvider('successJobProvider')]
    public function testHandleSuccess($handler, array $events): void
    {
        Async::run($handler, $events);

        $this->assertStringContainsString('ok!', current(Async::wait()));
        $this->assertStringContainsString('ok!', $this->app->get(EventTestClass::class)->capture);
    }

    public function testBatchHandleSuccess(): void
    {
        Async::batchRun(...self::successJobProvider());

        foreach (Async::wait() as $result) {
            $this->assertStringContainsString('ok!', $result);
            $this->assertStringContainsString('ok!', $this->app->get(EventTestClass::class)->capture);
        }
    }

    #[DataProvider('errorJobProvider')]
    public function testHandleError($handler, array $events): void
    {
        Async::run($handler, $events);
        $results = array_filter(Async::wait());

        $this->assertEmpty($results);
        $this->assertInstanceOf(\Exception::class, $this->app->get(EventTestClass::class)->capture);
    }

    public function testBatchHandleError(): void
    {
        Async::batchRun(...self::errorJobProvider());
        $results = array_filter(Async::wait());

        $this->assertEmpty($results);
        $this->assertInstanceOf(\Exception::class, $this->app->get(EventTestClass::class)->capture);
    }

    public function testMaxOutputLength(): void
    {
        Async::getPool()->defaultOutputLength(2);
        Async::run(TestClass::class);
        $results = array_filter(Async::wait());

        if (Pool::isSupported()) {
            $this->assertEmpty($results);
        } else {
            $this->assertEquals('ok!', $results[0]);
        }
    }

    public static function successJobProvider(): array
    {
        return [
            [
                TestClass::class,
                [
                    'success' => 'VXM\Async\Tests\EventTestClass@success',
                ],
            ],
            [
                new TestClass(),
                [
                    'success' => 'VXM\Async\Tests\EventTestClass@success',
                ],
            ],
            [
                function () {
                    return 'ok!';
                },
                [
                    'success' => 'VXM\Async\Tests\EventTestClass@success',
                ],
            ],
        ];
    }

    public static function errorJobProvider(): array
    {
        return [
            [
                TestClass::class.'@handleException',
                [
                    'error' => 'VXM\Async\Tests\EventTestClass@catch',
                ],
            ],
            [
                function () {
                    throw new TestException('ok!');
                },
                [
                    'error' => 'VXM\Async\Tests\EventTestClass@catch',
                ],
            ],
        ];
    }
}
