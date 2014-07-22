<?php

namespace CronRunner\Cron;

use \CronRunner\ArgumentsHandler;

/**
 * Interface that defines basic functions of cronjobs
 */
interface CronInterface {
    public function __construct(ArgumentsHandler $args);
    public function run();
    public function isLocked();
}
