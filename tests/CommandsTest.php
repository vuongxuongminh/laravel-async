<?php
/**
 * @link https://github.com/vuongxuongminh/laravel-async
 *
 * @copyright (c) Vuong Xuong Minh
 * @license [MIT](https://opensource.org/licenses/MIT)
 */

namespace VXM\Async\Tests;

class CommandsTest extends TestCase
{
    public function testJobMake(): void
    {
        $this->artisan('make:async-job', [
            'name' => 'TestJob',
        ])->assertExitCode(0);

        $this->assertFileExists($this->getBasePath() . '/app/AsyncJobs/TestJob.php');
    }
}
