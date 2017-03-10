<?php

date_default_timezone_set('America/Vancouver');

$site_path = realpath(dirname(__FILE__));// strip the /web from it

define('__SITE_PATH', $site_path);
define('__CACHE_DIRECTORY', $site_path . '/app/cache');
//include_once('phpunit.configuration.php');
require_once(__SITE_PATH . '/../vendor/composer/ClassLoader.php');
$loader = new Composer\Autoload\ClassLoader();
echo file_exists(__SITE_PATH . '/../vendor/tcdent/php-restclient/RestClient.php') ? "found\r\n" : "not found\r\n";
// register classes with namespaces
$loader->add('Gossamer\\Nephthys', __SITE_PATH . '/../src');
$loader->add('Gossamer\\Caching', __SITE_PATH . '/../vendor/gossamer/caching/src');
$loader->add('RestClient', __SITE_PATH . '/../vendor/tcdent/php-restclient');
$loader->add('Monolog', __SITE_PATH . '/../vendor/monolog/monolog/src');

// activate the autoloader
$loader->register();

// to enable searching the include path (eg. for PEAR packages)
$loader->setUseIncludePath(true);
