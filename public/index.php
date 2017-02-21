<?php

/**
 * App front controller.
 * Initializes app by loading config/bootstrap.php file.
 * Catches all requests and handles them through the framework Dispatcher.
 */

require dirname(__DIR__) . DIRECTORY_SEPARATOR . 'config' . DIRECTORY_SEPARATOR . 'bootstrap.php';

use Gina\Dispatcher;
use Gina\Request;

// Create a new Dispatcher and dispatch the current request
$dispatcher = new Dispatcher();
$dispatcher->dispatch(Request::createFromGlobals());
