# Changelog

## 3.0.0

- Drop support for older Laravel versions lower than 9.
- Require PHP 8.1 or above.
- Migrate from TravisCI to GitHub actions.
- Refactor syntax.

## 2.2.0

- Add support for Laravel 9.

## 2.1.0

- Add support PHP 8.
- Add `defaultOutputLength` configurable to config default output length of async processes.
- Add `withBinary` configurable to config PHP binary use in async processes.
- Deprecated `Async::createProcess` method.
- Add `Async::getPool` method support get current pool using.

## 2.0.3

- Add support Laravel 8.

## 2.0.2

- Bug #11: Fix missing ExceptionHandler concrete.

## 2.0.1

- Allowed Laravel 7.x.

## 2.0.0

- Add `batchRun` method.
- Remove class `VXM\Async\Process\SynchronousProcess`.

## 1.1.1

- Upgrade `spatie/async` package to `^1.4.0`.
- Deprecated `VXM\Async\Process\SynchronousProcess`.

## 1.1.0

- Support Laravel version 6.0
