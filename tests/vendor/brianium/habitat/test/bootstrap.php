<?php
if(!defined('DS'))
    define('DS', DIRECTORY_SEPARATOR);

$vendor = dirname(__DIR__) . DS . 'vendor';
$loader = include $vendor . DS . 'autoload.php';

//define path to kernel
define('FIXTURES', __DIR__ . DS . 'fixtures');

//we don't want this in the compose file so add it manually
$loader->add('Habitat', dirname($vendor) . '/test/');

//logging
$logs = dirname(__DIR__) . DS . 'logs';
if (! file_exists($logs))
    mkdir($logs);
if (! defined('LOGS'))
    define('LOGS', $logs);