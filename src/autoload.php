<?php

/**
 * Autoload classes by name and namespace
 *
 * @param string $class_name
 * @access public
 * @return null
 */
function my_autoload($class_name) {
    $class_name = str_replace('\\',DIRECTORY_SEPARATOR, $class_name);
    $fileName = dirname(__FILE__)."/".$class_name.'.php';
    if (is_file($fileName) && !is_dir($fileName)) {
        include_once $fileName;
    }
}

spl_autoload_register('my_autoload');
