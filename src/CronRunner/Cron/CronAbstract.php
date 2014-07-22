<?php
namespace CronRunner\Cron;

use \CronRunner\ArgumentsHandler;

/**
 * The default implementation of cronjob
 *
 * @uses CronInterface
 */
abstract class CronAbstract implements CronInterface {

    /**
     * _args
     *
     * @var ArgumentsHandler
     * @access private
     */
    private $_args;

    /**
     * Constructor
     *
     * @param ArgumentsHandler $args
     * @access public
     */
    public function __construct(ArgumentsHandler $args) {
        $this->_args = $args;

        $this->_init();
        $this->_args->parse();
    }

    /**
     * Get arguments handler
     *
     * @access protected
     * @return null
     */
    protected function _getArgs() {
        return $this->_args;
    }

    abstract protected function _init();
    abstract public function run();

    /**
     * Callback to identify if cronjob is locked
     *
     * @access public
     * @return bool
     */
    public function isLocked() {
        return false;
    }
}
