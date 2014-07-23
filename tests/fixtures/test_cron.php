<?php
namespace Crons;
use \CronRunner\Cron\CronAbstract;
class TestCron extends CronAbstract {
    public function run() {
        $flag = $this->_getArgs()->getArgByName('flag');
        $named = $this->_getArgs()->getArgByName('named');
        echo sprintf('flag: %s | named: %s', var_export($flag, true), var_export($named, true));
    }

    protected function _init() {
        $this->_getArgs()
            ->setFlags(array('flag','locked'))
            ->setValued(array('named'))
            ->setRequired(array('flag'));
    }

    public function isLocked() {
        if ($this->_getArgs()->getArgByName('locked')) {
            return true;
        }
        return false;
    }

}
