<?php
/**
 * Application bootstrap where constants are defined, Composer autoload
 * is run and configs are loaded
 */

// Shorthand for directory separator
define('DS', DIRECTORY_SEPARATOR);

// Root folder
define('ROOT', dirname(__DIR__));

// App folder holding src
define('APP', ROOT . DS . 'src');

// Require composer's autoload
require ROOT . DS . 'vendor' . DS . 'autoload.php';
