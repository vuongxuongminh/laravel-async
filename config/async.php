<?php

return [
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
     * An autoload script to boot composer autoload and Laravel application.
     * Default null meaning using an autoload of async extension.
     */
    'autoload' => null,
];
