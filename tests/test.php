<?php
if (!defined('FIXTURES_PATH')) {
    throw new Exception('Please define "FIXTURES_PATH"!');
}
require_once FIXTURES_PATH.'/test_cron.php';

class Test extends PHPUnit_Framework_TestCase {
    public function testUsage() {
        $out = "Usage: php runner.php --name <cronJob> Please inform name of CronJob!";
        $callback = function($string) {
            return trim(preg_replace("/[ ]+/"," ", str_replace(array("\n","\r","\t"), " ",$string)));
        };
        $this->setOutputCallback($callback);
        $this->expectOutputString($out);
        $argv = array(
            'runner.php',
        );
        include SRC_PATH.'/runner.php';
    }

    public function testeRequiredArg() {
        $this->expectOutputString("Please inform '--flag' information\n");
        $argv = array(
            'runner.php',
            '--name',
            'Test'
        );
        include SRC_PATH.'/runner.php';
    }

    public function testeOutputValues() {
        $this->expectOutputString("flag: true | named: 'named'");
        $argv = array(
            'runner.php',
            '--name',
            'Test',
            '--flag',
            '--named',
            'named'
        );
        include SRC_PATH.'/runner.php';
    }

    public function testeIsLocked() {
        $this->expectOutputString("The cron 'Test' is locked!\n");
        $argv = array(
            'runner.php',
            '--name',
            'Test',
            '--flag',
            '--locked'
        );
        include SRC_PATH.'/runner.php';
    }
}

