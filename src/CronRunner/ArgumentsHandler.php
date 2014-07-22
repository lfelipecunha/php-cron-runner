<?php
namespace CronRunner;

/**
 * ArgumentsHandler
 *
 * it's a handler to manipulate arguments that will be passed into a cronjob
 */
class ArgumentsHandler {

    /**
     * _args
     *
     * @var array
     * @access protected
     */
    protected $_args = array();

    /**
     * _originalArgs
     *
     * @var array
     * @access protected
     */
    protected $_originalArgs = array();

    /**
     * _flags
     *
     * @var array
     * @access protected
     */
    protected $_flags = array();

    /**
     * _valued
     *
     * @var array
     * @access protected
     */
    protected $_valued = array();

    /**
     * _required
     *
     * @var array
     * @access protected
     */
    protected $_required = array();

    /**
     * Constructor of class
     *
     * @param array $args
     * @access public
     */
    public function __construct(array $args) {
        $this->_originalArgs = $args;
        $this->_args = array('passedArgs' => array());
    }

    /**
     * Set a set of flags that can be passed to cronjob
     *
     * @param array $flags
     * @access public
     * @return ArgumentsHandler chaining
     */
    public function setFlags(array $flags) {
        $this->_flags = $flags;
        return $this;
    }

    /**
     * Set a set of valued keys that can be passed to cronjob
     *
     * @param array $valued
     * @access public
     * @return ArgumentsHandler chaining
     */
    public function setValued(array $valued) {
        $this->_valued = $valued;
        return $this;
    }

    /**
     * Set a set of keys that must be passed to cronjob
     *
     * @param array $required
     * @access public
     * @return ArgumentsHandler chaining
     */
    public function setRequired(array $required) {
        $this->_required = $required;
        return $this;
    }

    /**
     * Validate required params
     *
     * @access protected
     * @return ArgumentsHandler chaining
     */
    protected function _validate() {
        foreach ($this->_required as $required) {
            if (is_null($this->getArgByName($required))) {
                throw new \Exception("Please inform '--$required' information");
            }
        }
        return $this;
    }

    /**
     * It's parse passed params
     *
     * @access public
     * @return ArgumentsHandler chaining
     */
    public function parse() {
        $args = $this->_originalArgs;
        while (!empty($args)) {
            $arg = array_shift($args);
            if ($this->_isFlagArg($arg)) {
                $this->_addFlagArg($arg, $args);
            } else if ($this->_isValuedArg($arg)) {
                $this->_addValuedArg($arg, $args);
            } else {
                $this->_addPassedArg($arg, $args);
            }
        }
        $this->_validate();
        return $this;
    }

    /**
     * Insert a flag argument
     *
     * @param string $arg
     * @param array $args
     * @access protected
     * @return ArgumentsHandler chaining
     */
    protected function _addFlagArg($arg, &$args) {
        $value = true;
        $arg = substr($arg, 2);
        if (strpos($arg, "no-") === 0) {
            $arg = substr($arg, 3);
            $value = false;
        }
        $this->_args[$arg] = $value;
        return $this;
    }

    /**
     * Verify if argument is a flag
     *
     * @param string $arg
     * @access protected
     * @return bool
     */
    protected function _isFlagArg($arg) {
        if (strpos($arg, '--') === 0) {
            $arg = substr($arg,2);
            if (in_array($arg, $this->_flags)) {
                return true;
            }

            if (strpos($arg, "no-") === 0) {
                $arg = substr($arg, 3);
                if (in_array($arg, $this->_flags)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Insert a valued argument
     *
     * @param string $arg
     * @param array $args
     * @access protected
     * @return ArgumentsHandler chaining
     */
    protected function _addValuedArg($arg, &$args) {
        $arg = substr($arg, 2);
        $value = array_shift($args);
        $this->_args[$arg] = $value;
        return $this;
    }

    /**
     * Verify if arg is a valued argument
     *
     * @param string $arg
     * @access protected
     * @return bool
     */
    protected function _isValuedArg($arg) {
        if (strpos($arg, '--') === 0) {
            $arg = substr($arg,2);
            if (in_array($arg, $this->_valued)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Insert an argument into list of passed arguments
     *
     * @param string $arg
     * @param array $args
     * @access protected
     * @return ArgumentsHandler chaining
     */
    protected function _addPassedArg($arg, array &$args) {
        $this->_args['passedArgs'][] = $arg;
        return $this;
    }


    /**
     * Get argument by name
     *
     * @param string $name
     * @access public
     * @return mixed | null if not exists
     */
    public function getArgByName($name) {
        if (!array_key_exists($name, $this->_args)) {
            return null;
        }

        return $this->_args[$name];
    }

    /**
     * Get list o passed args
     *
     * @access public
     * @return array
     */
    public function getPassedArgs() {
        return $this->_args['passedArgs'];
    }


}
