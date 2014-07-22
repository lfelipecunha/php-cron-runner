<?php
require_once dirname(__FILE__).'/autoload.php';

use CronRunner\CronHandler;

$handler = CronHandler::getInstance();
$handler->run(array_slice($argv,1));
