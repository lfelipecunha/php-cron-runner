<?php
namespace CronRunner;

/**
 * CronHandler
 *
 * It's a handler that manipulate cronjobs
 */
class CronHandler {

    /**
     * Instance of Singleton Pattern
     */
    private static $_instance = null;

    /**
     * Private constructor
     */
    private function __construct() {}

    /**
     * Singleton Pattern
     *
     * access to the instance
     *
     * @static
     * @access public
     * @return CronHandler
     */
    public static function getInstance() {
        if (is_null(self::$_instance)) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Begin of proccess
     *
     * @param array $params
     * @access public
     * @return null
     */
    public function run(array $params) {
        try {
            $arg = new ArgumentsHandler($params);
            $arg->setValued(array('name'));
            $cron = $arg->parse()->getArgByName('name');

            if (is_null($cron)) {
                $this->_usage();
                throw new \Exception("Please inform name of CronJob!");
            }
            $cronName = "\Crons\\{$cron}Cron";
            if (!class_exists($cronName)) {
                throw new \Exception("Cron '$cron' not exists!");
            }

            $cronJob = new $cronName(new ArgumentsHandler($arg->getPassedArgs()));
            if (!$cronJob instanceof Cron\CronInterface) {
                throw new \Exception("Cron '$cron' not exists!");
            }
            if ($cronJob->isLocked()) {
                throw new \Exception("The cron '$cron' is locked!");
            }
            $cronJob->run();
        } catch (\Exception $e) {
            echo $e->getMessage()."\n";
        }
    }

    /**
     * Show usage information
     *
     * @access private
     * @return null
     */
    private function _usage() {
        echo "Usage: \n";
        echo "php runner.php --name <cronJob>\n\n\n";
    }
}
