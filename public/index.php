<?php
/**
 * Nettsamfunn1
 *
 * @copyright Copyright (c) 2012, Thomas lekanger
 * @version 1.0
 */

session_start();

define('TOP_DIR', dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR);

// Constant: CORE_DIR
// Contains the framework files
define('CORE_DIR', TOP_DIR . 'core' . DIRECTORY_SEPARATOR);

// Constant: APP_DIR
// Contains files related to the app
define('APP_DIR', TOP_DIR . 'app' . DIRECTORY_SEPARATOR);

// Constant: PUBLIC_DIR
// Contains public files
define('PUBLIC_DIR', dirname(__FILE__) . DIRECTORY_SEPARATOR);

define('ENV', 'development');

if(ENV == 'development'){
	error_reporting(E_ALL | E_STRICT);
	ini_set('display_errors', true);
}else{
	ini_set('display_errors', false);
}

date_default_timezone_set('Europe/Oslo');

// Include the needed files
require(TOP_DIR . 'config.php');
require(CORE_DIR . 'DB.php');
require(CORE_DIR . 'Memcached.php');
require(CORE_DIR . 'Front.php');
require(CORE_DIR . 'Controller.php');
require(CORE_DIR . 'JSONController.php');
require(CORE_DIR . 'View.php');
require(CORE_DIR . 'Utils.php');
// Custom, site-specific utillity functions
if(file_exists(APP_DIR . 'Utils.php')){
	require(APP_DIR . 'Utils.php');
}

// Load Twig and DB
$DB = new DB();
if(isset($config['memcached'])){
	$m = new Memcached($config['memcached']);
}
$twig = loadTwig();

// Autoload the classes specified in config.php
if(is_array($config['autoload']['classes'])){
	$classes = $config['autoload']['classes'];
	foreach($classes as $class => $args){
		require(APP_DIR . 'classes/'.$class.'.php');
		if(is_array($args)){
			$r = new ReflectionClass($class);
			${$class} = $r->newInstanceArgs($args);
		}else{
			${$class} = new $class;
		}
	}
}

// Now, run the app
$app = new Front($twig);

if(!isset($_SERVER['PHP_AUTH_USER'])){
	header('WWW-Authenticate: Basic realm="WafflesTV"');
	header('HTTP/1.0 401 Unauthorized');
}else{
	if($_SERVER['PHP_AUTH_USER'] !== $config['auth']['user'] || $_SERVER['PHP_AUTH_PW'] !== $config['auth']['password']){
		die('Wrong username or password.');
	}
}
$app->run();