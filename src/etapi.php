<?php
/**
 * This code was tested against PHP version 8.1.2
 *
 * @author Ludvik Jerabek
 * @package et-api-php
 * @version 1.0.0
 * @license MIT
 */


define('ETAPI_PHP_BASE', dirname(__FILE__));
define('ETAPI_PHP_SRC', ETAPI_PHP_BASE . '/etapi/');
define('ETAPI_PHP_COMMON', ETAPI_PHP_BASE . '/common/');

require ETAPI_PHP_COMMON . 'PSR4AutoLoader.php';

$loader = new Psr4AutoloaderClass();
$loader->addNamespace('etapi', ETAPI_PHP_SRC);
$loader->register();