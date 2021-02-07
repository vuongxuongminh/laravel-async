<?php

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
